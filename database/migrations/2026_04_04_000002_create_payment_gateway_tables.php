<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->id('idvendor');
            $table->string('nama_vendor', 255);
        });

        Schema::create('menu', function (Blueprint $table) {
            $table->id('idmenu');
            $table->string('nama_menu', 255);
            $table->integer('harga');
            $table->string('path_gambar', 255)->nullable();
            $table->unsignedBigInteger('idvendor');
            $table->foreign('idvendor')->references('idvendor')->on('vendor')->cascadeOnDelete();
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('idpesanan');
            $table->unsignedBigInteger('idvendor');
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->string('nama', 255);
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('total');
            $table->string('metode_bayar', 50)->nullable();
            $table->smallInteger('status_bayar')->default(0);

            $table->foreign('idvendor')->references('idvendor')->on('vendor')->cascadeOnDelete();
            $table->foreign('id_customer')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('iddetail_pesanan');
            $table->unsignedBigInteger('idmenu');
            $table->unsignedBigInteger('idpesanan');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->timestamp('timestamp')->useCurrent();
            $table->string('catatan', 255)->nullable();

            $table->foreign('idmenu')->references('idmenu')->on('menu')->cascadeOnDelete();
            $table->foreign('idpesanan')->references('idpesanan')->on('pesanan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('vendor');
    }
};
