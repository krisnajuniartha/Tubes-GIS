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
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Failed to fetch data from API'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
