<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPa;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

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
                // A::AUDIT,
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

    /** @return list<array<int|string, mixed>> */
    private function getKonfig(): array
    {
        /** @var list<array<int|string, mixed>> */
        return array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn(array $f) => !in_array(
                $f[2] ?? null,
                [
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
        $builder = $this->model
            ->db
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
            ->join('laboratorium.permintaan_lab_pa_item pi', 'pi.id_permintaan_pa_item = h.id_permintaan_pa_item')
            ->join('laboratorium.ref_item_pemeriksaan_lab ri', 'ri.id_item_lab = pi.id_item_pemeriksaan')
            ->where('h.id_permintaan_lab', $idPermintaanLab);

        /** @var list<array<string, mixed>> $hasilRows */
        $hasilRows = $this->model->guarded_get($builder, 'fetchItemTerpilih')->getResultArray();

        return array_map(static fn(array $row) => [
            'id_hasil_pa'           => $row['id_hasil_pa'] ?? null,
            'id_permintaan_pa_item' => $row['id_permintaan_pa_item'] ?? null,
            'kode_periksa'          => $row['kode_periksa'] ?? null,
            'nama_item'             => $row['nama_item'] ?? null,
            'diagnosa_klinis'       => $row['diagnosa_klinis'] ?? null,
            'makroskopik'           => $row['makroskopik'] ?? null,
            'mikroskopik'           => $row['mikroskopik'] ?? null,
            'kesimpulan'            => $row['kesimpulan'] ?? null,
            'kesan'                 => $row['kesan'] ?? null,
        ], $hasilRows);
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchItemUntukForm(int $idPermintaanLab): array
    {
        $builder = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_pa_item pai')
            ->select([
                'pai.id_permintaan_pa_item',
                'ri.kode_periksa',
                'ri.nama_item',
                'h.diagnosa_klinis',
                'h.makroskopik',
                'h.mikroskopik',
                'h.kesimpulan',
                'h.kesan',
            ])
            ->join('laboratorium.ref_item_pemeriksaan_lab ri', 'ri.id_item_lab = pai.id_item_pemeriksaan')
            ->join(
                'laboratorium.hasil_lab_pa h',
                'h.id_permintaan_pa_item = pai.id_permintaan_pa_item AND h.id_permintaan_lab = pai.id_permintaan_lab',
                'left',
            )
            ->where('pai.id_permintaan_lab', $idPermintaanLab)
            ->orderBy('pai.id_permintaan_pa_item', 'ASC');

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchItemUntukForm')->getResultArray();
    }

    // Sebuah item PA dianggap "lengkap" hanya bila semua field wajibnya terisi —
    // laporan PA yang setengah jadi (mis. makroskopik tanpa mikroskopik) tidak disimpan.
    /** @param array<string, mixed> $item */
    private function isItemLengkap(array $item): bool
    {
        foreach (['diagnosa_klinis', 'makroskopik', 'mikroskopik', 'kesimpulan'] as $field) {
            if (trim((string) ($item[$field] ?? '')) === '') {
                return false;
            }
        }
        return true;
    }

    /** @param list<array<string, mixed>> $hasilList */
    private function validateHasilList(array $hasilList): null|string
    {
        foreach ($hasilList as $item) {
            if ($this->isItemLengkap($item)) {
                return null;
            }
        }
        return 'Isi minimal satu hasil pemeriksaan lengkap (Diagnosa Klinis, Makroskopik, Mikroskopik, Kesimpulan) sebelum menyimpan.';
    }

    /**
     * @param list<array<string, mixed>> $hasilList
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \ReflectionException
     */
    private function upsertHasilPaItems(
        array $hasilList,
        int $idPermintaanLab,
        null|int $idDokterPj,
        null|int $idPetugasLab,
        string $tglJamHasil,
    ): void {
        /** @var array<int, array<string, mixed>> $existingByItem */
        $existingByItem = array_column(
            $this->model->set_filter('id_permintaan_lab', $idPermintaanLab)->findAll(),
            null,
            'id_permintaan_pa_item',
        );

        foreach ($hasilList as $item) {
            $idItem = (int) ($item['id_permintaan_pa_item'] ?? 0);
            if ($idItem <= 0 || !$this->isItemLengkap($item)) {
                continue;
            }

            $kesan = trim((string) ($item['kesan'] ?? ''));

            $data = [
                'id_dokter_pj'    => $idDokterPj,
                'id_petugas_lab'  => $idPetugasLab,
                'tgl_jam_hasil'   => $tglJamHasil,
                'diagnosa_klinis' => trim((string) ($item['diagnosa_klinis'] ?? '')),
                'makroskopik'     => trim((string) ($item['makroskopik'] ?? '')),
                'mikroskopik'     => trim((string) ($item['mikroskopik'] ?? '')),
                'kesimpulan'      => trim((string) ($item['kesimpulan'] ?? '')),
                'kesan'           => $kesan ? $kesan : null,
            ];

            if (array_key_exists($idItem, $existingByItem)) {
                $this->model->update((int) ($existingByItem[$idItem]['id_hasil_pa'] ?? 0), $data);
                continue;
            }
            $this->model->insert(
                $data
                + [
                    'id_permintaan_lab'     => $idPermintaanLab,
                    'id_permintaan_pa_item' => $idItem,
                ],
            );
        }
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function recomputeStatusPermintaan(int $idPermintaanLab): void
    {
        $total = $this->model
            ->db
            ->table('laboratorium.permintaan_lab_pa_item')
            ->where('id_permintaan_lab', $idPermintaanLab)
            ->countAllResults();

        $filled = $this->model
            ->db
            ->table('laboratorium.hasil_lab_pa')
            ->where('id_permintaan_lab', $idPermintaanLab)
            ->where(
                "TRIM(diagnosa_klinis) <> '' AND TRIM(makroskopik) <> '' AND TRIM(mikroskopik) <> '' AND TRIM(kesimpulan) <> ''",
                null,
                false,
            )
            ->countAllResults();

        $status = $total > 0 && $filled >= $total ? 3 : 2;

        $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header')
            ->where('id_permintaan', $idPermintaanLab)
            ->set('id_status_permintaan', $status)
            ->update();
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function deleteHasilPaByPermintaan(int $idPermintaanLab): void
    {
        $this->model->db->table('laboratorium.hasil_lab_pa')->where('id_permintaan_lab', $idPermintaanLab)->delete();
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function create_page(): string
    {
        return view('admin/laboratorium/tambah_hasil_pa', [
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

        if (!$idPermintaanLab) {
            session()->setFlashdata('error', 'Permintaan laboratorium wajib dipilih.');
            return redirect()->back()->withInput();
        }

        $err = $this->validateHasilList($hasilList);
        if ($err) {
            session()->setFlashdata('error', $err);
            return redirect()->back()->withInput();
        }

        $this->model->db->transStart();

        try {
            $this->upsertHasilPaItems($hasilList, $idPermintaanLab, $idDokterPj, $idPetugasLab, $tglJamHasil);
            $this->recomputeStatusPermintaan($idPermintaanLab);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menyimpan hasil lab PA.');
            }

            session()->setFlashdata('success', 'Hasil Lab PA berhasil disimpan.');
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

        return view('admin/laboratorium/tambah_hasil_pa', [
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

        if (!$idPermintaanLab) {
            session()->setFlashdata('error', 'Permintaan laboratorium wajib dipilih.');
            return redirect()->back()->withInput();
        }

        $err = $this->validateHasilList($hasilList);
        if ($err) {
            session()->setFlashdata('error', $err);
            return redirect()->back()->withInput();
        }

        $this->model->db->transStart();

        try {
            $this->upsertHasilPaItems($hasilList, $idPermintaanLab, $idDokterPj, $idPetugasLab, $tglJamHasil);
            $this->recomputeStatusPermintaan($idPermintaanLab);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal memperbarui hasil lab PA.');
            }

            session()->setFlashdata('success', 'Hasil Lab PA berhasil diperbarui.');
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
    // MODAL LIST — item permintaan digabung dengan hasil yang sudah ada
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
    // DELETE — hapus semua baris hasil PA per permintaan
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

        $this->model->db->transStart();

        try {
            $this->deleteHasilPaByPermintaan($idPermintaanLab);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menghapus hasil lab PA.');
            }

            session()->setFlashdata('success', 'Hasil Lab PA berhasil dihapus.');
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
            . ' FROM laboratorium.hasil_lab_pa'
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

        return view('Views/components/cetak/cetak_hasil_lab_pa', [
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
