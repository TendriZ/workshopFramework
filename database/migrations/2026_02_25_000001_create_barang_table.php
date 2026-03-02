<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 8)->primary();
            $table->string('nama', 50);
            $table->integer('harga');
            $table->timestamp('timestamp')->useCurrent();
        });

        // Create sequence for auto-generating id_barang
        DB::unprepared("
            CREATE SEQUENCE IF NOT EXISTS barang_seq START 1;
        ");

        // Create trigger function to auto-generate id_barang (format: BRG00001)
        DB::unprepared("
            CREATE OR REPLACE FUNCTION generate_id_barang()
            RETURNS TRIGGER AS \$\$
            DECLARE
                new_id VARCHAR(8);
                next_val INTEGER;
            BEGIN
                next_val := nextval('barang_seq');
                new_id := 'BRG' || LPAD(next_val::TEXT, 5, '0');
                NEW.id_barang := new_id;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER trg_barang_id
            BEFORE INSERT ON barang
            FOR EACH ROW
            WHEN (NEW.id_barang IS NULL OR NEW.id_barang = '')
            EXECUTE FUNCTION generate_id_barang();
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_barang_id ON barang;');
        DB::unprepared('DROP FUNCTION IF EXISTS generate_id_barang();');
        DB::unprepared('DROP SEQUENCE IF EXISTS barang_seq;');
        Schema::dropIfExists('barang');
    }
};
