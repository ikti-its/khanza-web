<?php

namespace App\Controllers;

class Pasien extends BaseController
{
    protected $api_url;

    public function __construct()
    {
        $this->api_url = getenv('api_URL');
    }

    public function dataPasien()
    {
        $title = 'Data Pasien';
        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 10;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $url = $this->api_url . "/pasien";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($http_status === 200) {
                    $data = json_decode($response, true);

                    return view('/admin/pasien/pasien_data', [
                        'pasien_data' => $data['data'],
                        'meta_data' => ['page' => $page, 'size' => $size, 'total' => $data['total'] ?? count($data['data'])],
                        'title' => $title
                    ]);
                } else {
                    return "Gagal mengambil data pasien. HTTP: $http_status";
                }
            } else {
                return "Tidak dapat menghubungi API.";
            }
        } else {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }
    }

    public function tambahPasien()
    {
        $title = 'Lihat Rekam Medis Pasien';
        $pasienData = null;

        return view('/admin/pasien/tambah_pasien', [
            'title' => $title,
            'pasien' => $pasienData,
        ]);
    }

    public function submitTambahPasien()
    {
        if (!$this->request->getPost()) {
            return redirect()->to('/pasien/tambah');
        }

        $tgl_lahir_raw = $this->request->getPost('tgl_lahir');
        $tgl_lahir_iso = $tgl_lahir_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_lahir_raw)) : null;

        $tgl_daftar_iso = date('Y-m-d\TH:i:sP');

        $postData = [
            'no_rkm_medis' => $this->request->getPost('no_rkm_medis'),
            'nm_pasien' => $this->request->getPost('nm_pasien'),
            'no_ktp' => $this->request->getPost('no_ktp'),
            'jk' => $this->request->getPost('jk'),
            'tmp_lahir' => $this->request->getPost('tmp_lahir'),
            'tgl_lahir' => $tgl_lahir_iso,
            'nm_ibu' => $this->request->getPost('nm_ibu'),
            'alamat' => $this->request->getPost('alamat'),
            'gol_darah' => $this->request->getPost('gol_darah'),
            'pekerjaan' => $this->request->getPost('pekerjaan'),
            'stts_nikah' => $this->request->getPost('stts_nikah'),
            'agama' => $this->request->getPost('agama'),
            'tgl_daftar' => $tgl_daftar_iso,
            'no_tlp' => $this->request->getPost('no_tlp'),
            'umur' => $this->request->getPost('umur'),
            'pnd' => $this->request->getPost('pnd'),
            'keluarga' => $this->request->getPost('keluarga'),
            'namakeluarga' => $this->request->getPost('namakeluarga'),
            'kd_pj' => $this->request->getPost('kd_pj'),
            'no_peserta' => $this->request->getPost('no_peserta'),
            'kd_kel' => $this->request->getPost('kd_kel'),
            'kd_kec' => $this->request->getPost('kd_kec'),
            'kd_kab' => $this->request->getPost('kd_kab'),
            'pekerjaanpj' => $this->request->getPost('pekerjaanpj'),
            'alamatpj' => $this->request->getPost('alamatpj'),
            'kelurahanpj' => $this->request->getPost('kelurahanpj'),
            'kecamatanpj' => $this->request->getPost('kecamatanpj'),
            'kabupatenpj' => $this->request->getPost('kabupatenpj'),
            'perusahaan_pasien' => $this->request->getPost('perusahaan_pasien'),
            'suku_bangsa' => $this->request->getPost('suku_bangsa'),
            'bahasa_pasien' => $this->request->getPost('bahasa_pasien'),
            'cacat_fisik' => $this->request->getPost('cacat_fisik'),
            'email' => $this->request->getPost('email'),
            'nip' => $this->request->getPost('nip'),
            'kd_prop' => $this->request->getPost('kd_prop'),
            'propinsipj' => $this->request->getPost('propinsipj'),
        ];
        // dd($postData);

        $jsonPayload = json_encode($postData);
        $url = $this->api_url . "/pasien";

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//             dd([
//     'payload' => $postData,
//     'json' => $jsonPayload,
//     'response' => $response,
//     'http_code' => $http_status,
// ]);
            curl_close($ch);

            if ($response && $http_status === 201) {
                return redirect()->to('/pasien');
            } else {
                return "Gagal menambahkan pasien. HTTP: $http_status";
            }
        } else {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }
    }

    public function lihatPasienByRM($no_rkm_medis)
    {
        $title = 'Lihat Rekam Medis Pasien';
        $pasienData = null;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $url = $this->api_url . "/pasien/" . urlencode($no_rkm_medis);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_status === 200 && $response) {
                $data = json_decode($response, true);
                $pasienData = $data['data'] ?? null;
            } else {
                return redirect()->to('/pasien')->with('error', 'Pasien tidak ditemukan.');
            }
        } else {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('/admin/pasien/lihat_pasien', [
            'title' => $title,
            'pasien' => $pasienData,
        ]);
    }
    public function editPasien($no_rkm_medis)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Data Pasien';

        $url = $this->api_url . '/pasien/' . urlencode($no_rkm_medis);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status !== 200 || !$response) {
            return $this->renderErrorView($http_status);
        }

        $data = json_decode($response, true);
        $pasienData = $data['data'] ?? null;

        $this->addBreadcrumb('Pasien', 'pasien_data');
        $this->addBreadcrumb('Edit', '');
    // dd($pasienData);
        return view('/admin/pasien/edit_pasien', [
            'title' => $title,
            'pasien' => $pasienData,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitEditPasien($no_rkm_medis)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/pasien/' . urlencode($no_rkm_medis);

        $tgl_lahir = $this->request->getPost('tgl_lahir');
        $tgl_lahir_iso = $tgl_lahir ? date(DATE_ATOM, strtotime($tgl_lahir)) : null;

        $postData = [
            'no_rkm_medis' => $this->request->getPost('no_rkm_medis'),
                'nm_pasien' => $this->request->getPost('nm_pasien'),
                'no_ktp' => $this->request->getPost('no_ktp'),
                'jk' => $this->request->getPost('jk'),
                'tmp_lahir' => $this->request->getPost('tmp_lahir'),
                'tgl_lahir' => $tgl_lahir_iso,
                'nm_ibu' => $this->request->getPost('nm_ibu'),
                'alamat' => $this->request->getPost('alamat'),
                'gol_darah' => $this->request->getPost('gol_darah'),
                'pekerjaan' => $this->request->getPost('pekerjaan'),
                'stts_nikah' => $this->request->getPost('stts_nikah'),
                'agama' => $this->request->getPost('agama'),
                'tgl_daftar' => $this->request->getPost('tgl_daftar'),
                'no_tlp' => $this->request->getPost('no_tlp'),
                'umur' => $this->request->getPost('umur'),
                'pnd' => $this->request->getPost('pnd'),
                'keluarga' => $this->request->getPost('keluarga'),
                'namakeluarga' => $this->request->getPost('namakeluarga'),
                'kd_pj' => $this->request->getPost('kd_pj'),
                'no_peserta' => $this->request->getPost('no_peserta'),
                'kd_kel' => $this->request->getPost('kd_kel'),
                'kd_kec' => $this->request->getPost('kd_kec'),
                'kd_kab' => $this->request->getPost('kd_kab'),
                'pekerjaanpj' => $this->request->getPost('pekerjaanpj'),
                'alamatpj' => $this->request->getPost('alamatpj'),
                'kelurahanpj' => $this->request->getPost('kelurahanpj'),
                'kecamatanpj' => $this->request->getPost('kecamatanpj'),
                'kabupatenpj' => $this->request->getPost('kabupatenpj'),
                'perusahaan_pasien' => $this->request->getPost('perusahaan_pasien'),
                'suku_bangsa' => $this->request->getPost('suku_bangsa'),
                'bahasa_pasien' => $this->request->getPost('bahasa_pasien'),
                'cacat_fisik' => $this->request->getPost('cacat_fisik'),
                'email' => $this->request->getPost('email'),
                'nip' => $this->request->getPost('nip'),
                'kd_prop' => $this->request->getPost('kd_prop'),
                'propinsipj' => $this->request->getPost('propinsipj'),
        ];

        $jsonPayload = json_encode($postData);
    // dd($jsonPayload);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonPayload),
            'Authorization: Bearer ' . $token,
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 200) {
            return redirect()->to('/pasien')->with('success', 'Data pasien berhasil diperbarui.');
        } else {
            return $this->renderErrorView($http_status);
        }
    }

    public function hapusPasien($no_rkm_medis)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/pasien/' . urlencode($no_rkm_medis);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 200) {
            return redirect()->to('/pasien')->with('success', 'Pasien berhasil dihapus.');
        } else {
            return $this->renderErrorView($http_status);
        }
    }
}