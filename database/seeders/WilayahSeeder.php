<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        // === PROVINSI ===
        DB::table('provinsi')->insert([
            ['id_provinsi' => 31, 'nama' => 'DKI Jakarta'],
            ['id_provinsi' => 32, 'nama' => 'Jawa Barat'],
            ['id_provinsi' => 33, 'nama' => 'Jawa Tengah'],
            ['id_provinsi' => 34, 'nama' => 'DI Yogyakarta'],
            ['id_provinsi' => 35, 'nama' => 'Jawa Timur'],
        ]);

        // === KOTA ===
        DB::table('kota')->insert([
            // DKI Jakarta
            ['id_kota' => 3101, 'id_provinsi' => 31, 'nama' => 'Kepulauan Seribu'],
            ['id_kota' => 3171, 'id_provinsi' => 31, 'nama' => 'Kota Jakarta Selatan'],
            ['id_kota' => 3172, 'id_provinsi' => 31, 'nama' => 'Kota Jakarta Timur'],
            ['id_kota' => 3173, 'id_provinsi' => 31, 'nama' => 'Kota Jakarta Pusat'],
            ['id_kota' => 3174, 'id_provinsi' => 31, 'nama' => 'Kota Jakarta Barat'],
            ['id_kota' => 3175, 'id_provinsi' => 31, 'nama' => 'Kota Jakarta Utara'],
            // Jawa Barat
            ['id_kota' => 3201, 'id_provinsi' => 32, 'nama' => 'Kabupaten Bogor'],
            ['id_kota' => 3273, 'id_provinsi' => 32, 'nama' => 'Kota Bandung'],
            ['id_kota' => 3275, 'id_provinsi' => 32, 'nama' => 'Kota Bekasi'],
            ['id_kota' => 3276, 'id_provinsi' => 32, 'nama' => 'Kota Depok'],
            // Jawa Tengah
            ['id_kota' => 3374, 'id_provinsi' => 33, 'nama' => 'Kota Semarang'],
            ['id_kota' => 3372, 'id_provinsi' => 33, 'nama' => 'Kota Surakarta'],
            ['id_kota' => 3371, 'id_provinsi' => 33, 'nama' => 'Kota Magelang'],
            // DI Yogyakarta
            ['id_kota' => 3471, 'id_provinsi' => 34, 'nama' => 'Kota Yogyakarta'],
            ['id_kota' => 3401, 'id_provinsi' => 34, 'nama' => 'Kabupaten Kulon Progo'],
            ['id_kota' => 3402, 'id_provinsi' => 34, 'nama' => 'Kabupaten Bantul'],
            ['id_kota' => 3403, 'id_provinsi' => 34, 'nama' => 'Kabupaten Gunungkidul'],
            ['id_kota' => 3404, 'id_provinsi' => 34, 'nama' => 'Kabupaten Sleman'],
            // Jawa Timur
            ['id_kota' => 3578, 'id_provinsi' => 35, 'nama' => 'Kota Surabaya'],
            ['id_kota' => 3573, 'id_provinsi' => 35, 'nama' => 'Kota Malang'],
            ['id_kota' => 3571, 'id_provinsi' => 35, 'nama' => 'Kota Kediri'],
        ]);

        // === KECAMATAN ===
        DB::table('kecamatan')->insert([
            // Jakarta Selatan
            ['id_kecamatan' => 317101, 'id_kota' => 3171, 'nama' => 'Tebet'],
            ['id_kecamatan' => 317102, 'id_kota' => 3171, 'nama' => 'Setiabudi'],
            ['id_kecamatan' => 317103, 'id_kota' => 3171, 'nama' => 'Mampang Prapatan'],
            // Jakarta Pusat
            ['id_kecamatan' => 317301, 'id_kota' => 3173, 'nama' => 'Menteng'],
            ['id_kecamatan' => 317302, 'id_kota' => 3173, 'nama' => 'Tanah Abang'],
            ['id_kecamatan' => 317303, 'id_kota' => 3173, 'nama' => 'Gambir'],
            // Kota Bandung
            ['id_kecamatan' => 327301, 'id_kota' => 3273, 'nama' => 'Coblong'],
            ['id_kecamatan' => 327302, 'id_kota' => 3273, 'nama' => 'Bandung Wetan'],
            ['id_kecamatan' => 327303, 'id_kota' => 3273, 'nama' => 'Cicendo'],
            // Kota Bekasi
            ['id_kecamatan' => 327501, 'id_kota' => 3275, 'nama' => 'Bekasi Timur'],
            ['id_kecamatan' => 327502, 'id_kota' => 3275, 'nama' => 'Bekasi Barat'],
            ['id_kecamatan' => 327503, 'id_kota' => 3275, 'nama' => 'Bekasi Selatan'],
            // Kota Semarang
            ['id_kecamatan' => 337401, 'id_kota' => 3374, 'nama' => 'Semarang Tengah'],
            ['id_kecamatan' => 337402, 'id_kota' => 3374, 'nama' => 'Semarang Selatan'],
            ['id_kecamatan' => 337403, 'id_kota' => 3374, 'nama' => 'Gajahmungkur'],
            // Kota Yogyakarta
            ['id_kecamatan' => 347101, 'id_kota' => 3471, 'nama' => 'Gedongtengen'],
            ['id_kecamatan' => 347102, 'id_kota' => 3471, 'nama' => 'Gondokusuman'],
            ['id_kecamatan' => 347103, 'id_kota' => 3471, 'nama' => 'Danurejan'],
            // Kabupaten Sleman
            ['id_kecamatan' => 340401, 'id_kota' => 3404, 'nama' => 'Depok'],
            ['id_kecamatan' => 340402, 'id_kota' => 3404, 'nama' => 'Mlati'],
            ['id_kecamatan' => 340403, 'id_kota' => 3404, 'nama' => 'Gamping'],
            // Kota Surabaya
            ['id_kecamatan' => 357801, 'id_kota' => 3578, 'nama' => 'Tegalsari'],
            ['id_kecamatan' => 357802, 'id_kota' => 3578, 'nama' => 'Genteng'],
            ['id_kecamatan' => 357803, 'id_kota' => 3578, 'nama' => 'Gubeng'],
        ]);

        // === KELURAHAN ===
        DB::table('kelurahan')->insert([
            // Tebet
            ['id_kecamatan' => 317101, 'nama' => 'Tebet Barat'],
            ['id_kecamatan' => 317101, 'nama' => 'Tebet Timur'],
            ['id_kecamatan' => 317101, 'nama' => 'Kebon Baru'],
            // Setiabudi
            ['id_kecamatan' => 317102, 'nama' => 'Setia Budi'],
            ['id_kecamatan' => 317102, 'nama' => 'Karet'],
            ['id_kecamatan' => 317102, 'nama' => 'Karet Kuningan'],
            // Menteng
            ['id_kecamatan' => 317301, 'nama' => 'Menteng'],
            ['id_kecamatan' => 317301, 'nama' => 'Pegangsaan'],
            ['id_kecamatan' => 317301, 'nama' => 'Cikini'],
            // Tanah Abang
            ['id_kecamatan' => 317302, 'nama' => 'Bendungan Hilir'],
            ['id_kecamatan' => 317302, 'nama' => 'Karet Tengsin'],
            ['id_kecamatan' => 317302, 'nama' => 'Kebon Melati'],
            // Coblong
            ['id_kecamatan' => 327301, 'nama' => 'Dago'],
            ['id_kecamatan' => 327301, 'nama' => 'Lebak Siliwangi'],
            ['id_kecamatan' => 327301, 'nama' => 'Cipaganti'],
            // Bandung Wetan
            ['id_kecamatan' => 327302, 'nama' => 'Cihapit'],
            ['id_kecamatan' => 327302, 'nama' => 'Tamansari'],
            // Bekasi Timur
            ['id_kecamatan' => 327501, 'nama' => 'Aren Jaya'],
            ['id_kecamatan' => 327501, 'nama' => 'Bekasi Jaya'],
            ['id_kecamatan' => 327501, 'nama' => 'Duren Jaya'],
            // Semarang Tengah
            ['id_kecamatan' => 337401, 'nama' => 'Pekunden'],
            ['id_kecamatan' => 337401, 'nama' => 'Karang Kidul'],
            ['id_kecamatan' => 337401, 'nama' => 'Sekayu'],
            // Gedongtengen
            ['id_kecamatan' => 347101, 'nama' => 'Pringgokusuman'],
            ['id_kecamatan' => 347101, 'nama' => 'Sosromenduran'],
            // Gondokusuman
            ['id_kecamatan' => 347102, 'nama' => 'Baciro'],
            ['id_kecamatan' => 347102, 'nama' => 'Demangan'],
            ['id_kecamatan' => 347102, 'nama' => 'Terban'],
            // Depok (Sleman)
            ['id_kecamatan' => 340401, 'nama' => 'Caturtunggal'],
            ['id_kecamatan' => 340401, 'nama' => 'Condongcatur'],
            ['id_kecamatan' => 340401, 'nama' => 'Maguwoharjo'],
            // Mlati
            ['id_kecamatan' => 340402, 'nama' => 'Sinduadi'],
            ['id_kecamatan' => 340402, 'nama' => 'Sendangadi'],
            ['id_kecamatan' => 340402, 'nama' => 'Tlogoadi'],
            // Tegalsari (Surabaya)
            ['id_kecamatan' => 357801, 'nama' => 'Tegalsari'],
            ['id_kecamatan' => 357801, 'nama' => 'Wonorejo'],
            ['id_kecamatan' => 357801, 'nama' => 'Kedungdoro'],
            // Genteng
            ['id_kecamatan' => 357802, 'nama' => 'Genteng'],
            ['id_kecamatan' => 357802, 'nama' => 'Embong Kaliasin'],
            ['id_kecamatan' => 357802, 'nama' => 'Kapasari'],
        ]);
    }
}
