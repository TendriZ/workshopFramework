<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinsi', function (Blueprint $table) {
            $table->id('id_provinsi');
            $table->string('nama', 100);
        });

        Schema::create('kota', function (Blueprint $table) {
            $table->id('id_kota');
            $table->unsignedBigInteger('id_provinsi');
            $table->string('nama', 100);
            $table->foreign('id_provinsi')->references('id_provinsi')->on('provinsi')->cascadeOnDelete();
        });

        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id('id_kecamatan');
            $table->unsignedBigInteger('id_kota');
            $table->string('nama', 100);
            $table->foreign('id_kota')->references('id_kota')->on('kota')->cascadeOnDelete();
        });

        Schema::create('kelurahan', function (Blueprint $table) {
            $table->id('id_kelurahan');
            $table->unsignedBigInteger('id_kecamatan');
            $table->string('nama', 100);
            $table->foreign('id_kecamatan')->references('id_kecamatan')->on('kecamatan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelurahan');
        Schema::dropIfExists('kecamatan');
        Schema::dropIfExists('kota');
        Schema::dropIfExists('provinsi');
    }
};
