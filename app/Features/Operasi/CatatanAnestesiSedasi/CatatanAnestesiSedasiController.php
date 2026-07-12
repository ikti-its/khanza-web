<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanAnestesiSedasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class CatatanAnestesiSedasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanAnestesiSedasiModel(),
            [
                ['Operasi',                 'operasi'],
                ['Catatan Anestesi Sedasi', 'catatan_anestesi_sedasi'],
            ],
            'Catatan Anestesi Sedasi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_catatan_anestesi',  'ID Catatan Anestesi'],
                [TABLE_ONLY, REQUIRED, I::INDEX,  'id_jadwal',            'Jadwal Operasi'],
                [HIDE,       REQUIRED, I::INDEX,  'id_tindakan',          'Tindakan'],
                [TABLE_ONLY, REQUIRED, I::INDEX,  'id_dokter_anestesi',   'Dokter Anestesi'],
                [HIDE,       REQUIRED, I::INDEX,  'id_dokter_bedah',      'Dokter Bedah'],
                [HIDE,       REQUIRED, I::INDEX,  'id_perawat_anestesi',  'Perawat Anestesi'],
                [HIDE,       REQUIRED, I::INDEX,  'id_perawat_bedah',     'Perawat Bedah'],
                [TABLE_ONLY, REQUIRED, I::DTIME,  'waktu_catatan',        'Waktu Catatan'],
                [HIDE,       REQUIRED, I::TEXT,   'diagnosa_pra_bedah',   'Diagnosa Pra Bedah'],
                [HIDE,       REQUIRED, I::TEXT,   'diagnosa_paska_bedah', 'Diagnosa Paska Bedah'],
                [HIDE,       REQUIRED, I::TIME,   'jam_pengkajian',       'Jam Pengkajian'],
                [HIDE,       REQUIRED, I::INDEX,  'id_kesadaran',         'Kesadaran'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'sistolik',             'Sistolik'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'diastolik',            'Diastolik'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'nadi',                 'Nadi'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'respiratory_rate',     'Respiratory Rate'],
                [FORM_ONLY,  REQUIRED, I::TEMP,   'suhu',                 'Suhu'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'saturasi_o2',          'Saturasi O2'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'tinggi_badan_cm',      'Tinggi Badan (cm)'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'berat_badan_kg',       'Berat Badan (kg)'],
                [FORM_ONLY,  REQUIRED, I::INDEX,  'id_golongan_darah',    'Golongan Darah'],
                [FORM_ONLY,  REQUIRED, I::INDEX,  'id_rhesus',            'Rhesus'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'hemoglobin',           'Hemoglobin'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'hematokrit',           'Hematokrit'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'leukosit',             'Leukosit'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'trombosit',            'Trombosit'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'bleeding_time_bt',     'Bleeding Time (BT)'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'clotting_time_ct',     'Clotting Time (CT)'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'gula_darah_sewaktu',   'Gula Darah Sewaktu'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'klinis_lain_lain',     'Klinis Lain-lain'],
                [FORM_ONLY,  REQUIRED, I::INDEX,  'id_asa',               'ASA'],
                [FORM_ONLY,  REQUIRED, I::BOOL,   'is_alergi',            'Alergi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'ket_alergi',           'Keterangan Alergi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'penyulit_pra',         'Penyulit Pra'],
                [FORM_ONLY,  REQUIRED, I::BOOL,   'is_lanjut_tindakan',   'Lanjut Tindakan'],
                [FORM_ONLY,  REQUIRED, I::INDEX,  'id_jenis_sedasi',      'Jenis Sedasi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'ket_sedasi',           'Keterangan Sedasi'],
                [FORM_ONLY,  REQUIRED, I::BOOL,   'is_epidural',          'Epidural'],
                [FORM_ONLY,  REQUIRED, I::BOOL,   'is_spinal',            'Spinal'],
                [FORM_ONLY,  REQUIRED, I::BOOL,   'is_anestesi_umum',     'Anestesi Umum'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,   'ket_anestesi_umum',    'Keterangan Anestesi Umum'],
                [FORM_ONLY,  REQUIRED, I::BOOL,   'is_blok_perifer',      'Blok Perifer'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,   'ket_blok_perifer',     'Keterangan Blok Perifer'],
                [FORM_ONLY,  REQUIRED, I::BOOL,   'is_batal_tindakan',    'Batal Tindakan'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,   'alasan_batal',         'Alasan Batal'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    #[\Override]
    protected function home(): RedirectResponse
    {
        $idJadwal = (int) ($this->request->getPost('id_jadwal') ?? 0);
        if ($idJadwal > 0) {
            return redirect()->to('/operasi/lembar-operasi/data?id_jadwal=' . $idJadwal);
        }
        return redirect()->to($this->get_uri_path() . '/data');
    }

    private function fetchJadwal(int $idJadwal): array
    {
        return $this->model
            ->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'po.nomor_reg',
                'po.id_tindakan',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
            ])
            ->join('operasi.permintaan_operasi po', 'po.id_permintaan = j.id_permintaan', 'left')
            ->join('registrasi.registrasi r', 'r.nomor_reg      = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien      = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang      = p.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan', 'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchOptions(): array
    {
        $db = $this->model->db;
        return [
            'kesadaran'      => $db
                ->table('operasi.ref_kesadaran')
                ->select('id_kesadaran, nama_kesadaran')
                ->get()
                ->getResultArray(),
            'golongan_darah' => $db
                ->table('darah.golongan_darah')
                ->select('id_golongan_darah, nama_golongan_darah')
                ->get()
                ->getResultArray(),
            'rhesus'         => $db->table('darah.rhesus')->select('id_rhesus, kode_rhesus')->get()->getResultArray(),
            'asa'            => $db
                ->table('operasi.ref_angka_asa')
                ->select('id_asa, nama_asa')
                ->get()
                ->getResultArray(),
            'jenis_sedasi'   => $db
                ->table('operasi.ref_jenis_sedasi')
                ->select('id_jenis_sedasi, nama_sedasi')
                ->get()
                ->getResultArray(),
            'alat'           => $db
                ->table('operasi.ref_alat_anestesi')
                ->select('id_alat, nama_alat')
                ->get()
                ->getResultArray(),
            'monitoring'     => $db
                ->table('operasi.ref_monitoring_anestesi')
                ->select('id_monitoring, nama_monitoring')
                ->get()
                ->getResultArray(),
        ];
    }

    private function fetchAlat(int $idCatatan): array
    {
        $rows = $this->model
            ->db
            ->table('operasi.catatan_anestesi_sedasi_alat')
            ->select('id_alat, is_digunakan, keterangan')
            ->where('id_catatan_anestesi', $idCatatan)
            ->get()
            ->getResultArray();

        return array_map(static function (array $row): array {
            $isTrue              = in_array($row['is_digunakan'], [true, 1, 't'], true);
            $row['is_digunakan'] = $isTrue ? '1' : '0';
            return $row;
        }, $rows);
    }

    private function fetchMonitoring(int $idCatatan): array
    {
        $rows = $this->model
            ->db
            ->table('operasi.catatan_anestesi_sedasi_monitoring')
            ->select('id_monitoring, is_digunakan, keterangan')
            ->where('id_catatan_anestesi', $idCatatan)
            ->get()
            ->getResultArray();

        return array_map(static function (array $row): array {
            $isTrue              = in_array($row['is_digunakan'], [true, 1, 't'], true);
            $row['is_digunakan'] = $isTrue ? '1' : '0';
            return $row;
        }, $rows);
    }

    // Helper dokter dan petugas
    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue): string
    {
        $row = $this->model
            ->db
            ->table("role.{$tabel} t")
            ->select('o.nama')
            ->join('person.orang o', 'o.id_orang = t.id_orang', 'left')
            ->where("t.{$idKolom}", $idValue)
            ->get()
            ->getRowArray();
        return $row['nama'] ?? '';
    }

    private function fetchTindakanName(int $idTindakan): string
    {
        $row = $this->model
            ->db
            ->table('operasi.ref_tindakan_operasi')
            ->select('nama_tindakan')
            ->where('id_tindakan', $idTindakan)
            ->get()
            ->getRowArray();
        return $row['nama_tindakan'] ?? '';
    }

    // Ekstraksi mapping array yang masif agar CRUD lebih mudah dibaca
    private function buildHeaderData(array $rawPost): array
    {
        return [
            'id_jadwal'            => (int) ($rawPost['id_jadwal'] ?? 0) ?: null,
            'id_tindakan'          => (int) ($rawPost['id_tindakan'] ?? 0) ?: null,
            'id_dokter_anestesi'   => (int) ($rawPost['id_dokter_anestesi'] ?? 0) ?: null,
            'id_dokter_bedah'      => (int) ($rawPost['id_dokter_bedah'] ?? 0) ?: null,
            'id_perawat_anestesi'  => (int) ($rawPost['id_perawat_anestesi'] ?? 0) ?: null,
            'id_perawat_bedah'     => (int) ($rawPost['id_perawat_bedah'] ?? 0) ?: null,
            'waktu_catatan'        => $rawPost['waktu_catatan'] ?? null,
            'diagnosa_pra_bedah'   => $rawPost['diagnosa_pra_bedah'] ?? null,
            'diagnosa_paska_bedah' => $rawPost['diagnosa_paska_bedah'] ?? null,
            'jam_pengkajian'       => $rawPost['jam_pengkajian'] ?? null,
            'id_kesadaran'         => (int) ($rawPost['id_kesadaran'] ?? 0) ?: null,
            'sistolik'             => $rawPost['sistolik'] ?? null,
            'diastolik'            => $rawPost['diastolik'] ?? null,
            'nadi'                 => $rawPost['nadi'] ?? null,
            'respiratory_rate'     => $rawPost['respiratory_rate'] ?? null,
            'suhu'                 => $rawPost['suhu'] ?? null,
            'saturasi_o2'          => $rawPost['saturasi_o2'] ?? null,
            'tinggi_badan_cm'      => $rawPost['tinggi_badan_cm'] ?? null,
            'berat_badan_kg'       => $rawPost['berat_badan_kg'] ?? null,
            'id_golongan_darah'    => (int) ($rawPost['id_golongan_darah'] ?? 0) ?: null,
            'id_rhesus'            => (int) ($rawPost['id_rhesus'] ?? 0) ?: null,
            'hemoglobin'           => $rawPost['hemoglobin'] ?? null,
            'hematokrit'           => $rawPost['hematokrit'] ?? null,
            'leukosit'             => $rawPost['leukosit'] ?? null,
            'trombosit'            => $rawPost['trombosit'] ?? null,
            'bleeding_time_bt'     => $rawPost['bleeding_time_bt'] ?? null,
            'clotting_time_ct'     => $rawPost['clotting_time_ct'] ?? null,
            'gula_darah_sewaktu'   => $rawPost['gula_darah_sewaktu'] ?? null,
            'klinis_lain_lain'     => $rawPost['klinis_lain_lain'] ?? null,
            'id_asa'               => (int) ($rawPost['id_asa'] ?? 0) ?: null,
            'is_alergi'            => $rawPost['is_alergi'] ?? null,
            'ket_alergi'           => ($rawPost['ket_alergi'] ?? '') !== '' ? $rawPost['ket_alergi'] : null,
            'penyulit_pra'         => ($rawPost['penyulit_pra'] ?? '') !== '' ? $rawPost['penyulit_pra'] : null,
            'is_lanjut_tindakan'   => $rawPost['is_lanjut_tindakan'] ?? null,
            'id_jenis_sedasi'      => (int) ($rawPost['id_jenis_sedasi'] ?? 0) ?: null,
            'ket_sedasi'           => ($rawPost['ket_sedasi'] ?? '') !== '' ? $rawPost['ket_sedasi'] : null,
            'is_epidural'          => $rawPost['is_epidural'] ?? null,
            'is_spinal'            => $rawPost['is_spinal'] ?? null,
            'is_anestesi_umum'     => $rawPost['is_anestesi_umum'] ?? null,
            'ket_anestesi_umum'    => $rawPost['ket_anestesi_umum'] ?? '',
            'is_blok_perifer'      => $rawPost['is_blok_perifer'] ?? null,
            'ket_blok_perifer'     => $rawPost['ket_blok_perifer'] ?? '',
            'is_batal_tindakan'    => $rawPost['is_batal_tindakan'] ?? null,
            'alasan_batal'         => $rawPost['alasan_batal'] ?? '',
        ];
    }

    // Centralisasi Batch Insert untuk alat dan monitoring
    private function insertAlatAndMonitoring(int $idCatatan, array $alatList, array $monitoringList): void
    {
        // 1. Eksekusi Alat
        $batchAlat = [];
        foreach ($alatList as $row) {
            $idAlat      = (int) ($row['id_alat'] ?? 0);
            $isDigunakan = ($row['is_digunakan'] ?? '') !== '' ? $row['is_digunakan'] : null;
            $keterangan  = ($row['keterangan'] ?? '') !== '' ? $row['keterangan'] : null;

            if ($idAlat === 0 || $isDigunakan === null && $keterangan === null) {
                continue;
            }

            $batchAlat[] = [
                'id_catatan_anestesi' => $idCatatan,
                'id_alat'             => $idAlat,
                'is_digunakan'        => $isDigunakan ?? '1',
                'keterangan'          => $keterangan,
            ];
        }
        if (!empty($batchAlat)) {
            (new \App\Features\Operasi\CatatanAnestesiSedasiAlat\CatatanAnestesiSedasiAlatModel())->insertBatch(
                $batchAlat,
            );
        }

        // 2. Eksekusi Monitoring
        $batchMonitoring = [];
        foreach ($monitoringList as $row) {
            $idMonitoring = (int) ($row['id_monitoring'] ?? 0);
            $isDigunakan  = ($row['is_digunakan'] ?? '') !== '' ? $row['is_digunakan'] : null;
            $keterangan   = ($row['keterangan'] ?? '') !== '' ? $row['keterangan'] : null;

            if ($idMonitoring === 0 || $isDigunakan === null && $keterangan === null) {
                continue;
            }

            $batchMonitoring[] = [
                'id_catatan_anestesi' => $idCatatan,
                'id_monitoring'       => $idMonitoring,
                'is_digunakan'        => $isDigunakan ?? '1',
                'keterangan'          => $keterangan,
            ];
        }
        if (!empty($batchMonitoring)) {
            (new \App\Features\Operasi\CatatanAnestesiSedasiMonitoring\CatatanAnestesiSedasiMonitoringModel())->insertBatch(
                $batchMonitoring,
            );
        }
    }

    private function buildViewData(array $jadwal, array $record, string $formAction, null|int $idCatatan = null): array
    {
        return [
            'judul'       => ($formAction === '/submittambah/' ? 'Tambah ' : 'Ubah ') . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [[
                'title' => $formAction === '/submittambah/' ? 'Tambah' : 'Ubah',
                'icon'  => '',
            ]]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => $formAction,
            'baris'       => $record,
            'jadwal'      => $jadwal,
            'options'     => $this->fetchOptions(),
            'alat'        => $idCatatan !== null ? $this->fetchAlat($idCatatan) : [],
            'monitoring'  => $idCatatan !== null ? $this->fetchMonitoring($idCatatan) : [],
        ];
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    public function create_page(): string
    {
        $idJadwal = (int) ($this->request->getGet('id_jadwal') ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        return view('admin/operasi/tambah_catatan_anestesi_sedasi', $this->buildViewData(
            $jadwal,
            [
                'id_jadwal'     => $idJadwal,
                'id_tindakan'   => $jadwal['id_tindakan'] ?? '',
                'nama_tindakan' => $jadwal['nama_tindakan'] ?? '',
            ],
            '/submittambah/',
        ));
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        $record   = $this->model->find_one($id);
        $idJadwal = (int) ($record['id_jadwal'] ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        if (($idDa = (int) ($record['id_dokter_anestesi'] ?? 0)) > 0) {
            $record['nama_dokter_anestesi'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDa);
        }
        if (($idDb = (int) ($record['id_dokter_bedah'] ?? 0)) > 0) {
            $record['nama_dokter_bedah'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDb);
        }
        if (($idPa = (int) ($record['id_perawat_anestesi'] ?? 0)) > 0) {
            $record['nama_perawat_anestesi'] = $this->fetchNamaRole('petugas', 'id_petugas', $idPa);
        }
        if (($idPb = (int) ($record['id_perawat_bedah'] ?? 0)) > 0) {
            $record['nama_perawat_bedah'] = $this->fetchNamaRole('petugas', 'id_petugas', $idPb);
        }
        if (($idT = (int) ($record['id_tindakan'] ?? 0)) > 0) {
            $record['nama_tindakan'] = $this->fetchTindakanName($idT);
        }

        foreach ([
            'is_alergi',
            'is_lanjut_tindakan',
            'is_epidural',
            'is_spinal',
            'is_anestesi_umum',
            'is_blok_perifer',
            'is_batal_tindakan',
        ] as $field) {
            if (!isset($record[$field])) {
                continue;
            }

            $isTrue         = in_array($record[$field], [true, 1, 't'], true);
            $record[$field] = $isTrue ? '1' : '0';
        }

        return view('admin/operasi/tambah_catatan_anestesi_sedasi', $this->buildViewData(
            $jadwal,
            $record,
            '/submitedit/' . $id,
            (int) $id,
        ));
    }

    // -------------------------------------------------------------------------
    // Create and Update
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost        = $this->request->getPost();
        $dataHeader     = $this->buildHeaderData($rawPost);
        $alatList       = $rawPost['alat'] ?? [];
        $monitoringList = $rawPost['monitoring'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->insert($dataHeader);

            $this->insertAlatAndMonitoring((int) $this->model->getInsertID(), $alatList, $monitoringList);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan catatan anestesi sedasi.');
            }

            return $this->home();
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) {
            return $this->home();
        }

        $rawPost        = $this->request->getPost();
        $dataHeader     = $this->buildHeaderData($rawPost);
        $alatList       = $rawPost['alat'] ?? [];
        $monitoringList = $rawPost['monitoring'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->update($id, $dataHeader);

            (new \App\Features\Operasi\CatatanAnestesiSedasiAlat\CatatanAnestesiSedasiAlatModel())
                ->where('id_catatan_anestesi', $id)
                ->delete();
            (new \App\Features\Operasi\CatatanAnestesiSedasiMonitoring\CatatanAnestesiSedasiMonitoringModel())
                ->where('id_catatan_anestesi', $id)
                ->delete();

            $this->insertAlatAndMonitoring((int) $id, $alatList, $monitoringList);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui catatan anestesi sedasi.');
            }

            return $this->home();
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }
}
