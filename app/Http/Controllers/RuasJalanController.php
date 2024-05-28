<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RuasJalanController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'paths' => 'required|string',
            'desa_id' => 'required|integer',
            'kode_ruas' => 'required|string',
            'nama_ruas' => 'required|string',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'eksisting_id' => 'required|integer',
            'kondisi_id' => 'required|integer',
            'jenisjalan_id' => 'required|integer',
            'keterangan' => 'required|string',
        ]);

        // Lakukan proses penyimpanan data sesuai kebutuhan Anda.
        // Contohnya menyimpan data ke dalam model:
        // $ruasJalan = RuasJalan::create($validatedData);

        return response()->json(['message' => 'Polyline saved successfully!', 'data' => $validatedData]);
    }
}
