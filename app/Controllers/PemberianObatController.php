<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PemberianObatController extends BaseController
{


    public function dataPemberianObat()
    {
        $title = 'Data Pemberian Obat';
    
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
    
            // ✅ Fetch pemberian_obat data
            $obat_url = $this->api_url . '/pemberian-obat';
            $ch = curl_init($obat_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
    
            if ($http_status !== 200) {
                return $this->renderErrorView($http_status);
            }
    
            $obat_data = json_decode($response, true);
            if (!isset($obat_data['data'])) {
                return $this->renderErrorView(500);
            }
    
            // ✅ Breadcrumbs
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Pemberian Obat', 'pemberianobat');
            $breadcrumbs = $this->getBreadcrumbs();

            // dd($obat_data);
    
            return view('/admin/pemberianobat/pemberianobat_data', [
                'pemberianobat_data' => $obat_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $obat_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function tambahPemberianObat($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Tambah Pemberian Obat';

        // ✅ Fetch pemberian obat data (e.g. from rawatinap or existing entry)
        $url = $this->api_url . '/pemberian-obat/' . $nomorRawat;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $parsed = json_decode($response, true);
        $pemberianobat = $parsed['data'][0] ?? []; // safely pick first if list

        $kelas = $pemberianobat['kelas'] ?? 'dasar';
        $url = $this->api_url . '/pemberian-obat/databarang?kelas=' . $kelas;

        // ✅ Fetch available obat from databarang
        $url = $this->api_url . '/pemberian-obat/databarang';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
        $obat_data = $data['data'] ?? [];
        

        // dd($obat_data);

        // ✅ Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemberian Obat', 'pemberianobat');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/pemberianObat/tambah_pemberianobat', [
            'pemberianobat' => $pemberianobat,
            'obat_list' => $obat_data,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }


    public function submitTambahPemberianObat()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $kode_obat = $this->request->getPost('kode_obat');
        $jumlah = floatval($this->request->getPost('jumlah'));

        // Step 1: Get current stok from gudang
        $getUrl = $this->api_url . '/gudang-barang/' . $kode_obat;

        $ch = curl_init($getUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $getResponse = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) {
            return $this->renderErrorView($status);
        }

        $gudangData = json_decode($getResponse, true)['data'] ?? null;
        if (!$gudangData) {
            return $this->renderErrorView(500);
        }
    // dd($gudangData);
        // Step 2: Update stok
        $newStok = floatval($gudangData['stok']) - $jumlah;
        if ($newStok < 0) $newStok = 0;

        $putData = [
            'id_barang_medis' => $gudangData['id_barang_medis'],
            'id_ruangan'      => $gudangData['id_ruangan'],
            'stok'            => $newStok,
            'no_batch'        => $gudangData['no_batch'],
            'no_faktur'       => $gudangData['no_faktur'],
        ];



        $putUrl = $this->api_url . '/gudang-barang/' . $gudangData['id'];;
        $putPayload = json_encode($putData);
    // dd($putUrl);
        $ch = curl_init($putUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $putPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($putPayload),
            'Authorization: Bearer ' . $token,
        ]);
        
        $putResponse = curl_exec($ch);
        $putStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    log_message('error', "PUT Response: " . $putResponse);
    log_message('error', "PUT Status: " . $putStatus);
        if ($putStatus !== 200) {
            return $this->renderErrorView($putStatus);
        }

        // Step 3: Submit pemberian obat
        $postData = [
            'tanggal_beri'   => $this->request->getPost('tanggal_beri') ?? date('Y-m-d'),
            'jam_beri'       => $this->request->getPost('jam_beri') ?? date('H:i:s'),
            'nomor_rawat'    => $this->request->getPost('nomor_rawat'),
            'nama_pasien'    => $this->request->getPost('nama_pasien'),
            'kode_obat'      => $kode_obat,
            'nama_obat'      => $this->request->getPost('nama_obat'),
            'embalase'       => $this->request->getPost('embalase'),
            'tuslah'         => $this->request->getPost('tuslah'),
            'jumlah'         => $this->request->getPost('jumlah'),
            'biaya_obat'     => floatval($this->request->getPost('biaya_obat')),
            'total'          => floatval($this->request->getPost('total')),
            'gudang'         => $this->request->getPost('gudang'),
            'no_batch'       => $this->request->getPost('no_batch'),
            'no_faktur'      => $this->request->getPost('no_faktur'),
        ];

        $url = $this->api_url . '/pemberian-obat';
        $payload = json_encode($postData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200 || $status === 201) {
            return redirect()->to(base_url('pemberianobat/' . $postData['nomor_rawat']));
        } else {
            return $this->renderErrorView($status);
        }
    }


    public function editPemberianObat($nomorRawat, $jamBeri)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Pemberian Obat';

        // ✅ Ambil data pemberian obat
        $url = $this->api_url . '/pemberian-obat/' . $nomorRawat;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $selectedObat = [];

        // ✅ Temukan data sesuai jam_beri
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $item) {
                if ($item['jam_beri'] === $jamBeri) {
                    $selectedObat = $item;
                    break;
                }
            }
        }

        // ✅ Ambil kelas dari selectedObat, default ke 'dasar'
        $kelas = $selectedObat['kelas'] ?? 'dasar';
        $obatUrl = $this->api_url . '/pemberian-obat/databarang?kelas=' . $kelas;
        $ch = curl_init($obatUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $obatResponse = curl_exec($ch);
        curl_close($ch);

        $obatData = json_decode($obatResponse, true);
        $obat_list = $obatData['data'] ?? [];

        // ✅ Breadcrumb
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemberian Obat', 'pemberianobat');
        $this->addBreadcrumb('Edit', 'edit');
    // dd($selectedObat);
        return view('/admin/pemberianObat/edit_pemberianobat', [
            'pemberianobat' => $selectedObat,
            'obat_list' => $obat_list,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }


    public function submitEditPemberianObat($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/pemberian-obat/' . $nomorRawat;

        $postData = [
            'tanggal_beri'   => $this->request->getPost('tanggal_beri'),
            'jam_beri'       => $this->request->getPost('jam_beri'),
            'nomor_rawat'    => $this->request->getPost('nomor_rawat'),
            'nama_pasien'    => $this->request->getPost('nama_pasien'),
            'kode_obat'      => $this->request->getPost('kode_obat'),
            'nama_obat'      => $this->request->getPost('nama_obat'),
            'embalase'       => $this->request->getPost('embalase'),
            'tuslah'         => $this->request->getPost('tuslah'),
            'jumlah'         => $this->request->getPost('jumlah'),
            'biaya_obat'     => floatval($this->request->getPost('biaya_obat')),
            'total'          => floatval($this->request->getPost('total')),
            'gudang'         => $this->request->getPost('gudang'),
            'no_batch'       => $this->request->getPost('no_batch'),
            'no_faktur'      => $this->request->getPost('no_faktur'),
        ];

        $payload = json_encode($postData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 200) {
            return redirect()->to(base_url('pemberianobat/' . $nomorRawat));
        } else {
            return $response;
        }
    }

    public function hapusPemberianObat($nomor_rawat, $jam_beri)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        $delete_url = $this->api_url . "/pemberian-obat/$nomor_rawat/$jam_beri";

        $ch = curl_init($delete_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }

        return redirect()->to('/pemberianobat')->with('success', 'Pemberian Obat deleted');
    }

    public function pemberianObatData($nomorRawat)
    {
        $title = 'Detail Pemberian Obat';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $url = $this->api_url . '/pemberian-obat/' . $nomorRawat;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_status !== 200) {
                return $this->renderErrorView($http_status);
            }

            $obat_data = json_decode($response, true);

            if (!isset($obat_data['data'])) {
                return $this->renderErrorView(500);
            }

            $data = $obat_data['data'];
            if (isset($data['nomor_rawat'])) {
                $data = [$data];
            }

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Pemberian Obat', 'pemberianobat');
            $breadcrumbs = $this->getBreadcrumbs();

            // dd($response);

            return view('/admin/pemberianObat/pemberianobat_data', [
                'pemberianobat_data' => $data,
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $obat_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitFromRawatinap($nomor_rawat)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->back()->with('error', 'Session token missing.');
        }

        $token = session()->get('jwt_token');

        // Step 1: Get rawat inap data
        $url_rawatinap = $this->api_url . '/rawatinap/' . $nomor_rawat;
        $ch = curl_init($url_rawatinap);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['data']) || $data['data'] === null) {
            return redirect()->back()->with('error', 'Rawat inap data not found.');
        }

        $rawatinap = is_string($data['data']) ? json_decode($data['data'], true) : $data['data'];

        if (!is_array($rawatinap)) {
            return redirect()->back()->with('error', 'Invalid rawat inap data format.');
        }

        // Step 2: Prepare prefill for the form
        $preFill = [
            'tanggal_beri'  => $rawatinap['tanggal_masuk'] ?? date('Y-m-d'),
            'jam_beri'      => $rawatinap['jam_masuk'] ?? date('H:i:s'),
            'nomor_rawat'   => $rawatinap['nomor_rawat'] ?? '',
            'nama_pasien'   => $rawatinap['nama_pasien'] ?? '',
            'kelas'         => !empty($rawatinap['kamar']) ? $rawatinap['kamar'] : 'kelas1',
            'kode_obat'     => '',
            'nama_obat'     => '',
            'embalase'      => '',
            'tuslah'        => '',
            'jumlah'        => '',
            'biaya_obat'    => '',
            'total'         => '',
            'gudang'        => '',
            'no_batch'      => '',
            'no_faktur'     => ''
        ];

        // Step 3: Open the tambah pemberianobat form (without submitting yet)
        return view('admin/pemberianobat/tambah_pemberianobat', [
            'prefill' => $preFill,
            'obat_list' => $this->getObatListFromAPI($token), // fetch available obat list
            'title' => 'Tambah Pemberian Obat'
        ]);
    }

    private function getObatListFromAPI($token)
    {
        $url = $this->api_url . '/pemberian-obat/databarang';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['data'] ?? [];
    }
}