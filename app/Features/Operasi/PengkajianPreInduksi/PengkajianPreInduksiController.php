<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreInduksi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PengkajianPreInduksiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengkajianPreInduksiModel(),
            [
                ['Operasi',                'operasi'],
                ['Pengkajian Pre Induksi', 'pengkajian_pre_induksi'],
            ],
            'Pengkajian Pre Induksi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_pengkajian',             'ID Pengkajian'],
                [SHOW,       REQUIRED, I::INDEX,  'id_jadwal',                 'Jadwal Operasi'],
                [TABLE_ONLY, REQUIRED, I::INDEX,  'id_dokter_anestesi',        'Dokter Anestesi'],
                [SHOW,       REQUIRED, I::DTIME,  'waktu_pengkajian',          'Waktu Pengkajian'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'sistolik',                  'Sistolik'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'diastolik',                 'Diastolik'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'nadi',                      'Nadi'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'respiratory_rate',          'Respiratory Rate'],
                [FORM_ONLY,  REQUIRED, I::TEMP,   'suhu',                      'Suhu'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'elektrokardiogram',         'Elektrokardiogram'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'vital_lain_lain',           'Vital Lain-lain'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_sesuai_asesmen',         'Sesuai Asesmen'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'perencanaan',               'Perencanaan'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'infus_perifer',             'Infus Perifer'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'kateter_vena_sentral_cvc',  'Kateter Vena Sentral (CVC)'],
                [FORM_ONLY,  REQUIRED, I::INDEX,  'id_posisi',                 'Posisi'],
                [FORM_ONLY,  REQUIRED, I::INDEX,  'id_premedikasi',            'Premedikasi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'ket_premedikasi',           'Keterangan Premedikasi'],
                [FORM_ONLY,  REQUIRED, I::INDEX,  'id_induksi',                'Induksi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'ket_induksi',               'Keterangan Induksi'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_intubasi_sesudah_tidur', 'Intubasi Sesudah Tidur'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_intubasi_oral',          'Intubasi Oral'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_intubasi_tracheostomi',  'Intubasi Tracheostomi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'intubasi_keterangan',       'Keterangan Intubasi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'sulit_ventilasi',           'Sulit Ventilasi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'sulit_intubasi',            'Sulit Intubasi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'ventilasi_keterangan',      'Keterangan Ventilasi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'teknik_regional_jenis',     'Teknik Regional Jenis'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'teknik_regional_lokasi',    'Teknik Regional Lokasi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'teknik_regional_jarum',     'Teknik Regional Jarum'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_kateter',                'Kateter'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'kateter_fiksasi_cm',        'Kateter Fiksasi (cm)'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'obat_obatan',               'Obat-obatan'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'komplikasi',                'Komplikasi'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'hasil',                     'Hasil'],
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
                'j.id_dokter_anestesi',
                'po.nomor_reg',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
                'oa.nama AS nama_dokter_anestesi',
            ])
            ->join('operasi.permintaan_operasi po', 'po.id_permintaan = j.id_permintaan', 'left')
            ->join('registrasi.registrasi r', 'r.nomor_reg      = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien      = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang      = p.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan', 'left')
            ->join('role.dokter da', 'da.id_dokter     = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa', 'oa.id_orang      = da.id_orang', 'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchOptions(): array
    {
        $db = $this->model->db;
        return [
            'jenis_airway' => $db
                ->table('operasi.ref_jenis_airway')
                ->select('id_jenis, nama_jenis')
                ->get()
                ->getResultArray(),
            'posisi'       => $db
                ->table('operasi.ref_posisi_pasien')
                ->select('id_posisi, nama_posisi')
                ->get()
                ->getResultArray(),
            'premedikasi'  => $db
                ->table('operasi.ref_premedikasi')
                ->select('id_premedikasi, nama_premedikasi')
                ->get()
                ->getResultArray(),
            'induksi'      => $db
                ->table('operasi.ref_induksi')
                ->select('id_induksi, nama_induksi')
                ->get()
                ->getResultArray(),
        ];
    }

    private function fetchAirway(int $idPengkajian): array
    {
        return $this->model
            ->db
            ->table('operasi.pengkajian_pre_induksi_airway')
            ->select('id_jenis_airway, nomor, jenis, fiksasi_cm, keterangan')
            ->where('id_pengkajian', $idPengkajian)
            ->get()
            ->getResultArray();
    }

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

    // Data Mapper for header
    private function buildHeaderData(array $rawPost): array
    {
        return [
            'id_jadwal'                 => (int) ($rawPost['id_jadwal'] ?? 0),
            'id_dokter_anestesi'        => (int) ($rawPost['id_dokter_anestesi'] ?? 0),
            'waktu_pengkajian'          => $rawPost['waktu_pengkajian'] ?? null,
            'sistolik'                  => $rawPost['sistolik'] ?? null,
            'diastolik'                 => $rawPost['diastolik'] ?? null,
            'nadi'                      => $rawPost['nadi'] ?? null,
            'respiratory_rate'          => $rawPost['respiratory_rate'] ?? null,
            'suhu'                      => $rawPost['suhu'] ?? null,
            'elektrokardiogram'         => $rawPost['elektrokardiogram'] ?? null,
            'vital_lain_lain'           => $rawPost['vital_lain_lain'] ?? null,
            'is_sesuai_asesmen'         => $rawPost['is_sesuai_asesmen'] ?? null,
            'perencanaan'               => $rawPost['perencanaan'] ?? null,
            'infus_perifer'             => $rawPost['infus_perifer'] ?? null,
            'kateter_vena_sentral_cvc'  => $rawPost['kateter_vena_sentral_cvc'] ?? null,
            'id_posisi'                 => (int) ($rawPost['id_posisi'] ?? 0),
            'id_premedikasi'            => (int) ($rawPost['id_premedikasi'] ?? 0),
            'ket_premedikasi'           => $rawPost['ket_premedikasi'] ?? null,
            'id_induksi'                => (int) ($rawPost['id_induksi'] ?? 0),
            'ket_induksi'               => $rawPost['ket_induksi'] ?? null,
            'is_intubasi_sesudah_tidur' => $rawPost['is_intubasi_sesudah_tidur'] ?? null,
            'is_intubasi_oral'          => $rawPost['is_intubasi_oral'] ?? null,
            'is_intubasi_tracheostomi'  => $rawPost['is_intubasi_tracheostomi'] ?? null,
            'intubasi_keterangan'       => $rawPost['intubasi_keterangan'] ?? null,
            'sulit_ventilasi'           => $rawPost['sulit_ventilasi'] ?? null,
            'sulit_intubasi'            => $rawPost['sulit_intubasi'] ?? null,
            'ventilasi_keterangan'      => $rawPost['ventilasi_keterangan'] ?? null,
            'teknik_regional_jenis'     => $rawPost['teknik_regional_jenis'] ?? null,
            'teknik_regional_lokasi'    => $rawPost['teknik_regional_lokasi'] ?? null,
            'teknik_regional_jarum'     => $rawPost['teknik_regional_jarum'] ?? null,
            'is_kateter'                => $rawPost['is_kateter'] ?? null,
            'kateter_fiksasi_cm'        => $rawPost['kateter_fiksasi_cm'] ?? null,
            'obat_obatan'               => $rawPost['obat_obatan'] ?? null,
            'komplikasi'                => $rawPost['komplikasi'] ?? null,
            'hasil'                     => $rawPost['hasil'] ?? null,
        ];
    }

    // Batch Insert for airway
    private function insertAirwayList(int $idPengkajian, array $airwayList): void
    {
        $batchAirway = [];
        foreach ($airwayList as $row) {
            $idJenis = (int) ($row['id_jenis_airway'] ?? 0);
            if ($idJenis === 0) {
                continue;
            }

            $batchAirway[] = [
                'id_pengkajian'   => $idPengkajian,
                'id_jenis_airway' => $idJenis,
                'nomor'           => ($row['nomor'] ?? '') !== '' ? $row['nomor'] : null,
                'jenis'           => ($row['jenis'] ?? '') !== '' ? $row['jenis'] : null,
                'fiksasi_cm'      => ($row['fiksasi_cm'] ?? '') !== '' ? $row['fiksasi_cm'] : null,
                'keterangan'      => ($row['keterangan'] ?? '') !== '' ? $row['keterangan'] : null,
            ];
        }

        if (!empty($batchAirway)) {
            (new \App\Features\Operasi\PengkajianPreInduksiAirway\PengkajianPreInduksiAirwayModel())->insertBatch(
                $batchAirway,
            );
        }
    }

    private function buildViewData(array $jadwal, array $record, string $formAction, array $airway = []): array
    {
        $isCreate = $formAction === '/submittambah/';
        return [
            'judul'       => ($isCreate ? 'Tambah ' : 'Ubah ') . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [[
                'title' => $isCreate ? 'Tambah' : 'Ubah',
                'icon'  => '',
            ]]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => $formAction,
            'baris'       => $record,
            'jadwal'      => $jadwal,
            'options'     => $this->fetchOptions(),
            'airway'      => $airway,
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

        return view('admin/operasi/tambah_pengkajian_pre_induksi', $this->buildViewData(
            $jadwal,
            [
                'id_jadwal'            => $idJadwal,
                'id_dokter_anestesi'   => $jadwal['id_dokter_anestesi'] ?? '',
                'nama_dokter_anestesi' => $jadwal['nama_dokter_anestesi'] ?? '',
                'nama_tindakan'        => $jadwal['nama_tindakan'] ?? '',
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
        $record['nama_tindakan'] = $jadwal['nama_tindakan'] ?? '';

        return view('admin/operasi/tambah_pengkajian_pre_induksi', $this->buildViewData(
            $jadwal,
            $record,
            '/submitedit/' . $id,
            $this->fetchAirway((int) $id),
        ));
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost    = $this->request->getPost();
        $dataHeader = $this->buildHeaderData($rawPost);
        $airwayList = $rawPost['airway'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->insert($dataHeader);
            $idPengkajian = $this->model->getInsertID();

            $this->insertAirwayList((int) $idPengkajian, $airwayList);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Transaksi gagal saat menyimpan pengkajian pre induksi.');
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

        $rawPost    = $this->request->getPost();
        $dataHeader = $this->buildHeaderData($rawPost);
        $airwayList = $rawPost['airway'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->update($id, $dataHeader);

            (new \App\Features\Operasi\PengkajianPreInduksiAirway\PengkajianPreInduksiAirwayModel())
                ->where('id_pengkajian', $id)
                ->delete();

            $this->insertAirwayList((int) $id, $airwayList);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Transaksi gagal saat memperbarui pengkajian pre induksi.');
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
    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) {
            return $this->home();
        }

        $this->model->db->transStart();

        try {
            (new \App\Features\Operasi\PengkajianPreInduksiAirway\PengkajianPreInduksiAirwayModel())
                ->where('id_pengkajian', $id)
                ->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus pengkajian pre induksi.');
            }

            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil dihapus.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return $this->home();
    }
}
