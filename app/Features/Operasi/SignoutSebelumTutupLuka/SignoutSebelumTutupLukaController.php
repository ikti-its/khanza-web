<?php
declare(strict_types=1);

namespace App\Features\Operasi\SignoutSebelumTutupLuka;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class SignoutSebelumTutupLukaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SignoutSebelumTutupLukaModel(),
            [
                ['Operasi',                     'operasi'],
                ['Sign Out Sebelum Tutup Luka', 'signout_sebelum_tutupluka'],
            ],
            'Sign Out Sebelum Tutup Luka',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX,  'id_signout',              'ID Sign Out'],
                [SHOW,      REQUIRED, I::INDEX,  'id_jadwal',               'Jadwal Operasi'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_sn_cn',                'SN/CN'],
                [SHOW,      REQUIRED, I::INDEX,  'id_dokter_bedah',         'Dokter Bedah'],
                [SHOW,      REQUIRED, I::INDEX,  'id_dokter_anestesi',      'Dokter Anestesi'],
                [SHOW,      REQUIRED, I::DTIME,  'waktu_signout',           'Waktu Sign Out'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_tindakan',             'Tindakan'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_nama_tindakan_sesuai', 'Nama Tindakan Sesuai'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_kasa_lengkap',         'Kasa Lengkap'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_instrumen_lengkap',    'Instrumen Lengkap'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_alat_tajam_lengkap',   'Alat Tajam Lengkap'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_label_spesimen',       'Label Spesimen'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_formulir_spesimen',    'Formulir Spesimen'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_konfirmasi_bedah',     'Konfirmasi Bedah'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_konfirmasi_anestesi',  'Konfirmasi Anestesi'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_konfirmasi_perawat',   'Konfirmasi Perawat'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'catatan_pemulihan',       'Catatan Pemulihan'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'id_perawat_ok',           'Perawat OK'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    #[\Override]
    protected function home(): RedirectResponse
    {
        $idJadwal = (int) ($this->request->getPost('id_jadwal') ?? 0);
        if ($idJadwal > 0) {
            return redirect()->to('/operasi/lembar-operasi/data?id_jadwal=' . $idJadwal);
        }
        return redirect()->to($this->get_uri_path() . '/data');
    }

    private function fetchJadwal(int $idJadwal): array
    {
        return $this->model
            ->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.id_dokter_bedah',
                'j.id_dokter_anestesi',
                'po.nomor_reg',
                'po.id_tindakan',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
                'ob.nama AS nama_dokter_bedah',
                'oa.nama AS nama_dokter_anestesi',
            ])
            ->join('operasi.permintaan_operasi po', 'po.id_permintaan = j.id_permintaan', 'left')
            ->join('registrasi.registrasi r', 'r.nomor_reg      = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien      = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang      = p.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan', 'left')
            ->join('role.dokter db', 'db.id_dokter     = j.id_dokter_bedah', 'left')
            ->join('person.orang ob', 'ob.id_orang      = db.id_orang', 'left')
            ->join('role.dokter da', 'da.id_dokter     = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa', 'oa.id_orang      = da.id_orang', 'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchOptions(): array
    {
        return [
            'spesimen' => $this->model
                ->db
                ->table('operasi.ref_status_spesimen')
                ->select('id_status_spesimen, nama_status')
                ->get()
                ->getResultArray(),
        ];
    }

    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue): string
    {
        $row = $this->model
            ->db
            ->table("role.{$tabel} t")
            ->select('o.nama')
            ->join('person.orang o', 'o.id_orang = t.id_orang', 'left')
            ->where("t.{$idKolom}", $idValue)
            ->get()
            ->getRowArray();
        return $row['nama'] ?? '';
    }

    private function fetchTindakanName(int $idTindakan): string
    {
        $row = $this->model
            ->db
            ->table('operasi.ref_tindakan_operasi')
            ->select('nama_tindakan')
            ->where('id_tindakan', $idTindakan)
            ->get()
            ->getRowArray();
        return $row['nama_tindakan'] ?? '';
    }

    private function buildViewData(array $jadwal, array $record, string $formAction): array
    {
        $isCreate = $formAction === '/submittambah/';
        return [
            'judul'       => ($isCreate ? 'Tambah ' : 'Ubah ') . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [[
                'title' => $isCreate ? 'Tambah' : 'Ubah',
                'icon'  => '',
            ]]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => $formAction,
            'baris'       => $record,
            'jadwal'      => $jadwal,
            'options'     => $this->fetchOptions(),
        ];
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    public function create_page(): string
    {
        $idJadwal = (int) ($this->request->getGet('id_jadwal') ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        return view('admin/operasi/tambah_signout_sebelum_tutupluka', $this->buildViewData(
            $jadwal,
            [
                'id_jadwal'            => $idJadwal,
                'id_tindakan'          => $jadwal['id_tindakan'] ?? '',
                'nama_tindakan'        => $jadwal['nama_tindakan'] ?? '',
                'id_dokter_bedah'      => $jadwal['id_dokter_bedah'] ?? '',
                'nama_dokter_bedah'    => $jadwal['nama_dokter_bedah'] ?? '',
                'id_dokter_anestesi'   => $jadwal['id_dokter_anestesi'] ?? '',
                'nama_dokter_anestesi' => $jadwal['nama_dokter_anestesi'] ?? '',
            ],
            '/submittambah/',
        ));
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        $record   = $this->model->find_one($id);
        $idJadwal = (int) ($record['id_jadwal'] ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        if (($idDb = (int) ($record['id_dokter_bedah'] ?? 0)) > 0) {
            $record['nama_dokter_bedah'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDb);
        }
        if (($idDa = (int) ($record['id_dokter_anestesi'] ?? 0)) > 0) {
            $record['nama_dokter_anestesi'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDa);
        }
        if (($idT = (int) ($record['id_tindakan'] ?? 0)) > 0) {
            $record['nama_tindakan'] = $this->fetchTindakanName($idT);
        }
        if (($idSn = (int) ($record['id_sn_cn'] ?? 0)) > 0) {
            $record['nama_sn_cn'] = $this->fetchNamaRole('petugas', 'id_petugas', $idSn);
        }
        if (($idPo = (int) ($record['id_perawat_ok'] ?? 0)) > 0) {
            $record['nama_perawat_ok'] = $this->fetchNamaRole('petugas', 'id_petugas', $idPo);
        }

        foreach ([
            'is_nama_tindakan_sesuai',
            'is_kasa_lengkap',
            'is_instrumen_lengkap',
            'is_alat_tajam_lengkap',
            'is_konfirmasi_bedah',
            'is_konfirmasi_anestesi',
            'is_konfirmasi_perawat',
        ] as $field) {
            if (isset($record[$field])) {
                $isTrue         = $record[$field] === true || $record[$field] == 1 || $record[$field] === 't';
                $record[$field] = $isTrue ? '1' : '0';
            }
        }

        return view('admin/operasi/tambah_signout_sebelum_tutupluka', $this->buildViewData(
            $jadwal,
            $record,
            '/submitedit/' . $id,
        ));
    }
}
