<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabMb;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

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
                // A::AUDIT,
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

    /** @return list<array<int|string, mixed>> */
    private function getKonfig(): array
    {
        /** @var list<array<int|string, mixed>> */
        return array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn(array $f) => !in_array(
                $f[2] ?? null,
                [
                    'id_hasil_mb',
                    'id_permintaan_lab',
                    'id_permintaan_mb_item',
                    'id_dokter_pj',
                    'id_petugas_lab',
                ],
                true,
            ),
        ));
    }

    /**
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchHeaderPermintaan(int $idPermintaanLab): array
    {
        $builder = $this->model
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
            ->where('plh.id_permintaan', $idPermintaanLab);

        /** @var array<string, mixed> */
        return $this->model->guarded_get($builder, 'fetchHeaderPermintaan')->getRowArray() ?? [];
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function fetchNamaDokterPj(int $idDokterPj): null|string
    {
        $builder = $this->model
            ->db
            ->table('role.dokter d')
            ->select(['o.nama AS nama_dokter_pj'])
            ->join('person.orang o', 'o.id_orang = d.id_orang')
            ->where('d.id_dokter', $idDokterPj);

        $row = $this->model->guarded_get($builder, 'fetchNamaDokterPj')->getRowArray();

        return is_string($row['nama_dokter_pj'] ?? null) ? $row['nama_dokter_pj'] : null;
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function fetchNamaPetugas(int $idPetugas): null|string
    {
        $builder = $this->model
            ->db
            ->table('role.petugas p')
            ->select(['o.nama AS nama_petugas'])
            ->join('person.orang o', 'o.id_orang = p.id_orang')
            ->where('p.id_petugas', $idPetugas);

        $row = $this->model->guarded_get($builder, 'fetchNamaPetugas')->getRowArray();

        return is_string($row['nama_petugas'] ?? null) ? $row['nama_petugas'] : null;
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchItemTerpilih(int $idPermintaanLab): array
    {
        $builderHasil = $this->model
            ->db
            ->table('laboratorium.hasil_lab_mb h')
            ->select([
                'h.id_hasil_mb',
                'h.id_permintaan_mb_item',
                'ri.kode_periksa',
                'ri.nama_item',
            ])
            ->join('laboratorium.permintaan_lab_mb_item mi', 'mi.id_permintaan_mb_item = h.id_permintaan_mb_item')
            ->join('laboratorium.ref_item_pemeriksaan_lab ri', 'ri.id_item_lab = mi.id_item_pemeriksaan')
            ->where('h.id_permintaan_lab', $idPermintaanLab)
            ->orderBy('h.id_permintaan_mb_item', 'ASC');

        /** @var list<array<string, mixed>> $hasilRows */
        $hasilRows = $this->model->guarded_get($builderHasil, 'fetchItemTerpilih')->getResultArray();

        if ($hasilRows === []) {
            return [];
        }

        $idHasilMbList = array_column($hasilRows, 'id_hasil_mb');

        $builderParam = $this->model
            ->db
            ->table('laboratorium.hasil_lab_mb_parameter hp')
            ->select([
                'hp.id_hasil_mb',
                'hp.id_hasil_mb_parameter',
                'hp.id_parameter',
                'rp.nama_parameter',
                'rp.satuan',
                'rp.nilai_rujukan',
                'hp.nilai_hasil',
                'hp.keterangan_hasil',
            ])
            ->join('laboratorium.ref_parameter_pemeriksaan_lab rp', 'rp.id_parameter = hp.id_parameter')
            ->whereIn('hp.id_hasil_mb', $idHasilMbList)
            ->orderBy('rp.id_parameter', 'ASC');

        /** @var list<array<string, mixed>> $parameters */
        $parameters = $this->model->guarded_get($builderParam, 'fetchItemTerpilih')->getResultArray();

        $groupedParams = [];
        foreach ($parameters as $param) {
            $idHasilMb                   = (string) ($param['id_hasil_mb'] ?? '');
            $groupedParams[$idHasilMb][] = $param;
        }

        foreach ($hasilRows as &$row) {
            $row['parameter'] = $groupedParams[(string) ($row['id_hasil_mb'] ?? '')] ?? [];
        }
        unset($row);

        return $hasilRows;
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchItemUntukForm(int $idPermintaanLab): array
    {
        $builder = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_mb_item pmi')
            ->select([
                'pmi.id_permintaan_mb_item',
                'ri.kode_periksa',
                'ri.nama_item',
                'pmp.id_parameter',
                'rp.nama_parameter',
                'rp.satuan',
                'rp.nilai_rujukan',
                'hp.nilai_hasil',
                'hp.keterangan_hasil',
            ])
            ->join('laboratorium.ref_item_pemeriksaan_lab ri', 'ri.id_item_lab = pmi.id_item_pemeriksaan')
            ->join(
                'laboratorium.permintaan_lab_mb_parameter pmp',
                'pmp.id_permintaan_mb_item = pmi.id_permintaan_mb_item',
            )
            ->join('laboratorium.ref_parameter_pemeriksaan_lab rp', 'rp.id_parameter = pmp.id_parameter')
            ->join(
                'laboratorium.hasil_lab_mb h',
                'h.id_permintaan_mb_item = pmi.id_permintaan_mb_item AND h.id_permintaan_lab = pmi.id_permintaan_lab',
                'left',
            )
            ->join(
                'laboratorium.hasil_lab_mb_parameter hp',
                'hp.id_hasil_mb = h.id_hasil_mb AND hp.id_parameter = pmp.id_parameter',
                'left',
            )
            ->where('pmi.id_permintaan_lab', $idPermintaanLab)
            ->orderBy('pmi.id_permintaan_mb_item', 'ASC')
            ->orderBy('pmp.id_parameter', 'ASC');

        /** @var list<array<string, mixed>> $rows */
        $rows = $this->model->guarded_get($builder, 'fetchItemUntukForm')->getResultArray();

        $items = [];
        foreach ($rows as $row) {
            $idItem = (int) ($row['id_permintaan_mb_item'] ?? 0);

            if (!array_key_exists($idItem, $items)) {
                $items[$idItem] = [
                    'id_permintaan_mb_item' => $idItem,
                    'kode_periksa'          => $row['kode_periksa'] ?? null,
                    'nama_item'             => $row['nama_item'] ?? null,
                    'parameter'             => [],
                ];
            }

            $items[$idItem]['parameter'][] = [
                'id_parameter'     => $row['id_parameter'] ?? null,
                'nama_parameter'   => $row['nama_parameter'] ?? null,
                'satuan'           => $row['satuan'] ?? null,
                'nilai_rujukan'    => $row['nilai_rujukan'] ?? null,
                'nilai_hasil'      => $row['nilai_hasil'] ?? null,
                'keterangan_hasil' => $row['keterangan_hasil'] ?? null,
            ];
        }

        return array_values($items);
    }

    /** @param list<array<string, mixed>> $hasilList */
    private function validateHasilList(array $hasilList): null|string
    {
        foreach ($hasilList as $item) {
            /** @var list<array<string, mixed>> $params */
            $params = is_array($item['parameter'] ?? null) ? $item['parameter'] : [];
            foreach ($params as $param) {
                if (trim((string) ($param['nilai_hasil'] ?? '')) !== '') {
                    return null;
                }
            }
        }
        return 'Isi minimal satu hasil pemeriksaan sebelum menyimpan.';
    }

    /** @param list<array<string, mixed>> $hasilList */
    private function validateInput(
        null|int $idPermintaanLab,
        null|int $idDokterPj,
        null|int $idPetugasLab,
        array $hasilList,
        string $emptyListMsg,
    ): null|string {
        if (!$idPermintaanLab) {
            return 'Permintaan laboratorium wajib dipilih.';
        }
        if (!$idDokterPj) {
            return 'Dokter PJ wajib dipilih.';
        }
        if (!$idPetugasLab) {
            return 'Petugas lab wajib dipilih.';
        }
        if ($hasilList === []) {
            return $emptyListMsg;
        }
        return $this->validateHasilList($hasilList);
    }

    /**
     * @param list<array<string, mixed>> $hasilList
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \ReflectionException
     */
    private function upsertHasilMbItems(
        array $hasilList,
        int $idPermintaanLab,
        null|int $idDokterPj,
        null|int $idPetugasLab,
        string $tglJamHasil,
        \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel $modelParam,
    ): void {
        /** @var array<int, array<string, mixed>> $existingByItem */
        $existingByItem = array_column(
            $this->model->set_filter('id_permintaan_lab', $idPermintaanLab)->findAll(),
            null,
            'id_permintaan_mb_item',
        );

        foreach ($hasilList as $item) {
            $idItem = (int) ($item['id_permintaan_mb_item'] ?? 0);
            if ($idItem <= 0) {
                continue;
            }

            /** @var list<array<string, mixed>> $params */
            $params        = is_array($item['parameter'] ?? null) ? $item['parameter'] : [];
            $existingHasil = $existingByItem[$idItem] ?? null;

            $adaNilaiTerisi = array_any(
                $params,
                static fn(array $p) => trim((string) ($p['nilai_hasil'] ?? '')) !== '',
            );
            if (!$adaNilaiTerisi && $existingHasil === null) {
                continue;
            }

            $idHasilMb = $existingHasil !== null
                ? $this->updateHasilMbHeader(
                    (int) ($existingHasil['id_hasil_mb'] ?? 0),
                    $idDokterPj,
                    $idPetugasLab,
                    $tglJamHasil,
                )
                : $this->insertHasilMbHeader($idPermintaanLab, $idItem, $idDokterPj, $idPetugasLab, $tglJamHasil);
            $existingByItem[$idItem] = ['id_hasil_mb' => $idHasilMb];

            /** @var array<int, array<string, mixed>> $existingParamByParam */
            $existingParamByParam = array_column(
                $modelParam->set_filter('id_hasil_mb', $idHasilMb)->findAll(),
                null,
                'id_parameter',
            );

            foreach ($params as $param) {
                $this->syncHasilMbParameter($modelParam, $existingParamByParam, $param, $idHasilMb);
            }
        }
    }

    /** @throws \ReflectionException */
    private function insertHasilMbHeader(
        int $idPermintaanLab,
        int $idItem,
        null|int $idDokterPj,
        null|int $idPetugasLab,
        string $tglJamHasil,
    ): int {
        $this->model->insert([
            'id_dokter_pj'          => $idDokterPj,
            'id_petugas_lab'        => $idPetugasLab,
            'tgl_jam_hasil'         => $tglJamHasil,
            'id_permintaan_lab'     => $idPermintaanLab,
            'id_permintaan_mb_item' => $idItem,
        ]);
        return (int) $this->model->getInsertID();
    }

    /** @throws \ReflectionException */
    private function updateHasilMbHeader(
        int $idHasilMb,
        null|int $idDokterPj,
        null|int $idPetugasLab,
        string $tglJamHasil,
    ): int {
        $this->model->update($idHasilMb, [
            'id_dokter_pj'   => $idDokterPj,
            'id_petugas_lab' => $idPetugasLab,
            'tgl_jam_hasil'  => $tglJamHasil,
        ]);
        return $idHasilMb;
    }

    /**
     * Simpan nilai parameter jika terisi; hapus baris lama jika nilainya dikosongkan kembali.
     *
     * @param array<int, array<string, mixed>> $existingParamByParam
     * @param array<string, mixed>             $param
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \ReflectionException
     */
    private function syncHasilMbParameter(
        \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel $modelParam,
        array $existingParamByParam,
        array $param,
        int $idHasilMb,
    ): void {
        $idParameter = (int) ($param['id_parameter'] ?? 0);
        if ($idParameter <= 0) {
            return;
        }

        $existingParam = $existingParamByParam[$idParameter] ?? null;
        $nilaiHasil    = trim((string) ($param['nilai_hasil'] ?? ''));

        if ($nilaiHasil === '') {
            if ($existingParam !== null) {
                $modelParam->delete((int) ($existingParam['id_hasil_mb_parameter'] ?? 0));
            }
            return;
        }

        $keteranganHasil = trim((string) ($param['keterangan_hasil'] ?? ''));

        $paramData = [
            'nilai_hasil'      => $nilaiHasil,
            'keterangan_hasil' => $keteranganHasil ? $keteranganHasil : null,
        ];

        if ($existingParam !== null) {
            $modelParam->update((int) ($existingParam['id_hasil_mb_parameter'] ?? 0), $paramData);
            return;
        }
        $modelParam->insert($paramData + ['id_hasil_mb' => $idHasilMb, 'id_parameter' => $idParameter]);
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function recomputeStatusPermintaan(int $idPermintaanLab): void
    {
        $total = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_mb_parameter pmp')
            ->join('laboratorium.permintaan_lab_mb_item pmi', 'pmi.id_permintaan_mb_item = pmp.id_permintaan_mb_item')
            ->where('pmi.id_permintaan_lab', $idPermintaanLab)
            ->countAllResults();

        $filled = $this->model
            ->db
            ->table('laboratorium.hasil_lab_mb_parameter hp')
            ->join('laboratorium.hasil_lab_mb h', 'h.id_hasil_mb = hp.id_hasil_mb')
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

    /**
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \ReflectionException
     */
    private function deleteHasilMbByPermintaan(
        int $idPermintaanLab,
        \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel $modelParam,
    ): void {
        $builder = $this->model
            ->db
            ->table('laboratorium.hasil_lab_mb')
            ->select('id_hasil_mb')
            ->where('id_permintaan_lab', $idPermintaanLab);

        /** @var list<array<string, mixed>> $rowsHasilLama */
        $rowsHasilLama = $this->model->guarded_get($builder, 'deleteHasilMbByPermintaan')->getResultArray();
        $idHasilLama   = array_column($rowsHasilLama, 'id_hasil_mb');

        if ($idHasilLama !== []) {
            $modelParam->whereIn('id_hasil_mb', $idHasilLama)->delete();
        }

        $this->model->db->table('laboratorium.hasil_lab_mb')->where('id_permintaan_lab', $idPermintaanLab)->delete();
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function create_page(): string
    {
        return view('admin/laboratorium/tambah_hasil_mb', [
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
        return $this->submitHasil(
            'Tidak ada item hasil pemeriksaan. Pilih permintaan dan pastikan item MB sudah termuat.',
            'Hasil Lab MB berhasil disimpan.',
            'Gagal menyimpan hasil lab MB.',
        );
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN UBAH
    // ──────────────────────────────────────────────────────────

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string
    {
        $idPermintaanLab = (int) $id;

        $baris = $this->model->where('id_permintaan_lab', $idPermintaanLab)->first();

        if (empty($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }
        assert(is_array($baris));

        $baris = array_merge($baris, $this->fetchHeaderPermintaan($idPermintaanLab));

        if (!empty($baris['id_dokter_pj'])) {
            $baris['nama_dokter_pj'] = $this->fetchNamaDokterPj((int) $baris['id_dokter_pj']) ?? '';
        }

        if (!empty($baris['id_petugas_lab'])) {
            $baris['nama_petugas'] = $this->fetchNamaPetugas((int) $baris['id_petugas_lab']) ?? '';
        }

        return view('admin/laboratorium/tambah_hasil_mb', [
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

        return $this->submitHasil(
            'Tidak ada item hasil pemeriksaan. Pastikan item MB masih termuat.',
            'Hasil Lab MB berhasil diperbarui.',
            'Gagal memperbarui hasil lab MB.',
        );
    }

    /** Alur simpan yang sama persis dipakai create() dan update(); hanya pesan-pesannya yang beda. */
    private function submitHasil(string $emptyListMsg, string $successMsg, string $failMsg): string|RedirectResponse
    {
        /** @var array<string, mixed> $rawPost */
        $rawPost = $this->request->getPost();

        $idPermintaanLab = (int) ($rawPost['id_permintaan_lab'] ?? 0)
            ? (int) ($rawPost['id_permintaan_lab'] ?? 0)
            : null;
        $idDokterPj   = (int) ($rawPost['id_dokter_pj'] ?? 0) ? (int) ($rawPost['id_dokter_pj'] ?? 0) : null;
        $idPetugasLab = (int) ($rawPost['id_petugas_lab'] ?? 0) ? (int) ($rawPost['id_petugas_lab'] ?? 0) : null;
        $tglJamHasil  = (string) ($rawPost['tgl_jam_hasil'] ?? date('Y-m-d H:i:s'));

        /** @var list<array<string, mixed>> $hasilList */
        $hasilList = is_array($rawPost['hasil'] ?? null) ? $rawPost['hasil'] : [];

        $err = $this->validateInput($idPermintaanLab, $idDokterPj, $idPetugasLab, $hasilList, $emptyListMsg);
        if ($err) {
            session()->setFlashdata('error', $err);
            return redirect()->back()->withInput();
        }

        assert($idPermintaanLab !== null && $idDokterPj !== null && $idPetugasLab !== null);

        $modelParam = new \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel();

        $this->model->db->transStart();

        try {
            $this->upsertHasilMbItems(
                $hasilList,
                $idPermintaanLab,
                $idDokterPj,
                $idPetugasLab,
                $tglJamHasil,
                $modelParam,
            );
            $this->recomputeStatusPermintaan($idPermintaanLab);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
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

        $modelParam = new \App\Features\Laboratorium\HasilLabMbParameter\HasilLabMbParameterModel();

        $this->model->db->transStart();

        try {
            $this->deleteHasilMbByPermintaan($idPermintaanLab, $modelParam);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menghapus hasil lab MB.');
            }

            session()->setFlashdata('success', 'Hasil Lab MB berhasil dihapus.');
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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function index(): string
    {
        $hasilSub =
            '(SELECT DISTINCT ON (id_permintaan_lab) id_permintaan_lab, id_dokter_pj, id_petugas_lab, tgl_jam_hasil'
            . ' FROM laboratorium.hasil_lab_mb'
            . ' ORDER BY id_permintaan_lab, tgl_jam_hasil DESC) h';

        $builder = $this->model
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
            ->orderBy('h.tgl_jam_hasil', 'DESC');

        /** @var list<array<string, mixed>> $rows */
        $rows = $this->model->guarded_get($builder, 'index')->getResultArray();

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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function print(int|string $id): string
    {
        $idPermintaanLab = (int) $id;

        $firstHasil = $this->model->where('id_permintaan_lab', $idPermintaanLab)->first();

        if (empty($firstHasil)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $builder = $this->model
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
            ->where('plh.id_permintaan', $idPermintaanLab);

        $header = $this->model->guarded_get($builder, 'print')->getRowArray() ?? [];

        return view('Views/components/cetak/cetak_hasil_lab_mb', [
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
