<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PemeriksaanRanap extends BaseController
{

    protected array $breadcrumbs = [];
    protected string $judul = 'Audit Pemeriksaan Ranap';
    protected string $modul_path = '/pemeriksaanranap';
    protected string $api_path = '/pemeriksaanranap';
    protected string $kolom_id = 'no_rawat';
    protected array $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => false,
                        ];
    protected array $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'Nomor Rawat'  , 'no_rawat'    , 'indeks'],
                            [1, 'Nomor RM'     , 'nomor_rm'    , 'indeks'],
                            [1, 'Nama Pasien'  , 'nama_pasien' , 'nama'],
                            [1, 'Tanggal Rawat', 'tanggal'     , 'tanggal'],
                            [1, 'Jam'          , 'jam'         , 'jam'],
                            [1, 'Suhu'         , 'suhu_tubuh'  , 'suhu'],
                            [0, 'TD (mmHG)'    , 'tensi'       ],
                            [0, 'HR (x/menit)' , 'nadi'        ],
                            [0, 'RR (x/menit)' , 'respirasi'   ],
                            [0, 'Tinggi (cm)'  , 'tinggi'      ],
                            [0, 'Berat (kg)'   , 'berat'       ],
                            [0, 'SpO2(%)'      , 'spo2'        ],
                            [0, 'GCS (E, V, M)', 'gcs'         ],
                            [0, 'Kesadaran'    , 'kesadaran'   ],
                            [0, 'Alergi'       , 'alergi'      ],
                            [0, 'Subjek'       , 'keluhan'     ],
                            [0, 'Objek'        , 'pemeriksaan' ],
                            [0, 'Asesmen'      , 'penilaian'   ],
                            [0, 'Plan'         , 'rtl'         ],
                            [0, 'Instruksi'    , 'instruksi'   ],
                            [0, 'Evaluasi'     , 'evaluasi'    ],
                            [0, 'NIP'          , 'nip'         ],
                            [0, 'Nama Petugas' , 'nama_petugas'],
                            [0, 'Profesi'      , 'nama_petugas']
                        ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function dataPemeriksaanRanap()
    {
        $title = 'Data Pemeriksaan Ranap';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $pemeriksaan_url = $this->api_url . '/pemeriksaanranap';

        // 🔹 Fetch pemeriksaan ranap data
        $ch = curl_init($pemeriksaan_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200 || !$response) {
            return $this->renderErrorView($status);
        }

        $data = json_decode($response, true);

        if (!isset($data['data']) || !is_array($data['data'])) {
            return $this->renderErrorView(500);
        }

        $pemeriksaan_list = $data['data'];

        // dd($pemeriksaan_list);

        // 🔁 Enrich each item with registrasi data and nama pegawai
        foreach ($pemeriksaan_list as &$item) {
            // ✅ Fetch registrasi data
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
                curl_close($ch_reg);
    // log_message('debug', "📥 Registrasi response for {$no_rawat}: {$response_reg}");
                $reg_data = json_decode($response_reg, true);
    // dd($reg_data);
                $item['nomor_rm'] = $reg_data['data']['nomor_rm'] ?? '';
                $item['nama_pasien'] = $reg_data['data']['nama_pasien'] ?? '';
                $item['umur'] = $reg_data['data']['umur'] ?? '';
                $item['jenis_kelamin'] = $reg_data['data']['jenis_kelamin'] ?? '';
                $item['penilaian'] = $item['penilaian'] ?? '-';
                $item['rtl'] = $item['rtl'] ?? '-';
                $item['keluhan'] = $item['keluhan'] ?? '-';
                $item['pemeriksaan'] = $item['pemeriksaan'] ?? '-';
            }

                // ✅ Fetch nama petugas based on NIP
            if (isset($item['nip']) && !empty($item['nip'])) {
                $nip = urlencode($item['nip']);
                $pegawai_url = $this->api_url . '/pegawai/nip/' . $nip;

                $ch_nama = curl_init($pegawai_url);
                curl_setopt($ch_nama, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_nama, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $response_nama = curl_exec($ch_nama);
                curl_close($ch_nama);

                $pegawai_data = json_decode($response_nama, true);
                // error_log("📥 Pegawai response for NIP {$nip}: " . $response_nama);

                // ✅ Defensive check for nested structure
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

        // Breadcrumbs & pagination
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemeriksaan Ranap', 'pemeriksaanranap');
        $breadcrumbs = $this->getBreadcrumbs();
    // dd($pemeriksaan_list);
        $meta_data = $data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => count($pemeriksaan_list)];
        // dd($pemeriksaan_list);
        return view('/admin/pemeriksaanranap/pemeriksaanranap_data', [
            'pemeriksaanranap_data' => $pemeriksaan_list,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $meta_data
        ]);
    }

    public function dataPemeriksaanRanapDetail($noRawat)
    {
        $title = 'Pemeriksaan Ranap Pasien';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $pemeriksaan_url = $this->api_url . '/pemeriksaanranap';

        $ch = curl_init($pemeriksaan_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200 || !$response) {
            return $this->renderErrorView($status);
        }

        $data = json_decode($response, true);
        if (!isset($data['data']) || !is_array($data['data'])) {
            return $this->renderErrorView(500);
        }
    // dd($data);
        // 🔍 Filter only entries with matching no_rawat
        $filtered = array_filter($data['data'], fn($item) => $item['no_rawat'] === $noRawat);

        // 🧩 Enrich each item
        foreach ($filtered as &$item) {
            // Registrasi
            $registrasi_url = $this->api_url . '/registrasi/by-no-rawat/' . urlencode($item['no_rawat']);
            $ch_reg = curl_init($registrasi_url);
            curl_setopt($ch_reg, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_reg, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token
            ]);
            $response_reg = curl_exec($ch_reg);
            curl_close($ch_reg);
            $reg_data = json_decode($response_reg, true);
            $item['nomor_rm'] = $reg_data['data']['nomor_rm'] ?? '';
            $item['nama_pasien'] = $reg_data['data']['nama_pasien'] ?? '';
            $item['umur'] = $reg_data['data']['umur'] ?? '';
            $item['jenis_kelamin'] = $reg_data['data']['jenis_kelamin'] ?? '';
            $item['penilaian'] = $item['penilaian'] ?? '-';
            $item['rtl'] = $item['rtl'] ?? '-';
            $item['keluhan'] = $item['keluhan'] ?? '-';
            $item['pemeriksaan'] = $item['pemeriksaan'] ?? '-';

            // Pegawai
            if (!empty($item['nip'])) {
                $nip = urlencode($item['nip']);
                $pegawai_url = $this->api_url . '/pegawai/nip/' . $nip;
                $ch_nama = curl_init($pegawai_url);
                curl_setopt($ch_nama, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_nama, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token
                ]);
                $response_nama = curl_exec($ch_nama);
                curl_close($ch_nama);
                $pegawai_data = json_decode($response_nama, true);
                $item['nama_petugas'] = $pegawai_data['data']['Nama'] ?? '—';
            } else {
                $item['nama_petugas'] = '—';
            }
        }

        // Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemeriksaan Ranap', 'pemeriksaanranap');
        $this->addBreadcrumb('Detail Pasien', '');
        $breadcrumbs = $this->getBreadcrumbs();
    // dd($filtered);
        return view('/admin/pemeriksaanranap/pemeriksaanranap_data', [
            'pemeriksaanranap_data' => $filtered,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => ['page' => 1, 'size' => count($filtered), 'total' => count($filtered)]
        ]);
    }


    public function tambahPemeriksaanRanap()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Tambah Pemeriksaan Ranap';

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemeriksaan Ranap', 'pemeriksaanranap');
        $this->addBreadcrumb('Tambah', 'tambah');
        $breadcrumbs = $this->getBreadcrumbs();

        // === Fetch NIP from Pegawai ===
        $pegawaiNips = [];
        $pegawaiUrl = $this->api_url . '/pegawai';
        $chPegawai = curl_init($pegawaiUrl);
        curl_setopt($chPegawai, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chPegawai, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);
        $pegawaiResponse = curl_exec($chPegawai);
        curl_close($chPegawai);
        $pegawaiData = json_decode($pegawaiResponse, true);

        if (isset($pegawaiData['data']) && is_array($pegawaiData['data'])) {
            foreach ($pegawaiData['data'] as $pegawai) {
                if (!empty($pegawai['nip'])) {
                    $pegawaiNips[] = $pegawai['nip'];
                }
            }
        }

        // === Fetch NIP from Dokter ===
        $dokterNips = [];
        $dokterUrl = $this->api_url . '/dokter';
        $chDokter = curl_init($dokterUrl);
        curl_setopt($chDokter, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chDokter, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);
        $dokterResponse = curl_exec($chDokter);
        curl_close($chDokter);
        $dokterData = json_decode($dokterResponse, true);

        if (isset($dokterData['data']) && is_array($dokterData['data'])) {
            foreach ($dokterData['data'] as $dokter) {
                if (!empty($dokter['kd_dokter'])) {
                    $dokterNips[] = $dokter['kd_dokter'];
                }
            }
        }

        // === Combine & Remove Duplicates ===
        $nip_list = array_unique(array_merge($pegawaiNips, $dokterNips));

        return view('/admin/pemeriksaanranap/tambah_pemeriksaanranap', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'nip_list' => $nip_list
        ]);
    }

   public function submitTambahPemeriksaanRanap()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        // Extract all posted values
        $postDataPemeriksaanRanap = [
            'nomor_rawat'        => $this->request->getPost('nomor_rawat'),
            'tgl_perawatan'      => $this->request->getPost('tgl_perawatan'),
            'jam_rawat'          => $this->request->getPost('jam_rawat'),
            'nip'                => $this->request->getPost('nip'),
            'nama_petugas'       => $this->request->getPost('nama_petugas'),
            'profesi'            => $this->request->getPost('profesi'),
            'nama_pasien'        => $this->request->getPost('nama_pasien'),
            'gcs'                => $this->request->getPost('gcs'), // Field label: GCS (E,V,M)
            'tensi'              => $this->request->getPost('tensi'),
            'nadi'               => $this->request->getPost('nadi'),
            'respirasi'          => $this->request->getPost('respirasi'),
            'suhu_tubuh'         => $this->request->getPost('suhu_tubuh'),
            'spo2'               => $this->request->getPost('spo2'),
            'tinggi'             => $this->request->getPost('tinggi'),
            'berat'              => $this->request->getPost('berat'),
            'kesadaran'          => $this->request->getPost('kesadaran'),
            'alergi'             => $this->request->getPost('alergi'),
            'penilaian'          => $this->request->getPost('penilaian'),
            'rtl'                => $this->request->getPost('rtl'),
            'instruksi'          => $this->request->getPost('instruksi'),
            'evaluasi'           => $this->request->getPost('evaluasi'),
            'keluhan'          => $this->request->getPost('keluhan'),
            'pemeriksaan'           => $this->request->getPost('pemeriksaan'),
        ];

        $pemeriksaan_url = $this->api_url . '/pemeriksaanranap';
        $jsonPayload = json_encode($postDataPemeriksaanRanap);
    // dd($jsonPayload);
        $ch = curl_init($pemeriksaan_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 201) {
            return redirect()->to(base_url('pemeriksaanranap'))->with('success', 'Pemeriksaan berhasil ditambahkan.');
        } else {
            return $this->renderErrorView($http_status, $response);
        }
    }

    public function editPemeriksaanRanap($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Pemeriksaan Ranap';
        $pemeriksaan_url = $this->api_url . '/pemeriksaanranap/' . $nomorRawat;

        // 🔹 Fetch pemeriksaan data
        $ch_pemeriksaan = curl_init($pemeriksaan_url);
        curl_setopt($ch_pemeriksaan, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_pemeriksaan, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch_pemeriksaan);
        $http_status = curl_getinfo($ch_pemeriksaan, CURLINFO_HTTP_CODE);
        curl_close($ch_pemeriksaan);

        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }

        $pemeriksaan_data = json_decode($response, true);
        $pemeriksaan = $pemeriksaan_data['data'] ?? [];

        // 🔹 Normalize jam (ISO 8601 → H:i:s)
        if (!empty($pemeriksaan['jam'])) {
            try {
                $jam = new \DateTime($pemeriksaan['jam']);
                $pemeriksaan['jam_rawat'] = $jam->format('H:i:s');
            } catch (\Exception $e) {
                $pemeriksaan['jam_rawat'] = '';
            }
        } else {
            $pemeriksaan['jam_rawat'] = '';
        }

        // 🔹 Normalize tanggal (optional)
        if (!empty($pemeriksaan['tanggal'])) {
            try {
                $tanggal = new \DateTime($pemeriksaan['tanggal']);
                $pemeriksaan['tgl_perawatan'] = $tanggal->format('Y-m-d');
            } catch (\Exception $e) {
                $pemeriksaan['tgl_perawatan'] = '';
            }
        } else {
            $pemeriksaan['tgl_perawatan'] = '';
        }

        // 🔹 Fetch registrasi data
        $nama_pasien = '';
        $tgl_lahir = '';

        if (!empty($pemeriksaan['no_rawat'])) {
            $registrasi_url = $this->api_url . '/registrasi/by-no-rawat/' . $pemeriksaan['no_rawat'];
            $ch_reg = curl_init($registrasi_url);
            curl_setopt($ch_reg, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_reg, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response_reg = curl_exec($ch_reg);
            curl_close($ch_reg);

            $reg_data = json_decode($response_reg, true);
            $nama_pasien = $reg_data['data']['nama_pasien'] ?? '';
        }

        // 🔹 Fetch nama petugas
        $nama_petugas = '—';
        if (!empty($pemeriksaan['nip'])) {
            $pegawai_url = $this->api_url . '/pegawai/nip/' . urlencode($pemeriksaan['nip']);
            $ch_petugas = curl_init($pegawai_url);
            curl_setopt($ch_petugas, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_petugas, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response_petugas = curl_exec($ch_petugas);
            curl_close($ch_petugas);

            $pegawai_data = json_decode($response_petugas, true);
            if (
                isset($pegawai_data['status']) &&
                $pegawai_data['status'] === 'success' &&
                isset($pegawai_data['data']['Nama'])
            ) {
                $nama_petugas = $pegawai_data['data']['Nama'];
            }
        }

        // 🔹 Fetch tanggal lahir pasien (if available)
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
            curl_close($ch_pasien);

            $pasien_data = json_decode($response_pasien, true);
            $tgl_lahir = $pasien_data['data']['tgl_lahir'] ?? '';
        }

        // 🔹 Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemeriksaan Ranap', 'pemeriksaanranap');
        $this->addBreadcrumb('Edit', 'edit');

        $breadcrumbs = $this->getBreadcrumbs();
    // dd($pemeriksaan);
        // 🔹 Return view with normalized values
        return view('/admin/pemeriksaanranap/edit_pemeriksaanranap', [
            'pemeriksaan' => $pemeriksaan,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'nama_pasien' => $nama_pasien,
            'tgl_lahir' => $tgl_lahir,
            'nama_petugas' => $nama_petugas,
        ]);
    }

    public function submitEditPemeriksaanRanap($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $pemeriksaan_url = $this->api_url . '/pemeriksaanranap/' . $nomorRawat;

        // Get data from POST
        $jam = $this->request->getPost('jam');
        $nip = $this->request->getPost('nip');

        $tanggalInput = trim($this->request->getPost('tanggal'));
        $tanggal = $tanggalInput !== '' ? $tanggalInput : date('Y-m-d');

        $jamRawat = $this->request->getPost('jam_rawat') ?? '';
        if (strlen($jamRawat) === 5) {
            // Format is HH:MM, pad with :00
            $jamRawat .= ':00';
        }


        // Minimal required fields for update
        $postDataPemeriksaanRanap = [
            'no_rawat'        => $this->request->getPost('nomor_rawat'),
            'tgl_perawatan'      => $this->request->getPost('tgl_perawatan'),
            'jam_rawat'         => $jamRawat,
            'nip'                => $this->request->getPost('nip'),
            'nama_petugas'       => $this->request->getPost('nama_petugas'),
            'profesi'            => $this->request->getPost('profesi'),
            'nama_pasien'        => $this->request->getPost('nama_pasien'),
            'gcs'                => $this->request->getPost('gcs'), // Field label: GCS (E,V,M)
            'tensi'              => $this->request->getPost('tensi'),
            'nadi'               => $this->request->getPost('nadi'),
            'respirasi'          => $this->request->getPost('respirasi'),
            'suhu_tubuh'         => $this->request->getPost('suhu_tubuh'),
            'spo2'               => $this->request->getPost('spo2'),
            'tinggi'             => $this->request->getPost('tinggi'),
            'berat'              => $this->request->getPost('berat'),
            'kesadaran'          => $this->request->getPost('kesadaran'),
            'alergi'             => $this->request->getPost('alergi'),
            'penilaian'          => $this->request->getPost('penilaian'),
            'rtl'                => $this->request->getPost('rtl'),
            'instruksi'          => $this->request->getPost('instruksi'),
            'evaluasi'           => $this->request->getPost('evaluasi'),
                    'keluhan'          => $this->request->getPost('keluhan'),
            'pemeriksaan'           => $this->request->getPost('pemeriksaan'),
        ];

        $jsonPayload = json_encode($postDataPemeriksaanRanap);
    // dd($postDataPemeriksaanRanap);
        $ch = curl_init($pemeriksaan_url);
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
            return redirect()->to(base_url('pemeriksaanranap'));
        } else {
            // Optional: Decode error for better display
            $error = json_decode($response, true);
            return $error['data'] ?? $response;
        }
    }


    public function hapusPemeriksaanRanap($nomorRawat)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $delete_url = $this->api_url . '/pemeriksaanranap/' . $nomorRawat;
    
            $ch_delete = curl_init($delete_url);
            curl_setopt($ch_delete, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch_delete, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_delete, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json',
            ]);
    
            $response = curl_exec($ch_delete);
            $http_status = curl_getinfo($ch_delete, CURLINFO_HTTP_CODE);
            curl_close($ch_delete);
    
            if ($http_status === 200 || $http_status === 204) {
                return redirect()->to(base_url('pemeriksaanranap'))->with('success', 'Data pemeriksaan ranap berhasil dihapus.');
            } else {
                return $this->renderErrorView($http_status);
            }
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitFromRawatinapToPemeriksaanRanap($nomor_rawat)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->back()->with('error', 'Session token missing.');
        }

        $token = session()->get('jwt_token');

        // Step 1: Fetch rawat inap data
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

        // Step 2: Prepare prefill form data
        $prefill = [
            'nomor_rawat'        => $rawatinap['nomor_rawat'] ?? $nomor_rawat,
            'nip'                => '', // to be selected by user
            'nama_pasien'        => $rawatinap['nama_pasien'] ?? '',
            'tanggal'            => $rawatinap['tanggal_masuk'] ?? date('Y-m-d'),
            'jam'                => $rawatinap['jam_masuk'] ?? date('H:i:s'),
            'diagnosa_awal'      => $rawatinap['diagnosa_awal'] ?? '',
            'status_pemeriksaan' => 'Belum Diperiksa',
        ];

        // Step 3: Fetch all NIP values (pegawai + dokter)
        $nip_list = [];

        // 🔹 From pegawai
        $pegawai_url = $this->api_url . '/pegawai';
        $ch_pegawai = curl_init($pegawai_url);
        curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);
        $pegawai_response = curl_exec($ch_pegawai);
        curl_close($ch_pegawai);
        $pegawai_data = json_decode($pegawai_response, true);

        if (isset($pegawai_data['data']) && is_array($pegawai_data['data'])) {
            foreach ($pegawai_data['data'] as $pegawai) {
                if (!empty($pegawai['nip'])) {
                    $nip_list[] = $pegawai['nip'];
                }
            }
        }

        // 🔹 From dokter
        $dokter_url = $this->api_url . '/dokter';
        $ch_dokter = curl_init($dokter_url);
        curl_setopt($ch_dokter, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_dokter, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);
        $dokter_response = curl_exec($ch_dokter);
        curl_close($ch_dokter);
        $dokter_data = json_decode($dokter_response, true);

        if (isset($dokter_data['data']) && is_array($dokter_data['data'])) {
            foreach ($dokter_data['data'] as $dokter) {
                if (!empty($dokter['kd_dokter'])) {
                    $nip_list[] = $dokter['kd_dokter'];
                }
            }
        }

        $nip_list = array_unique($nip_list);
        sort($nip_list); // optional sorting

        // Step 4: Render view
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemeriksaan Ranap', 'pemeriksaanranap');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/pemeriksaanranap/tambah_pemeriksaanranap', [
            'title'     => 'Tambah Pemeriksaan Ranap',
            'breadcrumbs' => $this->getBreadcrumbs(),
            'prefill'   => $prefill,
            'nip_list'  => $nip_list,
        ]);
    }

    public function submitFromRegistrasiToPemeriksaanRanap($nomor_reg)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->back()->with('error', 'Session token missing.');
        }

        $token = session()->get('jwt_token');

        // Step 1: Fetch registrasi data
        $url_registrasi = $this->api_url . '/registrasi/' . $nomor_reg;
        $ch = curl_init($url_registrasi);
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

        // Step 2: Prepare prefill form data
        $prefill = [
            'nomor_rawat'        => $registrasi['nomor_rawat'] ?? '',
            'nip'                => '', // to be selected by user
            'nama_pasien'        => $registrasi['nama_pasien'] ?? '',
            'tanggal'            => $registrasi['tanggal_registrasi'] ?? date('Y-m-d'),
            'jam'                => $registrasi['jam_registrasi'] ?? date('H:i:s'),
            'diagnosa_awal'      => '', // not available from registrasi directly
            'status_pemeriksaan' => 'Belum Diperiksa',
        ];

        // Step 3: Fetch all NIP values (pegawai + dokter)
        $nip_list = [];

        // 🔹 From pegawai
        $pegawai_url = $this->api_url . '/pegawai';
        $ch_pegawai = curl_init($pegawai_url);
        curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);
        $pegawai_response = curl_exec($ch_pegawai);
        curl_close($ch_pegawai);
        $pegawai_data = json_decode($pegawai_response, true);

        if (isset($pegawai_data['data']) && is_array($pegawai_data['data'])) {
            foreach ($pegawai_data['data'] as $pegawai) {
                if (!empty($pegawai['nip'])) {
                    $nip_list[] = $pegawai['nip'];
                }
            }
        }

        // 🔹 From dokter
        $dokter_url = $this->api_url . '/dokter';
        $ch_dokter = curl_init($dokter_url);
        curl_setopt($ch_dokter, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_dokter, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);
        $dokter_response = curl_exec($ch_dokter);
        curl_close($ch_dokter);
        $dokter_data = json_decode($dokter_response, true);

        if (isset($dokter_data['data']) && is_array($dokter_data['data'])) {
            foreach ($dokter_data['data'] as $dokter) {
                if (!empty($dokter['kd_dokter'])) {
                    $nip_list[] = $dokter['kd_dokter'];
                }
            }
        }

        $nip_list = array_unique($nip_list);
        sort($nip_list);

        // Step 4: Render view
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemeriksaan Ranap', 'pemeriksaanranap');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/pemeriksaanranap/tambah_pemeriksaanranap', [
            'title'      => 'Tambah Pemeriksaan Ranap',
            'breadcrumbs'=> $this->getBreadcrumbs(),
            'prefill'    => $prefill,
            'nip_list'   => $nip_list,
        ]);
    }
}
