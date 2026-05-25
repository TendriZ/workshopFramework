<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_nfc', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 50)->unique();
            $table->string('nama_kartu', 100);
            $table->string('jenis', 50)->default('peserta'); // peserta, dosen, staff
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->foreignId('kartu_nfc_id')->nullable()->constrained('kartu_nfc')->onDelete('set null');
            $table->string('kelas', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kartu_nfc_id')->constrained('kartu_nfc');
            $table->timestamp('waktu_scan')->nullable();
            $table->enum('status', ['masuk', 'keluar'])->default('masuk');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
        Schema::dropIfExists('peserta');
        Schema::dropIfExists('kartu_nfc');
    }
};