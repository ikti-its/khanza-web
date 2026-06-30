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
                ['Operasi',          'operasi'],
                ['Tagihan Operasi',  'tagihan_operasi'],
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
        return $this->model->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.tanggal',
                'j.waktu_mulai',
                'j.waktu_selesai',
                'po.nomor_reg',
                'po.id_tindakan',
                'op.nama AS nama_pasien',
                'ti.kode_tindakan',
                'ti.nama_tindakan',
                'ti.tarif_kelas_3 AS tarif',
                'ob.nama AS nama_dokter_bedah',
            ])
            ->join('operasi.permintaan_operasi po',      'po.id_permintaan = j.id_permintaan',  'left')
            ->join('registrasi.registrasi r',           'r.nomor_reg      = po.nomor_reg',     'left')
            ->join('role.pasien p',                      'p.id_pasien      = r.id_pasien',      'left')
            ->join('person.orang op',                    'op.id_orang      = p.id_orang',       'left')
            ->join('operasi.ref_tindakan_operasi ti',    'ti.id_tindakan   = po.id_tindakan',   'left')
            ->join('role.dokter db',                     'db.id_dokter     = j.id_dokter_bedah', 'left')
            ->join('person.orang ob',                    'ob.id_orang      = db.id_orang',      'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchKategori(): array
    {
        return $this->model->db
            ->table('operasi.ref_kategori_operasi')
            ->select(['id_kategori', 'nama_kategori'])
            ->orderBy('id_kategori', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function fetchTindakanTagihan(int $idTagihan): array
    {
        return $this->model->db
            ->table('operasi.tagihan_operasi_tindakan tt')
            ->select(['tt.id_tindakan', 'r.kode_tindakan', 'r.nama_tindakan', 'r.tarif_kelas_3 AS tarif'])
            ->join('operasi.ref_tindakan_operasi r', 'r.id_tindakan = tt.id_tindakan', 'left')
            ->where('tt.id_tagihan', $idTagihan)
            ->get()
            ->getResultArray();
    }

    private function fetchObat(int $idTagihan): array
    {
        return $this->model->db
            ->table('operasi.tagihan_operasi_obat o')
            ->select(['o.id_detail', 'o.id_barang', 'b.kode_barang', 'b.nama AS nama_barang', 'o.jumlah', 'b.h_dasar AS harga'])
            ->join('inventori_medis.data_barang b', 'b.id_barang = o.id_barang', 'left')
            ->where('o.id_tagihan', $idTagihan)
            ->get()
            ->getResultArray();
    }

    private function resolveTimMedisNames(array $baris): array
    {
        $dokterCols = [
            'id_operator_1', 'id_operator_2', 'id_operator_3',
            'id_dokter_anestesi', 'id_dokter_anak', 'id_dokter_pj_anak', 'id_dokter_umum',
            'id_ast_operator_1', 'id_ast_operator_2', 'id_ast_operator_3',
        ];
        $petugasCols = [
            'id_bidan_1', 'id_bidan_2', 'id_bidan_3',
            'id_perawat_luar', 'id_instrumen',
            'id_ast_anestesi_1', 'id_ast_anestesi_2',
            'id_perawat_resus',
            'id_onloop_1', 'id_onloop_2', 'id_onloop_3', 'id_onloop_4', 'id_onloop_5',
        ];

        $dokterIds  = array_values(array_filter(array_map(fn($k) => $baris[$k] ?? null, $dokterCols)));
        $petugasIds = array_values(array_filter(array_map(fn($k) => $baris[$k] ?? null, $petugasCols)));

        $dokterNames  = [];
        $petugasNames = [];

        if ($dokterIds) {
            foreach ($this->model->db->table('role.dokter d')
                ->select(['d.id_dokter', 'o.nama'])
                ->join('person.orang o', 'o.id_orang = d.id_orang', 'left')
                ->whereIn('d.id_dokter', $dokterIds)
                ->get()->getResultArray() as $row) {
                $dokterNames[(int) $row['id_dokter']] = $row['nama'];
            }
        }

        if ($petugasIds) {
            foreach ($this->model->db->table('role.petugas pt')
                ->select(['pt.id_petugas', 'o.nama'])
                ->join('person.orang o', 'o.id_orang = pt.id_orang', 'left')
                ->whereIn('pt.id_petugas', $petugasIds)
                ->get()->getResultArray() as $row) {
                $petugasNames[(int) $row['id_petugas']] = $row['nama'];
            }
        }

        $names = [];
        foreach ($dokterCols as $col) {
            $id = $baris[$col] ?? null;
            $names['nama_' . substr($col, 3)] = $id ? ($dokterNames[(int) $id] ?? '') : '';
        }
        foreach ($petugasCols as $col) {
            $id = $baris[$col] ?? null;
            $names['nama_' . substr($col, 3)] = $id ? ($petugasNames[(int) $id] ?? '') : '';
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

        // Pre-fill tindakan utama dari jadwal
        $tindakanTerpilih = [];
        if (!empty($jadwal['id_tindakan'])) {
            $tindakanTerpilih = [[
                'id_tindakan'   => $jadwal['id_tindakan'],
                'kode_tindakan' => $jadwal['kode_tindakan'] ?? '',
                'nama_tindakan' => $jadwal['nama_tindakan'] ?? '',
                'tarif'         => $jadwal['tarif'] ?? 0,
            ]];
        }

        return view('admin/operasi/tagihan_operasi_form', [
            'judul'             => 'Buat Tagihan Operasi',
            'breadcrumbs'       => [...$this->breadcrumbs, ['title' => 'Buat', 'icon' => 'tambah']],
            'modul_path'        => $this->get_uri_path(),
            'form_action'       => '/submittambah',
            'baris'             => $jadwal,
            'tindakan_terpilih' => $tindakanTerpilih,
            'obat'              => [],
            'kategori'          => $this->fetchKategori(),
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
            'judul'             => 'Edit Tagihan Operasi',
            'breadcrumbs'       => [...$this->breadcrumbs, ['title' => 'Edit', 'icon' => 'ubah']],
            'modul_path'        => $this->get_uri_path(),
            'kolom_id'          => $this->model->primaryKey,
            'form_action'       => "/submitedit/{$id}",
            'baris'             => $baris,
            'tindakan_terpilih' => $this->fetchTindakanTagihan((int) $id),
            'obat'              => $this->fetchObat((int) $id),
            'kategori'          => $this->fetchKategori(),
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

            $this->saveTindakan((int) $idTagihan, $rawPost['tindakan'] ?? []);
            $this->saveObat((int) $idTagihan, $rawPost['obat'] ?? []);

            $this->model->db->transComplete();

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
        if ($id == 0) return $this->home();

        $rawPost = $this->request->getPost();
        $data    = $this->buildData($rawPost);

        try {
            $this->model->db->transStart();

            $this->model->update($id, $data);

            $this->model->db->table('operasi.tagihan_operasi_tindakan')->where('id_tagihan', $id)->delete();
            $this->saveTindakan((int) $id, $rawPost['tindakan'] ?? []);

            $this->model->db->table('operasi.tagihan_operasi_obat')->where('id_tagihan', $id)->delete();
            $this->saveObat((int) $id, $rawPost['obat'] ?? []);

            $this->model->db->transComplete();

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
            'id_jadwal'          => $post['id_jadwal']          ?? null,
            'id_kategori'        => $post['id_kategori']        ?? null,
            'jenis_anestesi'     => $post['jenis_anestesi']     ?? null,
            'tanggal_mulai'      => $post['tanggal_mulai']      ?: null,
            'tanggal_selesai'    => $post['tanggal_selesai']    ?: null,
            'diagnosis_pre'      => $post['diagnosis_pre']      ?? null,
            'diagnosis_post'     => $post['diagnosis_post']     ?? null,
            'jaringan'           => $post['jaringan']           ?? null,
            'laporan'            => $post['laporan']            ?? null,
            'is_pa'              => isset($post['is_pa']),
            'id_operator_1'      => $post['id_operator_1']      ?: null,
            'id_operator_2'      => $post['id_operator_2']      ?: null,
            'id_operator_3'      => $post['id_operator_3']      ?: null,
            'id_dokter_anestesi' => $post['id_dokter_anestesi'] ?: null,
            'id_dokter_anak'     => $post['id_dokter_anak']     ?: null,
            'id_dokter_pj_anak'  => $post['id_dokter_pj_anak']  ?: null,
            'id_dokter_umum'     => $post['id_dokter_umum']     ?: null,
            'id_ast_operator_1'  => $post['id_ast_operator_1']  ?: null,
            'id_ast_operator_2'  => $post['id_ast_operator_2']  ?: null,
            'id_ast_operator_3'  => $post['id_ast_operator_3']  ?: null,
            'id_bidan_1'         => $post['id_bidan_1']         ?: null,
            'id_bidan_2'         => $post['id_bidan_2']         ?: null,
            'id_bidan_3'         => $post['id_bidan_3']         ?: null,
            'id_perawat_luar'    => $post['id_perawat_luar']    ?: null,
            'id_instrumen'       => $post['id_instrumen']       ?: null,
            'id_ast_anestesi_1'  => $post['id_ast_anestesi_1']  ?: null,
            'id_ast_anestesi_2'  => $post['id_ast_anestesi_2']  ?: null,
            'id_perawat_resus'   => $post['id_perawat_resus']   ?: null,
            'id_onloop_1'        => $post['id_onloop_1']        ?: null,
            'id_onloop_2'        => $post['id_onloop_2']        ?: null,
            'id_onloop_3'        => $post['id_onloop_3']        ?: null,
            'id_onloop_4'        => $post['id_onloop_4']        ?: null,
            'id_onloop_5'        => $post['id_onloop_5']        ?: null,
        ];
    }

    private function saveTindakan(int $idTagihan, array $tindakanList): void
    {
        foreach ($tindakanList as $tindakan) {
            if (empty($tindakan['id_tindakan'])) continue;
            $this->model->db->table('operasi.tagihan_operasi_tindakan')->insert([
                'id_tagihan'  => $idTagihan,
                'id_tindakan' => (int) $tindakan['id_tindakan'],
            ]);
        }
    }

    private function saveObat(int $idTagihan, array $obatList): void
    {
        foreach ($obatList as $obat) {
            if (empty($obat['id_barang']) || empty($obat['jumlah'])) continue;
            $this->model->db->table('operasi.tagihan_operasi_obat')->insert([
                'id_tagihan' => $idTagihan,
                'id_barang'  => (int) $obat['id_barang'],
                'jumlah'     => (int) $obat['jumlah'],
            ]);
        }
    }
}