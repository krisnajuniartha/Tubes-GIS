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
                'json' => [
                    'email'    => $request->input('email'),
                    'password' => $request->input('password'),
                ],
            ]);

            $body = $response->getBody();
            $content = $body->getContents();
            $data = json_decode($content, true);

            // Check if the meta key exists in the response
            if (isset($data['meta']['token'])) {
                // Save token to session
                Session::put('api_token', $data['meta']['token']);
                return redirect('dashboard')->with('status', 'Login successful! Welcome.');
            } else {
                return redirect()->back()->withErrors(['login' => 'Failed to get token from API response.']);
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $content = $response->getBody()->getContents();
                return redirect()->back()->withErrors(['login' => 'Failed to login.']);
            } else {
                return redirect()->back()->withErrors(['login' => 'Failed to connect to the server.']);
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

            // Redirect ke halaman login dengan pesan berhasil logout
            return redirect('login')->with('status', 'Logged out successfully.');
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $content = $response->getBody()->getContents();
                return redirect()->back()->withErrors(['logout' => 'Logout failed, please try again.']);
            } else {
                return redirect()->back()->withErrors(['logout' => 'Failed to connect to the server.']);
            }
        }
    }
}
