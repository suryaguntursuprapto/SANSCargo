<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create the detail_pengiriman table first
        Schema::create('detail_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->string('no_resi', 10)->nullable()->unique();
            $table->string('asal')->nullable();
            $table->string('tujuan')->nullable();
            $table->text('detail_alamat')->nullable();
            $table->string('status')->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
        
        // Then create the barang_pengiriman table which references detail_pengiriman
        Schema::create('barang_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengiriman_id')->constrained('detail_pengiriman')->onDelete('cascade');
            $table->string('nama_barang');
            $table->string('jenis_barang');
            $table->text('deskripsi_barang');
            $table->decimal('berat_barang', 8, 2); // kg
            $table->decimal('panjang_barang', 8, 2); // cm
            $table->decimal('lebar_barang', 8, 2); // cm
            $table->decimal('tinggi_barang', 8, 2); // cm
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop tables in reverse order
        Schema::dropIfExists('barang_pengiriman');
        Schema::dropIfExists('detail_pengiriman');
    }
};