<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Polyline;
use GuzzleHttp\Client;

class MapController extends Controller
{

    public function create()
    {
        return view('map.form');
    }

    public function getRuasJalan()
    {
        try {
            $apiToken = Session::get('api_token');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
            ])->get('https://gisapis.manpits.xyz/api/ruasjalan');

            if ($response->successful()) {
                $data = $response->json();
                return response()->json(['status' => 'success', 'ruasjalan' => $data['ruasjalan']]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Failed to fetch data from API'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
       $request->validate([
            'name' => 'required|string|max:255',
            'coordinates' => 'required|array',
            'coordinates.*.lat' => 'required|numeric',
            'coordinates.*.lng' => 'required|numeric',
        ]);
    
        $polyline = new Polyline();
        $polyline->name = $request->name;
        $polyline->coordinates = $this->encodePolyline(json_encode($request->coordinates)); // Mengenkripsi koordinat
        $polyline->save();
    
        return redirect()->route('dasboard')->with('success', 'Polyline created successfully.');
    }

    public function edit($id)
    {
        $token = session('token');
        $client = new Client();
        $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/ruasjalan/'. $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            $data_ruas_jalan = json_decode($response->getBody(), true);
            $data_region = $this->getDataRegion($token);
            return view('polyline.edit', compact('data_ruas_jalan', 'data_region'));
        } else {
            return redirect()->back()->with('error', 'Failed to fetch data from API');
        }
    }

    private function getDataRegion($token)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/mregion', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        } else {
            return [];
        }
    }

    private function encodePolyline($polyline)
    {
        // Enkripsi polyline menggunakan simple XOR cipher
        $key = 'your_secret_key_here';
        $result = '';
        for ($i = 0; $i < strlen($polyline); $i++) {
            $result .= chr(ord($polyline[$i]) ^ ord($key[$i % strlen($key)]));
        }
        return $result;
    }

    private function decodePolyline($encodedPolyline)
    {
        // Dekripsi polyline menggunakan simple XOR cipher
        $key = 'your_secret_key_here';
        $result = '';
        for ($i = 0; $i < strlen($encodedPolyline); $i++) {
            $result .= chr(ord($encodedPolyline[$i]) ^ ord($key[$i % strlen($key)]));
        }
        return $result;
    }
}