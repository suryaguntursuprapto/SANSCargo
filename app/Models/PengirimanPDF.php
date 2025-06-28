<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanPDF extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengirimanPDF';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_resi',
        'tanggal_pengiriman',
        'asal',
        'tujuan',
        'status',
        'tipe',
        'branch',
        'tipe_pengiriman',
        'nama_pengirim',
        'telepon_pengirim',
        'email_pengirim',
        'alamat_pengirim',
        'nama_penerima',
        'telepon_penerima',
        'email_penerima',
        'alamat_penerima',
        'jenis_layanan',
        'asuransi_pengiriman',
        'packing_pengiriman',
        'metode_pembayaran',
        'diskon',
        'total_sub_biaya',
        'total_biaya_pengiriman',
        'catatan_tambahan',
        'import_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_pengiriman' => 'date',
        'diskon' => 'float',
        'total_sub_biaya' => 'float',
        'total_biaya_pengiriman' => 'float',
    ];

    /**
     * Get the import that this pengiriman belongs to.
     */
    public function import()
    {
        return $this->belongsTo(PengirimanImport::class, 'import_id');
    }

    /**
     * Get the items for this pengiriman.
     */
    public function items()
    {
        return $this->hasMany(PengirimanItem::class);
    }

    /**
     * Calculate the dimensions volume.
     * 
     * @return float
     */
    public function calculateVolumeWeight()
    {
        $totalVolumeWeight = 0;
        
        foreach ($this->items as $item) {
            // Volume in cm³ / 6000 = Volumetric weight in kg
            $volumeWeight = ($item->panjang_barang * $item->lebar_barang * $item->tinggi_barang) / 6000;
            $totalVolumeWeight += $volumeWeight;
        }
        
        return $totalVolumeWeight;
    }

    /**
     * Get the total actual weight.
     * 
     * @return float
     */
    public function getTotalWeight()
    {
        return $this->items->sum('berat_barang');
    }

    /**
     * Get the chargeable weight (higher of actual weight and volumetric weight).
     * 
     * @return float
     */
    public function getChargeableWeight()
    {
        $actualWeight = $this->getTotalWeight();
        $volumeWeight = $this->calculateVolumeWeight();
        
        return max($actualWeight, $volumeWeight);
    }

    /**
     * Generate a tracking URL.
     * 
     * @return string
     */
    public function getTrackingUrl()
    {
        return url('tracking/' . $this->nomor_resi);
    }

    /**
     * Generate a new receipt number.
     * 
     * @return string
     */
    public static function generateReceiptNumber()
    {
        $prefix = 'CSM';
        $date = now()->format('ymd');
        $random = mt_rand(1000, 9999);
        
        // Check if the generated number already exists
        $exists = true;
        $number = null;
        
        while ($exists) {
            $number = $prefix . $date . $random;
            $exists = self::where('nomor_resi', $number)->exists();
            
            if ($exists) {
                // Try a different random number
                $random = mt_rand(1000, 9999);
            }
        }
        
        return $number;
    }
}