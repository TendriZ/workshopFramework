<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration untuk data wilayah Indonesia dengan kode BPS asli
     * (bukan auto-increment, menggunakan kode wilayah standar)
     */
    public function up(): void
    {
        // Tabel Provinsi
        Schema::create('provinsi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_provinsi')->primary(); // ← NON auto-increment
            $table->string('nama', 100);
            
            // Index untuk performa
            $table->index('nama');
        });

        // Tabel Kota/Kabupaten
        Schema::create('kota', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kota')->primary(); // ← NON auto-increment
            $table->unsignedBigInteger('id_provinsi');
            $table->string('nama', 100);
            
            // Foreign key dengan cascade delete
            $table->foreign('id_provinsi')
                  ->references('id_provinsi')
                  ->on('provinsi')
                  ->cascadeOnDelete();
            
            // Index untuk performa
            $table->index('id_provinsi');
            $table->index('nama');
        });

        // Tabel Kecamatan
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kecamatan')->primary(); // ← NON auto-increment
            $table->unsignedBigInteger('id_kota');
            $table->string('nama', 100);
            
            // Foreign key dengan cascade delete
            $table->foreign('id_kota')
                  ->references('id_kota')
                  ->on('kota')
                  ->cascadeOnDelete();
            
            // Index untuk performa
            $table->index('id_kota');
            $table->index('nama');
        });

        // Tabel Kelurahan/Desa
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->id('id_kelurahan'); // ← AUTO-INCREMENT (kelurahan banyak, pakai auto)
            $table->unsignedBigInteger('id_kecamatan');
            $table->string('nama', 100);
            
            // Foreign key dengan cascade delete
            $table->foreign('id_kecamatan')
                  ->references('id_kecamatan')
                  ->on('kecamatan')
                  ->cascadeOnDelete();
            
            // Index untuk performa
            $table->index('id_kecamatan');
            $table->index('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop dalam urutan terbalik (child dulu, parent terakhir)
        Schema::dropIfExists('kelurahan');
        Schema::dropIfExists('kecamatan');
        Schema::dropIfExists('kota');
        Schema::dropIfExists('provinsi');
    }
};