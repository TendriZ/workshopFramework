<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            ['nama' => 'Buku Tulis Sidu', 'harga' => 5000],
            ['nama' => 'Pensil 2B Faber Castell', 'harga' => 3500],
            ['nama' => 'Pulpen Pilot G2', 'harga' => 12000],
            ['nama' => 'Penghapus Staedtler', 'harga' => 4000],
            ['nama' => 'Penggaris 30cm', 'harga' => 7500],
            ['nama' => 'Spidol Snowman', 'harga' => 6000],
            ['nama' => 'Tipe-X Kenko', 'harga' => 8500],
            ['nama' => 'Stabilo Boss Kuning', 'harga' => 15000],
            ['nama' => 'Map Plastik F4', 'harga' => 3000],
            ['nama' => 'Lem Kertas UHU', 'harga' => 9000],
            ['nama' => 'Gunting Joyko', 'harga' => 11000],
            ['nama' => 'Rautan Pensil', 'harga' => 2500],
        ];

        foreach ($barangs as $barang) {
            DB::table('barang')->insert([
                'nama' => $barang['nama'],
                'harga' => $barang['harga'],
                'timestamp' => now(),
            ]);
        }
    }
}
