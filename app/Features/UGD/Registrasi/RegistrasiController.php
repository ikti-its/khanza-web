<?php
declare(strict_types=1);

namespace App\Features\UGD\Registrasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;

final class RegistrasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RegistrasiModel(),
            [
                ['UGD',        'ugd'],
                ['Registrasi', 'registrasi'],
            ],
            'Registrasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_registrasi',     'ID Registrasi'],
                [SHOW, REQUIRED, I::INDEX,  'nomor_reg',         'Nomor Registrasi'],
                [SHOW, REQUIRED, I::INDEX,  'nomor_rawat',       'Nomor Rawat'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_reg',       'Tanggal Registrasi'],
                [SHOW, REQUIRED, I::INDEX,  'id_pasien',         'ID Pasien'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter',         'ID Dokter'],
                [HIDE, REQUIRED, I::INDEX,  'id_pj_pasien',      'ID Penanggung Jawab'],
                [SHOW, REQUIRED, I::SELECT, 'hubungan_pj',       'Hubungan Penanggung Jawab'],
                [HIDE, REQUIRED, I::INDEX,  'id_alamat_pj',      'Alamat Penanggung Jawab'],
                [HIDE, REQUIRED, I::MONEY,  'biaya_registrasi',  'Biaya Registrasi'],
                [SHOW, REQUIRED, I::SELECT, 'status_rawat',      'Status Rawat'],
                [SHOW, REQUIRED, I::SELECT, 'jenis_bayar',       'Jenis Bayar'],
                [SHOW, REQUIRED, I::SELECT, 'status_bayar',      'Status Bayar'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_triase',  'Status Triase'],
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
            fn($f) => !in_array($f[2], ['id_registrasi', 'nomor_reg', 'nomor_rawat', 'id_pasien', 'id_dokter', 'id_status_triase'], true)
        ));
    }

    private function generateNomorReg(): string
    {
        helper('autonomor');

        $lastNo = $this->model->db
            ->table('registrasi.registrasi')
            ->select('nomor_reg')
            ->like('nomor_reg', 'REG-' . date('Ymd'), 'after')
            ->orderBy('nomor_reg', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return generateNextNoRegistrasi($lastNo['nomor_reg'] ?? null);
    }

    private function generateNomorRawat(): string
    {
        helper('autonomor');

        $lastNo = $this->model->db
            ->table('registrasi.registrasi')
            ->select('nomor_rawat')
            ->orderBy('nomor_rawat', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return generateNextNoRawat($lastNo['nomor_rawat'] ?? null);
    }

    private function fetchPasienByRm(string $noRm): ?array
    {
        $row = $this->model->db
            ->table('role.pasien p')
            ->select('p.id_pasien, p.nomor_rm, o.nama')
            ->join('person.orang o', 'o.id_orang = p.id_orang')
            ->where('p.nomor_rm', $noRm)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function buildPostData(): array
    {
        return [
            'tanggal_reg'       => $this->request->getPost('tanggal_reg'),
            'id_pasien'         => (int) $this->request->getPost('id_pasien'),
            'id_dokter'         => (int) $this->request->getPost('id_dokter'),
            'id_pj_pasien'      => (int) $this->request->getPost('id_pj_pasien'),
            'id_alamat_pj'      => (int) $this->request->getPost('id_alamat_pj'),
            'hubungan_pj'       => (int) $this->request->getPost('hubungan_pj'),
            'biaya_registrasi'  => (float) $this->request->getPost('biaya_registrasi'),
            'jenis_bayar'       => (int) $this->request->getPost('jenis_bayar'),
            'status_rawat'      => (int) $this->request->getPost('status_rawat'),
            'status_bayar'      => (int) $this->request->getPost('status_bayar'),
        ];
    }

    // ──────────────────────────────────────────────────────────
    // PAGES
    // ──────────────────────────────────────────────────────────

    #[\Override]
    final public function create_page(): string
    {
        $ugdUnit = $this->model->db
            ->table('unit.unit')
            ->select('biaya_registrasi_baru')
            ->where('id_unit', 22)
            ->get()
            ->getRowArray();

        $mockBaris = [
            'id_registrasi'     => '',
            'nomor_reg'         => $this->generateNomorReg(),
            'nomor_rawat'       => $this->generateNomorRawat(),
            'tanggal_reg'       => date('Y-m-d H:i:s'),
            'id_pasien'         => '',
            'id_dokter'         => '',
            'id_pj_pasien'      => '',
            'id_alamat_pj'      => '',
            'hubungan_pj'       => '',
            'biaya_default'     => (int) ($ugdUnit['biaya_registrasi_baru'] ?? 0),
            'status_rawat'      => '',
            'jenis_bayar'       => '',
            'status_bayar'      => '',
        ];

        $dataPasien = null;
        $noRm = $this->request->getGet('no_rm') ?? '';
        if ($noRm !== '') {
            $dataPasien = $this->fetchPasienByRm($noRm);
            if ($dataPasien) {
                $mockBaris['id_pasien'] = $dataPasien['id_pasien'];
            }
        }

        $mockBaris['redirect_to'] = $this->request->getGet('redirect_to') ?? '';
        
        return view('admin/ugd/tambah_ugd_registrasi', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->getKonfig(),
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
            'data_pasien' => $dataPasien,
        ]);
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $data = $this->model->find_one($id);
        if (!$data) return $this->home();

        $dataPasien = [
            'nomor_rm' => $data['nomor_rm'] ?? '',
            'nama'     => $data['id_pasien_nama'] ?? '',
        ];

        return view('admin/ugd/tambah_ugd_registrasi', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->getKonfig(),
            'baris'       => $data,
            'form_action' => '/submitedit/' . $id,
            'data_pasien' => $dataPasien,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // PROSES SIMPAN
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function create(): string|RedirectResponse
    {
        helper('autonomor');

        $lastRmReg = $this->model->db
            ->table('registrasi.registrasi')
            ->select('nomor_reg')
            ->like('nomor_reg', 'REG-' . date('Ymd'), 'after')
            ->orderBy('nomor_reg', 'DESC')
            ->limit(1)->get()->getRowArray();

        $lastRmRawat = $this->model->db
            ->table('registrasi.registrasi')
            ->select('nomor_rawat')
            ->orderBy('nomor_rawat', 'DESC')
            ->limit(1)->get()->getRowArray();

        $nomorReg   = generateNextNoRegistrasi($lastRmReg['nomor_reg'] ?? null);
        $nomorRawat = generateNextNoRawat($lastRmRawat['nomor_rawat'] ?? null);

        $shared = $this->buildPostData();
        $ugdData = array_merge($shared, ['nomor_reg' => $nomorReg, 'nomor_rawat' => $nomorRawat, 'id_status_triase'  => 1]);
        $rmData  = array_merge($shared, [
            'nomor_reg'         => $nomorReg,
            'nomor_rawat'       => $nomorRawat,
            'unit'              => 22,
            'no_telepon'        => null,
            'status_registrasi' => null,
            'status_poli'       => null,
        ]);

        $db = $this->model->db;
        $db->transBegin();
        try {
            $this->model->insert($ugdData);
            $db->table('registrasi.registrasi')->insert($rmData);

            if ($db->transStatus() === false) {
                $db->transRollback();
                session()->setFlashdata('error', 'Gagal menyimpan data registrasi.');
                return redirect()->back()->withInput();
            }

            $db->transCommit();
            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil disimpan.');
            $redirect_to = $this->request->getPost('redirect_to');
            return $redirect_to ? redirect()->to($redirect_to) : $this->home();
        } catch (\ReflectionException | DatabaseException $e) {
            $db->transRollback();
            $msg = $e instanceof DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $current = $this->model->find($id);
        if (!is_array($current)) return $this->home();

        $shared  = $this->buildPostData();
        $ugdData = $shared;
        $rmData  = array_merge($shared, [
            'unit'              => 22,
            'no_telepon'        => null,
            'status_registrasi' => null,
            'status_poli'       => null,
        ]);

        $db = $this->model->db;
        $db->transBegin();
        try {
            $this->model->update($id, $ugdData);
            $db->table('registrasi.registrasi')
                ->where('nomor_reg', $current['nomor_reg'])
                ->update($rmData);

            if ($db->transStatus() === false) {
                $db->transRollback();
                session()->setFlashdata('error', 'Gagal memperbarui data registrasi.');
                return redirect()->back()->withInput();
            }

            $db->transCommit();
            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil diperbarui.');
            return $this->home();
        } catch (\ReflectionException | DatabaseException $e) {
            $db->transRollback();
            $msg = $e instanceof DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $current = $this->model->find($id);
        if (!is_array($current)) return $this->home();

        $db = $this->model->db;
        $db->transBegin();
        try {
            $db->table('registrasi.registrasi')
                ->where('nomor_reg', $current['nomor_reg'])
                ->delete();
            $this->model->delete($id);

            if ($db->transStatus() === false) {
                $db->transRollback();
                session()->setFlashdata('error', 'Gagal menghapus data registrasi.');
                return $this->home();
            }

            $db->transCommit();
            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil dihapus.');
        } catch (DatabaseException $e) {
            $db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
        }

        return $this->home();
    }

    // ──────────────────────────────────────────────────────────
    // List
    // ──────────────────────────────────────────────────────────
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model->builder($tabel . ' r')
            ->select([
                'r.id_registrasi',
                'r.nomor_reg',
                'r.nomor_rawat',
                'r.tanggal_reg',
                'r.id_pasien',
                'r.id_dokter',
                'p.nomor_rm',
                'o.nama AS nama_pasien',
                'o.tanggal_lahir',
                'od.nama AS nama_dokter'
            ])
            ->join('role.pasien p',   'p.id_pasien = r.id_pasien', 'inner')
            ->join('person.orang o',  'o.id_orang  = p.id_orang', 'inner')
            ->join('role.dokter d',   'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang',  'left')
            ->orderBy('r.tanggal_reg', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
