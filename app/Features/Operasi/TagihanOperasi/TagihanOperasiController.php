<?php
declare(strict_types=1);

namespace App\Features\Operasi\TagihanOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class TagihanOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TagihanOperasiModel(),
            [
                ['Operasi',         'operasi'],
                ['Tagihan Operasi', 'tagihan_operasi'],
            ],
            'Tagihan Operasi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX, 'id_tagihan',      'ID Tagihan'],
                [SHOW,       OPTIONAL, I::INDEX, 'id_jadwal',       'Jadwal Operasi'],
                [SHOW,       OPTIONAL, I::INDEX, 'id_kategori',     'Kategori Operasi'],
                [TABLE_ONLY, OPTIONAL, I::DTIME, 'tanggal_mulai',   'Tgl. Mulai'],
                [TABLE_ONLY, OPTIONAL, I::DTIME, 'tanggal_selesai', 'Tanggal Selesai'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,  'jenis_anestesi',  'Jenis Anestesi'],
                [TABLE_ONLY, OPTIONAL, I::MONEY, 'total_tagihan',   'Total Tagihan'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Private Helpers — Fetch
    // -------------------------------------------------------------------------

    /**
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchJadwal(int $idJadwal): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.tanggal',
                'j.waktu_mulai',
                'j.tanggal_selesai AS jadwal_tanggal_selesai',
                'j.waktu_selesai',
                'j.id_dokter_bedah',
                'j.id_dokter_anestesi',
                'po.nomor_reg',
                'po.id_tindakan',
                'op.nama AS nama_pasien',
                'ti.kode_tindakan',
                'ti.nama_tindakan',
                'ti.tarif_kelas_3 AS tarif',
                'ob.nama AS nama_dokter_bedah',
                'oa.nama AS nama_dokter_anestesi',
            ])
            ->join('operasi.permintaan_operasi po', 'po.id_permintaan  = j.id_permintaan', 'left')
            ->join('registrasi.registrasi r', 'r.nomor_reg       = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien       = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang       = p.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan    = po.id_tindakan', 'left')
            ->join('role.dokter db', 'db.id_dokter      = j.id_dokter_bedah', 'left')
            ->join('person.orang ob', 'ob.id_orang       = db.id_orang', 'left')
            ->join('role.dokter da', 'da.id_dokter      = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa', 'oa.id_orang       = da.id_orang', 'left')
            ->where('j.id_jadwal', $idJadwal);

        /** @var array<string, mixed> */
        return $this->model->guarded_get($builder, 'fetchJadwal')->getRowArray() ?? [];
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchTimJadwal(int $idJadwal): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.jadwal_operasi_tim jt')
            ->select('jt.id_dokter, jt.id_petugas, rp.kode AS peran, COALESCE(od.nama, op.nama) AS nama', false)
            ->join('operasi.ref_peran_tim_medis rp', 'rp.id_peran = jt.id_peran', 'left')
            ->join('role.dokter d', 'd.id_dokter   = jt.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang   = d.id_orang', 'left')
            ->join('role.petugas pt', 'pt.id_petugas = jt.id_petugas', 'left')
            ->join('person.orang op', 'op.id_orang   = pt.id_orang', 'left')
            ->where('jt.id_jadwal', $idJadwal);

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchTimJadwal')->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchKategori(): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.ref_kategori_operasi')
            ->select(['id_kategori', 'nama_kategori'])
            ->orderBy('id_kategori', 'ASC');

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchKategori')->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchTemplateLaporan(): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.ref_template_laporan_operasi')
            ->select(['id_template', 'nama_template', 'isi_template'])
            ->orderBy('nama_template', 'ASC');

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchTemplateLaporan')->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchPaketTagihan(int $idTagihan): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.tagihan_operasi_tindakan tt')
            ->select([
                'tt.id_paket',
                'p.id_tindakan',
                'k.nama_komponen',
                'p.tarif_kelas_3 AS tarif',
                'ti.nama_tindakan',
            ])
            ->join('operasi.paket_tindakan_operasi p', 'p.id_paket = tt.id_paket', 'left')
            ->join('operasi.ref_komponen_jasa k', 'k.id_komponen = p.id_komponen', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan = p.id_tindakan', 'left')
            ->where('tt.id_tagihan', $idTagihan);

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchPaketTagihan')->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchPaketByTindakan(int $idTindakan): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.paket_tindakan_operasi p')
            ->select(['p.id_paket', 'p.id_tindakan', 'k.nama_komponen', 'p.tarif_kelas_3 AS tarif', 'ti.nama_tindakan'])
            ->join('operasi.ref_komponen_jasa k', 'k.id_komponen  = p.id_komponen', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan = p.id_tindakan', 'left')
            ->where('p.id_tindakan', $idTindakan)
            ->orderBy('p.id_komponen', 'ASC');

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchPaketByTindakan')->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchObat(int $idTagihan): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.tagihan_operasi_obat o')
            ->select([
                'o.id_detail',
                'o.id_barang',
                'b.kode_barang',
                'b.nama AS nama_barang',
                'o.jumlah',
                'b.h_dasar AS harga',
            ])
            ->join('inventori_medis.data_barang b', 'b.id_barang = o.id_barang', 'left')
            ->where('o.id_tagihan', $idTagihan);

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchObat')->getResultArray();
    }

    /**
     * Total tagihan dibekukan saat simpan, tidak dihitung ulang dari tarif referensi terkini.
     *
     * @param list<array<string, mixed>> $paketList
     * @param list<array<string, mixed>> $obatList
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function computeTotal(array $paketList, array $obatList): float
    {
        $idPaket = array_values(array_filter(array_map(
            static fn(array $p) => (int) ($p['id_paket'] ?? 0),
            $paketList,
        )));

        $totalPaket = 0.0;
        if ($idPaket) {
            $sumBuilder = $this->model
                ->db
                ->table('operasi.paket_tindakan_operasi')
                ->selectSum('tarif_kelas_3')
                ->whereIn('id_paket', $idPaket);

            $tarifRow   = $this->model->guarded_get($sumBuilder, 'computeTotal')->getRowArray() ?? [];
            $totalPaket = is_numeric($tarifRow['tarif_kelas_3'] ?? null) ? (float) $tarifRow['tarif_kelas_3'] : 0.0;
        }

        $jumlahByBarang = [];
        foreach ($obatList as $obat) {
            if (empty($obat['id_barang']) || empty($obat['jumlah'])) {
                continue;
            }
            $jumlahByBarang[(int) $obat['id_barang']] = (int) $obat['jumlah'];
        }

        $totalObat = 0.0;
        if ($jumlahByBarang) {
            $hargaBuilder = $this->model
                ->db
                ->table('inventori_medis.data_barang')
                ->select(['id_barang', 'h_dasar'])
                ->whereIn('id_barang', array_keys($jumlahByBarang));

            /** @var list<array<string, mixed>> $hargaRows */
            $hargaRows = $this->model->guarded_get($hargaBuilder, 'computeTotal')->getResultArray();
            foreach ($hargaRows as $row) {
                $totalObat +=
                    (is_numeric($row['h_dasar'] ?? null) ? (float) $row['h_dasar'] : 0.0)
                    * $jumlahByBarang[(int) ($row['id_barang'] ?? 0)];
            }
        }

        return $totalPaket + $totalObat;
    }

    /**
     * @param array<string, mixed> $baris
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function resolveTimMedisNames(array $baris): array
    {
        $dokterCols = [
            'id_operator_1',
            'id_operator_2',
            'id_operator_3',
            'id_dokter_anestesi',
            'id_dokter_anak',
            'id_dokter_pj_anak',
            'id_dokter_umum',
        ];
        $petugasCols = [
            'id_ast_operator_1',
            'id_ast_operator_2',
            'id_ast_operator_3',
            'id_bidan_1',
            'id_bidan_2',
            'id_bidan_3',
            'id_perawat_luar',
            'id_instrumen',
            'id_ast_anestesi_1',
            'id_ast_anestesi_2',
            'id_perawat_resus',
            'id_onloop_1',
            'id_onloop_2',
            'id_onloop_3',
            'id_onloop_4',
            'id_onloop_5',
        ];

        $dokterIds  = array_values(array_filter(array_map(static fn($k) => $baris[$k] ?? null, $dokterCols)));
        $petugasIds = array_values(array_filter(array_map(static fn($k) => $baris[$k] ?? null, $petugasCols)));

        $dokterNames  = [];
        $petugasNames = [];

        if ($dokterIds) {
            $dokterBuilder = $this->model
                ->db
                ->table('role.dokter d')
                ->select(['d.id_dokter', 'o.nama'])
                ->join('person.orang o', 'o.id_orang = d.id_orang', 'left')
                ->whereIn('d.id_dokter', $dokterIds);

            /** @var list<array<string, mixed>> $dokterRows */
            $dokterRows = $this->model->guarded_get($dokterBuilder, 'resolveTimMedisNames')->getResultArray();
            foreach ($dokterRows as $row) {
                $dokterNames[(int) ($row['id_dokter'] ?? 0)] = (string) ($row['nama'] ?? '');
            }
        }

        if ($petugasIds) {
            $petugasBuilder = $this->model
                ->db
                ->table('role.petugas pt')
                ->select(['pt.id_petugas', 'o.nama'])
                ->join('person.orang o', 'o.id_orang = pt.id_orang', 'left')
                ->whereIn('pt.id_petugas', $petugasIds);

            /** @var list<array<string, mixed>> $petugasRows */
            $petugasRows = $this->model->guarded_get($petugasBuilder, 'resolveTimMedisNames')->getResultArray();
            foreach ($petugasRows as $row) {
                $petugasNames[(int) ($row['id_petugas'] ?? 0)] = (string) ($row['nama'] ?? '');
            }
        }

        $names = [];
        foreach ($dokterCols as $col) {
            $names['nama_' . substr($col, 3)] = !empty($baris[$col]) ? $dokterNames[(int) $baris[$col]] ?? '' : '';
        }
        foreach ($petugasCols as $col) {
            $names['nama_' . substr($col, 3)] = !empty($baris[$col]) ? $petugasNames[(int) $baris[$col]] ?? '' : '';
        }

        return $names;
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function create_page(): string
    {
        $idJadwal = (int) ($this->request->getGet('id_jadwal') ?? 0);
        $jadwal   = $idJadwal ? $this->fetchJadwal($idJadwal) : [];

        // Pre-fill tanggal mulai/selesai dari jadwal (tanggal + jam), tetap bisa diedit user
        if (!empty($jadwal['tanggal']) && !empty($jadwal['waktu_mulai'])) {
            $jadwal['tanggal_mulai'] = (string) $jadwal['tanggal'] . ' ' . (string) $jadwal['waktu_mulai'];
        }
        if (!empty($jadwal['tanggal']) && !empty($jadwal['waktu_selesai'])) {
            $tanggalSelesai = $jadwal['jadwal_tanggal_selesai'] ?? null
                ? (string) $jadwal['jadwal_tanggal_selesai']
                : (string) $jadwal['tanggal'];
            $jadwal['tanggal_selesai'] = $tanggalSelesai . ' ' . (string) $jadwal['waktu_selesai'];
        }

        // Pre-fill paket komponen dari tindakan utama jadwal
        $paketTerpilih = [];
        if (!empty($jadwal['id_tindakan'])) {
            $paketTerpilih = $this->fetchPaketByTindakan((int) $jadwal['id_tindakan']);
        }

        // Mapping dokter jadwal → field tagihan
        $jadwal['id_operator_1']   = $jadwal['id_dokter_bedah'] ?? null;
        $jadwal['nama_operator_1'] = $jadwal['nama_dokter_bedah'] ?? '';
        // id_dokter_anestesi dan nama_dokter_anestesi sudah same key, langsung tersedia

        // Tim medis tambahan dari jadwal → field tagihan sesuai kode peran
        if ($idJadwal) {
            foreach ($this->fetchTimJadwal($idJadwal) as $anggota) {
                $peran = (string) ($anggota['peran'] ?? '');
                if ($peran === '') {
                    continue;
                }
                $jadwal['id_' . $peran] = !empty($anggota['id_dokter'])
                    ? $anggota['id_dokter']
                    : $anggota['id_petugas'] ?? null;
                $jadwal['nama_' . $peran] = $anggota['nama'] ?? '';
            }
        }

        return view('admin/operasi/tagihan_operasi_form', [
            'judul'            => 'Buat Tagihan Operasi',
            'breadcrumbs'      => [...$this->breadcrumbs, ['title' => 'Buat', 'icon' => 'tambah']],
            'modul_path'       => $this->get_uri_path(),
            'form_action'      => '/submittambah',
            'baris'            => $jadwal,
            'paket_terpilih'   => $paketTerpilih,
            'obat'             => [],
            'kategori'         => $this->fetchKategori(),
            'template_laporan' => $this->fetchTemplateLaporan(),
        ]);
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        $baris = $this->model->find_one($id);
        if (!is_array($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        if (!empty($baris['id_jadwal'])) {
            $jadwal = $this->fetchJadwal((int) $baris['id_jadwal']);
            $names  = $this->resolveTimMedisNames($baris);
            $baris  = [...$baris, ...$jadwal, ...$names];
        }

        return view('admin/operasi/tagihan_operasi_form', [
            'judul'            => 'Ubah Tagihan Operasi',
            'breadcrumbs'      => [...$this->breadcrumbs, ['title' => 'Ubah', 'icon' => 'ubah']],
            'modul_path'       => $this->get_uri_path(),
            'kolom_id'         => $this->model->primaryKey,
            'form_action'      => "/submitedit/{$id}",
            'baris'            => $baris,
            'paket_terpilih'   => $this->fetchPaketTagihan((int) $id),
            'obat'             => $this->fetchObat((int) $id),
            'kategori'         => $this->fetchKategori(),
            'template_laporan' => $this->fetchTemplateLaporan(),
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        /** @var array<string, mixed> $rawPost */
        $rawPost = $this->request->getPost();
        /** @var list<array<string, mixed>> $paketList */
        $paketList = $rawPost['paket'] ?? [];
        /** @var list<array<string, mixed>> $obatList */
        $obatList = $rawPost['obat'] ?? [];

        try {
            $this->validateObatList($obatList);
            $data = $this->buildData($rawPost, $paketList, $obatList);

            $this->model->db->transStart();

            $idTagihan = $this->model->insert($data);

            if ($this->model->db->transStatus()) {
                $this->savePaket((int) $idTagihan, $paketList);
            }
            if ($this->model->db->transStatus()) {
                $this->saveObat((int) $idTagihan, $obatList);
            }

            // Ambil pesan error DB sebelum transComplete()/ROLLBACK menghapus jejaknya.
            $dbErrorMsg = null;
            if (!$this->model->db->transStatus()) {
                $dbErrorMsg = $this->model->db->error()['message'] ?? '';
                if ($dbErrorMsg === '') {
                    $dbErrorMsg = 'Gagal menyimpan tagihan operasi.';
                }
            }

            $this->model->db->transComplete();

            if ($dbErrorMsg !== null) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException($dbErrorMsg);
            }

            session()->setFlashdata('success', 'Tagihan operasi berhasil dibuat.');
            return redirect()->to($this->get_uri_path() . '/data');
        } catch (\Exception $e) {
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
        if ((int) $id === 0) {
            return $this->home();
        }

        /** @var array<string, mixed> $rawPost */
        $rawPost = $this->request->getPost();
        /** @var list<array<string, mixed>> $paketList */
        $paketList = $rawPost['paket'] ?? [];
        /** @var list<array<string, mixed>> $obatList */
        $obatList = $rawPost['obat'] ?? [];

        try {
            $this->validateObatList($obatList);
            $data = $this->buildData($rawPost, $paketList, $obatList);

            $this->model->db->transStart();

            $this->model->update($id, $data);

            if ($this->model->db->transStatus()) {
                $this->model->db->table('operasi.tagihan_operasi_tindakan')->where('id_tagihan', $id)->delete();
            }
            if ($this->model->db->transStatus()) {
                $this->savePaket((int) $id, $paketList);
            }
            if ($this->model->db->transStatus()) {
                $this->model->db->table('operasi.tagihan_operasi_obat')->where('id_tagihan', $id)->delete();
            }
            if ($this->model->db->transStatus()) {
                $this->saveObat((int) $id, $obatList);
            }

            // Ambil pesan error DB sebelum transComplete()/ROLLBACK menghapus jejaknya.
            $dbErrorMsg = null;
            if (!$this->model->db->transStatus()) {
                $dbErrorMsg = $this->model->db->error()['message'] ?? '';
                if ($dbErrorMsg === '') {
                    $dbErrorMsg = 'Gagal memperbarui tagihan operasi.';
                }
            }

            $this->model->db->transComplete();

            if ($dbErrorMsg !== null) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException($dbErrorMsg);
            }

            session()->setFlashdata('success', 'Tagihan operasi berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');
        } catch (\Exception $e) {
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
        if ((int) $id === 0) {
            return $this->home();
        }

        try {
            $this->model->db->transStart();

            $this->model->db->table('operasi.tagihan_operasi_tindakan')->where('id_tagihan', $id)->delete();
            $this->model->db->table('operasi.tagihan_operasi_obat')->where('id_tagihan', $id)->delete();
            $this->model->delete($id);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menghapus tagihan operasi.');
            }

            session()->setFlashdata('success', 'Tagihan operasi berhasil dihapus.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return $this->home();
    }

    // -------------------------------------------------------------------------
    // Private Save Helpers
    // -------------------------------------------------------------------------

    /**
     * @param array<string, mixed> $post
     * @param list<array<string, mixed>> $paketList
     * @param list<array<string, mixed>> $obatList
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function buildData(array $post, array $paketList, array $obatList): array
    {
        return [
            'id_jadwal'           => $post['id_jadwal'] ?? null,
            'id_kategori'         => $post['id_kategori'] ?? null,
            'jenis_anestesi'      => $post['jenis_anestesi'] ?? null,
            'tanggal_mulai'       => $post['tanggal_mulai'] ?? null ? $post['tanggal_mulai'] : null,
            'tanggal_selesai'     => $post['tanggal_selesai'] ?? null ? $post['tanggal_selesai'] : null,
            'total_tagihan'       => $this->computeTotal($paketList, $obatList),
            'diagnosis_pre'       => $post['diagnosis_pre'] ?? null,
            'diagnosis_post'      => $post['diagnosis_post'] ?? null,
            'jaringan'            => $post['jaringan'] ?? null,
            'laporan'             => $post['laporan'] ?? null,
            'id_template_laporan' => $post['id_template_laporan'] ?? null ? $post['id_template_laporan'] : null,
            'is_pa'               => array_key_exists('is_pa', $post),
            'id_operator_1'       => $post['id_operator_1'] ?? null ? $post['id_operator_1'] : null,
            'id_operator_2'       => $post['id_operator_2'] ?? null ? $post['id_operator_2'] : null,
            'id_operator_3'       => $post['id_operator_3'] ?? null ? $post['id_operator_3'] : null,
            'id_dokter_anestesi'  => $post['id_dokter_anestesi'] ?? null ? $post['id_dokter_anestesi'] : null,
            'id_dokter_anak'      => $post['id_dokter_anak'] ?? null ? $post['id_dokter_anak'] : null,
            'id_dokter_pj_anak'   => $post['id_dokter_pj_anak'] ?? null ? $post['id_dokter_pj_anak'] : null,
            'id_dokter_umum'      => $post['id_dokter_umum'] ?? null ? $post['id_dokter_umum'] : null,
            'id_ast_operator_1'   => $post['id_ast_operator_1'] ?? null ? $post['id_ast_operator_1'] : null,
            'id_ast_operator_2'   => $post['id_ast_operator_2'] ?? null ? $post['id_ast_operator_2'] : null,
            'id_ast_operator_3'   => $post['id_ast_operator_3'] ?? null ? $post['id_ast_operator_3'] : null,
            'id_bidan_1'          => $post['id_bidan_1'] ?? null ? $post['id_bidan_1'] : null,
            'id_bidan_2'          => $post['id_bidan_2'] ?? null ? $post['id_bidan_2'] : null,
            'id_bidan_3'          => $post['id_bidan_3'] ?? null ? $post['id_bidan_3'] : null,
            'id_perawat_luar'     => $post['id_perawat_luar'] ?? null ? $post['id_perawat_luar'] : null,
            'id_instrumen'        => $post['id_instrumen'] ?? null ? $post['id_instrumen'] : null,
            'id_ast_anestesi_1'   => $post['id_ast_anestesi_1'] ?? null ? $post['id_ast_anestesi_1'] : null,
            'id_ast_anestesi_2'   => $post['id_ast_anestesi_2'] ?? null ? $post['id_ast_anestesi_2'] : null,
            'id_perawat_resus'    => $post['id_perawat_resus'] ?? null ? $post['id_perawat_resus'] : null,
            'id_onloop_1'         => $post['id_onloop_1'] ?? null ? $post['id_onloop_1'] : null,
            'id_onloop_2'         => $post['id_onloop_2'] ?? null ? $post['id_onloop_2'] : null,
            'id_onloop_3'         => $post['id_onloop_3'] ?? null ? $post['id_onloop_3'] : null,
            'id_onloop_4'         => $post['id_onloop_4'] ?? null ? $post['id_onloop_4'] : null,
            'id_onloop_5'         => $post['id_onloop_5'] ?? null ? $post['id_onloop_5'] : null,
        ];
    }

    /**
     * @param list<array<string, mixed>> $paketList
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function savePaket(int $idTagihan, array $paketList): void
    {
        foreach ($paketList as $paket) {
            if (empty($paket['id_paket'])) {
                continue;
            }
            $this->model
                ->db
                ->table('operasi.tagihan_operasi_tindakan')
                ->insert([
                    'id_tagihan' => $idTagihan,
                    'id_paket'   => (int) $paket['id_paket'],
                ]);
            if (!$this->model->db->transStatus()) {
                return;
            }
        }
    }

    /**
     * @param list<array<string, mixed>> $obatList
     * @throws \RuntimeException
     */
    private function validateObatList(array $obatList): void
    {
        foreach ($obatList as $obat) {
            if (empty($obat['id_barang'])) {
                continue;
            }
            if (($obat['jumlah'] ?? null) === null || $obat['jumlah'] === '') {
                continue;
            }
            if (!is_numeric($obat['jumlah']) || (int) $obat['jumlah'] <= 0) {
                throw new \RuntimeException('Jumlah obat/BHP harus berupa angka positif.');
            }
        }
    }

    /**
     * @param list<array<string, mixed>> $obatList
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function saveObat(int $idTagihan, array $obatList): void
    {
        foreach ($obatList as $obat) {
            if (empty($obat['id_barang']) || empty($obat['jumlah'])) {
                continue;
            }
            $this->model
                ->db
                ->table('operasi.tagihan_operasi_obat')
                ->insert([
                    'id_tagihan' => $idTagihan,
                    'id_barang'  => (int) $obat['id_barang'],
                    'jumlah'     => (int) $obat['jumlah'],
                ]);
            if (!$this->model->db->transStatus()) {
                return;
            }
        }
    }
}
