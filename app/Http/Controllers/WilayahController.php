<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function provinsi()
    {
        return response()->json(Provinsi::orderBy('nama')->get());
    }

    public function kota($id_provinsi)
    {
        return response()->json(
            Kota::where('id_provinsi', $id_provinsi)->orderBy('nama')->get()
        );
    }

    public function kecamatan($id_kota)
    {
        return response()->json(
            Kecamatan::where('id_kota', $id_kota)->orderBy('nama')->get()
        );
    }

    public function kelurahan($id_kecamatan)
    {
        return response()->json(
            Kelurahan::where('id_kecamatan', $id_kecamatan)->orderBy('nama')->get()
        );
    }
}
