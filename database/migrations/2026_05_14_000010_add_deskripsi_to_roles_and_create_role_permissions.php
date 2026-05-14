<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom deskripsi ke tabel roles jika belum ada
        if (!Schema::hasColumn('roles', 'deskripsi')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->text('deskripsi')->nullable()->after('role');
            });
        }

        // Buat tabel role_permissions
        if (!Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('role_id');
                $table->string('menu_key');       // key unik menu, misal: 'barang', 'barang-masuk'
                $table->boolean('can_view')->default(false);
                $table->boolean('can_add')->default(false);
                $table->timestamps();

                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->unique(['role_id', 'menu_key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');

        if (Schema::hasColumn('roles', 'deskripsi')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('deskripsi');
            });
        }
    }
};
