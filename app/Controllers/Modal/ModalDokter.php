<?php
declare(strict_types=1);

namespace App\Controllers\Modal;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;

class ModalDokter extends ControllerTemplateLegacy
{
    public function listDokter()
    {
        if (!session()->has('jwt_token')) {
            return $this->listDokterFromDb();
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . "/dokter";

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

        return $this->listDokterFromDb();
    }

    private function listDokterFromDb()
    {
        $db   = \Config\Database::connect();
        $rows = $db->table('role.dokter d')
            ->select(['d.kode_dokter', 'o.nama AS nama_dokter', 'd.spesialis'])
            ->join('person.orang o', 'o.id_orang = d.id_orang')
            ->orderBy('o.nama', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}
