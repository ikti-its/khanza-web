<?php

namespace App\Controllers\Modal;

use App\Controllers\BaseController;

class ModalPasien extends BaseController
{
    public function listPasien()
    {
        if (!session()->has('jwt_token')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . "/masterpasien";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 200 && $response) {
            $data = json_decode($response, true);
            return $this->response->setJSON($data);
        }

        return $this->response->setStatusCode($http_status)->setJSON(['error' => 'Gagal mengambil data pasien']);
    }
}
