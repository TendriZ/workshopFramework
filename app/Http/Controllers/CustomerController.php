<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = \App\Models\Customer::all();
        return view('customer.index', compact('customers'));
    }

    public function create1()
    {
        return view('customer.create1');
    }

    public function store1(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'foto' => 'required'
        ]);

        $customer = new \App\Models\Customer();
        $customer->nama = $request->nama;
        $customer->alamat = $request->alamat;
        $customer->provinsi = $request->provinsi;
        $customer->kota = $request->kota;
        $customer->kecamatan = $request->kecamatan;
        $customer->kodepos_kelurahan = $request->kodepos_kelurahan;
        
        // Save base64 image as blob
        if ($request->foto) {
            $image_parts = explode(";base64,", $request->foto);
            $image_base64 = base64_decode($image_parts[1]);
            $customer->foto_blob = $image_base64;
        }

        $customer->save();

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan (Blob)');
    }

    public function create2()
    {
        return view('customer.create2');
    }

    public function store2(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'foto' => 'required'
        ]);

        $customer = new \App\Models\Customer();
        $customer->nama = $request->nama;
        $customer->alamat = $request->alamat;
        $customer->provinsi = $request->provinsi;
        $customer->kota = $request->kota;
        $customer->kecamatan = $request->kecamatan;
        $customer->kodepos_kelurahan = $request->kodepos_kelurahan;
        
        // Save base64 image as file
        if ($request->foto) {
            $image_parts = explode(";base64,", $request->foto);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file_name = uniqid() . '.png';
            $file_path = public_path('uploads/customers/' . $file_name);
            
            if (!file_exists(public_path('uploads/customers'))) {
                mkdir(public_path('uploads/customers'), 0755, true);
            }
            file_put_contents($file_path, $image_base64);
            $customer->foto_path = 'uploads/customers/' . $file_name;
        }

        $customer->save();

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan (File Path)');
    }
}
