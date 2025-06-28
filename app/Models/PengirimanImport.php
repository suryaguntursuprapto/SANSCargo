<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'file_path',
        'file_size',
        'file_type',
        'status',
        'processed_at',
        'processed_by',
        'total_records',
        'successful_records',
        'failed_records',
        'notes',
    ];
    
    protected $casts = [
        'processed_at' => 'datetime',
    ];

    // In your PengirimanImport model
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB';
    }
}