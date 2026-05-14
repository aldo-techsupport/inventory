<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Salin data satuan_barang ke kolom baru sebelum drop FK
        Schema::table('barang', function (Blueprint $table) {
            $table->string('satuan')->nullable()->after('jenis_id');
        });

        // Migrasi data dari relasi ke kolom teks
        DB::statement('
            UPDATE barang b
            LEFT JOIN satuan s ON b.satuan_id = s.id
            SET b.satuan = s.satuan_barang
        ');

        // Hapus FK dan kolom satuan_id
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign(['satuan_id']);
            $table->dropColumn('satuan_id');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->unsignedBigInteger('satuan_id')->nullable()->after('jenis_id');
            $table->foreign('satuan_id')->references('id')->on('satuan')->nullOnDelete();
            $table->dropColumn('satuan');
        });
    }
};
