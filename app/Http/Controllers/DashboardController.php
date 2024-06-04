<?php

namespace App\Http\Controllers;

use App\Helpers\PolylineHelper;
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
}
