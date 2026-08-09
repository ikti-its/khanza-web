<?php
declare(strict_types=1);

namespace App\Features\Registrasi\Registrasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class RegistrasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RegistrasiModel(),
            [
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

    /** @return list<array<int|string, mixed>> */
    private function getKonfig(): array
    {
        /** @var list<array<int|string, mixed>> */
        return array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn(array $f) => !in_array(
                $f[2] ?? null,
                [
                    'id_registrasi',
                    'nomor_reg',
                    'nomor_rawat',
                    'id_dokter',
                    'id_pasien',
                    'unit',
                    'id_pj_pasien',
                    'id_alamat_pj',
                    'biaya_registrasi',
                ],
                true,
            ),
        ));
    }

    /**
     * @throws DatabaseException
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     */
    private function generateNomorReg(): string
    {
        helper('autonomor');

        $builder = $this->model
            ->db
            ->table('registrasi.registrasi')
            ->select('nomor_reg')
            ->like('nomor_reg', 'REG-' . date('Ymd'), 'after')
            ->orderBy('nomor_reg', 'DESC')
            ->limit(1);

        $lastNo = $this->model->guarded_get($builder, 'generateNomorReg')->getRowArray();

        return generateNextNoRegistrasi(is_string($lastNo['nomor_reg'] ?? null) ? $lastNo['nomor_reg'] : null);
    }

    /**
     * @throws DatabaseException
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     */
    private function generateNomorRawat(): string
    {
        helper('autonomor');

        $builder = $this->model
            ->db
            ->table('registrasi.registrasi')
            ->select('nomor_rawat')
            ->orderBy('nomor_rawat', 'DESC')
            ->limit(1);

        $lastNo = $this->model->guarded_get($builder, 'generateNomorRawat')->getRowArray();

        return generateNextNoRawat(is_string($lastNo['nomor_rawat'] ?? null) ? $lastNo['nomor_rawat'] : null);
    }

    /**
     * @return array<string, mixed>|null
     * @throws DatabaseException
     */
    private function fetchPasienByRm(string $noRm): null|array
    {
        $builder = $this->model
            ->db
            ->table('role.pasien p')
            ->select('p.id_pasien, p.nomor_rm, o.nama')
            ->join('person.orang o', 'o.id_orang = p.id_orang')
            ->where('p.nomor_rm', $noRm);

        $row = $this->model->guarded_get($builder, 'fetchPasienByRm')->getRowArray();

        /** @var array<string, mixed>|null */
        return $row ?: null;
    }

    /**
     * @return array<string, mixed>|null
     * @throws DatabaseException
     */
    private function fetchUnitById(int $id): null|array
    {
        $builder = $this->model
            ->db
            ->table('unit.unit')
            ->select('id_unit, nama_unit, biaya_registrasi_baru')
            ->where('id_unit', $id);

        $row = $this->model->guarded_get($builder, 'fetchUnitById')->getRowArray();

        /** @var array<string, mixed>|null */
        return $row ?: null;
    }

    /** @return array<string, mixed> */
    private function buildPostData(): array
    {
        $biayaRegistrasi  = (string) ($this->request->getPost('biaya_registrasi') ?? '');
        $statusRegistrasi = (string) ($this->request->getPost('status_registrasi') ?? '');
        $statusPoli       = (string) ($this->request->getPost('status_poli') ?? '');

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
            'biaya_registrasi'  => is_numeric($biayaRegistrasi) ? (float) $biayaRegistrasi : 0.0,
            'jenis_bayar'       => (int) $this->request->getPost('jenis_bayar'),
            'status_registrasi' => $statusRegistrasi !== '' ? (int) $statusRegistrasi : null,
            'status_rawat'      => (int) $this->request->getPost('status_rawat'),
            'status_poli'       => $statusPoli !== '' ? (int) $statusPoli : null,
            'status_bayar'      => (int) $this->request->getPost('status_bayar'),
        ];
    }

    // ──────────────────────────────────────────────────────────
    // PAGES
    // ──────────────────────────────────────────────────────────

    /**
     * @throws DatabaseException
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     */
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

        $noRm = (string) ($this->request->getGet('no_rm') ?? '');
        if ($noRm !== '') {
            $pasien = $this->fetchPasienByRm($noRm);
            if ($pasien) {
                $baris['id_pasien']      = $pasien['id_pasien'] ?? '';
                $baris['nomor_rm']       = $pasien['nomor_rm'] ?? '';
                $baris['id_pasien_nama'] = $pasien['nama'] ?? '';
            }
        }

        $unitId = (int) ($this->request->getGet('unit') ?? 0);
        if ($unitId > 0) {
            $unit = $this->fetchUnitById($unitId);
            if ($unit) {
                $baris['unit']             = $unit['id_unit'] ?? '';
                $baris['nama_unit']        = $unit['nama_unit'] ?? '';
                $baris['biaya_registrasi'] = $unit['biaya_registrasi_baru'] ?? 0;
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
    public function update_page(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $data = $this->model->find_one($id);
        if (!$data)
            return $this->home();

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
            $redirect_to = (string) ($this->request->getPost('redirect_to') ?? '');
            return $redirect_to !== '' ? redirect()->to($redirect_to) : $this->home();
        } catch (\ReflectionException|DatabaseException $e) {
            $msg = $e instanceof DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->home();

        try {
            $this->model->update($id, $this->buildPostData());
            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil diperbarui.');
            return $this->home();
        } catch (\ReflectionException|DatabaseException $e) {
            $msg = $e instanceof DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return redirect()->back()->withInput();
        }
    }

    /**
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws DatabaseException
     */
    public function list(): ResponseInterface
    {
        $tabel = $this->model->table;

        $builder = $this->model
            ->builder($tabel . ' r')
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
                "COALESCE(ri.kamar, '-') AS kamar",
            ])
            ->join('role.pasien p', 'p.id_pasien = r.id_pasien', 'inner')
            ->join('person.orang o', 'o.id_orang  = p.id_orang', 'inner')
            ->join('role.dokter d', 'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang', 'left')
            ->join('rawat_inap.registrasi ri', 'ri.id_registrasi = r.id_registrasi', 'left')
            ->orderBy('r.tanggal_reg', 'DESC');

        $data = $this->model->guarded_get($builder, 'list')->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}
