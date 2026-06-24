<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPa;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class HasilLabPaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabPaModel(),
            [
                ['Laboratorium', 'laboratorium'],
                ['Hasil Lab PA', 'hasil_lab_pa'],
            ],
            'Hasil Lab PA',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::PRINT,
            ],
             [
                [HIDE, OPTIONAL, I::INDEX, 'id_hasil_pa',           'ID Hasil PA'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_lab',     'No. Permintaan Lab'],
                [HIDE, REQUIRED, I::INDEX, 'id_permintaan_pa_item', 'ID Permintaan PA Item'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_pj',          'Kode Dokter PJ'],
                [SHOW, REQUIRED, I::TEXT,  'id_petugas_lab',        'Petugas Lab'],
                [SHOW, REQUIRED, I::DTIME, 'tgl_jam_hasil',         'Tanggal dan Jam Hasil'],
                [SHOW, REQUIRED, I::TEXT,  'diagnosa_klinis',       'Diagnosa Klinis'],
                [SHOW, REQUIRED, I::TEXT,  'makroskopik',           'Makroskopik'],
                [SHOW, REQUIRED, I::TEXT,  'mikroskopik',           'Mikroskopik'],
                [SHOW, REQUIRED, I::TEXT,  'kesimpulan',            'Kesimpulan'],
                [SHOW, OPTIONAL, I::TEXT,  'kesan',                 'Kesan'],
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
                'id_hasil_pa',
                'id_permintaan_lab',
                'id_permintaan_pa_item',
                'id_dokter_pj',
                'id_petugas_lab',
                'diagnosa_klinis',
                'makroskopik',
                'mikroskopik',
                'kesimpulan',
                'kesan',
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
            ->join('rekam_medis.registrasi r',  'r.nomor_reg = plh.nomor_reg')
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
            ->table('laboratorium.hasil_lab_pa h')
            ->select([
                'h.id_hasil_pa',
                'h.id_permintaan_pa_item',
                'ri.kode_periksa',
                'ri.nama_item',
                'h.diagnosa_klinis',
                'h.makroskopik',
                'h.mikroskopik',
                'h.kesimpulan',
                'h.kesan',
            ])
            ->join('laboratorium.permintaan_lab_pa_item pi',   'pi.id_permintaan_pa_item = h.id_permintaan_pa_item')
            ->join('laboratorium.ref_item_pemeriksaan_lab ri', 'ri.id_item_lab = pi.id_item_pemeriksaan')
            ->where('h.id_permintaan_lab', $idPermintaanLab)
            ->get()->getResultArray();
 
        return array_map(fn($row) => [
            'id_hasil_pa'           => $row['id_hasil_pa'],
            'id_permintaan_pa_item' => $row['id_permintaan_pa_item'],
            'kode_periksa'          => $row['kode_periksa'],
            'nama_item'             => $row['nama_item'],
            'diagnosa_klinis'       => $row['diagnosa_klinis'],
            'makroskopik'           => $row['makroskopik'],
            'mikroskopik'           => $row['mikroskopik'],
            'kesimpulan'            => $row['kesimpulan'],
            'kesan'                 => $row['kesan'],
        ], $hasilRows);
    }
 
    private function insertHasilPaItems(
        array $hasilList,
        ?int $idPermintaanLab,
        ?int $idDokterPj,
        ?int $idPetugasLab,
        string $tglJamHasil,
    ): void {
        if (empty($hasilList)) return;

        $batchData = array_map(fn($item) => [
            'id_permintaan_lab'     => $idPermintaanLab,
            'id_permintaan_pa_item' => (int) ($item['id_permintaan_pa_item'] ?? 0),
            'id_dokter_pj'          => $idDokterPj,
            'id_petugas_lab'        => $idPetugasLab,
            'tgl_jam_hasil'         => $tglJamHasil,
            'diagnosa_klinis'       => trim($item['diagnosa_klinis'] ?? ''),
            'makroskopik'           => trim($item['makroskopik']     ?? ''),
            'mikroskopik'           => trim($item['mikroskopik']     ?? ''),
            'kesimpulan'            => trim($item['kesimpulan']      ?? ''),
            'kesan'                 => trim($item['kesan']           ?? '') ?: null,
        ], $hasilList);

        $this->model->insertBatch($batchData);
    }
 
    private function deleteHasilPaByPermintaan(int $idPermintaanLab): void
    {
        $this->model->db
            ->table('laboratorium.hasil_lab_pa')
            ->where('id_permintaan_lab', $idPermintaanLab)
            ->delete();
    }
 
    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    final public function create_page(): string
    {
        return view('admin/laboratorium/tambah_hasil_pa', [
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
 
        $this->model->db->transStart();
 
        try {
            $this->insertHasilPaItems($hasilList, $idPermintaanLab, $idDokterPj, $idPetugasLab, $tglJamHasil);

            $this->model->db
                ->table('laboratorium.permintaan_lab_header')
                ->where('id_permintaan', $idPermintaanLab)
                ->set('id_status_permintaan', 3)
                ->update();

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan hasil lab PA.');
            }
 
            session()->setFlashdata('success', 'Hasil Lab PA berhasil disimpan.');
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
 
        return view('admin/laboratorium/tambah_hasil_pa', [
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
 
        $this->model->db->transStart();
 
        try {
            $this->deleteHasilPaByPermintaan($idPermintaanLab);
            $this->insertHasilPaItems($hasilList, $idPermintaanLab, $idDokterPj, $idPetugasLab, $tglJamHasil);

            $this->model->db
                ->table('laboratorium.permintaan_lab_header')
                ->where('id_permintaan', $idPermintaanLab)
                ->set('id_status_permintaan', 3)
                ->update();

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui hasil lab PA.');
            }
 
            session()->setFlashdata('success', 'Hasil Lab PA berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');
 
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }
 
    // ──────────────────────────────────────────────────────────
    // DELETE — hapus semua baris hasil PA per permintaan
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        $idPermintaanLab = (int) $id;
        if ($idPermintaanLab == 0) return $this->home();

        if (!$this->model->where('id_permintaan_lab', $idPermintaanLab)->countAllResults()) {
            return $this->home();
        }

        $this->model->db->transStart();

        try {
            $this->deleteHasilPaByPermintaan($idPermintaanLab);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus hasil lab PA.');
            }
 
            session()->setFlashdata('success', 'Hasil Lab PA berhasil dihapus.');
 
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
                  . ' FROM laboratorium.hasil_lab_pa'
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
            ->join('rekam_medis.registrasi r',             'r.nomor_reg = plh.nomor_reg',           'left')
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
            ->join('rekam_medis.registrasi r',  'r.nomor_reg = plh.nomor_reg',         'left')
            ->join('role.pasien p',             'p.id_pasien = r.id_pasien',            'left')
            ->join('person.orang o',            'o.id_orang  = p.id_orang',             'left')
            ->join('role.dokter d',             'd.id_dokter = plh.id_dokter_perujuk',  'left')
            ->join('person.orang od',           'od.id_orang = d.id_orang',             'left')
            ->where('plh.id_permintaan', $idPermintaanLab)
            ->get()->getRowArray() ?? [];

        return view('Views/components/cetak/cetak_hasil_lab_pa', [
            'header'         => $header,
            'items'          => $this->fetchItemTerpilih($idPermintaanLab),
            'tgl_jam_hasil'  => $firstHasil['tgl_jam_hasil'] ?? '',
            'nama_dokter_pj' => !empty($firstHasil['id_dokter_pj'])   ? $this->fetchNamaDokterPj((int) $firstHasil['id_dokter_pj'])  : null,
            'nama_petugas'   => !empty($firstHasil['id_petugas_lab']) ? $this->fetchNamaPetugas((int) $firstHasil['id_petugas_lab']) : null,
        ]);
    }
}
