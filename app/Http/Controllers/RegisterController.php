<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
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
}
