<?php

namespace App\Services;

class NomorGeneratorService
{
    protected $api_url;
    protected $token;

    public function __construct()
    {
        $this->api_url = getenv('api_URL'); // ← sesuai .env kamu
        $this->token = session()->get('jwt_token');
    }

    public function getLastNoRM(): ?string
    {
        $url = $this->api_url . "/masterpasien?limit=1&sort=desc";
        $response = $this->curlGet($url);
        return $response['data'][0]['no_rkm_medis'] ?? null;
    }

    public function getLastSKL(string $bulan, string $tahun): ?string
    {
        $url = $this->api_url . "/kelahiranbayi?bulan=$bulan&tahun=$tahun";
        $response = $this->curlGet($url);

        if (!$response || empty($response['data'])) return null;

        // Urutkan manual berdasarkan nomor urut SKL
        $data = $response['data'];

        usort($data, function ($a, $b) {
            // Ambil bagian nomor urut SKL (misal 0003 dari 0003/RM-SKL/07/2025)
            preg_match('/^(\d{4})/', $a['no_skl'], $matchA);
            preg_match('/^(\d{4})/', $b['no_skl'], $matchB);

            return (int)($matchB[1] ?? 0) <=> (int)($matchA[1] ?? 0); // descending
        });

        return $data[0]['no_skl'] ?? null;
    }


    private function curlGet(string $url): ?array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response ? json_decode($response, true) : null;
    }
}
