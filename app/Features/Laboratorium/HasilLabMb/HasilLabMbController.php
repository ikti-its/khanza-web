<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabMb;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class HasilLabMbController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabMbModel(),
            [
                ['Laboratorium', 'laboratorium'],
                ['Hasil Lab MB', 'hasil_lab_mb'],
            ],
            'Hasil Lab MB',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::PRINT,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_hasil_mb',           'ID Hasil MB'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_lab',     'No. Permintaan Lab'],
                [HIDE, REQUIRED, I::INDEX, 'id_permintaan_mb_item', 'ID Permintaan MB Item'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_pj',          'Kode Dokter PJ'],
                [SHOW, REQUIRED, I::TEXT,  'id_petugas_lab',        'Petugas Lab'],
                [SHOW, REQUIRED, I::DTIME, 'tgl_jam_hasil',         'Tanggal dan Jam Hasil'],
            ],
        );
    }

    // ──────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────
 
    private function getKonfig(): array
    {
        return array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn($f) => !in_array($f[2], [
                'id_hasil_mb',
                'id_permintaan_lab',
                'id_permintaan_mb_item',
                'id_dokter_pj',
                'id_petugas_lab',
            ], true)
        ));
    }
 
    private function fetchHeaderPermintaan(int $idPermintaanLab): array
    {
        return $this->model->db
            ->table('laboratorium.permintaan_lab_header plh')
            ->select([
                'plh.id_permintaan',
                'plh.no_permintaan',
                'plh.nomor_reg',
                'o.nama  AS nama_pasien',
                'od.nama AS nama_dokter_perujuk',
                'r.id_dokter AS id_dokter_perujuk',
            ])
            ->join('registrasi.registrasi r',  'r.nomor_reg = plh.nomor_reg')
            ->join('role.pasien p',             'p.id_pasien = r.id_pasien',  'left')
            ->join('person.orang o',            'o.id_orang  = p.id_orang',   'left')
            ->join('role.dokter d',             'd.id_dokter = r.id_dokter',  'left')
            ->join('person.orang od',           'od.id_orang = d.id_orang',   'left')
            ->where('plh.id_permintaan', $idPermintaanLab)
            ->get()->getRowArray() ?? [];
    }
 
    private function fetchNamaDokterPj(int $idDokterPj): ?string
    {
        $row = $this->model->db
            ->table('role.dokter d')
            ->select(['o.nama AS nama_dokter_pj'])
            ->join('person.orang o', 'o.id_orang = d.id_orang')
            ->where('d.id_dokter', $idDokterPj)
            ->get()->getRowArray();
 
        return $row['nama_dokter_pj'] ?? null;
    }
 
    private function fetchNamaPetugas(int $idPetugas): ?string
    {
        $row = $this->model->db
            ->table('role.petugas p')
            ->select(['o.nama AS nama_petugas'])
            ->join('person.orang o', 'o.id_orang = p.id_orang')
            ->where('p.id_petugas', $idPetugas)
            ->get()->getRowArray();
 
        return $row['nama_petugas'] ?? null;
    }
 
    private function fetchItemTerpilih(int $idPermintaanLab): array
    {
        $hasilRows = $this->model->db
            ->table('laboratorium.hasil_lab_mb h')
            ->select([
                'h.id_hasil_mb',
                'h.id_permintaan_mb_item',
                'ri.kode_periksa',
                'ri.nama_item',
            ])
            ->join('laboratorium.permintaan_lab_mb_item mi',   'mi.id_permintaan_mb_item = h.id_permintaan_mb_item')
            ->join('laboratorium.ref_item_pemeriksaan_lab ri', 'ri.id_item_lab = mi.id_item_pemeriksaan')
            ->where('h.id_permintaan_lab', $idPermintaanLab)
            ->orderBy('h.id_permintaan_mb_item', 'ASC')
            ->get()->getResultArray();

        if (empty($hasilRows)) return [];
 
        $idHasilMbList = array_column($hasilRows, 'id_hasil_mb');

        $parameters = $this->model->db
            ->table('laboratorium.hasil_lab_mb_parameter hp')
            ->select([
                'hp.id_hasil_mb', 'hp.id_hasil_mb_parameter', 'hp.id_parameter',
                'rp.nama_parameter', 'rp.satuan', 'rp.nilai_rujukan',
                'hp.nilai_hasil', 'hp.keterangan_hasil',
            ])
            ->join('laboratorium.ref_parameter_pemeriksaan_lab rp', 'rp.id_parameter = hp.id_parameter')
            ->whereIn('hp.id_hasil_mb', $idHasilMbList)
            ->orderBy('rp.id_parameter', 'ASC')
            ->get()->getResultArray();

        $groupedParams = [];
        foreach ($parameters as $param) {
            $groupedParams[$param['id_hasil_mb']][] = $param;
        }

        foreach ($hasilRows as &$row) {
            $row['parameter'] = $groupedParams[$row['id_hasil_mb']] ?? [];
        }

        return $hasilRows;
    }
 
    private function validateHasilList(array $hasilList): ?string
    {
        foreach ($hasilList as $item) {
            foreach ($item['parameter'] ?? [] as $param) {
                if ((int) ($param['id_parameter'] ?? 0) <= 0) continue;
                if (trim($param['nilai_hasil'] ?? '') === '') {
                    return 'Semua nilai hasil parameter wajib diisi sebelum disimpan.';
                }
            }
        }
        return null;
    }

    private function validateInput(
        ?int $idPermintaanLab,
        ?int $idDokterPj,
        ?int $idPetugasLab,
        array $hasilList,
        string $emptyListMsg,
    ): ?string {
        if (!$idPermintaanLab) return 'Permintaan laboratorium wajib dipilih.';
        if (!$idDokterPj)      return 'Dokter PJ wajib dipilih.';
        if (!$idPetugasLab)    return 'Petugas lab wajib dipilih.';
        if (empty($hasilList)) return $emptyListMsg;
        return $this->validateHasilList($hasilList);
    }

    private function insertHasilMbItems(
        array $hasilList,
        ?int $idPermintaanLab,
        ?int $idDokterPj,
        ?int $idPetugasLab,
        string $tglJamHasil,
        \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel $modelParam,
    ): void {
        $paramBatch =[];

        foreach ($hasilList as $item) {
            $this->model->insert([
                'id_permintaan_lab'     => $idPermintaanLab,
                'id_permintaan_mb_item' => (int) ($item['id_permintaan_mb_item'] ?? 0),
                'id_dokter_pj'          => $idDokterPj,
                'id_petugas_lab'        => $idPetugasLab,
                'tgl_jam_hasil'         => $tglJamHasil,
            ]);
            $idHasilMb = $this->model->getInsertID();
 
            foreach ($item['parameter'] ?? [] as $param) {
                $idParameter = (int) ($param['id_parameter'] ?? 0);
                if ($idParameter <= 0) continue;

                // Mengumpulkan parameter untuk insertBatch
                $paramBatch[] = [
                    'id_hasil_mb'      => $idHasilMb,
                    'id_parameter'     => $idParameter,
                    'nilai_hasil'      => trim($param['nilai_hasil']      ?? ''),
                    'keterangan_hasil' => trim($param['keterangan_hasil'] ?? '') ?: null,
                ];
            }
        }
        // Eksekusi semua parameter sekaligus
        if (!empty($paramBatch)) {
            $modelParam->insertBatch($paramBatch);
        }
    }
 
    private function deleteHasilMbByPermintaan(
        int $idPermintaanLab,
        \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel $modelParam,
    ): void {
        $idHasilLama = array_column(
            $this->model->db
                ->table('laboratorium.hasil_lab_mb')
                ->select('id_hasil_mb')
                ->where('id_permintaan_lab', $idPermintaanLab)
                ->get()->getResultArray(),
            'id_hasil_mb'
        );
 
        if (!empty($idHasilLama)) {
            $modelParam->whereIn('id_hasil_mb', $idHasilLama)->delete();
        }
 
        $this->model->db
            ->table('laboratorium.hasil_lab_mb')
            ->where('id_permintaan_lab', $idPermintaanLab)
            ->delete();
    }
 
    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    final public function create_page(): string
    {
        return view('admin/laboratorium/tambah_hasil_mb', [
            'judul'         => 'Tambah ' . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->model->primaryKey,
            'konfig'        => $this->getKonfig(),
            'baris'         => [],
            'form_action'   => '/submittambah',
            'item_terpilih' => [],
        ]);
    }
 
    // ──────────────────────────────────────────────────────────
    // PROSES SIMPAN
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $idPermintaanLab = (int) ($rawPost['id_permintaan_lab'] ?? 0) ?: null;
        $idDokterPj      = (int) ($rawPost['id_dokter_pj']      ?? 0) ?: null;
        $idPetugasLab    = (int) ($rawPost['id_petugas_lab']    ?? 0) ?: null;
        $tglJamHasil     = $rawPost['tgl_jam_hasil'] ?? date('Y-m-d H:i:s');
        $hasilList       = $rawPost['hasil'] ?? [];

        if ($err = $this->validateInput(
            $idPermintaanLab, $idDokterPj, $idPetugasLab, $hasilList,
            'Tidak ada item hasil pemeriksaan. Pilih permintaan dan pastikan item MB sudah termuat.',
        )) {
            session()->setFlashdata('error', $err);
            return redirect()->back()->withInput();
        }

        $modelParam = new \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel();

        $this->model->db->transStart();

        try {
            $this->insertHasilMbItems($hasilList, $idPermintaanLab, $idDokterPj, $idPetugasLab, $tglJamHasil, $modelParam);

            $this->model->db
                ->table('laboratorium.permintaan_lab_header')
                ->where('id_permintaan', $idPermintaanLab)
                ->set('id_status_permintaan', 3)
                ->update();

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan hasil lab MB.');
            }
 
            session()->setFlashdata('success', 'Hasil Lab MB berhasil disimpan.');
            return redirect()->to($this->get_uri_path() . '/data');
 
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }
 
    // ──────────────────────────────────────────────────────────
    // HALAMAN UBAH
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    final public function update_page(int|string $id): string
    {
        $idPermintaanLab = (int) $id;

        $baris = $this->model->where('id_permintaan_lab', $idPermintaanLab)->first();

        if (empty($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $baris = array_merge($baris, $this->fetchHeaderPermintaan($idPermintaanLab));
 
        if (!empty($baris['id_dokter_pj'])) {
            $baris['nama_dokter_pj'] = $this->fetchNamaDokterPj((int) $baris['id_dokter_pj']) ?? '';
        }
 
        if (!empty($baris['id_petugas_lab'])) {
            $baris['nama_petugas'] = $this->fetchNamaPetugas((int) $baris['id_petugas_lab']) ?? '';
        }
 
        return view('admin/laboratorium/tambah_hasil_mb', [
            'judul'         => 'Ubah ' . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->model->primaryKey,
            'konfig'        => $this->getKonfig(),
            'baris'         => $baris,
            'form_action'   => '/submitedit/' . $id,
            'item_terpilih' => $this->fetchItemTerpilih($idPermintaanLab),
        ]);
    }
 
    // ──────────────────────────────────────────────────────────
    // PROSES UPDATE
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $rawPost = $this->request->getPost();

        $idPermintaanLab = (int) ($rawPost['id_permintaan_lab'] ?? 0) ?: null;
        $idDokterPj      = (int) ($rawPost['id_dokter_pj']      ?? 0) ?: null;
        $idPetugasLab    = (int) ($rawPost['id_petugas_lab']    ?? 0) ?: null;
        $tglJamHasil     = $rawPost['tgl_jam_hasil'] ?? date('Y-m-d H:i:s');
        $hasilList       = $rawPost['hasil'] ?? [];

        if ($err = $this->validateInput(
            $idPermintaanLab, $idDokterPj, $idPetugasLab, $hasilList,
            'Tidak ada item hasil pemeriksaan. Pastikan item MB masih termuat.',
        )) {
            session()->setFlashdata('error', $err);
            return redirect()->back()->withInput();
        }

        $modelParam = new \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel();

        $this->model->db->transStart();

        try {
            $this->deleteHasilMbByPermintaan($idPermintaanLab, $modelParam);
            $this->insertHasilMbItems($hasilList, $idPermintaanLab, $idDokterPj, $idPetugasLab, $tglJamHasil, $modelParam);

            $this->model->db
                ->table('laboratorium.permintaan_lab_header')
                ->where('id_permintaan', $idPermintaanLab)
                ->set('id_status_permintaan', 3)
                ->update();

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui hasil lab MB.');
            }
 
            session()->setFlashdata('success', 'Hasil Lab MB berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');
 
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }
 
    // ──────────────────────────────────────────────────────────
    // DELETE — cascade hapus parameter lalu header
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        $idPermintaanLab = (int) $id;
        if ($idPermintaanLab == 0) return $this->home();

        if (!$this->model->where('id_permintaan_lab', $idPermintaanLab)->countAllResults()) {
            return $this->home();
        }

        $modelParam = new \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel();

        $this->model->db->transStart();

        try {
            $this->deleteHasilMbByPermintaan($idPermintaanLab, $modelParam);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus hasil lab MB.');
            }
 
            session()->setFlashdata('success', 'Hasil Lab MB berhasil dihapus.');
 
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }
 
        return $this->home();
    }

    // ──────────────────────────────────────────────────────────
    // INDEX — satu baris per permintaan yang sudah ada hasilnya
    // ──────────────────────────────────────────────────────────

    #[\Override]
    final public function index(): string
    {
        $hasilSub = '(SELECT DISTINCT ON (id_permintaan_lab) id_permintaan_lab, id_dokter_pj, id_petugas_lab, tgl_jam_hasil'
                  . ' FROM laboratorium.hasil_lab_mb'
                  . ' ORDER BY id_permintaan_lab, tgl_jam_hasil DESC) h';

        $rows = $this->model->db
            ->table('laboratorium.permintaan_lab_header plh')
            ->select([
                'plh.id_permintaan AS id_permintaan_lab',
                'plh.no_permintaan', 'plh.nomor_reg',
                'p.nomor_rm', 'o.nama',
                'h.tgl_jam_hasil',
                'opj.nama AS nama_dokter_pj',
                's.nama_status',
            ])
            ->join('registrasi.registrasi r',             'r.nomor_reg = plh.nomor_reg',           'left')
            ->join('role.pasien p',                        'p.id_pasien = r.id_pasien',             'left')
            ->join('person.orang o',                       'o.id_orang  = p.id_orang',              'left')
            ->join('laboratorium.ref_status_permintaan s', 's.id_status = plh.id_status_permintaan','left')
            ->join($hasilSub,                              'h.id_permintaan_lab = plh.id_permintaan')
            ->join('role.dokter dpj',                      'dpj.id_dokter = h.id_dokter_pj',        'left')
            ->join('person.orang opj',                     'opj.id_orang  = dpj.id_orang',          'left')
            ->orderBy('h.tgl_jam_hasil', 'DESC')
            ->get()->getResultArray();

        $konfig = [
            [1, 'No. Permintaan', 'no_permintaan',  'teks',    0],
            [1, 'No. Registrasi', 'nomor_reg',      'teks',    0],
            [1, 'Nama Pasien',    'nama',           'teks',    0],
            [1, 'Dokter PJ',      'nama_dokter_pj', 'teks',    0],
            [1, 'Tgl. Hasil',     'tgl_jam_hasil',  'tanggal', 0],
            [1, 'Status',         'nama_status',    'status',  0],
        ];

        return view('/layouts/data', [
            'judul'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs,
            'meta_data'    => ['page' => 1, 'size' => count($rows), 'total' => 1],
            'modul_path'   => $this->get_uri_path(),
            'kolom_id'     => 'id_permintaan_lab',
            'konfig'       => $konfig,
            'aksi'         => $this->actions,
            'tabel'        => $rows,
            'row_alert'    => [],
            'child_link'   => null,
            'query_string' => '',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // CETAK
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function print(int|string $id): string
    {
        $idPermintaanLab = (int) $id;

        $firstHasil = $this->model->where('id_permintaan_lab', $idPermintaanLab)->first();

        if (empty($firstHasil)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $header = $this->model->db
            ->table('laboratorium.permintaan_lab_header plh')
            ->select([
                'plh.no_permintaan', 'plh.nomor_reg', 'plh.tgl_permintaan',
                'p.nomor_rm', 'o.nama AS nama_pasien',
                'od.nama AS nama_dokter_perujuk',
            ])
            ->join('registrasi.registrasi r',  'r.nomor_reg = plh.nomor_reg',         'left')
            ->join('role.pasien p',             'p.id_pasien = r.id_pasien',            'left')
            ->join('person.orang o',            'o.id_orang  = p.id_orang',             'left')
            ->join('role.dokter d',             'd.id_dokter = plh.id_dokter_perujuk',  'left')
            ->join('person.orang od',           'od.id_orang = d.id_orang',             'left')
            ->where('plh.id_permintaan', $idPermintaanLab)
            ->get()->getRowArray() ?? [];

        return view('Views/components/cetak/cetak_hasil_lab_mb', [
            'header'         => $header,
            'items'          => $this->fetchItemTerpilih($idPermintaanLab),
            'tgl_jam_hasil'  => $firstHasil['tgl_jam_hasil'] ?? '',
            'nama_dokter_pj' => !empty($firstHasil['id_dokter_pj'])   ? $this->fetchNamaDokterPj((int) $firstHasil['id_dokter_pj'])   : null,
            'nama_petugas'   => !empty($firstHasil['id_petugas_lab']) ? $this->fetchNamaPetugas((int) $firstHasil['id_petugas_lab'])  : null,
        ]);
    }
}
