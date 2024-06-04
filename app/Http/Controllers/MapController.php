<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MapController extends Controller
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

        $apiToken = Session::get('api_token');

        if (!$apiToken) {
            return response()->json(['message' => 'API token not found'], 401);
        }

        try {
            $response = Http::withToken($apiToken)->post('https://gisapis.manpits.xyz/api/ruasjalan', $validatedData);

            if ($response->successful()) {
                return response()->json(['message' => 'Polyline saved successfully!', 'data' => $response->json()], 200);
            } else {
                return response()->json(['message' => 'Failed to save polyline', 'error' => $response->json()], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}