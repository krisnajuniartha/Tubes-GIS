<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\RequestException;

class AuthController extends Controller
{
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
                Session::put('token', $data['meta']['token']);
                return redirect('/')->with('status', 'Login successful! Welcome.');
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

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $client = new Client();
            $response = $client->post('https://gisapis.manpits.xyz/api/register', [
                'form_params' => [
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => $validatedData['password'],
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);

            // Redirect to login page with success message
            return redirect('/login')->with('status', 'Registration successful! Please login.');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['registration' => 'Registration failed: ' . $e->getMessage()]);
        }
    }


    public function logout(Request $request)
    {
        try {
            $client = new Client();
            $token = Session::get('token'); // Ambil token dari sesi

            $response = $client->post('https://gisapis.manpits.xyz/api/logout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            // Hapus token dari sesi setelah logout berhasil
            Session::forget('token');

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

    public function getUser()
    {
        try {
            $token = session('token');

            $client = new Client();
            $response = $client->get('https://gisapis.manpits.xyz/api/user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
            ]);

            $body = $response->getBody();
            $content = $body->getContents();
            $data = json_decode($content, true);

            if ($response->getStatusCode() === 200 && isset($data['data']['user']['name'])) {
                $name = $data['data']['user']['name'];
                session(['user_name' => $name]);
                return $name;
            } else {
                return response()->json($data, $response->getStatusCode());
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $content = $response->getBody()->getContents();
                return response()->json(['error' => 'Failed to get user data.'], $response->getStatusCode());
            } else {
                return response()->json(['error' => 'Failed to connect to the server.'], 500);
            }
        }
    }

}
