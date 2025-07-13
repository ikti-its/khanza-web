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
        $url = $this->api_url . "/kelahiranbayi?bulan=$bulan&tahun=$tahun&limit=1&sort=desc";
        $response = $this->curlGet($url);
        return $response['data'][0]['no_skl'] ?? null;
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
