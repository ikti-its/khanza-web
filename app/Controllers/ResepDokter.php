<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ResepDokter extends BaseController
{

protected array $breadcrumbs = [];
    protected string $judul = 'Audit Resep Dokter';
    protected string $modul_path = '/resepdokter';
    protected string $api_path = '/resepdokter';
    protected string $kolom_id = 'no_resep';
    protected array $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => false,
                            'hapus'    => false,
                        ];
    protected array $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'No Racik'    , 'no_resep'    , 'indeks'],
                            [1, 'Kode Obat'   , 'kode_barang' , 'indeks'],
                            // [1, 'Nama Obat'   , 'nama_obat'   , 'teks'],
                            [1, 'Jumlah'      , 'jumlah'      , 'jumlah'], 
                            [1, 'Aturan Pakai', 'aturan_pakai', 'teks'],
                            // [1, 'Biaya'       , 'harga'       , 'uang'],
                        ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function dataResepDokter()
    {
        $title = 'Data Resep Dokter';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            // ✅ Fetch resep dokter data
            $url = $this->api_url . '/resep-dokter';
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

            $resep_data = json_decode($response, true);
            if (!isset($resep_data['data'])) {
                return $this->renderErrorView(500);
            }

            // ✅ Breadcrumbs
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Resep Dokter', 'resepdokter');
            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/resepdokter/resepdokter_data', [
                'resepdokter_data' => $resep_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $resep_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function tambahResepDokter($noResep = null)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Tambah Resep Dokter';
        $resep = [];

        if ($noResep) {
            $url = $this->api_url . '/resep-dokter/' . $noResep;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            $parsed = json_decode($response, true);
            $resep = $parsed['data'][0] ?? [];
        }

        // ✅ Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Dokter', 'resepdokter');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/resepdokter/tambah_resepdokter', [
            'resepdokter' => $resep,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitTambahResepDokter()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $postData = [
                'no_resep'      => $this->request->getPost('no_resep'),
                'kode_barang'   => $this->request->getPost('kode_barang'),
                'jumlah'        => floatval($this->request->getPost('jumlah')),
                'aturan_pakai'  => $this->request->getPost('aturan_pakai'),
            ];

            $url = $this->api_url . '/resep-dokter';
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

            if ($status === 201 || $status === 200) {
                return redirect()->to(base_url('resepdokter/' . $postData['no_resep']));
            } else {
                return $this->renderErrorView($status);
            }
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function editResepDokter($noResep, $kodeBarang)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Resep Dokter';

        // Fetch detail resep dokter berdasarkan no_resep
        $url = $this->api_url . '/resep-dokter/' . $noResep;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $selectedResep = [];

        // Temukan entry yang cocok dengan kode_barang
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $item) {
                if ($item['kode_barang'] === $kodeBarang) {
                    $selectedResep = $item;
                    break;
                }
            }
        }

        // 🔍 Fetch nomor_rawat dan nomor_rm dari /resep-obat/:no_resep
        $infoUrl = $this->api_url . '/resep-obat/' . $noResep;
        $infoCh = curl_init($infoUrl);
        curl_setopt($infoCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($infoCh, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $infoResponse = curl_exec($infoCh);
        curl_close($infoCh);

        $infoData = json_decode($infoResponse, true);
        $nomorRawat = $infoData['data']['no_rawat'] ?? '';
        $nomorRM = $infoData['data']['nomor_rm'] ?? '';
    // dd($infoData);
        // 🔎 Fetch obat list
        $obatUrl = $this->api_url . '/pemberian-obat/databarang';
        $obatCh = curl_init($obatUrl);
        curl_setopt($obatCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($obatCh, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $obatResponse = curl_exec($obatCh);
        curl_close($obatCh);

        $obatData = json_decode($obatResponse, true);
        $obat_list = $obatData['data'] ?? [];

        // Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Dokter', 'resepdokter');
        $this->addBreadcrumb('Edit', 'edit');
    // dd([$nomorRawat, $nomorRM]);
        return view('/admin/resepobat/edit_resepobat', [
            'resepdokter'   => $selectedResep,
            'title'         => $title,
            'obat_list'     => $obat_list,
            'nomor_rawat'   => $nomorRawat,
            'nomor_rm'      => $nomorRM,
            'breadcrumbs'   => $this->getBreadcrumbs()
        ]);
    }

    public function submitEditResepDokter($noResep)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $url = $this->api_url . '/resep-dokter';
    
        $postData = [
            'no_resep'     => $noResep,
            'kode_barang'  => $this->request->getPost('kode_barang'),
            'jumlah'       => floatval($this->request->getPost('jumlah')),
            'aturan_pakai' => $this->request->getPost('aturan_pakai'),
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
            return redirect()->to(base_url('resepdokter/' . $noResep));
        } else {
            return $this->renderErrorView($http_status);
        }
    }
    
    public function hapusResepDokter($noResep, $kodeBarang)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
    
        $delete_url = $this->api_url . "/resep-dokter/$noResep/$kodeBarang";
    
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
    
        return redirect()->to('/resepdokter')->with('success', 'Resep dokter deleted');
    }

    public function ResepDokterData($noResep)
    {
        $title = 'Detail Resep Dokter';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        // ✅ Step 1: Get resep dokter detail
        $url = $this->api_url . '/resep-dokter/' . $noResep;
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

        $resep_data = json_decode($response, true);
        // dd($resep_data);
        if (!isset($resep_data['data'])) {
            return $this->renderErrorView(500);
        }

        $data = $resep_data['data'];

        if (!is_array($data)) {
            log_message('error', 'Invalid or missing data in /resep-dokter/' . $noResep);
            return $this->renderErrorView(404); // or 204 No Content, depending on your policy
        }
        if (isset($data['no_resep'])) {
            $data = [$data]; // make single object into array
        }

        // ✅ Step 2: Fetch barang lookup
        $barang_lookup = [];
        $barang_url = $this->api_url . '/pemberian-obat/databarang'; // adjust endpoint if needed
        $ch2 = curl_init($barang_url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $barang_response = curl_exec($ch2);
        curl_close($ch2);

        $barang_data = json_decode($barang_response, true);
        $barang_lookup = [];

        foreach ($barang_data['data'] ?? [] as $item) {
            if (isset($item['kode_obat'], $item['nama_obat'])) {
                $barang_lookup[$item['kode_obat']] = $item['nama_obat'];
            } else {
                log_message('error', 'Missing keys in databarang item: ' . json_encode($item));
            }
        }

        // ✅ Fetch all racikan by no_resep
        $url_racikan = $this->api_url . '/resep-dokter-racikan-detail/' . $noResep;
        $ch_racikan = curl_init($url_racikan);
        curl_setopt($ch_racikan, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_racikan, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response_racikan = curl_exec($ch_racikan);
        $http_status_racikan = curl_getinfo($ch_racikan, CURLINFO_HTTP_CODE);
        curl_close($ch_racikan);

        if ($http_status_racikan === 200) {
            $racikan_data = json_decode($response_racikan, true);
            $racikan_list = is_array($racikan_data['data'] ?? null) ? $racikan_data['data'] : [];
        } else {
            log_message('error', "Failed to fetch racikan data for no_resep $noResep. HTTP status: $http_status_racikan");
            $racikan_list = [];
        }


        // Get resep_obat header (to fetch kd_dokter)
        $url_header = $this->api_url . '/resep-obat/' . $noResep;
        $ch_header = curl_init($url_header);
        curl_setopt($ch_header, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_header, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response_header = curl_exec($ch_header);
        curl_close($ch_header);

        $resep_header_data = json_decode($response_header, true);
        $resepobat_header = $resep_header_data['data'] ?? [];

        $dokter_nama = 'Tidak ditemukan';
        if (!empty($resepobat_header['kd_dokter'])) {
            $dokter_url = $this->api_url . '/dokter/' . $resepobat_header['kd_dokter'];
            $ch_dokter = curl_init($dokter_url);
            curl_setopt($ch_dokter, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_dokter, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $dokter_response = curl_exec($ch_dokter);
            curl_close($ch_dokter);

            $dokter_data = json_decode($dokter_response, true);
            if (isset($dokter_data['data']['nama_dokter'])) {
                $dokter_nama = $dokter_data['data']['nama_dokter'];
            } else {
                log_message('error', 'Dokter name not found in response: ' . json_encode($dokter_data));
            }
        }

        $barang_lookup = [];
        $harga_lookup = [];

        foreach ($barang_data['data'] ?? [] as $item) {
            if (isset($item['kode_obat'], $item['nama_obat'])) {
                $barang_lookup[$item['kode_obat']] = $item['nama_obat'];

                // choose desired class pricing, e.g. Dasar
                $harga_lookup[$item['kode_obat']] = $item['Dasar'] ?? 0;
            }
        }


        // ✅ Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Dokter', 'resepdokter');
        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/resepdokter/resepdokter_data', [
            'racikan_list'     => $racikan_list,
            'resepdokter_data' => $data,
            'barang_lookup'    => $barang_lookup,
            'harga_lookup'     => $harga_lookup,
            'resepobat_header' => $resepobat_header,
            'dokter_nama'      => $dokter_nama,
            'title'            => $title,
            'breadcrumbs'      => $breadcrumbs,
            'meta_data'        => $resep_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
        ]);
    }


    public function submitFromRawatinap($nomor_rawat)
    {
        if (session()->has('jwt_token')) {
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

            if ($data && isset($data['data'])) {
                $rawatinap = $data['data'];

                // Step 2: Map to resep_dokter
                $postData = [
                    'no_resep'      => 'RSP' . date('Ymd') . rand(100, 999),
                    'kode_barang'   => '', // to be selected by user
                    'jumlah'        => 0,
                    'aturan_pakai'  => '',
                ];

                // Step 3: Submit to Go API /resep-dokter
                $url_resep = $this->api_url . '/resep-dokter';
                $ch2 = curl_init($url_resep);
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
                    return redirect()->to('/resepdokter/' . $postData['no_resep'])->with('success', 'Data resep dokter berhasil disimpan.');
                } else {
                    return redirect()->to('/resepdokter/' . $postData['no_resep'])->with('error', 'Gagal menyimpan resep dokter.');
                }
            } else {
                return redirect()->back()->with('error', 'Data rawat inap tidak ditemukan.');
            }
        }

        return redirect()->back()->with('error', 'Tidak ada token sesi.');
    }
}