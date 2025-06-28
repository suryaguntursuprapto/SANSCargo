<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsiPengiriman extends Model
{
    use HasFactory;

    protected $table = 'opsi_pengiriman';

    protected $fillable = [
        'pengiriman_id',
        'tipe_pengiriman',
        'jenis_layanan',
        'asuransi',
        'packing_tambahan',
        'branch_id'
    ];

    protected $casts = [
        'asuransi' => 'boolean',
        'packing_tambahan' => 'boolean',
    ];

    /**
     * Relationship to DetailPengiriman
     */
    public function pengiriman()
    {
        return $this->belongsTo(DetailPengiriman::class, 'pengiriman_id');
    }

    /**
     * Relationship to Branch
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get service color based on type
     */
    public function getServiceColorAttribute()
    {
        switch ($this->jenis_layanan) {
            case 'Express':
                return 'bg-red-100 text-red-800';
            case 'Regular':
                return 'bg-blue-100 text-blue-800';
            case 'Same Day':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Get delivery type icon
     */
    public function getDeliveryIconAttribute()
    {
        return $this->tipe_pengiriman === 'Dijemput' ? 'fa-hand-paper' : 'fa-store';
    }

    /**
     * Get estimated delivery days
     */
    public function getEstimatedDaysAttribute()
    {
        switch ($this->jenis_layanan) {
            case 'Express':
                return '1-2 hari';
            case 'Regular':
                return '2-3 hari';
            case 'Same Day':
                return 'Hari ini';
            default:
                return 'N/A';
        }
    }
}