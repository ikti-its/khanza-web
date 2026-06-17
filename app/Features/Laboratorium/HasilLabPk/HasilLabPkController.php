<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPk;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;


final class HasilLabPkController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabPkModel(),
            [
                ['Laboratorium', 'laboratorium'],
                ['Hasil Lab PK', 'hasil_lab_pk'],
            ],
            'Hasil Lab PK',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_hasil_pk',           'ID Hasil PK'],
                [SHOW, REQUIRED, I::INDEX,  'id_permintaan_lab',     'No. Permintaan Lab'],
                [HIDE, REQUIRED, I::INDEX,  'id_permintaan_pk_item', 'ID Permintaan PK Item'],
                [SHOW, REQUIRED, I::TEXT,   'id_dokter_pj',          'Kode Dokter PJ'],
                [SHOW, REQUIRED, I::TEXT,   'id_petugas_lab',        'Petugas Lab'],
                [SHOW, REQUIRED, I::DTIME,  'tgl_jam_hasil',         'Tanggal dan Jam Hasil'],
                [SHOW, REQUIRED, I::SELECT, 'id_kategori_usia',      'Kategori Usia'],
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
                'id_hasil_pk',
                'id_permintaan_lab',
                'id_permintaan_pk_item',
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
            ->table('laboratorium.hasil_lab_pk h')
            ->select([
                'h.id_hasil_pk',
                'h.id_permintaan_pk_item',
                'ri.kode_periksa',
                'ri.nama_item',
                'h.tgl_jam_hasil',
            ])
            ->join('laboratorium.permintaan_lab_pk_item pi',   'pi.id_permintaan_pk_item = h.id_permintaan_pk_item')
            ->join('laboratorium.ref_item_pemeriksaan_lab ri', 'ri.id_item_lab = pi.id_item_pemeriksaan')
            ->where('h.id_permintaan_lab', $idPermintaanLab)
            ->get()->getResultArray();

        if(empty($hasilRows)) return [];
 
        $idHasilPkList = array_column($hasilRows, 'id_hasil_pk');

        $parameters = $this->model->db
            ->table('laboratorium.hasil_lab_pk_parameter hp')
            ->select([
                'hp.id_hasil_pk', 'hp.id_hasil_pk_parameter', 'hp.id_parameter',
                'rp.nama_parameter', 'rp.satuan', 'rp.nilai_rujukan',
                'hp.nilai_hasil', 'hp.keterangan_hasil',
            ])
            ->join('laboratorium.ref_parameter_pemeriksaan_lab rp', 'rp.id_parameter = hp.id_parameter')
            ->whereIn('hp.id_hasil_pk', $idHasilPkList)
            ->orderBy('rp.id_parameter', 'ASC')
            ->get()->getResultArray();

        $groupedParams = [];
        foreach ($parameters as $param) {
            $groupedParams[$param['id_hasil_pk']][] = $param;
        }

        foreach ($hasilRows as &$row) {
            $row['parameter'] = $groupedParams[$row['id_hasil_pk']] ?? [];
        }

        return $hasilRows;
    }
 
    private function insertHasilPkItems(
        array $hasilList,
        ?int $idPermintaanLab,
        ?int $idDokterPj,
        ?int $idPetugasLab,
        string $tglJamHasil,
        ?int $idKategoriUsia,
        \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel $modelParam,
    ): void {
        $paramBatch = [];

        foreach ($hasilList as $item) {
            $this->model->insert([
                'id_permintaan_lab'     => $idPermintaanLab,
                'id_permintaan_pk_item' => (int) ($item['id_permintaan_pk_item'] ?? 0),
                'id_dokter_pj'          => $idDokterPj,
                'id_petugas_lab'        => $idPetugasLab,
                'tgl_jam_hasil'         => $tglJamHasil,
                'id_kategori_usia'      => $idKategoriUsia,
            ]);
            $idHasilPk = $this->model->getInsertID();
 
            foreach ($item['parameter'] ?? [] as $param) {
                $idParameter = (int) ($param['id_parameter'] ?? 0);
                if ($idParameter <= 0) continue;
 
                $paramBatch[] = [
                    'id_hasil_pk'      => $idHasilPk,
                    'id_parameter'     => $idParameter,
                    'nilai_hasil'      => trim($param['nilai_hasil']      ?? ''),
                    'keterangan_hasil' => trim($param['keterangan_hasil'] ?? '') ?: null,
                ];
            }
        }

        if (!empty($paramBatch)) {
            $modelParam->insertBatch($paramBatch);
        }
    }
 
    private function deleteHasilPkByPermintaan(
        int $idPermintaanLab,
        \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel $modelParam,
    ): void {
        $idHasilLama = array_column(
            $this->model->db
                ->table('laboratorium.hasil_lab_pk')
                ->select('id_hasil_pk')
                ->where('id_permintaan_lab', $idPermintaanLab)
                ->get()->getResultArray(),
            'id_hasil_pk'
        );
 
        if (!empty($idHasilLama)) {
            $modelParam->whereIn('id_hasil_pk', $idHasilLama)->delete();
        }
 
        $this->model->db
            ->table('laboratorium.hasil_lab_pk')
            ->where('id_permintaan_lab', $idPermintaanLab)
            ->delete();
    }
 
    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    final public function create_page(): string
    {
        return view('admin/laboratorium/tambah_hasil_pk', [
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
        $tglJamHasil     = $rawPost['tgl_jam_hasil']  ?? date('Y-m-d H:i:s');
        $idKategoriUsia  = (int) ($rawPost['id_kategori_usia'] ?? 0) ?: null;
        $hasilList       = $rawPost['hasil'] ?? [];
 
        $modelParam = new \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel();
 
        $this->model->db->transStart();
 
        try {
            $this->insertHasilPkItems($hasilList, $idPermintaanLab, $idDokterPj, $idPetugasLab, $tglJamHasil, $idKategoriUsia, $modelParam);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan hasil lab PK.');
            }
 
            session()->setFlashdata('success', 'Hasil Lab PK berhasil disimpan.');
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
        $baris = $this->model->find($id);
 
        if (empty($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }
 
        $idPermintaanLab = (int) $baris['id_permintaan_lab'];
 
        $baris = array_merge($baris, $this->fetchHeaderPermintaan($idPermintaanLab));
 
        if (!empty($baris['id_dokter_pj'])) {
            $baris['nama_dokter_pj'] = $this->fetchNamaDokterPj((int) $baris['id_dokter_pj']) ?? '';
        }
 
        if (!empty($baris['id_petugas_lab'])) {
            $baris['nama_petugas'] = $this->fetchNamaPetugas((int) $baris['id_petugas_lab']) ?? '';
        }
 
        return view('admin/laboratorium/tambah_hasil_pk', [
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
        $tglJamHasil     = $rawPost['tgl_jam_hasil']  ?? date('Y-m-d H:i:s');
        $idKategoriUsia  = (int) ($rawPost['id_kategori_usia'] ?? 0) ?: null;
        $hasilList       = $rawPost['hasil'] ?? [];
 
        $modelParam = new \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel();
 
        $this->model->db->transStart();
 
        try {
            $this->deleteHasilPkByPermintaan($idPermintaanLab, $modelParam);
            $this->insertHasilPkItems($hasilList, $idPermintaanLab, $idDokterPj, $idPetugasLab, $tglJamHasil, $idKategoriUsia, $modelParam);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui hasil lab PK.');
            }
 
            session()->setFlashdata('success', 'Hasil Lab PK berhasil diperbarui.');
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
        if ($id == 0) return $this->home();
 
        $baris = $this->model->find($id);
        if (empty($baris)) return $this->home();
 
        $modelParam = new \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel();
 
        $this->model->db->transStart();
 
        try {
            $this->deleteHasilPkByPermintaan((int) $baris['id_permintaan_lab'], $modelParam);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus hasil lab PK.');
            }
 
            session()->setFlashdata('success', 'Hasil Lab PK berhasil dihapus.');
 
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }
 
        return $this->home();
    }
}
