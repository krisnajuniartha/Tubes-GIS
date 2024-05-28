<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{
    public function index()
    {
        return view('frontend.dashboard');
    }

    public function getRuasJalan()
    {
        try {
            $token = Session::get('api_token');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('https://gisapis.manpits.xyz/api/ruasjalan');

            if ($response->successful()) {
                $data = $response->json();

                // Decode polyline paths
                foreach ($data['ruasjalan'] as &$ruas) {
                    $ruas['decoded_paths'] = \App\Helpers\PolylineHelper::decodePolyline($ruas['paths']);
                    Log::info('Decoded paths for ruas id ' . $ruas['id'], $ruas['decoded_paths']);
                }

                return response()->json($data);
            } else {
                return response()->json(['error' => 'Failed to fetch data from API'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
