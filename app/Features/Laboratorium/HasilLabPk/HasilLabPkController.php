<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPk;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

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
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::PRINT,
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
            static fn($f) => !in_array(
                $f[2],
                [
                    'id_hasil_pk',
                    'id_permintaan_lab',
                    'id_permintaan_pk_item',
                    'id_dokter_pj',
                    'id_petugas_lab',
                ],
                true,
            ),
        ));
    }

    private function fetchHeaderPermintaan(int $idPermintaanLab): array
    {
        return $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header plh')
            ->select([
                'plh.id_permintaan',
                'plh.no_permintaan',
                'plh.nomor_reg',
                'o.nama  AS nama_pasien',
                'od.nama AS nama_dokter_perujuk',
                'r.id_dokter AS id_dokter_perujuk',
            ])
            ->join('registrasi.registrasi r', 'r.nomor_reg = plh.nomor_reg')
            ->join('role.pasien p', 'p.id_pasien = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang  = p.id_orang', 'left')
            ->join('role.dokter d', 'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang', 'left')
            ->where('plh.id_permintaan', $idPermintaanLab)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchNamaDokterPj(int $idDokterPj): null|string
    {
        $row = $this->model
            ->db
            ->table('role.dokter d')
            ->select(['o.nama AS nama_dokter_pj'])
            ->join('person.orang o', 'o.id_orang = d.id_orang')
            ->where('d.id_dokter', $idDokterPj)
            ->get()
            ->getRowArray();

        return $row['nama_dokter_pj'] ?? null;
    }

    private function fetchNamaPetugas(int $idPetugas): null|string
    {
        $row = $this->model
            ->db
            ->table('role.petugas p')
            ->select(['o.nama AS nama_petugas'])
            ->join('person.orang o', 'o.id_orang = p.id_orang')
            ->where('p.id_petugas', $idPetugas)
            ->get()
            ->getRowArray();

        return $row['nama_petugas'] ?? null;
    }

    private function fetchItemTerpilih(int $idPermintaanLab): array
    {
        $hasilRows = $this->model
            ->db
            ->table('laboratorium.hasil_lab_pk h')
            ->select([
                'h.id_hasil_pk',
                'h.id_permintaan_pk_item',
                'ri.kode_periksa',
                'ri.nama_item',
                'h.tgl_jam_hasil',
            ])
            ->join('laboratorium.permintaan_lab_pk_item pi', 'pi.id_permintaan_pk_item = h.id_permintaan_pk_item')
            ->join('laboratorium.ref_item_pemeriksaan_lab ri', 'ri.id_item_lab = pi.id_item_pemeriksaan')
            ->where('h.id_permintaan_lab', $idPermintaanLab)
            ->orderBy('h.id_permintaan_pk_item', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($hasilRows)) {
            return [];
        }

        $idHasilPkList = array_column($hasilRows, 'id_hasil_pk');

        $parameters = $this->model
            ->db
            ->table('laboratorium.hasil_lab_pk_parameter hp')
            ->select([
                'hp.id_hasil_pk',
                'hp.id_hasil_pk_parameter',
                'hp.id_parameter',
                'rp.nama_parameter',
                'rp.satuan',
                'rp.nilai_rujukan',
                'hp.nilai_hasil',
                'hp.keterangan_hasil',
            ])
            ->join('laboratorium.ref_parameter_pemeriksaan_lab rp', 'rp.id_parameter = hp.id_parameter')
            ->whereIn('hp.id_hasil_pk', $idHasilPkList)
            ->orderBy('rp.id_parameter', 'ASC')
            ->get()
            ->getResultArray();

        $groupedParams = [];
        foreach ($parameters as $param) {
            $groupedParams[$param['id_hasil_pk']][] = $param;
        }

        foreach ($hasilRows as &$row) {
            $row['parameter'] = $groupedParams[$row['id_hasil_pk']] ?? [];
        }

        return $hasilRows;
    }

    private function fetchItemUntukForm(int $idPermintaanLab): array
    {
        $rows = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_pk_item pki')
            ->select([
                'pki.id_permintaan_pk_item',
                'ri.kode_periksa',
                'ri.nama_item',
                'pkp.id_parameter',
                'rp.nama_parameter',
                'rp.satuan',
                'rp.nilai_rujukan',
                'hp.nilai_hasil',
                'hp.keterangan_hasil',
            ])
            ->join('laboratorium.ref_item_pemeriksaan_lab ri', 'ri.id_item_lab = pki.id_item_pemeriksaan')
            ->join(
                'laboratorium.permintaan_lab_pk_parameter pkp',
                'pkp.id_permintaan_pk_item = pki.id_permintaan_pk_item',
            )
            ->join('laboratorium.ref_parameter_pemeriksaan_lab rp', 'rp.id_parameter = pkp.id_parameter')
            ->join(
                'laboratorium.hasil_lab_pk h',
                'h.id_permintaan_pk_item = pki.id_permintaan_pk_item AND h.id_permintaan_lab = pki.id_permintaan_lab',
                'left',
            )
            ->join(
                'laboratorium.hasil_lab_pk_parameter hp',
                'hp.id_hasil_pk = h.id_hasil_pk AND hp.id_parameter = pkp.id_parameter',
                'left',
            )
            ->where('pki.id_permintaan_lab', $idPermintaanLab)
            ->orderBy('pki.id_permintaan_pk_item', 'ASC')
            ->orderBy('pkp.id_parameter', 'ASC')
            ->get()
            ->getResultArray();

        $items = [];
        foreach ($rows as $row) {
            $idItem = (int) $row['id_permintaan_pk_item'];

            if (!array_key_exists($idItem, $items)) {
                $items[$idItem] = [
                    'id_permintaan_pk_item' => $idItem,
                    'kode_periksa'          => $row['kode_periksa'],
                    'nama_item'             => $row['nama_item'],
                    'parameter'             => [],
                ];
            }

            $items[$idItem]['parameter'][] = [
                'id_parameter'     => $row['id_parameter'],
                'nama_parameter'   => $row['nama_parameter'],
                'satuan'           => $row['satuan'],
                'nilai_rujukan'    => $row['nilai_rujukan'],
                'nilai_hasil'      => $row['nilai_hasil'],
                'keterangan_hasil' => $row['keterangan_hasil'],
            ];
        }

        return array_values($items);
    }

    private function upsertHasilPkItems(
        array $hasilList,
        int $idPermintaanLab,
        null|int $idDokterPj,
        null|int $idPetugasLab,
        string $tglJamHasil,
        null|int $idKategoriUsia,
        \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel $modelParam,
    ): void {
        $existingByItem = array_column(
            $this->model->set_filter('id_permintaan_lab', $idPermintaanLab)->findAll(),
            null,
            'id_permintaan_pk_item',
        );

        foreach ($hasilList as $item) {
            $idItem = (int) ($item['id_permintaan_pk_item'] ?? 0);
            if ($idItem <= 0) {
                continue;
            }

            $params        = $item['parameter'] ?? [];
            $existingHasil = $existingByItem[$idItem] ?? null;

            $adaNilaiTerisi = array_any($params, static fn($p) => trim($p['nilai_hasil'] ?? '') !== '');
            if (!$adaNilaiTerisi && $existingHasil === null) {
                continue;
            }

            $idHasilPk = $existingHasil !== null
                ? $this->updateHasilPkHeader(
                    (int) $existingHasil['id_hasil_pk'],
                    $idDokterPj,
                    $idPetugasLab,
                    $tglJamHasil,
                    $idKategoriUsia,
                )
                : $this->insertHasilPkHeader(
                    $idPermintaanLab,
                    $idItem,
                    $idDokterPj,
                    $idPetugasLab,
                    $tglJamHasil,
                    $idKategoriUsia,
                );
            $existingByItem[$idItem] = ['id_hasil_pk' => $idHasilPk];

            $existingParamByParam = array_column(
                $modelParam->set_filter('id_hasil_pk', $idHasilPk)->findAll(),
                null,
                'id_parameter',
            );

            foreach ($params as $param) {
                $this->syncHasilPkParameter($modelParam, $existingParamByParam, $param, $idHasilPk);
            }
        }
    }

    private function insertHasilPkHeader(
        int $idPermintaanLab,
        int $idItem,
        null|int $idDokterPj,
        null|int $idPetugasLab,
        string $tglJamHasil,
        null|int $idKategoriUsia,
    ): int {
        $this->model->insert([
            'id_dokter_pj'          => $idDokterPj,
            'id_petugas_lab'        => $idPetugasLab,
            'tgl_jam_hasil'         => $tglJamHasil,
            'id_kategori_usia'      => $idKategoriUsia,
            'id_permintaan_lab'     => $idPermintaanLab,
            'id_permintaan_pk_item' => $idItem,
        ]);
        return (int) $this->model->getInsertID();
    }

    private function updateHasilPkHeader(
        int $idHasilPk,
        null|int $idDokterPj,
        null|int $idPetugasLab,
        string $tglJamHasil,
        null|int $idKategoriUsia,
    ): int {
        $this->model->update($idHasilPk, [
            'id_dokter_pj'     => $idDokterPj,
            'id_petugas_lab'   => $idPetugasLab,
            'tgl_jam_hasil'    => $tglJamHasil,
            'id_kategori_usia' => $idKategoriUsia,
        ]);
        return $idHasilPk;
    }

    /** Simpan nilai parameter jika terisi; hapus baris lama jika nilainya dikosongkan kembali. */
    private function syncHasilPkParameter(
        \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel $modelParam,
        array $existingParamByParam,
        array $param,
        int $idHasilPk,
    ): void {
        $idParameter = (int) ($param['id_parameter'] ?? 0);
        if ($idParameter <= 0) {
            return;
        }

        $existingParam = $existingParamByParam[$idParameter] ?? null;
        $nilaiHasil    = trim($param['nilai_hasil'] ?? '');

        if ($nilaiHasil === '') {
            if ($existingParam !== null) {
                $modelParam->delete((int) $existingParam['id_hasil_pk_parameter']);
            }
            return;
        }

        $keteranganHasil = trim($param['keterangan_hasil'] ?? '');

        $paramData = [
            'nilai_hasil'      => $nilaiHasil,
            'keterangan_hasil' => $keteranganHasil ? $keteranganHasil : null,
        ];

        if ($existingParam !== null) {
            $modelParam->update((int) $existingParam['id_hasil_pk_parameter'], $paramData);
            return;
        }
        $modelParam->insert($paramData + ['id_hasil_pk' => $idHasilPk, 'id_parameter' => $idParameter]);
    }

    private function recomputeStatusPermintaan(int $idPermintaanLab): void
    {
        $total = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_pk_parameter pkp')
            ->join('laboratorium.permintaan_lab_pk_item pki', 'pki.id_permintaan_pk_item = pkp.id_permintaan_pk_item')
            ->where('pki.id_permintaan_lab', $idPermintaanLab)
            ->countAllResults();

        $filled = $this->model
            ->db
            ->table('laboratorium.hasil_lab_pk_parameter hp')
            ->join('laboratorium.hasil_lab_pk h', 'h.id_hasil_pk = hp.id_hasil_pk')
            ->where('h.id_permintaan_lab', $idPermintaanLab)
            ->where("TRIM(hp.nilai_hasil) <> ''", null, false)
            ->countAllResults();

        $status = $total > 0 && $filled >= $total ? 3 : 2;

        $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header')
            ->where('id_permintaan', $idPermintaanLab)
            ->set('id_status_permintaan', $status)
            ->update();
    }

    private function validateHasilList(array $hasilList): null|string
    {
        foreach ($hasilList as $item) {
            foreach ($item['parameter'] ?? [] as $param) {
                if (trim($param['nilai_hasil'] ?? '') !== '') {
                    return null;
                }
            }
        }
        return 'Isi minimal satu hasil pemeriksaan sebelum menyimpan.';
    }

    private function deleteHasilPkByPermintaan(
        int $idPermintaanLab,
        \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel $modelParam,
    ): void {
        $idHasilLama = array_column(
            $this->model
                ->db
                ->table('laboratorium.hasil_lab_pk')
                ->select('id_hasil_pk')
                ->where('id_permintaan_lab', $idPermintaanLab)
                ->get()
                ->getResultArray(),
            'id_hasil_pk',
        );

        if (!empty($idHasilLama)) {
            $modelParam->whereIn('id_hasil_pk', $idHasilLama)->delete();
        }

        $this->model->db->table('laboratorium.hasil_lab_pk')->where('id_permintaan_lab', $idPermintaanLab)->delete();
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function create_page(): string
    {
        return view('admin/laboratorium/tambah_hasil_pk', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->getKonfig(),
            'baris'       => [],
            'form_action' => '/submittambah',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // PROSES SIMPAN
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function create(): string|RedirectResponse
    {
        return $this->submitHasil('Hasil Lab PK berhasil disimpan.', 'Gagal menyimpan hasil lab PK.');
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN UBAH
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function update_page(int|string $id): string
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

        return view('admin/laboratorium/tambah_hasil_pk', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->getKonfig(),
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // PROSES UPDATE
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        return $this->submitHasil('Hasil Lab PK berhasil diperbarui.', 'Gagal memperbarui hasil lab PK.');
    }

    /** Alur simpan yang sama persis dipakai create() dan update(); hanya pesan sukses/gagal yang beda. */
    private function submitHasil(string $successMsg, string $failMsg): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $idPermintaanLab = (int) ($rawPost['id_permintaan_lab'] ?? 0)
            ? (int) ($rawPost['id_permintaan_lab'] ?? 0)
            : null;
        $idDokterPj     = (int) ($rawPost['id_dokter_pj'] ?? 0) ? (int) ($rawPost['id_dokter_pj'] ?? 0) : null;
        $idPetugasLab   = (int) ($rawPost['id_petugas_lab'] ?? 0) ? (int) ($rawPost['id_petugas_lab'] ?? 0) : null;
        $tglJamHasil    = $rawPost['tgl_jam_hasil'] ?? date('Y-m-d H:i:s');
        $idKategoriUsia = (int) ($rawPost['id_kategori_usia'] ?? 0) ? (int) ($rawPost['id_kategori_usia'] ?? 0) : null;
        $hasilList      = $rawPost['hasil'] ?? [];

        if (!$idPermintaanLab) {
            session()->setFlashdata('error', 'Permintaan laboratorium wajib dipilih.');
            return redirect()->back()->withInput();
        }

        $err = $this->validateHasilList($hasilList);
        if ($err) {
            session()->setFlashdata('error', $err);
            return redirect()->back()->withInput();
        }

        $modelParam = new \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel();

        $this->model->db->transStart();

        try {
            $this->upsertHasilPkItems(
                $hasilList,
                $idPermintaanLab,
                $idDokterPj,
                $idPetugasLab,
                $tglJamHasil,
                $idKategoriUsia,
                $modelParam,
            );
            $this->recomputeStatusPermintaan($idPermintaanLab);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException($failMsg);
            }

            session()->setFlashdata('success', $successMsg);
            return redirect()->to($this->get_uri_path() . '/data');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    // ──────────────────────────────────────────────────────────
    // MODAL LIST — item+parameter permintaan digabung dengan hasil yang sudah ada
    // ──────────────────────────────────────────────────────────

    public function list(): ResponseInterface
    {
        $idPermintaanLab = (int) ($this->request->getGet('id_permintaan') ?? 0);

        return $this->response->setJSON([
            'data' => $idPermintaanLab > 0 ? $this->fetchItemUntukForm($idPermintaanLab) : [],
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // DELETE — cascade hapus parameter lalu header
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        $idPermintaanLab = (int) $id;
        if ($idPermintaanLab === 0) {
            return $this->home();
        }

        if (!$this->model->where('id_permintaan_lab', $idPermintaanLab)->countAllResults()) {
            return $this->home();
        }

        $modelParam = new \App\Features\Laboratorium\HasilLabPkParameter\HasilLabPkParameterModel();

        $this->model->db->transStart();

        try {
            $this->deleteHasilPkByPermintaan($idPermintaanLab, $modelParam);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menghapus hasil lab PK.');
            }

            session()->setFlashdata('success', 'Hasil Lab PK berhasil dihapus.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return $this->home();
    }

    // ──────────────────────────────────────────────────────────
    // INDEX — satu baris per permintaan yang sudah ada hasilnya
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function index(): string
    {
        $hasilSub =
            '(SELECT DISTINCT ON (id_permintaan_lab) id_permintaan_lab, id_dokter_pj, id_petugas_lab, tgl_jam_hasil'
            . ' FROM laboratorium.hasil_lab_pk'
            . ' ORDER BY id_permintaan_lab, tgl_jam_hasil DESC) h';

        $rows = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header plh')
            ->select([
                'plh.id_permintaan AS id_permintaan_lab',
                'plh.no_permintaan',
                'plh.nomor_reg',
                'p.nomor_rm',
                'o.nama',
                'h.tgl_jam_hasil',
                'opj.nama AS nama_dokter_pj',
                's.nama_status',
            ])
            ->join('registrasi.registrasi r', 'r.nomor_reg = plh.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang  = p.id_orang', 'left')
            ->join('laboratorium.ref_status_permintaan s', 's.id_status = plh.id_status_permintaan', 'left')
            ->join($hasilSub, 'h.id_permintaan_lab = plh.id_permintaan')
            ->join('role.dokter dpj', 'dpj.id_dokter = h.id_dokter_pj', 'left')
            ->join('person.orang opj', 'opj.id_orang  = dpj.id_orang', 'left')
            ->orderBy('h.tgl_jam_hasil', 'DESC')
            ->get()
            ->getResultArray();

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

        $header = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header plh')
            ->select([
                'plh.no_permintaan',
                'plh.nomor_reg',
                'plh.tgl_permintaan',
                'p.nomor_rm',
                'o.nama AS nama_pasien',
                'od.nama AS nama_dokter_perujuk',
            ])
            ->join('registrasi.registrasi r', 'r.nomor_reg = plh.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang  = p.id_orang', 'left')
            ->join('role.dokter d', 'd.id_dokter = plh.id_dokter_perujuk', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang', 'left')
            ->where('plh.id_permintaan', $idPermintaanLab)
            ->get()
            ->getRowArray() ?? [];

        return view('Views/components/cetak/cetak_hasil_lab_pk', [
            'header'         => $header,
            'items'          => $this->fetchItemTerpilih($idPermintaanLab),
            'tgl_jam_hasil'  => $firstHasil['tgl_jam_hasil'] ?? '',
            'nama_dokter_pj' => !empty($firstHasil['id_dokter_pj'])
                ? $this->fetchNamaDokterPj((int) $firstHasil['id_dokter_pj'])
                : null,
            'nama_petugas'   => !empty($firstHasil['id_petugas_lab'])
                ? $this->fetchNamaPetugas((int) $firstHasil['id_petugas_lab'])
                : null,
        ]);
    }
}
