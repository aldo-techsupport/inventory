<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->json('gambar')->nullable();
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->unsignedBigInteger('jenis_id')->nullable();
            $table->unsignedBigInteger('satuan_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('jenis_id')->references('id')->on('jenis')->nullOnDelete();
            $table->foreign('satuan_id')->references('id')->on('satuan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
