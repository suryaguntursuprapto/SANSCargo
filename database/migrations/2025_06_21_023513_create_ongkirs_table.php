<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOngkirsTable extends Migration
{
    public function up()
    {
        Schema::create('ongkir', function (Blueprint $table) {
            $table->id();
            $table->string('kota_asal');
            $table->string('kota_tujuan');
            $table->string('jenis_layanan'); // Express, Regular, Economy
            $table->decimal('berat_minimum', 8, 2); // dalam kg
            $table->decimal('berat_maksimum', 8, 2); // dalam kg
            $table->decimal('harga_per_kg', 10, 2);
            $table->decimal('harga_minimum', 10, 2);
            $table->integer('estimasi_hari');
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['kota_asal', 'kota_tujuan']);
            $table->index('jenis_layanan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ongkir');
    }
}

