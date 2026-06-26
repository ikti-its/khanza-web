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
                [HIDE, OPTIONAL, I::INDEX, 'id_tagihan',      'ID Tagihan'],
                [HIDE, OPTIONAL, I::INDEX, 'id_jadwal',       'ID Jadwal'],
                [HIDE, OPTIONAL, I::INDEX, 'id_kategori',     'ID Kategori'],
                [SHOW, OPTIONAL, I::TEXT,  'nomor_reg',       'No. Registrasi'],
                [SHOW, OPTIONAL, I::TEXT,  'nama',            'Nama Pasien'],
                [SHOW, OPTIONAL, I::TEXT,  'nama_tindakan',   'Tindakan'],
                [SHOW, OPTIONAL, I::TEXT,  'nama_kategori',   'Kategori'],
                [SHOW, OPTIONAL, I::DATE,  'tanggal_mulai',   'Tgl. Mulai'],
                [HIDE, OPTIONAL, I::TEXT,  'jenis_anestesi',  'Jenis Anestesi'],
                [HIDE, OPTIONAL, I::DATE,  'tanggal_selesai', 'Tanggal Selesai'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Private Helpers
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
                'ti.nama_tindakan',
                'ob.nama AS nama_dokter_bedah',
            ])
            ->join('operasi.permintaan_operasi po',      'po.id_permintaan = j.id_permintaan',  'left')
            ->join('rekam_medis.registrasi r',           'r.nomor_reg      = po.nomor_reg',     'left')
            ->join('role.pasien p',                      'p.id_pasien      = r.id_pasien',      'left')
            ->join('person.orang op',                    'op.id_orang      = p.id_orang',       'left')
            ->join('operasi.ref_tindakan_operasi ti',    'ti.id_tindakan   = po.id_tindakan',   'left')
            ->join('role.dokter db',                     'db.id_dokter     = j.id_dokter_bedah', 'left')
            ->join('person.orang ob',                    'ob.id_orang      = db.id_orang',      'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchPaket(int $idTagihan): array
    {
        return $this->model->db
            ->table('operasi.tagihan_operasi_paket tp')
            ->select(['tp.id_tagihan_paket', 'tp.id_paket', 'rk.nama_komponen', 'p.tarif_kelas_3', 'p.tarif_kelas_2', 'p.tarif_kelas_1', 'p.tarif_vip', 'p.tarif_vvip'])
            ->join('operasi.paket_tindakan_operasi p',  'p.id_paket    = tp.id_paket',    'left')
            ->join('operasi.ref_komponen_jasa rk',      'rk.id_komponen = p.id_komponen', 'left')
            ->where('tp.id_tagihan', $idTagihan)
            ->get()
            ->getResultArray();
    }

    private function fetchObat(int $idTagihan): array
    {
        return $this->model->db
            ->table('operasi.tagihan_operasi_obat o')
            ->select(['o.id_detail', 'o.id_barang', 'b.nama AS nama_barang', 'o.jumlah', 'o.harga_satuan'])
            ->join('inventori_medis.data_barang b', 'b.id_barang = o.id_barang', 'left')
            ->where('o.id_tagihan', $idTagihan)
            ->get()
            ->getResultArray();
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    final public function create_page(): string
    {
        return view('admin/operasi/tagihan_operasi_form', [
            'judul'       => 'Buat Tagihan Operasi',
            'breadcrumbs' => [...$this->breadcrumbs, ['title' => 'Buat', 'icon' => 'tambah']],
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submitcreate',
            'baris'       => [],
            'paket'       => [],
            'obat'        => [],
        ]);
    }

    #[\Override]
    final public function update_page(int|string $id): string
    {
        $baris = $this->model->find($id) ?? [];

        if (!empty($baris['id_jadwal'])) {
            $baris = array_merge($baris, $this->fetchJadwal((int) $baris['id_jadwal']));
        }

        return view('admin/operasi/tagihan_operasi_form', [
            'judul'       => 'Edit Tagihan Operasi',
            'breadcrumbs' => [...$this->breadcrumbs, ['title' => 'Edit', 'icon' => 'ubah']],
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'form_action' => "/submitedit/{$id}",
            'baris'       => $baris,
            'paket'       => $this->fetchPaket((int) $id),
            'obat'        => $this->fetchObat((int) $id),
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $data = $this->buildData($rawPost);

        try {
            $this->model->db->transStart();

            $idTagihan = $this->model->insert($data);

            $this->savePaket((int) $idTagihan, $rawPost['id_paket'] ?? []);
            $this->saveObat((int) $idTagihan, $rawPost['obat'] ?? []);

            $this->model->db->transComplete();

            session()->setFlashdata('success', 'Tagihan operasi berhasil dibuat.');
            return redirect()->to($this->get_uri_path() . '/data');

        } catch (\Exception $e) {
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $rawPost = $this->request->getPost();

        $data = $this->buildData($rawPost);

        try {
            $this->model->db->transStart();

            $this->model->update($id, $data);

            $this->model->db->table('operasi.tagihan_operasi_paket')->where('id_tagihan', $id)->delete();
            $this->savePaket((int) $id, $rawPost['id_paket'] ?? []);

            $this->model->db->table('operasi.tagihan_operasi_obat')->where('id_tagihan', $id)->delete();
            $this->saveObat((int) $id, $rawPost['obat'] ?? []);

            $this->model->db->transComplete();

            session()->setFlashdata('success', 'Tagihan operasi berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');

        } catch (\Exception $e) {
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
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
            'tanggal_mulai'      => $post['tanggal_mulai']      ?? null,
            'tanggal_selesai'    => $post['tanggal_selesai']    ?? null,
            'diagnosis_pre'      => $post['diagnosis_pre']      ?? null,
            'diagnosis_post'     => $post['diagnosis_post']     ?? null,
            'jaringan'           => $post['jaringan']           ?? null,
            'laporan'            => $post['laporan']            ?? null,
            'is_pa'              => $post['is_pa']              ?? false,
            'id_operator_1'      => $post['id_operator_1']      ?? null,
            'id_operator_2'      => $post['id_operator_2']      ?? null,
            'id_operator_3'      => $post['id_operator_3']      ?? null,
            'id_dokter_anestesi' => $post['id_dokter_anestesi'] ?? null,
            'id_dokter_anak'     => $post['id_dokter_anak']     ?? null,
            'id_dokter_pj_anak'  => $post['id_dokter_pj_anak'] ?? null,
            'id_dokter_umum'     => $post['id_dokter_umum']     ?? null,
            'id_ast_operator_1'  => $post['id_ast_operator_1']  ?? null,
            'id_ast_operator_2'  => $post['id_ast_operator_2']  ?? null,
            'id_ast_operator_3'  => $post['id_ast_operator_3']  ?? null,
            'id_bidan_1'         => $post['id_bidan_1']         ?? null,
            'id_bidan_2'         => $post['id_bidan_2']         ?? null,
            'id_bidan_3'         => $post['id_bidan_3']         ?? null,
            'id_perawat_luar'    => $post['id_perawat_luar']    ?? null,
            'id_instrumen'       => $post['id_instrumen']       ?? null,
            'id_ast_anestesi_1'  => $post['id_ast_anestesi_1']  ?? null,
            'id_ast_anestesi_2'  => $post['id_ast_anestesi_2']  ?? null,
            'id_perawat_resus'   => $post['id_perawat_resus']   ?? null,
            'id_onloop_1'        => $post['id_onloop_1']        ?? null,
            'id_onloop_2'        => $post['id_onloop_2']        ?? null,
            'id_onloop_3'        => $post['id_onloop_3']        ?? null,
            'id_onloop_4'        => $post['id_onloop_4']        ?? null,
            'id_onloop_5'        => $post['id_onloop_5']        ?? null,
        ];
    }

    private function savePaket(int $idTagihan, array $idPaketList): void
    {
        foreach ($idPaketList as $idPaket) {
            if (!$idPaket) continue;
            $this->model->db->table('operasi.tagihan_operasi_paket')->insert([
                'id_tagihan' => $idTagihan,
                'id_paket'   => (int) $idPaket,
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