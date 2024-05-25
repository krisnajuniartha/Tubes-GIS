<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\RequestException;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $status = $request->session()->get('status');
        return view('auth.login', compact('status'));
    }

    public function login(Request $request)
    {
        try {
            $client = new Client();
    
            $response = $client->post('https://gisapis.manpits.xyz/api/login', [
                'json' => [ // Mengirim data sebagai payload JSON
                    'email'    => $request->input('email'),
                    'password' => $request->input('password'),
                ],
            ]);
    
            $body = $response->getBody();
            $content = $body->getContents();
            $data = json_decode($content, true);

            Session::put('api_data', $data);
            
            return redirect('dashboard')->with('status', 'Login successful! Welcome.');
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $content = $response->getBody()->getContents();
                return response()->json(['error' => 'Failed to login.'], $response->getStatusCode());
                
            } else {
                return response()->json(['error' => 'Failed to connect to the server.'], 500);
            }
        }
    }

    public function logout(Request $request)
    {
        try {
            $client = new Client();
            $token = Session::get('api_token'); // Ambil token dari sesi

            $response = $client->post('https://gisapis.manpits.xyz/api/logout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            // Hapus token dari sesi setelah logout berhasil
            Session::forget('api_token');

            return redirect('login')->with('status', 'Logged out successfully.');
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return redirect()->route('dashboard')->withErrors([
                    'logout' => 'Logout failed, please try again.',
                ]);
            } else {
                return redirect()->route('dashboard')->withErrors([
                    'logout' => 'Failed to connect to the server.',
                ]);
            }
        }
    }
}
