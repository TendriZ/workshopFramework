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
            try {
                $provinsis = Provinsi::orderBy('nama')->get();

                return response()->json([
                    'status'  => 'success',
                    'code'    => 200,
                    'message' => 'Data provinsi berhasil diambil',
                    'data'    => $provinsis
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 500,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'data'    => []
                ], 500);
            }
        }

    public function kota($id_provinsi)
    {
        try {
            if (empty($id_provinsi)) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 400,
                    'message' => 'ID Provinsi tidak boleh kosong',
                    'data'    => []
                ], 400);
            }

            $kotas = Kota::where('id_provinsi', $id_provinsi)
                        ->orderBy('nama')
                        ->get();

            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Data kota berhasil diambil',
                'data'    => $kotas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data'    => []
            ], 500);
        }
    }

    /**
     * Get data Kecamatan berdasarkan Kota
     */
    public function kecamatan($id_kota)
    {
        try {
            if (empty($id_kota)) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 400,
                    'message' => 'ID Kota tidak boleh kosong',
                    'data'    => []
                ], 400);
            }

            $kecamatans = Kecamatan::where('id_kota', $id_kota)
                                  ->orderBy('nama')
                                  ->get();

            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Data kecamatan berhasil diambil',
                'data'    => $kecamatans
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data'    => []
            ], 500);
        }
    }

    /**
     * Get data Kelurahan berdasarkan Kecamatan
     */
    public function kelurahan($id_kecamatan)
    {
        try {
            if (empty($id_kecamatan)) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 400,
                    'message' => 'ID Kecamatan tidak boleh kosong',
                    'data'    => []
                ], 400);
            }

            $kelurahans = Kelurahan::where('id_kecamatan', $id_kecamatan)
                                  ->orderBy('nama')
                                  ->get();

            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Data kelurahan berhasil diambil',
                'data'    => $kelurahans
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data'    => []
            ], 500);
        }
    }
}