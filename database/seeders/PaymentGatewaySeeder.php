<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vendor')->upsert([
            ['idvendor' => 1, 'nama_vendor' => 'Kantin Bu Rini'],
            ['idvendor' => 2, 'nama_vendor' => 'Kantin Pak Joko'],
        ], ['idvendor'], ['nama_vendor']);

        DB::table('menu')->upsert([
            ['idmenu' => 1, 'idvendor' => 1, 'nama_menu' => 'Nasi Goreng', 'harga' => 18000, 'path_gambar' => null],
            ['idmenu' => 2, 'idvendor' => 1, 'nama_menu' => 'Mie Ayam', 'harga' => 15000, 'path_gambar' => null],
            ['idmenu' => 3, 'idvendor' => 2, 'nama_menu' => 'Ayam Geprek', 'harga' => 20000, 'path_gambar' => null],
            ['idmenu' => 4, 'idvendor' => 2, 'nama_menu' => 'Es Teh Manis', 'harga' => 5000, 'path_gambar' => null],
        ], ['idmenu'], ['idvendor', 'nama_menu', 'harga', 'path_gambar']);
    }
}
