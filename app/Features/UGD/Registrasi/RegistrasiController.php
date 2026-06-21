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
                [SHOW, REQUIRED, I::DTIME,  'tanggal_kunjungan', 'Tanggal Kunjungan'],
                [SHOW, REQUIRED, I::INDEX,  'id_pasien',         'ID Pasien'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter',         'ID Dokter'],
                [HIDE, REQUIRED, I::INDEX,  'id_pj_pasien',      'ID Penanggung Jawab'],
                [HIDE, REQUIRED, I::TEXT,   'hubungan_pj',       'Hubungan Penanggung Jawab'],
                [HIDE, REQUIRED, I::TEXT,   'alamat_pj',         'Alamat Penanggung Jawab'],
                [HIDE, REQUIRED, I::MONEY,  'biaya_registrasi',  'Biaya Registrasi'],
                [HIDE, REQUIRED, I::TEXT,   'status_rawat',      'Status Rawat'],
                [HIDE, REQUIRED, I::TEXT,   'jenis_bayar',       'Jenis Bayar'],
                [SHOW, REQUIRED, I::SELECT, 'status_bayar',      'Status Bayar'],
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
            fn($f) => !in_array($f[2], ['id_registrasi', 'nomor_reg', 'nomor_rawat', 'id_pasien', 'id_dokter'], true)
        ));
    }

    private function generateNomorReg(): string
    {
        helper('autonomor');

        $lastNo = $this->model->db
            ->table('ugd.registrasi')
            ->select('nomor_reg')
            ->like('nomor_reg', 'UGD' . date('Ymd'), 'after')
            ->orderBy('nomor_reg', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return generateNextNoRegUGD($lastNo['nomor_reg'] ?? null);
    }

    private function generateNomorRawat(): string
    {
        helper('autonomor');

        $lastNo = $this->model->db
            ->table('ugd.registrasi')
            ->select('nomor_rawat')
            ->like('nomor_rawat', date('Ymd'), 'after')
            ->orderBy('nomor_rawat', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return generateNextNoRawatUGD($lastNo['nomor_rawat'] ?? null);
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

    // ──────────────────────────────────────────────────────────
    // PAGES
    // ──────────────────────────────────────────────────────────

    #[\Override]
    final public function create_page(): string
    {
        $mockBaris = [
            'id_registrasi'     => '',
            'nomor_reg'         => $this->generateNomorReg(),
            'nomor_rawat'       => $this->generateNomorRawat(),
            'tanggal_kunjungan' => date('Y-m-d H:i:s'),
            'id_pasien'         => '',
            'id_dokter'         => '',
            'id_pj_pasien'      => '',
            'hubungan_pj'       => '',
            'alamat_pj'         => '',
            'biaya_registrasi'  => '',
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

    // ──────────────────────────────────────────────────────────
    // PROSES SIMPAN
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $idPasien = (int) $this->request->getPost('id_pasien');

        // id_pj_pasien is NOT NULL FK — use patient's own id_orang as placeholder
        $idPjPasien = (int) ($this->model->db
            ->table('role.pasien')
            ->select('id_orang')
            ->where('id_pasien', $idPasien)
            ->get()
            ->getRowArray()['id_orang'] ?? 0);

        $postData = [
            'nomor_reg'         => $this->request->getPost('nomor_reg'),
            'nomor_rawat'       => $this->request->getPost('nomor_rawat'),
            'tanggal_kunjungan' => $this->request->getPost('tanggal_kunjungan'),
            'id_pasien'         => $idPasien,
            'id_dokter'         => (int) $this->request->getPost('id_dokter'),
            'id_pj_pasien'      => $idPjPasien,
            'alamat_pj'         => '',
            'hubungan_pj'       => '',
            'biaya_registrasi'  => 0,
            'status_rawat'      => '',
            'jenis_bayar'       => '',
            'status_bayar'      => (int) $this->request->getPost('status_bayar'),
        ];

        try {
            $this->model->insert($postData);
            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil disimpan.');
            return $this->home();
        } catch (\ReflectionException | DatabaseException $e) {
            $msg = $e instanceof DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return redirect()->back()->withInput();
        }
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
                'r.tanggal_kunjungan',
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
            ->orderBy('r.tanggal_kunjungan', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
