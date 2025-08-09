<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Tindakan extends BaseController
{

protected array $breadcrumbs = [];
    protected string $judul = 'Tindakan';
    protected string $modul_path = '/tindakan';
    protected string $api_path = '/tindakan';
    protected string $kolom_id = 'nomor_rawat';
    protected array $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => false,
                        ];
    protected array $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'Tindakan', 'tindakan'     , 'teks'],
                            [1, 'Dokter'  , 'nama_dokter'  , 'nama'],
                            [1, 'Petugas' , 'nama_petugas' , 'nama'],
                            [1, 'Tanggal' , 'tanggal_rawat', 'tanggal'],
                            [1, 'Jam'     , 'jam_rawat'    , 'jam'],
                            [1, 'Biaya'   , 'biaya'        , 'uang'],
                        ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

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
                        curl_setopt($ch2, CURLOPT_TIMEOUT, 10);           // max total waktu
curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);     // max waktu koneksi
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
        $title = 'Tambah Tindakan';

        // 🔹 Fetch tindakan (to edit existing if needed)
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
        $existing_tindakan = $tindakan_data['data'][0] ?? [];

        // 🔹 Fetch rawat inap to determine kamar
        $ranap_url = $this->api_url . '/rawatinap/' . $nomorRawat;
        $ch_ranap = curl_init($ranap_url);
        curl_setopt($ch_ranap, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_ranap, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $ranap_response = curl_exec($ch_ranap);
        curl_close($ch_ranap);

        $ranap_data = json_decode($ranap_response, true);
        $nomor_bed = $ranap_data['data']['kamar'] ?? null;

        $kodeBangsal = null;

        // 🔹 Fetch kamar to get kode_bangsal
        if ($nomor_bed) {
            $kamar_url = $this->api_url . '/kamar/' . $nomor_bed;
            $ck = curl_init($kamar_url);
            curl_setopt($ck, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ck, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $res_kamar = curl_exec($ck);
            curl_close($ck);

            $data_kamar = json_decode($res_kamar, true);
            $kodeBangsalRaw = $data_kamar['data']['kelas'] ?? null;
        }

        // 🔹 Normalize kode_bangsal
        $kodeBangsalRaw = strtoupper(trim($kodeBangsalRaw ?? ''));
        if (in_array($kodeBangsalRaw, ['1', '2', '3'])) {
            $normalizedBangsal = 'K' . $kodeBangsalRaw;
        } else {
            $normalizedBangsal = $kodeBangsalRaw;
        }

        // 🔹 Fetch all jenis tindakan
        $jenis_url = $this->api_url . '/tindakan/jenis';
        $ch2 = curl_init($jenis_url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $jenis_response = curl_exec($ch2);
        curl_close($ch2);

        $jenis_data = json_decode($jenis_response, true);
        $jenis_all = $jenis_data['data'] ?? [];

        // 🔹 Filter jenis tindakan
        $jenis_tindakan = array_filter($jenis_all, function ($item) use ($normalizedBangsal) {
            $kode = strtoupper(trim($item['kode_bangsal'] ?? ''));
            return $kode === $normalizedBangsal || $kode === '-';
        });

        // 🔹 Breadcrumb + render
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Tindakan', 'tindakan');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/tindakan/tambah_tindakan_id', [
            'tindakan' => $existing_tindakan,
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
            $biaya = $this->request->getPost('biaya');
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
                // 'biaya' => floatval($this->request->getPost('biaya')),
                'biaya' => floatval($biaya),
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

    public function editTindakan($nomorRawat, $jamRawat)
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
        curl_close($ch);

        $data = json_decode($response, true);
        $selectedTindakan = [];

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

        // ✅ Find the tindakan with the correct jam_rawat
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $t) {
                if ($t['jam_rawat'] === $jamRawat) {
                    $selectedTindakan = $t;
                    break;
                }
            }
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Tindakan', 'tindakan');
        $this->addBreadcrumb('Edit', 'edit');

        return view('/admin/tindakan/edit_tindakan', [
            'tindakan' => $selectedTindakan,
            'jenis_tindakan' => $jenis_tindakan, // ✅ this fixes the undefined variable error
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }
    
    public function submitEditTindakan($nomorRawat, $jamRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        // Build API URL with nomor_rawat and jam_rawat
        $tindakan_url = $this->api_url . '/tindakan/' . $nomorRawat . '/' . $jamRawat;

        $postData = [
            'nomor_rawat'    => $this->request->getPost('nomor_rawat'),
            'nomor_rm'       => $this->request->getPost('nomor_rm'),
            'nama_pasien'    => $this->request->getPost('nama_pasien'),
            'tindakan'       => $this->request->getPost('tindakan'),
            'kode_dokter'    => $this->request->getPost('kode_dokter'),
            'nama_dokter'    => $this->request->getPost('nama_dokter'),
            'nip'            => $this->request->getPost('nip'),
            'nama_petugas'   => $this->request->getPost('nama_petugas'),
            'tanggal_rawat'  => $this->request->getPost('tanggal_rawat'),
            'jam_rawat'      => $this->request->getPost('jam_rawat'),
            'biaya'          => floatval($this->request->getPost('biaya')),
        ];

        $payload = json_encode($postData);
    // dd($tindakan_url);
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
            return redirect()->to(base_url('tindakan/' . $nomorRawat));
        } else {
            return $response;
        }
    }

    public function tindakanData($nomorRawat)
    {
        $title = 'Detail Tindakan';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        // 🔹 Fetch tindakan data
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

        $data = [];
        $tindakan_data = [];

        if ($http_status === 200) {
            $tindakan_data = json_decode($response, true);

            if (isset($tindakan_data['data'])) {
                $data = $tindakan_data['data'];

                // Normalize to array
                if (isset($data['nomor_rawat'])) {
                    $data = [$data];
                }
            }
        }

        // 🔹 Fetch jenis tindakan
        $jenis_url = $this->api_url . '/tindakan/jenis';
        $ch2 = curl_init($jenis_url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 10);           // max total waktu
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);     // max waktu koneksi
        $jenis_response = curl_exec($ch2);
        curl_close($ch2);

        $jenis_data = json_decode($jenis_response, true);
        $jenis_tindakan = $jenis_data['data'] ?? [];

        // 🔹 Fetch rawat inap to get nomor_bed
        $ranap_url = $this->api_url . '/rawatinap/' . $nomorRawat;

        $ch3 = curl_init($ranap_url);
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch3, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $ranap_response = curl_exec($ch3);
        curl_close($ch3);

        $ranap_data = json_decode($ranap_response, true);
        $nomor_bed = $ranap_data['data']['kamar'] ?? null;
    // dd($nomor_bed);
        $kelas = null;

        // 🔹 Fetch kamar if nomor_bed is available
        if ($nomor_bed) {
            $kamar_url = $this->api_url . '/kamar/' . $nomor_bed;
            $ch4 = curl_init($kamar_url);
            curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch4, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $kamar_response = curl_exec($ch4);
            curl_close($ch4);

            $kamar_data = json_decode($kamar_response, true);
            $kelas = $kamar_data['data']['kelas'] ?? null;
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Tindakan', 'tindakan');
        $breadcrumbs = $this->getBreadcrumbs();
    // dd($kelas);
        return view('/admin/tindakan/tindakan_data', [
            'tindakan_data' => $data,
            'jenis_tindakan' => $jenis_tindakan,
            'kelas' => $kelas,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $tindakan_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 0],
        ]);
    }

    public function submitFromRawatinap($nomor_rawat)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->back()->with('error', 'Session token missing.');
        }

        $token = session()->get('jwt_token');

        // 🔹 Step 1: Get rawat inap data
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

        // 🔹 Step 2: Get nomor_bed to fetch kamar
        $nomor_bed = $rawatinap['kamar'] ?? null;
        $kodeBangsal = null;

        if ($nomor_bed) {
            $url_kamar = $this->api_url . '/kamar/' . $nomor_bed;
            $ck = curl_init($url_kamar);
            curl_setopt($ck, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ck, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $res_kamar = curl_exec($ck);
            curl_close($ck);

            $data_kamar = json_decode($res_kamar, true);
            $kodeBangsal = $data_kamar['data']['kelas'] ?? null;
        }


        // 🔹 Step 3: Prefill form values
        $preFill = [
            'nomor_rawat'    => $rawatinap['nomor_rawat'] ?? $nomor_rawat,
            'nomor_rm'       => $rawatinap['nomor_rm'] ?? '',
            'nama_pasien'    => $rawatinap['nama_pasien'] ?? '',
            'kode_dokter'    => $rawatinap['kode_dokter'] ?? 'D001',
            'tanggal_rawat'  => $rawatinap['tanggal_masuk'] ?? date('Y-m-d'),
            'jam_rawat'      => $rawatinap['jam_masuk'] ?? date('H:i:s'),
            'biaya'          => $rawatinap['total_biaya'] ?? 0,
        ];

        // 🔹 Step 4: Get all jenis tindakan
        $jenis_url = $this->api_url . '/tindakan/jenis';
        $cj = curl_init($jenis_url);
        curl_setopt($cj, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cj, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $jenis_response = curl_exec($cj);
        curl_close($cj);

        $jenis_data = json_decode($jenis_response, true);
        $jenis_all = $jenis_data['data'] ?? [];

        // 🔹 Step 5: Filter based on kelas
        $kodeBangsalRaw = strtoupper(trim($kodeBangsal ?? ''));

        // Normalize to K1, K2, etc., or keep as VIP/VVIP
        if (in_array($kodeBangsalRaw, ['1', '2', '3'])) {
            $normalizedBangsal = 'K' . $kodeBangsalRaw;
        } else {
            $normalizedBangsal = $kodeBangsalRaw;
        }

        $jenis_tindakan = array_filter($jenis_all, function ($item) use ($normalizedBangsal) {
            $kode = strtoupper(trim($item['kode_bangsal'] ?? ''));
            return $kode === $normalizedBangsal || $kode === '-';
        });


    // dd($normalizedBangsal);
        return view('admin/tindakan/tambah_tindakan', [
            'prefill' => $preFill,
            'jenis_tindakan' => $jenis_tindakan
        ]);
    }


    public function submitFromRegistrasi($nomor_reg)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->back()->with('error', 'Session token missing.');
        }

        $token = session()->get('jwt_token');

        // Step 1: Fetch registrasi data
        $url = $this->api_url . '/registrasi/' . $nomor_reg;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['data']) || $data['data'] === null) {
            return redirect()->back()->with('error', 'Registrasi data not found.');
        }

        $registrasi = is_string($data['data']) ? json_decode($data['data'], true) : $data['data'];

        if (!is_array($registrasi)) {
            return redirect()->back()->with('error', 'Invalid registrasi data format.');
        }

        // Prepare data for the view
        $preFill = [
            'nomor_rawat'    => $registrasi['nomor_rawat'] ?? '',
            'nomor_rm'       => $registrasi['nomor_rm'] ?? '',
            'nama_pasien'    => $registrasi['nama_pasien'] ?? '',
            'kode_dokter'    => $registrasi['kode_dokter'] ?? 'D001',
            'tanggal_rawat'  => !empty($registrasi['tanggal']) ? $registrasi['tanggal'] : date('Y-m-d'),
            'jam_rawat'      => !empty($registrasi['jam']) ? $registrasi['jam'] : date('H:i:s'),
            'biaya'          => $registrasi['biaya_registrasi'] ?? 0,
        ];

        // Step 2: Open the form (tambahTindakan view) with pre-filled data
        return view('admin/tindakan/tambah_tindakan', [
            'prefill' => $preFill,
            'jenis_tindakan' => $this->getJenisTindakan($token) // 👈 helper function you create
        ]);
    }

    private function getJenisTindakan($token)
    {
        $url = $this->api_url . '/tindakan/jenis'; // ✅ correct route from Go backend
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

    public function submitFromUGD($nomor_rawat)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->back()->with('error', 'Session token missing.');
        }

        $token = session()->get('jwt_token');

        // Step 1: Fetch ALL UGD data
        $url = $this->api_url . '/ugd';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['data']) || empty($data['data'])) {
            return redirect()->back()->with('error', 'UGD data not found.');
        }

        // Step 2: Search for the matching nomor_rawat manually
        $ugdList = $data['data'];
        $selectedUGD = null;

        foreach ($ugdList as $ugd) {
            if (isset($ugd['nomor_rawat']) && $ugd['nomor_rawat'] == $nomor_rawat) {
                $selectedUGD = $ugd;
                break;
            }
        }

        if (!$selectedUGD) {
            return redirect()->back()->with('error', 'UGD with nomor_rawat not found.');
        }

        // Step 3: Prepare data for the view
        $preFill = [
            'nomor_rawat'    => $selectedUGD['nomor_rawat'] ?? '',
            'nomor_rm'       => $selectedUGD['nomor_rm'] ?? '',
            'nama_pasien'    => $selectedUGD['nama_pasien'] ?? '',
            'kode_dokter'    => $selectedUGD['kode_dokter'] ?? 'D001',
            'tanggal_rawat'  => !empty($selectedUGD['tanggal']) ? $selectedUGD['tanggal'] : date('Y-m-d'),
            'jam_rawat'      => !empty($selectedUGD['jam']) ? $selectedUGD['jam'] : date('H:i:s'),
            'biaya'          => $selectedUGD['biaya_registrasi'] ?? 0,
        ];

        // Step 4: Open the tambahTindakan form
        return view('admin/tindakan/tambah_tindakan', [
            'prefill' => $preFill,
            'jenis_tindakan' => $this->getJenisTindakan($token) // helper to load jenis tindakan list
        ]);
    }

    public function hapusTindakan($nomorRawat, $jamRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        // Build DELETE API URL
        $deleteUrl = $this->api_url . '/tindakan/' . $nomorRawat . '/' . $jamRawat;

        // Initialize cURL
        $ch = curl_init($deleteUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus !== 200) {
            return $this->renderErrorView($httpStatus);
        }

        return redirect()->to('/tindakan')->with('success', 'Tindakan berhasil dihapus');
    }
}