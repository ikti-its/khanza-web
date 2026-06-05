<?php
declare(strict_types=1);

namespace App\Controllers;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;
use App\Core\Controller\ErrorController;

class CatatanObservasiRanap extends ControllerTemplateLegacy
{

protected array $breadcrumbs = [];
    protected string $judul = 'Catatan Observasi Ranap';
    protected string $modul_path = '/catatanobservasiranap';
    protected string $api_path = '/catatanobservasiranap';
    protected string $kolom_id = 'no_rawat';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true,
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor Rawat'      , 'no_rawat'     , 'indeks'],
        [1, 'Nama Pasien'      , 'nama_pasien'  , 'nama'  ],
        [1, 'Umur'             , 'umur'         , 'jumlah'],
        [1, 'Jenis Kelamin'    , 'jenis_kelamin', 'status'], 
        [1, 'Tanggal Observasi', 'tanggal'      , 'tanggal'],
        [0, 'Jam Observasi'    , 'jam'          , 'jam'   ],
        [0, 'GCS (E, V, M)'    , 'gcs'          , 'jumlah'],
        [0, 'TD (mmHG)'        , 'td'           , ],
        [0, 'HR (x/menit)'     , 'hr'           , ],
        [0, 'RR (x/menit)'     , 'rr'           , ],
        [0, 'Suhu (C)'         , 'suhu'         , ],
        [0, 'SpO2(%)'          , 'spo2'         , ],
        // [0, 'TFU'              , 'tfu'          , ],
        // [0, 'Kontraksi/HIS'    , 'kontraksi'    , ],
        // [0, 'Perdarahan'       , 'perdarahan'   , ],
        // [0, 'Keterangan'       , 'keterangan'   , ], 
        // [0, 'BJJ'              , 'bjj'          , ],
        // [0, 'PPV'              , 'ppv'          , ],
        // [0, 'VT'               , 'vt'           , ],
        [0, 'NIP'              , 'nip'          , ],
        [0, 'Nama Petugas'     , 'nama_petugas' , ],
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function dataCatatanObservasi()
    {
        $title = 'Catatan Observasi Rawat Inap';

        if (!session()->has('jwt_token')) {
            return ErrorController::renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/catatan-observasi-ranap';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        if ($status !== 200 || !$response) {
            return ErrorController::renderErrorView($status);
        }

        $data = json_decode($response, true);
        if (!isset($data['data']) || !is_array($data['data'])) {
            return ErrorController::renderErrorView(500);
        }

        $list = $data['data'];

        // Tambahkan nama pasien & petugas (opsional)
        foreach ($list as &$item) {
            if (isset($item['no_rawat'])) {
                $no_rawat = $item['no_rawat'];
                $registrasi_url = $this->api_url . '/registrasi/by-no-rawat/' . $no_rawat;

                $ch_reg = curl_init($registrasi_url);
                curl_setopt($ch_reg, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_reg, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $response_reg = curl_exec($ch_reg);


                $reg_data = json_decode($response_reg, true);
                $item['nama_pasien'] = $reg_data['data']['nama_pasien'] ?? '';
                $item['umur'] = $reg_data['data']['umur'] ?? '';
                $item['jenis_kelamin'] = $reg_data['data']['jenis_kelamin'] ?? '';
                $item['nomor_rm'] = $reg_data['data']['nomor_rm'] ?? ''; // ✅ Tambahkan ini
            }

            if (!empty($item['nip'])) {
                $nip = urlencode($item['nip']);
                $pegawai_url = $this->api_url . '/pegawai/nip/' . $nip;

                $ch_nama = curl_init($pegawai_url);
                curl_setopt($ch_nama, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_nama, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $response_nama = curl_exec($ch_nama);


                $pegawai_data = json_decode($response_nama, true);
                if (
                    isset($pegawai_data['status']) &&
                    $pegawai_data['status'] === 'success' &&
                    isset($pegawai_data['data']['Nama'])
                ) {
                    $item['nama_petugas'] = $pegawai_data['data']['Nama'];
                } else {
                    $item['nama_petugas'] = '—';
                }
            } else {
                $item['nama_petugas'] = '—';
            }
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Observasi Rawat Inap', 'catatanobservasiranap');
        
        $meta_data = $data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => count($list)];
// dd($list);
        return view('/admin/observasiranap/catatanobservasiranap_data', [
            'catatan_data' => $list,
            'title' => $title,
            'breadcrumbs' => $this->breadcrumbs,
            'meta_data' => $meta_data
        ]);
    }

    public function tambahCatatanObservasi()
    {
        if (session()->has('jwt_token')) {
            $title = 'Tambah Catatan Observasi Rawat Inap';

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Observasi Rawat Inap', 'catatanobservasiranap');
            $this->addBreadcrumb('Tambah', 'tambah');
            
            return view('/admin/observasiranap/tambah_catatanobservasiranap', [
                'title' => $title,
                'breadcrumbs' => $this->breadcrumbs
            ]);
        } else {
            return ErrorController::renderErrorView(401);
        }
    }

    public function submitTambahCatatanObservasi()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $tgl_lahir_raw = $this->request->getPost('tgl_lahir');
            $tgl_perawatan_raw = $this->request->getPost('tgl_perawatan');

            $tgl_lahir = null;
            $tgl_perawatan = null;

            try {
                if (!empty($tgl_lahir_raw)) {
                    $tgl_lahir = (new \DateTime($tgl_lahir_raw))->format('Y-m-d');
                } else {
                    // Optional: fallback ke tanggal hari ini
                    $tgl_lahir = date('Y-m-d');
                }

                if (!empty($tgl_perawatan_raw)) {
                    $tgl_perawatan = (new \DateTime($tgl_perawatan_raw))->format('Y-m-d');
                } else {
                    $tgl_perawatan = date('Y-m-d');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Format tanggal tidak valid.');
            }


            $postData = [
                'no_rawat'     => $this->request->getPost('no_rawat'),
                'nama_pasien'  => $this->request->getPost('nama_pasien'),
                // 'tgl_lahir'    => $tgl_lahir,
                'tgl_perawatan'=> $tgl_perawatan,
                'jam_rawat'    => $this->request->getPost('jam_rawat'),
                'nip'          => $this->request->getPost('nip'),
                'gcs'          => $this->request->getPost('gcs'),
                'td'           => $this->request->getPost('td'),
                'hr'           => $this->request->getPost('hr'),
                'rr'           => $this->request->getPost('rr'),
                'suhu'         => $this->request->getPost('suhu'),
                'spo2'         => $this->request->getPost('spo2'),
            ];
// dd($postData);

            $url = $this->api_url . '/catatan-observasi-ranap';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);
            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);


            if ($http_status === 201) {
                return redirect()->to(base_url('catatanobservasiranap'));
            } else {
                return $response;
            }
        } else {
            return ErrorController::renderErrorView(401);
        }
    }



    public function editCatatanObservasiRanap($noRawat, $tglPerawatan)
    {
        if (!session()->has('jwt_token')) {
            return ErrorController::renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Observasi Rawat Inap';

        // 🔹 Fetch specific catatan observasi berdasarkan no_rawat dan tgl_perawatan
        $url = $this->api_url . '/catatan-observasi-ranap/' . $noRawat . '/' . $tglPerawatan;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        if ($status !== 200) {
            return ErrorController::renderErrorView($status);
        }

        $data = json_decode($response, true);
        $catatan = $data['data'] ?? [];
        // 🔹 Normalisasi format tanggal ISO ke Y-m-d
        $catatan['tgl_perawatan'] = date('Y-m-d', strtotime($catatan['tgl_perawatan']));
        
        // 🔹 Fetch pasien data
        $nama_pasien = '';
        $tgl_lahir = '';
        $no_rkm_medis = '';

        if (!empty($catatan['no_rawat'])) {
            $registrasi_url = $this->api_url . '/registrasi/by-no-rawat/' . $catatan['no_rawat'];

            $ch_reg = curl_init($registrasi_url);
            curl_setopt($ch_reg, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_reg, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response_reg = curl_exec($ch_reg);


            $reg_data = json_decode($response_reg, true);
            $nama_pasien = $reg_data['data']['nama_pasien'] ?? '';
            $no_rkm_medis = $reg_data['data']['nomor_rm'] ?? '';

            if (!empty($no_rkm_medis)) {
                $pasien_url = $this->api_url . '/pasien/' . urlencode($no_rkm_medis);

                $ch_pasien = curl_init($pasien_url);
                curl_setopt($ch_pasien, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_pasien, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $response_pasien = curl_exec($ch_pasien);


                $pasien_data = json_decode($response_pasien, true);
                $tgl_lahir = $pasien_data['data']['tgl_lahir'] ?? '';
            }
        }

        // 🔹 Fetch nama petugas
        $nama_petugas = '—';
        if (!empty($catatan['nip'])) {
            $pegawai_url = $this->api_url . '/pegawai/nip/' . urlencode($catatan['nip']);

            $ch_petugas = curl_init($pegawai_url);
            curl_setopt($ch_petugas, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_petugas, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response_petugas = curl_exec($ch_petugas);


            $pegawai_data = json_decode($response_petugas, true);
            if (
                isset($pegawai_data['status']) &&
                $pegawai_data['status'] === 'success' &&
                isset($pegawai_data['data']['Nama'])
            ) {
                $nama_petugas = $pegawai_data['data']['Nama'];
            }
        }
    // dd($catatan);
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Observasi Rawat Inap', 'catatanobservasiranap');
        $this->addBreadcrumb('Edit', 'edit');
        
        return view('/admin/observasiranap/edit_catatanobservasiranap', [
            'catatan'       => $catatan,
            'title'         => $title,
            'breadcrumbs'   => $this->breadcrumbs,
            'nama_pasien'   => $nama_pasien,
            'tgl_lahir'     => $tgl_lahir,
            'nama_petugas'  => $nama_petugas,
        ]);
    }


    public function submitEditCatatanObservasiRanap($noRawat, $tanggalObservasi)
    {
        if (!session()->has('jwt_token')) {
            return ErrorController::renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        // URL termasuk tanggal observasi sebagai identitas data yang akan diupdate
        $url = $this->api_url . '/catatan-observasi-ranap/' . $noRawat . '/' . $tanggalObservasi;

        // Gunakan tanggal dari POST jika tersedia, fallback ke $tanggalObservasi
        $tanggalInput = trim($this->request->getPost('tanggal'));
        $tanggal = $tanggalInput !== '' ? $tanggalInput : $tanggalObservasi;

        $data = [
            'no_rawat'     => $noRawat,
            'tgl_perawatan'=> $tanggal,
            'jam_rawat'    => $this->request->getPost('jam'),
            'nip'          => $this->request->getPost('nip'),
            'gcs'          => $this->request->getPost('gcs'),
            'td'           => $this->request->getPost('td'),
            'hr'           => $this->request->getPost('hr'),
            'rr'           => $this->request->getPost('rr'),
            'suhu'         => $this->request->getPost('suhu'),
            'spo2'         => $this->request->getPost('spo2'),
            'kontraksi'    => $this->request->getPost('kontraksi'),
            'bjj'          => $this->request->getPost('bjj'),
            'ppv'          => $this->request->getPost('ppv'),
            'vt'           => $this->request->getPost('vt'),
        ];

        $json = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json),
            'Authorization: Bearer ' . $token,
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        if ($status === 200) {
            return redirect()->to(base_url('catatanobservasiranap'));
        } else {
            $error = json_decode($response, true);
            return $error['data'] ?? $response;
        }
    }
   public function hapusCatatanObservasiRanap($noRawat)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $delete_url = $this->api_url . '/catatan-observasi-ranap/' . $noRawat;

            $ch = curl_init($delete_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json',
            ]);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);


            if ($http_status === 200 || $http_status === 204) {
                return redirect()->to(base_url('catatanobservasiranap'))->with('success', 'Data observasi Rawat Inap berhasil dihapus.');
            } else {
                return ErrorController::renderErrorView($http_status);
            }
        } else {
            return ErrorController::renderErrorView(401);
        }
    }

    public function submitFromRawatinapToCatatanObservasi($nomor_rawat)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->back()->with('error', 'Session token missing.');
        }

        $token = session()->get('jwt_token');

        // Step 1: Ambil data rawat inap
        $url_rawatinap = $this->api_url . '/rawatinap/' . $nomor_rawat;
        $ch = curl_init($url_rawatinap);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);


        $data = json_decode($response, true);

        if (!isset($data['data']) || $data['data'] === null) {
            return redirect()->back()->with('error', 'Rawat inap data not found.');
        }

        $rawatinap = is_string($data['data']) ? json_decode($data['data'], true) : $data['data'];
        if (!is_array($rawatinap)) {
            return redirect()->back()->with('error', 'Invalid rawat inap data format.');
        }

        // Step 2: Ambil tgl_lahir dari data pasien
        $nomor_rm = $rawatinap['nomor_rm'];
        $tgl_lahir = null;

        if ($nomor_rm) {
            $url_pasien = $this->api_url . '/pasien/' . urlencode($nomor_rm);
            $pch = curl_init($url_pasien);
            curl_setopt($pch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($pch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $presp = curl_exec($pch);


            $pasien_data = json_decode($presp, true);
            if (isset($pasien_data['data']['tgl_lahir'])) {
                $tgl_lahir = $pasien_data['data']['tgl_lahir'];
            }
        }

        // Ambil data pegawai
        $url_pegawai = $this->api_url . '/pegawai';
        $pch = curl_init($url_pegawai);
        curl_setopt($pch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($pch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $presp = curl_exec($pch);


        $pegawai_data = json_decode($presp, true);
        $pegawai_list = $pegawai_data['data'] ?? [];

        // Step 3: Siapkan prefill data
        $prefill = [
            'no_rawat'     => $rawatinap['nomor_rawat'] ?? $nomor_rawat,
            'nip'          => '', // Petugas input sendiri
            'nama_pasien'  => $rawatinap['nama_pasien'] ?? '',
            'tanggal'      => $rawatinap['tanggal_masuk'] ?? date('Y-m-d'),
            'jam'          => $rawatinap['jam_masuk'] ?? date('H:i:s'),
            'kontraksi'    => '',
            'bjj'          => '',
            'ppv'          => '',
            'vt'           => '',
            'tgl_lahir'    => $tgl_lahir ?? '',
        ];

        // Breadcrumb
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Observasi Rawat Inap', 'catatanobservasiranap');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/observasiranap/tambah_catatanobservasi', [
            'title' => 'Tambah Observasi Rawat Inap',
            'breadcrumbs' => $this->breadcrumbs,
            'prefill' => $prefill,
            'pegawai_list' => $pegawai_list
        ]);
    }

    public function lihatCatatanObservasiByNoRawat($no_rawat)
    {
        $title = 'Catatan Observasi Rawat Inap';

        if (!session()->has('jwt_token')) {
            return ErrorController::renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/catatan-observasi-ranap/' . $no_rawat;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        if ($status !== 200 || !$response) {
            return ErrorController::renderErrorView($status);
        }

        $data = json_decode($response, true);
        $list = $data['data'] ?? [];

        // Enrich data with patient and staff names
        foreach ($list as &$item) {
            if (isset($item['no_rawat'])) {
                $registrasi_url = $this->api_url . '/registrasi/by-no-rawat/' . $item['no_rawat'];
                $ch_reg = curl_init($registrasi_url);
                curl_setopt($ch_reg, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_reg, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $response_reg = curl_exec($ch_reg);


                $reg_data = json_decode($response_reg, true);
                $item['nama_pasien'] = $reg_data['data']['nama_pasien'] ?? '';
                $item['umur'] = $reg_data['data']['umur'] ?? '';
                $item['jenis_kelamin'] = $reg_data['data']['jenis_kelamin'] ?? '';
                $item['nomor_rm'] = $reg_data['data']['nomor_rm'] ?? ''; // ✅ Tambahkan ini
            }

            if (!empty($item['nip'])) {
                $pegawai_url = $this->api_url . '/pegawai/nip/' . urlencode($item['nip']);
                $ch_peg = curl_init($pegawai_url);
                curl_setopt($ch_peg, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_peg, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $pegawai_response = curl_exec($ch_peg);


                $pegawai_data = json_decode($pegawai_response, true);
                $item['nama_petugas'] = $pegawai_data['data']['Nama'] ?? '—';
            } else {
                $item['nama_petugas'] = '—';
            }
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Observasi Rawat Inap', 'catatanobservasiranap');
        $this->addBreadcrumb('Lihat', '');
        
        return view('/admin/observasiranap/catatanobservasiranap_data', [
            'catatan_data' => $list,
            'title' => $title,
            'breadcrumbs' => $this->breadcrumbs,
            'meta_data' => ['page' => 1, 'size' => count($list), 'total' => count($list)]
        ]);
    }
}