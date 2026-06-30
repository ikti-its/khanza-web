<?php
declare(strict_types=1);

namespace App\Features\Registrasi\Registrasi;

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
                ['Registrasi',  'registrasi'],
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
                [SHOW, REQUIRED, I::TEXT,   'nomor_reg',         'Nomor Registrasi'],
                [SHOW, REQUIRED, I::TEXT,   'nomor_rawat',       'Nomor Rawat'],
                [SHOW, REQUIRED, I::SELECT, 'id_dokter',         'Dokter'],
                [SHOW, REQUIRED, I::SELECT, 'id_pasien',         'Pasien'],
                [SHOW, REQUIRED, I::SELECT, 'unit',              'Unit'],
                [SHOW, REQUIRED, I::SELECT, 'id_pj_pasien',      'Penanggung Jawab'],
                [SHOW, REQUIRED, I::SELECT, 'id_alamat_pj',      'Alamat PJ'],
                [SHOW, REQUIRED, I::SELECT, 'hubungan_pj',       'Hubungan PJ'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_reg',       'Tanggal Registrasi'],
                [SHOW, OPTIONAL, I::TEXT,   'no_telepon',        'No. Telepon Pasien'],
                [SHOW, OPTIONAL, I::MONEY,  'biaya_registrasi',  'Biaya Registrasi'],
                [SHOW, REQUIRED, I::SELECT, 'jenis_bayar',       'Jenis Bayar'],
                [SHOW, REQUIRED, I::SELECT, 'status_registrasi', 'Status Registrasi'],
                [SHOW, REQUIRED, I::SELECT, 'status_rawat',      'Status Rawat'],
                [SHOW, REQUIRED, I::SELECT, 'status_poli',       'Status Poliklinik'],
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
            fn($f) => !in_array($f[2], [
                'id_registrasi', 'nomor_reg', 'nomor_rawat',
                'id_dokter', 'id_pasien', 'unit', 'id_pj_pasien', 'id_alamat_pj',
                'biaya_registrasi',
            ], true)
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

    private function fetchUnitById(int $id): ?array
    {
        $row = $this->model->db
            ->table('unit.unit')
            ->select('id_unit, nama_unit, biaya_registrasi_baru')
            ->where('id_unit', $id)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    private function buildPostData(): array
    {
        return [
            'nomor_reg'         => $this->request->getPost('nomor_reg'),
            'nomor_rawat'       => $this->request->getPost('nomor_rawat'),
            'tanggal_reg'       => $this->request->getPost('tanggal_reg'),
            'id_dokter'         => (int) $this->request->getPost('id_dokter'),
            'id_pasien'         => (int) $this->request->getPost('id_pasien'),
            'unit'              => (int) $this->request->getPost('unit'),
            'id_pj_pasien'      => (int) $this->request->getPost('id_pj_pasien'),
            'id_alamat_pj'      => (int) $this->request->getPost('id_alamat_pj'),
            'hubungan_pj'       => (int) $this->request->getPost('hubungan_pj'),
            'no_telepon'        => $this->request->getPost('no_telepon') ?: null,
            'biaya_registrasi'  => (float) $this->request->getPost('biaya_registrasi'),
            'jenis_bayar'       => (int) $this->request->getPost('jenis_bayar'),
            'status_registrasi' => ($v = $this->request->getPost('status_registrasi')) ? (int) $v : null,
            'status_rawat'      => (int) $this->request->getPost('status_rawat'),
            'status_poli'       => ($v = $this->request->getPost('status_poli')) ? (int) $v : null,
            'status_bayar'      => (int) $this->request->getPost('status_bayar'),
        ];
    }

    // ──────────────────────────────────────────────────────────
    // PAGES
    // ──────────────────────────────────────────────────────────

    #[\Override]
    final public function create_page(): string
    {
        $baris = [
            'nomor_reg'         => $this->generateNomorReg(),
            'nomor_rawat'       => $this->generateNomorRawat(),
            'tanggal_reg'       => date('Y-m-d H:i:s'),
            'id_pasien'         => '',
            'nomor_rm'          => '',
            'id_pasien_nama'    => '',
            'hubungan_pj'       => '',
            'no_telepon'        => '',
            'jenis_bayar'       => '',
            'status_registrasi' => '',
            'status_rawat'      => '',
            'status_poli'       => '',
            'status_bayar'      => '',
        ];

        $baris['redirect_to'] = $this->request->getGet('redirect_to') ?? '';

        $noRm = $this->request->getGet('no_rm') ?? '';
        if ($noRm !== '') {
            $pasien = $this->fetchPasienByRm($noRm);
            if ($pasien) {
                $baris['id_pasien']      = $pasien['id_pasien'];
                $baris['nomor_rm']       = $pasien['nomor_rm'];
                $baris['id_pasien_nama'] = $pasien['nama'];
            }
        }

        $unitId = (int) ($this->request->getGet('unit') ?? 0);
        if ($unitId > 0) {
            $unit = $this->fetchUnitById($unitId);
            if ($unit) {
                $baris['unit']             = $unit['id_unit'];
                $baris['nama_unit']        = $unit['nama_unit'];
                $baris['biaya_registrasi'] = $unit['biaya_registrasi_baru'];
            }
        }

        return view('admin/rekam_medis/tambah_rm_registrasi', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'konfig'      => $this->getKonfig(),
            'baris'       => $baris,
            'form_action' => '/submittambah',
        ]);
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $data = $this->model->find_one($id);
        if (!$data) return $this->home();

        return view('admin/rekam_medis/tambah_rm_registrasi', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'konfig'      => $this->getKonfig(),
            'baris'       => $data,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // PROSES SIMPAN
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function create(): string|RedirectResponse
    {
        try {
            $this->model->insert($this->buildPostData());
            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil disimpan.');
            $redirect_to = $this->request->getPost('redirect_to');
            return $redirect_to ? redirect()->to($redirect_to) : $this->home();
        } catch (\ReflectionException | DatabaseException $e) {
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

        try {
            $this->model->update($id, $this->buildPostData());
            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil diperbarui.');
            return $this->home();
        } catch (\ReflectionException | DatabaseException $e) {
            $msg = $e instanceof DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return redirect()->back()->withInput();
        }
    }

    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model->builder($tabel . ' r')
            ->select([
                'r.id_registrasi',
                'r.nomor_reg',
                'r.nomor_rawat',
                'r.tanggal_reg',
                'p.nomor_rm',
                'o.nama',
                'd.kode_dokter',
                'od.nama AS nama_dokter',
                'r.id_dokter',
                "COALESCE(ri.kamar, '-') AS kamar"
            ])
            ->join('role.pasien p',   'p.id_pasien = r.id_pasien', 'inner')
            ->join('person.orang o',  'o.id_orang  = p.id_orang', 'inner')
            ->join('role.dokter d',   'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang',  'left')
            ->join('rawat_inap.registrasi ri', 'ri.id_registrasi = r.id_registrasi', 'left')
            ->orderBy('r.tanggal_reg', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}
