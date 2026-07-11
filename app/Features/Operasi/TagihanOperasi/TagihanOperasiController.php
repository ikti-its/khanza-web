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
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX, 'id_tagihan',      'ID Tagihan'],
                [SHOW,      OPTIONAL, I::INDEX, 'id_jadwal',       'Jadwal Operasi'],
                [SHOW,      OPTIONAL, I::INDEX, 'id_kategori',     'Kategori Operasi'],
                [FORM_ONLY, OPTIONAL, I::DATE,  'tanggal_mulai',   'Tgl. Mulai'],
                [FORM_ONLY, OPTIONAL, I::TEXT,  'jenis_anestesi',  'Jenis Anestesi'],
                [FORM_ONLY, OPTIONAL, I::DATE,  'tanggal_selesai', 'Tanggal Selesai'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Private Helpers — Fetch
    // -------------------------------------------------------------------------

    private function fetchJadwal(int $idJadwal): array
    {
        return $this->model
            ->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.tanggal',
                'j.waktu_mulai',
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
            ->where('j.id_jadwal', $idJadwal)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchTimJadwal(int $idJadwal): array
    {
        return $this->model
            ->db
            ->table('operasi.jadwal_operasi_tim jt')
            ->select('jt.id_dokter, jt.id_petugas, rp.kode AS peran, COALESCE(od.nama, op.nama) AS nama', false)
            ->join('operasi.ref_peran_tim_medis rp', 'rp.id_peran = jt.id_peran', 'left')
            ->join('role.dokter d', 'd.id_dokter   = jt.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang   = d.id_orang', 'left')
            ->join('role.petugas pt', 'pt.id_petugas = jt.id_petugas', 'left')
            ->join('person.orang op', 'op.id_orang   = pt.id_orang', 'left')
            ->where('jt.id_jadwal', $idJadwal)
            ->get()
            ->getResultArray();
    }

    private function fetchKategori(): array
    {
        return $this->model
            ->db
            ->table('operasi.ref_kategori_operasi')
            ->select(['id_kategori', 'nama_kategori'])
            ->orderBy('id_kategori', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function fetchPaketTagihan(int $idTagihan): array
    {
        return $this->model
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
            ->where('tt.id_tagihan', $idTagihan)
            ->get()
            ->getResultArray();
    }

    private function fetchPaketByTindakan(int $idTindakan): array
    {
        return $this->model
            ->db
            ->table('operasi.paket_tindakan_operasi p')
            ->select(['p.id_paket', 'p.id_tindakan', 'k.nama_komponen', 'p.tarif_kelas_3 AS tarif', 'ti.nama_tindakan'])
            ->join('operasi.ref_komponen_jasa k', 'k.id_komponen  = p.id_komponen', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan = p.id_tindakan', 'left')
            ->where('p.id_tindakan', $idTindakan)
            ->orderBy('p.id_komponen', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function fetchObat(int $idTagihan): array
    {
        return $this->model
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
            ->where('o.id_tagihan', $idTagihan)
            ->get()
            ->getResultArray();
    }

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
            'id_ast_operator_1',
            'id_ast_operator_2',
            'id_ast_operator_3',
        ];
        $petugasCols = [
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

        $dokterIds  = array_values(array_filter(array_map(fn($k) => $baris[$k] ?? null, $dokterCols)));
        $petugasIds = array_values(array_filter(array_map(fn($k) => $baris[$k] ?? null, $petugasCols)));

        $dokterNames  = [];
        $petugasNames = [];

        if ($dokterIds) {
            foreach ($this->model
                ->db
                ->table('role.dokter d')
                ->select(['d.id_dokter', 'o.nama'])
                ->join('person.orang o', 'o.id_orang = d.id_orang', 'left')
                ->whereIn('d.id_dokter', $dokterIds)
                ->get()
                ->getResultArray() as $row) {
                $dokterNames[(int) $row['id_dokter']] = $row['nama'];
            }
        }

        if ($petugasIds) {
            foreach ($this->model
                ->db
                ->table('role.petugas pt')
                ->select(['pt.id_petugas', 'o.nama'])
                ->join('person.orang o', 'o.id_orang = pt.id_orang', 'left')
                ->whereIn('pt.id_petugas', $petugasIds)
                ->get()
                ->getResultArray() as $row) {
                $petugasNames[(int) $row['id_petugas']] = $row['nama'];
            }
        }

        $names = [];
        foreach ($dokterCols as $col) {
            $id                               = $baris[$col] ?? null;
            $names['nama_' . substr($col, 3)] = $id ? $dokterNames[(int) $id] ?? '' : '';
        }
        foreach ($petugasCols as $col) {
            $id                               = $baris[$col] ?? null;
            $names['nama_' . substr($col, 3)] = $id ? $petugasNames[(int) $id] ?? '' : '';
        }

        return $names;
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    final public function create_page(): string
    {
        $idJadwal = (int) ($this->request->getGet('id_jadwal') ?? 0);
        $jadwal   = $idJadwal ? $this->fetchJadwal($idJadwal) : [];

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
                $peran = $anggota['peran'] ?? '';
                if ($peran === '' || $peran === null)
                    continue;
                $jadwal['id_' . $peran]   = $anggota['id_dokter'] ?: $anggota['id_petugas'];
                $jadwal['nama_' . $peran] = $anggota['nama'] ?? '';
            }
        }

        return view('admin/operasi/tagihan_operasi_form', [
            'judul'          => 'Buat Tagihan Operasi',
            'breadcrumbs'    => [...$this->breadcrumbs, ['title' => 'Buat', 'icon' => 'tambah']],
            'modul_path'     => $this->get_uri_path(),
            'form_action'    => '/submittambah',
            'baris'          => $jadwal,
            'paket_terpilih' => $paketTerpilih,
            'obat'           => [],
            'kategori'       => $this->fetchKategori(),
        ]);
    }

    #[\Override]
    final public function update_page(int|string $id): string
    {
        $baris = $this->model->find($id) ?? [];

        if (!empty($baris['id_jadwal'])) {
            $jadwal = $this->fetchJadwal((int) $baris['id_jadwal']);
            $names  = $this->resolveTimMedisNames($baris);
            $baris  = [...$baris, ...$jadwal, ...$names];
        }

        return view('admin/operasi/tagihan_operasi_form', [
            'judul'          => 'Ubah Tagihan Operasi',
            'breadcrumbs'    => [...$this->breadcrumbs, ['title' => 'Ubah', 'icon' => 'ubah']],
            'modul_path'     => $this->get_uri_path(),
            'kolom_id'       => $this->model->primaryKey,
            'form_action'    => "/submitedit/{$id}",
            'baris'          => $baris,
            'paket_terpilih' => $this->fetchPaketTagihan((int) $id),
            'obat'           => $this->fetchObat((int) $id),
            'kategori'       => $this->fetchKategori(),
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();
        $data    = $this->buildData($rawPost);

        try {
            $this->model->db->transStart();

            $idTagihan = $this->model->insert($data);

            if ($this->model->db->transStatus() !== false) {
                $this->savePaket((int) $idTagihan, $rawPost['paket'] ?? []);
            }
            if ($this->model->db->transStatus() !== false) {
                $this->saveObat((int) $idTagihan, $rawPost['obat'] ?? []);
            }

            // Ambil pesan error DB sebelum transComplete()/ROLLBACK menghapus jejaknya.
            $dbErrorMsg = $this->model->db->transStatus() === false
                ? ($this->model->db->error()['message'] ?: 'Gagal menyimpan tagihan operasi.')
                : null;

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
        if ($id == 0)
            return $this->home();

        $rawPost = $this->request->getPost();
        $data    = $this->buildData($rawPost);

        try {
            $this->model->db->transStart();

            $this->model->update($id, $data);

            if ($this->model->db->transStatus() !== false) {
                $this->model->db->table('operasi.tagihan_operasi_tindakan')->where('id_tagihan', $id)->delete();
            }
            if ($this->model->db->transStatus() !== false) {
                $this->savePaket((int) $id, $rawPost['paket'] ?? []);
            }
            if ($this->model->db->transStatus() !== false) {
                $this->model->db->table('operasi.tagihan_operasi_obat')->where('id_tagihan', $id)->delete();
            }
            if ($this->model->db->transStatus() !== false) {
                $this->saveObat((int) $id, $rawPost['obat'] ?? []);
            }

            // Ambil pesan error DB sebelum transComplete()/ROLLBACK menghapus jejaknya.
            $dbErrorMsg = $this->model->db->transStatus() === false
                ? ($this->model->db->error()['message'] ?: 'Gagal memperbarui tagihan operasi.')
                : null;

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

    // -------------------------------------------------------------------------
    // Private Save Helpers
    // -------------------------------------------------------------------------

    private function buildData(array $post): array
    {
        return [
            'id_jadwal'          => $post['id_jadwal'] ?? null,
            'id_kategori'        => $post['id_kategori'] ?? null,
            'jenis_anestesi'     => $post['jenis_anestesi'] ?? null,
            'tanggal_mulai'      => $post['tanggal_mulai'] ?: null,
            'tanggal_selesai'    => $post['tanggal_selesai'] ?: null,
            'diagnosis_pre'      => $post['diagnosis_pre'] ?? null,
            'diagnosis_post'     => $post['diagnosis_post'] ?? null,
            'jaringan'           => $post['jaringan'] ?? null,
            'laporan'            => $post['laporan'] ?? null,
            'is_pa'              => isset($post['is_pa']),
            'id_operator_1'      => $post['id_operator_1'] ?: null,
            'id_operator_2'      => $post['id_operator_2'] ?: null,
            'id_operator_3'      => $post['id_operator_3'] ?: null,
            'id_dokter_anestesi' => $post['id_dokter_anestesi'] ?: null,
            'id_dokter_anak'     => $post['id_dokter_anak'] ?: null,
            'id_dokter_pj_anak'  => $post['id_dokter_pj_anak'] ?: null,
            'id_dokter_umum'     => $post['id_dokter_umum'] ?: null,
            'id_ast_operator_1'  => $post['id_ast_operator_1'] ?: null,
            'id_ast_operator_2'  => $post['id_ast_operator_2'] ?: null,
            'id_ast_operator_3'  => $post['id_ast_operator_3'] ?: null,
            'id_bidan_1'         => $post['id_bidan_1'] ?: null,
            'id_bidan_2'         => $post['id_bidan_2'] ?: null,
            'id_bidan_3'         => $post['id_bidan_3'] ?: null,
            'id_perawat_luar'    => $post['id_perawat_luar'] ?: null,
            'id_instrumen'       => $post['id_instrumen'] ?: null,
            'id_ast_anestesi_1'  => $post['id_ast_anestesi_1'] ?: null,
            'id_ast_anestesi_2'  => $post['id_ast_anestesi_2'] ?: null,
            'id_perawat_resus'   => $post['id_perawat_resus'] ?: null,
            'id_onloop_1'        => $post['id_onloop_1'] ?: null,
            'id_onloop_2'        => $post['id_onloop_2'] ?: null,
            'id_onloop_3'        => $post['id_onloop_3'] ?: null,
            'id_onloop_4'        => $post['id_onloop_4'] ?: null,
            'id_onloop_5'        => $post['id_onloop_5'] ?: null,
        ];
    }

    private function savePaket(int $idTagihan, array $paketList): void
    {
        foreach ($paketList as $paket) {
            if (empty($paket['id_paket']))
                continue;
            $this->model
                ->db
                ->table('operasi.tagihan_operasi_tindakan')
                ->insert([
                    'id_tagihan' => $idTagihan,
                    'id_paket'   => (int) $paket['id_paket'],
                ]);
            if ($this->model->db->transStatus() === false)
                return;
        }
    }

    private function saveObat(int $idTagihan, array $obatList): void
    {
        foreach ($obatList as $obat) {
            if (empty($obat['id_barang']) || empty($obat['jumlah']))
                continue;
            $this->model
                ->db
                ->table('operasi.tagihan_operasi_obat')
                ->insert([
                    'id_tagihan' => $idTagihan,
                    'id_barang'  => (int) $obat['id_barang'],
                    'jumlah'     => (int) $obat['jumlah'],
                ]);
            if ($this->model->db->transStatus() === false)
                return;
        }
    }
}
