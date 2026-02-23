<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PDFController extends Controller
{
    /**
     * Halaman pilihan cetak PDF
     */
    public function index()
    {
        return view('doc.index');
    }

    /**
     * Form input Sertifikat
     */
    public function sertifikat()
    {
        return view('doc.sertifikat-form');
    }

    /**
     * Generate Sertifikat (Landscape A4)
     */
    public function generateSertifikat(Request $request)
    {
        $validated = $request->validate([
            'nomor'       => 'required|string|max:100',
            'nama'        => 'required|string|max:255',
            'jabatan'     => 'required|string|max:255',
            'dekan'       => 'required|string|max:255',
            'koordinator' => 'required|string|max:255',
            'ketua'       => 'required|string|max:255',
            'tanggal'     => 'required|date',
        ]);

        $pdf = Pdf::loadView('doc.sertifikat', $validated)
            ->setPaper('a4', 'landscape')
            ->setWarnings(false);

        $fileName = 'sertifikat_' . str_replace(' ', '-', $validated['nama']) . '.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * Form input Undangan / Surat Resmi
     */
    public function undangan()
    {
        return view('doc.undangan-form');
    }

    /**
     * Generate Undangan / Surat Resmi (Portrait A4)
     */
    public function generateUndangan(Request $request)
    {
        $validated = $request->validate([
            'nomor'         => 'required|string|max:100',
            'lampiran'      => 'required|string|max:100',
            'perihal'       => 'required|string|max:255',
            'kepada'        => 'required|string|max:255',
            'acara'         => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'waktu'         => 'required|string|max:100',
            'tempat'        => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'dekan'         => 'required|string|max:255',
            'nip_dekan'     => 'required|string|max:100',
        ]);

        // Format tanggal ke bahasa Indonesia (contoh: Senin, 10 Maret 2025)
        Carbon::setLocale('id');
        $validated['tanggal'] = Carbon::parse($validated['tanggal'])
            ->translatedFormat('l, d F Y');
        $validated['tanggal_surat'] = Carbon::parse($validated['tanggal_surat'])
            ->translatedFormat('d F Y');

        $pdf = Pdf::loadView('doc.undangan', $validated)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $fileName = 'undangan_' . str_replace(' ', '-', $validated['perihal']) . '.pdf';

        return $pdf->stream($fileName);
    }
}
