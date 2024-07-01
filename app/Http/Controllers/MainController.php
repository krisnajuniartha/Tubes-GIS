<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;

class MainController extends Controller
{
    public function showAddRuasJalan(Request $request)
    {
        $provinsi = [];
        $kabupaten = [];
        $kecamatan = [];
        $desa = [];

        $eksisting = [];
        $jenisJalan = [];
        $kondisiJalan = [];

        // Mendapatkan token dari sesi
        $token = Session::get('token');

        // Inisialisasi client GuzzleHttp
        $client = new Client();

        try {
            // Lakukan permintaan GET ke API mregion
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/mregion', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token // Gunakan token dari sesi
                ]
            ]);

            // Mendapatkan data JSON dari respons
            $data = json_decode($response->getBody()->getContents(), true);

            // Simpan data provinsi, kabupaten, dan kecamatan ke dalam variabel terpisah
            $provinsi = $data['provinsi'];
            $kabupaten = $data['kabupaten'];
            $kecamatan = $data['kecamatan'];
            $desa = $data['desa'];
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['error' => $e->getMessage()], 500);
        }

        try {
            // Lakukan permintaan GET ke API meksisting
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/meksisting', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token // Gunakan token dari sesi
                ]
            ]);

            // Mendapatkan data JSON dari respons
            $data = json_decode($response->getBody()->getContents(), true);

            // Simpan data perkerasan eksisting ke dalam variabel
            $eksisting = $data['eksisting'];
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['error' => $e->getMessage()], 500);
        }

        try {
            // Lakukan permintaan GET ke API mjenisjalan
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/mjenisjalan', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token // Gunakan token dari sesi
                ]
            ]);

            // Mendapatkan data JSON dari respons
            $data = json_decode($response->getBody()->getContents(), true);

            // Simpan data jenis jalan ke dalam variabel
            $jenisJalan = $data['eksisting'];
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['error' => $e->getMessage()], 500);
        }

        try {
            // Lakukan permintaan GET ke API mkondisi
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/mkondisi', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token // Gunakan token dari sesi
                ]
            ]);

            // Mendapatkan data JSON dari respons
            $data = json_decode($response->getBody()->getContents(), true);

            // Simpan data kondisi jalan ke dalam variabel
            $kondisiJalan = $data['eksisting'];
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Tampilkan data
        return view('frontend.tambah-form', compact('provinsi', 'kabupaten', 'kecamatan', 'desa', 'eksisting', 'jenisJalan', 'kondisiJalan'));
    }

    public function getKabupatenByProvinsiId($provinsiId)
    {
        $token = Session::get('token');
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/kabupaten/' . $provinsiId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json($data['kabupaten']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getKecamatanByKabupatenId($kabupatenId)
    {
        $token = Session::get('token');
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/kecamatan/' . $kabupatenId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json($data['kecamatan']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDesaByKecamatanId($kecamatanId)
    {
        $token = Session::get('token');
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/desa/' . $kecamatanId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json($data['desa']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function view()
    {
        if (Session::has('token')) {
            return $this->getRuasJalan('frontend.dashboard');
        }

        return view('frontend.dashboard');
    }

    public function getRuasJalan($viewType)
    {
        $token = Session::get('token');
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/ruasjalan', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
            ]);
            
            $data = json_decode($response->getBody(), true);

            if (isset($data['ruasjalan']) && !empty($data['ruasjalan'])) {
                $ruasJalanDetails = [];

                // Loop through each ruas jalan and collect all necessary details
                foreach ($data['ruasjalan'] as $ruas) {
                    $latLngArray = $this->decodePolyline($ruas['paths']);

                    // Add additional details to the array
                    $ruasJalanDetails[] = [
                        'id' => $ruas['id'],
                        'paths' => $latLngArray,
                        'paths2' => $ruas['paths'],
                        'desa_id' => $ruas['desa_id'],
                        'kode_ruas' => $ruas['kode_ruas'],
                        'nama_ruas' => $ruas['nama_ruas'],
                        'panjang' => $ruas['panjang'],
                        'lebar' => $ruas['lebar'],
                        'eksisting_id' => $ruas['eksisting_id'],
                        'kondisi_id' => $ruas['kondisi_id'],
                        'jenisjalan_id' => $ruas['jenisjalan_id'],
                        'keterangan' => $ruas['keterangan']
                    ];
                }

                
                return view($viewType, compact('ruasJalanDetails'));
                // return view('frontend.dashboard-main2', compact('ruasJalanDetails'));
            } else {
                // Jika array 'ruasjalan' kosong atau tidak diset, kembalikan view tanpa data polyline
                // return view('frontend.dashboard-main2');
                return view($viewType);
            }
        } catch (\Exception $e) {
            // Tangani semua pengecualian
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRuasJalanTable ($viewType = 'default-view')
    {   
        
        return $this->getRuasJalan('frontend.table-form');
    }

    public function getRuasJalanForEdit()
    {
        return $this->getRuasJalan('frontend.edit-form');
    }

    private function decodePolyline($encoded)
    {
        $index = $lat = $lng = $i = 0;
        $latlngs = [];

        while ($index < strlen($encoded)) {
            $shift = $result = 0;
            do {
                $bit = ord(substr($encoded, $index++)) - 63;
                $result |= ($bit & 0x1f) << $shift;
                $shift += 5;
            } while ($bit >= 0x20);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;

            $shift = $result = 0;
            do {
                $bit = ord(substr($encoded, $index++)) - 63;
                $result |= ($bit & 0x1f) << $shift;
                $shift += 5;
            } while ($bit >= 0x20);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;

            $latlngs[] = [$lat * 1e-5, $lng * 1e-5];
        }

        return $latlngs;
    }

    public function search(Request $request)
    {
        $token = Session::get('token');
        $client = new Client();
        $searchTerm = $request->input('search');
    
        // Mapping untuk jenis jalan, kondisi jalan, dan eksisting
        $jenisjalanMapping = [
            'desa' => 1,
            'kabupaten' => 2,
            'provinsi' => 3,
        ];
    
        $kondisiMapping = [
            'baik' => 1,
            'sedang' => 2,
            'rusak' => 3,
        ];
    
        $eksistingMapping = [
            'tanah' => 1,
            'tanah/beton' => 2,
            'perkerasan' => 3,
            'koral' => 4,
            'lapen' => 5,
            'paving' => 6,
            'hotmix' => 7,
            'beton' => 8,
            'beton/lapen' => 9,
        ];
    
        // Check if the search term matches any known types
        $jenisjalan_id = null;
        $kondisi_id = null;
        $eksisting_id = null;
    
        foreach ($jenisjalanMapping as $key => $id) {
            if (stripos($searchTerm, $key) !== false) {
                $jenisjalan_id = $id;
                break;
            }
        }
    
        foreach ($kondisiMapping as $key => $id) {
            if (stripos($searchTerm, $key) !== false) {
                $kondisi_id = $id;
                break;
            }
        }
    
        foreach ($eksistingMapping as $key => $id) {
            if (stripos($searchTerm, $key) !== false) {
                $eksisting_id = $id;
                break;
            }
        }
    
        try {
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/ruasjalan', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
            ]);
    
            $data = json_decode($response->getBody(), true);
    
            if (isset($data['ruasjalan']) && !empty($data['ruasjalan'])) {
                $ruasJalanDetails = [];
    
                foreach ($data['ruasjalan'] as $ruas) {
                    $match = (!$searchTerm || stripos($ruas['kode_ruas'], $searchTerm) !== false || stripos($ruas['nama_ruas'], $searchTerm) !== false);
                    
                    // Check if it matches jenis jalan, kondisi jalan, atau eksisting
                    $match = $match || ($jenisjalan_id !== null && $ruas['jenisjalan_id'] == $jenisjalan_id);
                    $match = $match || ($kondisi_id !== null && $ruas['kondisi_id'] == $kondisi_id);
                    $match = $match || ($eksisting_id !== null && $ruas['eksisting_id'] == $eksisting_id);
    
                    if ($match) {
                        $latLngArray = $this->decodePolyline($ruas['paths']);
    
                        $ruasJalanDetails[] = [
                            'id' => $ruas['id'],
                            'paths' => $latLngArray,
                            'paths2' => $ruas['paths'],
                            'desa_id' => $ruas['desa_id'],
                            'kode_ruas' => $ruas['kode_ruas'],
                            'nama_ruas' => $ruas['nama_ruas'],
                            'panjang' => $ruas['panjang'],
                            'lebar' => $ruas['lebar'],
                            'eksisting_id' => $ruas['eksisting_id'],
                            'kondisi_id' => $ruas['kondisi_id'],
                            'jenisjalan_id' => $ruas['jenisjalan_id'],
                            'keterangan' => $ruas['keterangan']
                        ];
                    }
                }
    
                return view('frontend.table-form', compact('ruasJalanDetails'));
            } else {
                return view('frontend.table-form');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    


    public function submitRuasJalan(Request $request)
    {
        $token = Session::get('token');
        $client = new Client();

        try {
            $response = $client->request('POST', 'https://gisapis.manpits.xyz/api/ruasjalan', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'form_params' => [
                    'paths' => $request->paths,
                    'desa_id' => $request->desa_id,
                    'kode_ruas' => $request->kode_ruas,
                    'nama_ruas' => $request->nama_ruas,
                    'panjang' => $request->panjang,
                    'lebar' => $request->lebar,
                    'eksisting_id' => $request->eksisting_id,
                    'kondisi_id' => $request->kondisi_id,
                    'jenisjalan_id' => $request->jenisjalan_id,
                    'keterangan' => $request->keterangan,
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Handle response as needed
            // For example, redirect back with success message
            return redirect()->back()->with('success', 'Ruas Jalan berhasil ditambahkan');
        } catch (\Exception $e) {
            // Handle exception
            return redirect()->back()->with('error', 'Gagal menambahkan Ruas Jalan: ' . $e->getMessage());
        }
    }

    public function updateRuasJalan(Request $request, $id)
    {
        $token = Session::get('token');
        $client = new Client();

        try {
            $response = $client->request('PUT', 'https://gisapis.manpits.xyz/api/ruasjalan/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'form_params' => [
                    'paths' => $request->paths_get,
                    'desa_id' => $request->desa_id,
                    'kode_ruas' => $request->kode_ruas,
                    'nama_ruas' => $request->nama_ruas,
                    'panjang' => $request->panjang,
                    'lebar' => $request->lebar,
                    'eksisting_id' => $request->eksisting_id,
                    'kondisi_id' => $request->kondisi_id,
                    'jenisjalan_id' => $request->jenisjalan_id,
                    'keterangan' => $request->keterangan,
                ],
            ]);
        
            // Log response
            Log::info('Response: ' . $response->getBody());
        
            if ($response->getStatusCode() == 200) {
                return redirect()->back()->with('success', 'Ruas Jalan updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update Ruas Jalan.');
            }
        } catch (\Exception $e) {
            // Log exception
            Log::error('Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update Ruas Jalan: ' . $e->getMessage());
        }
    }

    public function deleteRuasJalan(Request $request, $id)
    {
        // Mendapatkan token dari sesi
        $token = Session::get('token');
    
        // Membuat instance Guzzle HTTP Client
        $client = new Client();
    
        try {
            // Melakukan permintaan DELETE ke API dengan menyertakan token
            $response = $client->request('DELETE', 'https://gisapis.manpits.xyz/api/ruasjalan/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);
    
            // Mengecek apakah permintaan berhasil (berhasil jika kode status 204)
            if ($response->getStatusCode() === 204) {
                // Jika berhasil, kembalikan respon yang sesuai
                return redirect()->back()->with('success', 'Ruas jalan berhasil dihapus');
            } else {
                // Jika kode status bukan 204, berikan pesan error
                return redirect()->back()->with('error', 'Gagal menghapus ruas jalan');
            }
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus ruas jalan: ' . $e->getMessage());
        }
    }
    
}
