<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        
        // Create the related tables
        Schema::create('opsi_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengiriman_id')->constrained('detail_pengiriman')->onDelete('cascade');
            $table->string('tipe_pengiriman');
            $table->string('jenis_layanan');
            $table->boolean('asuransi')->default(false);
            $table->boolean('packing_tambahan')->default(false);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();
        });
        
        Schema::create('pengirim_penerima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengiriman_id')->constrained('detail_pengiriman')->onDelete('cascade');
            $table->string('nama_pengirim');
            $table->string('telepon_pengirim');
            $table->string('email_pengirim')->nullable();
            $table->text('alamat_pengirim');
            $table->string('nama_penerima');
            $table->string('telepon_penerima');
            $table->string('email_penerima')->nullable();
            $table->text('alamat_penerima');
            $table->timestamps();
        });
        
        Schema::create('informasi_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengiriman_id')->constrained('detail_pengiriman')->onDelete('cascade');
            $table->decimal('total_sub_biaya', 12, 2);
            $table->decimal('total_biaya_pengiriman', 12, 2);
            $table->string('metode_pembayaran');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('informasi_pembayaran');
        Schema::dropIfExists('pengirim_penerima');
        Schema::dropIfExists('opsi_pengiriman');
    }
};