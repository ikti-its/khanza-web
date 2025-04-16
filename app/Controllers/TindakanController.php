<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TindakanController extends BaseController
{
    public function dataTindakan()
    {
        $title = 'Data Tindakan';
    
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
    
            // ✅ Fetch tindakan data
            $tindakan_url = $this->api_url . '/tindakan';
            $ch = curl_init($tindakan_url);
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
    
            $tindakan_data = json_decode($response, true);
            if (!isset($tindakan_data['data'])) {
                return $this->renderErrorView(500);
            }
    
            // ✅ Fetch jenis_tindakan data (this was missing)
            $jenis_url = $this->api_url . '/tindakan/jenis';
            $ch2 = curl_init($jenis_url);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $jenis_response = curl_exec($ch2);
            curl_close($ch2);
    
            $jenis_data = json_decode($jenis_response, true);
            $jenis_tindakan = $jenis_data['data'] ?? [];
    
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Tindakan', 'tindakan');
            $breadcrumbs = $this->getBreadcrumbs();
    
            return view('/admin/tindakan/tindakan_data', [
                'tindakan_data' => $tindakan_data['data'],
                'jenis_tindakan' => $jenis_tindakan,
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $tindakan_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }
    

    public function tambahTindakan($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $title = 'Edit Tindakan';
    
        // Fetch specific tindakan data
        $tindakan_url = $this->api_url . '/tindakan/' . $nomorRawat;
        $ch = curl_init($tindakan_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }
    
        $tindakan_data = json_decode($response, true);
    
        // ✅ Fetch jenis tindakan list
        $jenis_url = $this->api_url . '/tindakan/jenis';
        $ch2 = curl_init($jenis_url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $jenis_response = curl_exec($ch2);
        curl_close($ch2);
    
        $jenis_data = json_decode($jenis_response, true);
        $jenis_tindakan = $jenis_data['data'] ?? [];
    
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Tindakan', 'tindakan');
        $this->addBreadcrumb('Edit', 'edit');
    
        return view('/admin/tindakan/tambah_tindakan', [
            'tindakan' => $tindakan_data['data'][0] ?? [],
            'jenis_tindakan' => $jenis_tindakan,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }
    

    public function submitTambahTindakan()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $nomor_rawat = $this->request->getPost('nomor_rawat');
            $postData = [
                'nomor_rawat' => $this->request->getPost('nomor_rawat'),
                'nomor_rm' => $this->request->getPost('nomor_rm'),
                'nama_pasien' => $this->request->getPost('nama_pasien'),
                'tindakan' => $this->request->getPost('tindakan'),
                'kode_dokter' => $rawatinap['kode_dokter'] ?? 'D001',
                'nama_dokter' => $this->request->getPost('nama_dokter'),
                'nip' => $this->request->getPost('nip'),
                'nama_petugas' => $this->request->getPost('nama_petugas'),
                'tanggal_rawat' => $rawatinap['tanggal_masuk'] ?? date('Y-m-d'),
                'jam_rawat' => $rawatinap['tanggal_masuk'] ?? date('H:i:s'),
                'biaya' => floatval($this->request->getPost('biaya')),
            ];

            $tindakan_url = $this->api_url . '/tindakan';
            $payload = json_encode($postData);

            $ch = curl_init($tindakan_url);
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

            if ($status === 201 || $status === 200) {
                // ✅ Redirect to the specific tindakan/{nomor_rawat}
                return redirect()->to(base_url('tindakan/' . $nomor_rawat));
            } else {
                return $response;
            }
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function editTindakan($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Tindakan';
        $tindakan_url = $this->api_url . '/tindakan/' . $nomorRawat;

        $ch = curl_init($tindakan_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }

        $data = json_decode($response, true);

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Tindakan', 'tindakan');
        $this->addBreadcrumb('Edit', 'edit');

        return view('/admin/tindakan/edit_tindakan', [
            'tindakan' => $data['data'][0] ?? [],
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitEditTindakan($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $tindakan_url = $this->api_url . '/tindakan/' . $nomorRawat;

        $postData = [
            'nomor_rawat' => $this->request->getPost('nomor_rawat'),
            'nomor_rm' => $this->request->getPost('nomor_rm'),
            'nama_pasien' => $this->request->getPost('nama_pasien'),
            'tindakan' => $this->request->getPost('tindakan'),
            'kode_dokter' => $this->request->getPost('kode_dokter'),
            'nama_dokter' => $this->request->getPost('nama_dokter'),
            'nip' => $this->request->getPost('nip'),
            'nama_petugas' => $this->request->getPost('nama_petugas'),
            'tanggal_rawat' => $this->request->getPost('tanggal_rawat'),
            'jam_rawat' => $this->request->getPost('jam_rawat'),
            'biaya' => floatval($this->request->getPost('biaya')),
        ];

        $payload = json_encode($postData);
        $ch = curl_init($tindakan_url);
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
            return redirect()->to(base_url('tindakan'));
        } else {
            return $response;
        }
    }

    public function hapusTindakan($nomor_rawat, $jam_rawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
    
        $delete_url = $this->api_url . "/tindakan/$nomor_rawat/$jam_rawat";
    
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
    
        return redirect()->to('/tindakan')->with('success', 'Tindakan deleted');
    }
    

    public function tindakanData($nomorRawat)
    {
        $title = 'Detail Tindakan';
    
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $tindakan_url = $this->api_url . '/tindakan/' . $nomorRawat;
    
            $ch = curl_init($tindakan_url);
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
    
            $tindakan_data = json_decode($response, true);
    
            if (!isset($tindakan_data['data'])) {
                return $this->renderErrorView(500);
            }
    
            // Ensure $tindakan_data['data'] is an array
            $data = $tindakan_data['data'];
            if (isset($data['nomor_rawat'])) {
                $data = [$data]; // convert to array with one item
            }
    
            // ✅ Fetch jenis_tindakan (MISSING BEFORE)
            $jenis_url = $this->api_url . '/tindakan/jenis';
            $ch2 = curl_init($jenis_url);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $jenis_response = curl_exec($ch2);
            curl_close($ch2);
    
            $jenis_data = json_decode($jenis_response, true);
            $jenis_tindakan = $jenis_data['data'] ?? [];
    
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Tindakan', 'tindakan');
            $breadcrumbs = $this->getBreadcrumbs();
    
            return view('/admin/tindakan/tindakan_data', [
                'tindakan_data' => $data,
                'jenis_tindakan' => $jenis_tindakan, // ✅ now available in the view
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $tindakan_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }
    

    public function submitFromRawatinap($nomor_rawat)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            // Step 1: Get rawat inap data by nomor_rawat
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

            if ($data && isset($data['data'])) {
                $rawatinap = $data['data'];
                // dd($rawatinap);
                // Step 2: Copy needed data to tindakan
                $postData = [
                    'nomor_rawat' => $rawatinap['nomor_rawat'],
                    'nomor_rm'    => $rawatinap['nomor_rm'],
                    'nama_pasien' => $rawatinap['nama_pasien'],
                    'kode_dokter' => $rawatinap['kode_dokter'] ?? 'D001',
                    'tanggal_rawat' => $rawatinap['tanggal_masuk'] ?? date('Y-m-d'),
                    'jam_rawat' => $rawatinap['jam_masuk'] ?? date('H:i:s'),
                    'biaya'       => $rawatinap['total_biaya'],
                ];
                
                // Step 3: Send to Go API
                $url_tindakan = $this->api_url . '/tindakan';
                $ch2 = curl_init($url_tindakan);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_POST, true);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($postData));
                curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json'
                ]);
                $result = curl_exec($ch2);
                $status = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                curl_close($ch2);

                if ($status === 201 || $status === 200) {
                    return redirect()->to('/tindakan/' . $nomor_rawat);
                } else {
                    return redirect()->back()->with('error', 'Gagal menyimpan data tindakan.');
                }
            } else {
                return redirect()->back()->with('error', 'Data rawat inap tidak ditemukan.');
            }
        }
        return redirect()->back()->with('error', 'Tidak ada token sesi.');
    }



}

