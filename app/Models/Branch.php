<?php

// app/Models/Branch.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_branch',
        'nama_branch', 
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'status',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'status' => 'boolean'
    ];

    // Relationship dengan users
    public function users()
    {
        return $this->hasMany(User::class, 'branch', 'nama_branch');
    }

    // Scope untuk branch aktif
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}