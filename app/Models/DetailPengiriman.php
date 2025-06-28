<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPengiriman extends Model
{
    use HasFactory;

    protected $table = 'detail_pengiriman';

    protected $fillable = [
        'no_resi',
        'asal',
        'tujuan',
        'detail_alamat',
        'status',
        'catatan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship to BarangPengiriman
     */
    public function barangPengiriman()
    {
        return $this->hasMany(BarangPengiriman::class, 'pengiriman_id');
    }

    /**
     * Relationship to OpsiPengiriman
     */
    public function opsiPengiriman()
    {
        return $this->hasOne(OpsiPengiriman::class, 'pengiriman_id');
    }

    /**
     * Relationship to PengirimPenerima
     */
    public function pengirimPenerima()
    {
        return $this->hasOne(PengirimPenerima::class, 'pengiriman_id');
    }

    /**
     * Relationship to InformasiPembayaran
     */
    public function informasiPembayaran()
    {
        return $this->hasOne(InformasiPembayaran::class, 'pengiriman_id');
    }

    /**
     * Generate unique resi number
     */
    public static function generateResi()
    {
        do {
            // Generate resi with pattern: CCM + YYYYMMDD + 4 digit random
            $resi = 'CCM' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('no_resi', $resi)->exists());

        return $resi;
    }

    /**
     * Get status color attribute
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'Draft':
                return 'bg-gray-100 text-gray-800';
            case 'processed':
                return 'bg-blue-100 text-blue-800';
            case 'picked_up':
                return 'bg-yellow-100 text-yellow-800';
            case 'in_transit':
                return 'bg-orange-100 text-orange-800';
            case 'delivered':
                return 'bg-green-100 text-green-800';
            case 'cancelled':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'Draft' => 'Draft',
            'processed' => 'Diproses',
            'picked_up' => 'Dijemput',
            'in_transit' => 'Dalam Perjalanan',
            'delivered' => 'Terkirim',
            'cancelled' => 'Dibatalkan'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get total weight
     */
    public function getTotalWeightAttribute()
    {
        return $this->barangPengiriman->sum('berat_barang');
    }

    /**
     * Get total items
     */
    public function getTotalItemsAttribute()
    {
        return $this->barangPengiriman->count();
    }

    /**
     * Get total cost
     */
    public function getTotalCostAttribute()
    {
        return $this->informasiPembayaran ? $this->informasiPembayaran->total_biaya_pengiriman : 0;
    }

    /**
     * Check if pengiriman has resi
     */
    public function hasResi()
    {
        return !empty($this->no_resi);
    }

    /**
     * Check if pengiriman is completed
     */
    public function isCompleted()
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if pengiriman can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['Draft', 'processed']);
    }

    /**
     * Check if pengiriman can be deleted
     */
    public function canBeDeleted()
    {
        return in_array($this->status, ['Draft', 'cancelled']);
    }

    /**
     * Scope for specific status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for has resi
     */
    public function scopeHasResi($query)
    {
        return $query->whereNotNull('no_resi')->where('no_resi', '!=', '');
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }
}