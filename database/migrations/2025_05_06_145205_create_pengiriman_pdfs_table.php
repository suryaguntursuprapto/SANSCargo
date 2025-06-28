<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengiriman_pdfs', function (Blueprint $table) {
            $table->id();
            
            // Basic shipping information
            $table->string('nomor_resi')->unique();
            $table->date('tanggal_pengiriman');
            $table->string('asal');
            $table->string('tujuan');
            $table->string('status')->default('pending');
            $table->string('tipe')->nullable();
            
            // Sender information
            $table->string('branch')->nullable();
            $table->string('tipe_pengiriman')->nullable();
            $table->string('nama_pengirim');
            $table->string('telepon_pengirim')->nullable();
            $table->string('email_pengirim')->nullable();
            $table->text('alamat_pengirim');
            
            // Recipient information
            $table->string('nama_penerima');
            $table->string('telepon_penerima')->nullable();
            $table->string('email_penerima')->nullable();
            $table->text('alamat_penerima');
            
            // Service information
            $table->string('jenis_layanan')->nullable();
            $table->string('asuransi_pengiriman')->nullable();
            $table->string('packing_pengiriman')->nullable();
            
            // Payment information
            $table->string('metode_pembayaran')->nullable();
            $table->decimal('diskon', 5, 2)->nullable();
            $table->decimal('total_sub_biaya', 10, 2)->nullable();
            $table->decimal('total_biaya_pengiriman', 10, 2)->nullable();
            
            // Additional notes
            $table->text('catatan_tambahan')->nullable();
            
            // Import reference
            $table->foreignId('import_id')->nullable()->constrained('pengiriman_imports')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_pengiriman');
        Schema::dropIfExists('pengiriman_pdfs');
    }
};