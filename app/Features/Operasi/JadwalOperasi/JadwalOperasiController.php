<?php
declare(strict_types=1);

namespace App\Features\Operasi\JadwalOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class JadwalOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JadwalOperasiModel(),
            [
                ['Operasi',        'operasi'],
                ['Jadwal Operasi', 'jadwal_operasi'],
            ],
            'Jadwal Operasi',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::LEMBAR_OPERASI,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_jadwal',          'ID Jadwal'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan',      'Permintaan Operasi'],
                [SHOW, REQUIRED, I::INDEX, 'id_ruangan',         'Ruangan'],
                [SHOW, REQUIRED, I::INDEX, 'id_dokter_bedah',    'Dokter Bedah'],
                [SHOW, REQUIRED, I::INDEX, 'id_dokter_anestesi', 'Dokter Anestesi'],
                [SHOW, REQUIRED, I::DATE,  'tanggal',            'Tanggal'],
                [SHOW, REQUIRED, I::TIME,  'waktu_mulai',        'Waktu Mulai'],
                [SHOW, REQUIRED, I::TIME,  'waktu_selesai',      'Waktu Selesai'],
                [SHOW, REQUIRED, I::INDEX, 'id_status',          'Status'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    private function fetchPermintaan(int $idPermintaan): array
    {
        return $this->model->db
            ->table('operasi.permintaan_operasi po')
            ->select([
                'po.id_permintaan',
                'po.nomor_reg',
                'po.tanggal_minta',
                'po.is_cito',
                'op.nama AS nama_pasien',
                'od.nama AS nama_dokter_peminta',
                'ti.nama_tindakan',
            ])
            ->join('rekam_medis.registrasi r',        'r.nomor_reg    = po.nomor_reg',   'left')
            ->join('role.pasien p',                   'p.id_pasien    = r.id_pasien',    'left')
            ->join('person.orang op',                 'op.id_orang    = p.id_orang',     'left')
            ->join('role.dokter d',                   'd.id_dokter    = po.id_dokter',   'left')
            ->join('person.orang od',                 'od.id_orang    = d.id_orang',     'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan = po.id_tindakan', 'left')
            ->where('po.id_permintaan', $idPermintaan)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue): string
    {
        $row = $this->model->db
            ->table("role.{$tabel} t")
            ->select('o.nama')
            ->join('person.orang o', 'o.id_orang = t.id_orang', 'left')
            ->where("t.{$idKolom}", $idValue)
            ->get()->getRowArray();
        return $row['nama'] ?? '';
    }

    private function fetchNamaRuangan(int $idRuangan): string
    {
        $row = $this->model->db
            ->table('ruangan.ruangan')
            ->select('nama_ruangan')
            ->where('id_ruangan', $idRuangan)
            ->get()->getRowArray();
        return $row['nama_ruangan'] ?? '';
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    final public function update_page(int|string $id): string
    {
        $baris = $this->model->find($id) ?? [];

        if (!empty($baris['id_permintaan'])) {
            $baris = array_merge($baris, $this->fetchPermintaan((int) $baris['id_permintaan']));
        }

        if (($idDb = (int) ($baris['id_dokter_bedah'] ?? 0)) > 0) {
            $baris['nama_dokter_bedah'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDb);
        }

        if (($idDa = (int) ($baris['id_dokter_anestesi'] ?? 0)) > 0) {
            $baris['nama_dokter_anestesi'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDa);
        }

        if (($idRuangan = (int) ($baris['id_ruangan'] ?? 0)) > 0) {
            $baris['nama_ruangan'] = $this->fetchNamaRuangan($idRuangan);
        }

        return view('admin/operasi/jadwalkan_operasi', [
            'judul'       => 'Jadwalkan Operasi',
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Jadwalkan', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $rawPost = $this->request->getPost();

        $data = [
            'id_ruangan'         => $rawPost['id_ruangan']         ?? null,
            'id_dokter_bedah'    => $rawPost['id_dokter_bedah']    ?? null,
            'id_dokter_anestesi' => $rawPost['id_dokter_anestesi'] ?? null,
            'tanggal'            => $rawPost['tanggal']            ?? null,
            'waktu_mulai'        => $rawPost['waktu_mulai']        ?? null,
            'waktu_selesai'      => $rawPost['waktu_selesai']      ?? null,
            'id_status'          => 2,
        ];

        try {
            $this->model->update($id, $data);

            session()->setFlashdata('success', 'Jadwal operasi berhasil disimpan.');
            return redirect()->to('/operasi/permintaan-operasi/data');

        } catch (\Exception $e) {
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    // -------------------------------------------------------------------------
    // Index
    // -------------------------------------------------------------------------

    #[\Override]
    final public function index(): string
    {
        $rows = $this->model->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.tanggal',
                'j.waktu_mulai',
                'j.waktu_selesai',
                'po.nomor_reg',
                'po.is_cito',
                'op.nama AS nama_pasien',
                'ru.nama_ruangan',
                'ob.nama AS nama_dokter_bedah',
                'oa.nama AS nama_dokter_anestesi',
                'j.id_status',
                'rs.nama_status',
            ])
            ->join('operasi.permintaan_operasi po',      'po.id_permintaan = j.id_permintaan',   'left')
            ->join('rekam_medis.registrasi r',           'r.nomor_reg      = po.nomor_reg',       'left')
            ->join('role.pasien p',                      'p.id_pasien      = r.id_pasien',        'left')
            ->join('person.orang op',                    'op.id_orang      = p.id_orang',         'left')
            ->join('ruangan.ruangan ru',                 'ru.id_ruangan    = j.id_ruangan',       'left')
            ->join('role.dokter db',                     'db.id_dokter     = j.id_dokter_bedah',  'left')
            ->join('person.orang ob',                    'ob.id_orang      = db.id_orang',        'left')
            ->join('role.dokter da',                     'da.id_dokter     = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa',                    'oa.id_orang      = da.id_orang',        'left')
            ->join('operasi.ref_status_operasi rs',      'rs.id_status     = j.id_status',        'left')
            ->orderBy('po.is_cito', 'DESC')
            ->orderBy('j.tanggal',  'ASC')
            ->orderBy('j.waktu_mulai', 'ASC')
            ->get()
            ->getResultArray();

        $konfig = [
            [1, 'No. Registrasi',   'nomor_reg',           'teks',    0],
            [1, 'Nama Pasien',      'nama_pasien',         'teks',    0],
            [1, 'Ruangan',          'nama_ruangan',        'teks',    0],
            [1, 'Dokter Bedah',     'nama_dokter_bedah',   'teks',    0],
            [1, 'Tanggal',          'tanggal',             'tanggal', 0],
            [1, 'Waktu',            'waktu_mulai',         'teks',    0],
            [1, 'Status',           'nama_status',         'status',  0],
            [1, 'CITO',             'is_cito',             'bool',    0],
        ];

        return view('/layouts/data', [
            'judul'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs,
            'meta_data'    => ['page' => 1, 'size' => count($rows), 'total' => 1],
            'modul_path'   => $this->get_uri_path(),
            'kolom_id'     => 'id_jadwal',
            'konfig'       => $konfig,
            'aksi'         => $this->actions,
            'tabel'        => $rows,
            'row_alert'    => [],
            'query_string' => '',
        ]);
    }
}
