<?php

namespace App\Jobs;

use App\Models\PengirimanImport;
use App\Models\Pengiriman;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessPengirimanImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $import;
    
    /**
     * The number of seconds the job can run before timing out.
     * Maximum processing time is 10 minutes.
     *
     * @var int
     */
    public $timeout = 600;
    
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\PengirimanImport  $import
     * @return void
     */
    public function __construct(PengirimanImport $import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Record the start time
            $startTime = now();
            
            // Update status to processing
            $this->import->status = 'processing';
            $this->import->processing_started_at = $startTime;
            $this->import->save();

            // Process the file based on its extension
            $path = Storage::path($this->import->file_path);
            $extension = strtolower(pathinfo($this->import->original_filename, PATHINFO_EXTENSION));

            // Estimate processing time based on file size
            $estimatedSeconds = $this->estimateProcessingTime($this->import->file_size);
            $estimatedCompletion = $startTime->addSeconds($estimatedSeconds);
            
            $this->import->estimated_completion_at = $estimatedCompletion;
            $this->import->save();

            // Process the file
            $totalRecords = 0;
            $successfulRecords = 0;
            $failedRecords = 0;

            if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
                // Process Excel or CSV files
                list($totalRecords, $successfulRecords, $failedRecords) = $this->processSpreadsheet($path, $extension);
            } elseif ($extension === 'pdf') {
                // Process PDF file
                // This is a placeholder for PDF processing logic
                list($totalRecords, $successfulRecords, $failedRecords) = $this->processPdf($path);
            } elseif (in_array($extension, ['doc', 'docx'])) {
                // Process Word document
                // This is a placeholder for Word document processing logic
                list($totalRecords, $successfulRecords, $failedRecords) = $this->processWordDocument($path);
            } else {
                throw new \Exception("Format file tidak didukung.");
            }

            // Update import record with results
            $this->import->status = 'processed';
            $this->import->processed_at = Carbon::now();
            $this->import->total_records = $totalRecords;
            $this->import->successful_records = $successfulRecords;
            $this->import->failed_records = $failedRecords;
            $this->import->processed_records = $successfulRecords;
            $this->import->save();

            Log::info("Import ID {$this->import->id} processed successfully.");

        } catch (\Exception $e) {
            // Update the import status to failed
            $this->import->status = 'failed';
            $this->import->error_message = $e->getMessage();
            $this->import->save();

            Log::error("Import ID {$this->import->id} failed: " . $e->getMessage());
        }
    }
    
    /**
     * Estimate processing time based on file size.
     *
     * @param int $fileSize File size in bytes
     * @return int Estimated seconds for processing
     */
    private function estimateProcessingTime($fileSize)
    {
        // Base processing time - 30 seconds minimum
        $baseTime = 30;
        
        // For each MB, add 15 seconds (rough estimate)
        $fileSizeMB = $fileSize / (1024 * 1024);
        $sizeBasedTime = $fileSizeMB * 15;
        
        // Cap maximum estimated time at 10 minutes (600 seconds)
        return min(600, $baseTime + $sizeBasedTime);
    }

    /**
     * Process spreadsheet files (Excel or CSV).
     *
     * @param  string  $path
     * @param  string  $extension
     * @return array
     */
    private function processSpreadsheet($path, $extension)
    {
        $totalRecords = 0;
        $successfulRecords = 0;
        $failedRecords = 0;

        try {
            // Create a reader based on file extension
            $reader = IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            
            // Load spreadsheet
            $spreadsheet = $reader->load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Get highest row and column
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
            
            // Assuming first row is header
            $headers = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $headers[] = trim($worksheet->getCellByColumnAndRow($col, 1)->getValue());
            }
            
            // Process data rows
            for ($row = 2; $row <= $highestRow; $row++) {
                $totalRecords++;
                
                $rowData = [];
                $isValidRow = false;
                
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $rowData[$headers[$col - 1]] = $value;
                    
                    // Check if at least one field has a value
                    if (!empty($value)) {
                        $isValidRow = true;
                    }
                }
                
                // Skip empty rows
                if (!$isValidRow) {
                    continue;
                }
                
                try {
                    // Create a new Pengiriman record
                    $this->createPengiriman($rowData);
                    $successfulRecords++;
                    
                    // Update progress every 10 records
                    if ($successfulRecords % 10 === 0) {
                        $this->updateProgress($totalRecords, $highestRow - 1);
                    }
                } catch (\Exception $e) {
                    $failedRecords++;
                    Log::error("Failed to import row {$row}: " . $e->getMessage());
                }
                
                // Simulate processing delay
                usleep(10000); // 10ms delay per record
            }
            
        } catch (\Exception $e) {
            Log::error("Error processing spreadsheet: " . $e->getMessage());
            throw $e;
        }
        
        return [$totalRecords, $successfulRecords, $failedRecords];
    }
    
    /**
     * Update the import progress.
     *
     * @param int $processed Number of processed records
     * @param int $total Total number of records
     * @return void
     */
    private function updateProgress($processed, $total)
    {
        if ($total === 0) {
            $progressPercentage = 100;
        } else {
            $progressPercentage = min(99, round(($processed / $total) * 100));
        }
        
        $this->import->progress_percentage = $progressPercentage;
        $this->import->save();
    }

    /**
     * Process PDF file.
     *
     * @param  string  $path
     * @return array
     */
    private function processPdf($path)
    {
        // This is a placeholder for PDF processing logic
        // In a real implementation, you would use a PDF parser library
        
        // For demonstration purposes, we'll simulate processing
        sleep(2); // Simulate processing time
        
        $totalRecords = rand(10, 50);
        $successfulRecords = rand(5, $totalRecords);
        $failedRecords = $totalRecords - $successfulRecords;
        
        return [$totalRecords, $successfulRecords, $failedRecords];
    }

    /**
     * Process Word document.
     *
     * @param  string  $path
     * @return array
     */
    private function processWordDocument($path)
    {
        // This is a placeholder for Word document processing logic
        // In a real implementation, you would use a Word document parser library
        
        // For demonstration purposes, we'll simulate processing
        sleep(2); // Simulate processing time
        
        $totalRecords = rand(10, 50);
        $successfulRecords = rand(5, $totalRecords);
        $failedRecords = $totalRecords - $successfulRecords;
        
        return [$totalRecords, $successfulRecords, $failedRecords];
    }

    /**
     * Create a new Pengiriman record from imported data.
     *
     * @param  array  $data
     * @return \App\Models\Pengiriman
     */
    private function createPengiriman($data)
    {
        // This is a placeholder for creating a Pengiriman record
        // In a real implementation, you would map the data to your Pengiriman model
        
        // For demonstration purposes, we'll just validate some required fields
        $requiredFields = ['no_resi', 'pengirim', 'penerima', 'alamat_tujuan'];
        
        foreach ($requiredFields as $field) {
            // Use appropriate field name mapping based on your expected headers
            $mappedField = $this->mapFieldName($field);
            
            if (!isset($data[$mappedField]) || empty($data[$mappedField])) {
                throw new \Exception("Field {$mappedField} is required.");
            }
        }
        
        // In a real implementation, you would create and save the Pengiriman model
        // For demo purposes, we're just simulating this step
        
        return true;
    }

    /**
     * Map internal field names to expected import headers.
     *
     * @param  string  $field
     * @return string
     */
    private function mapFieldName($field)
    {
        // Map internal field names to expected column headers in the import file
        $mapping = [
            'no_resi' => 'No. Resi',
            'pengirim' => 'Nama Pengirim',
            'penerima' => 'Nama Penerima',
            'alamat_tujuan' => 'Alamat Tujuan',
            // Add more mappings as needed
        ];
        
        return $mapping[$field] ?? $field;
    }
}