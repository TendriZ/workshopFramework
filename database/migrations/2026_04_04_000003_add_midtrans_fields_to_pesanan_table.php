<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('order_id_midtrans', 100)->nullable()->unique()->after('status_bayar');
            $table->text('snap_token')->nullable()->after('order_id_midtrans');
            $table->json('midtrans_response')->nullable()->after('snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['order_id_midtrans', 'snap_token', 'midtrans_response']);
        });
    }
};
