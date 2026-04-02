<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GiziClassifierService
{
    protected $baseUrl;

    public function __construct()
    {
        // Ganti sesuai URL Flask kamu
        $this->baseUrl = env('FLASK_API_URL', 'http://127.0.0.1:5000');
    }

    /**
     * Cek koneksi ke Flask API
     */
    public function checkConnection()
    {
        try {
            $response = Http::timeout(3)->get($this->baseUrl);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Prediksi status gizi (pakai Random Forest dari Flask)
     */
    public function predict($data)
    {
        try {
            $response = Http::timeout(10)->post($this->baseUrl . '/predict-all', [
                'jenis_kelamin' => $data['jenis_kelamin'], // L / P
                'umur'           => $data['umur'],          // bulan
                'berat_badan'    => $data['berat_badan'],   // kg
                'tinggi_badan'   => $data['tinggi_badan'],  // cm
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'error' => true,
                'message' => 'Gagal response dari API Flask'
            ];

        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Tidak bisa konek ke Flask API: ' . $e->getMessage()
            ];
        }
    }
}