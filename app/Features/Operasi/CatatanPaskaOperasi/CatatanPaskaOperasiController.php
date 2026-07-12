<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanPaskaOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class CatatanPaskaOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanPaskaOperasiModel(),
            [
                ['Operasi',               'operasi'],
                ['Catatan Paska Operasi', 'catatan_paska_operasi'],
            ],
            'Catatan Paska Operasi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX, 'id_catatan_paska',        'ID Catatan Paska'],
                [SHOW,      REQUIRED, I::INDEX, 'id_jadwal',               'Jadwal Operasi'],
                [SHOW,      REQUIRED, I::INDEX, 'id_dokter_bedah',         'Dokter Bedah'],
                [SHOW,      REQUIRED, I::DTIME, 'waktu_penilaian',         'Waktu Penilaian'],
                [FORM_ONLY, REQUIRED, I::TEXT,  'instruksi_rawat',         'Instruksi Rawat'],
                [FORM_ONLY, REQUIRED, I::TEXT,  'instruksi_cairan',        'Instruksi Cairan'],
                [FORM_ONLY, REQUIRED, I::TEXT,  'instruksi_antibiotik',    'Instruksi Antibiotik'],
                [FORM_ONLY, REQUIRED, I::TEXT,  'instruksi_analgetik',     'Instruksi Analgetik'],
                [FORM_ONLY, REQUIRED, I::TEXT,  'instruksi_medikamentosa', 'Instruksi Medikamentosa'],
                [FORM_ONLY, REQUIRED, I::TEXT,  'instruksi_diet',          'Instruksi Diet'],
                [FORM_ONLY, REQUIRED, I::TEXT,  'instruksi_penunjang',     'Instruksi Penunjang'],
                [FORM_ONLY, REQUIRED, I::TEXT,  'instruksi_transfusi',     'Instruksi Transfusi'],
                [FORM_ONLY, REQUIRED, I::TEXT,  'instruksi_lainnya',       'Instruksi Lainnya'],
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
                'po.nomor_reg',
                'po.id_tindakan',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
                'ob.nama AS nama_dokter_bedah',
            ])
            ->join('operasi.permintaan_operasi po', 'po.id_permintaan = j.id_permintaan', 'left')
            ->join('registrasi.registrasi r', 'r.nomor_reg      = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien      = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang      = p.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan', 'left')
            ->join('role.dokter db', 'db.id_dokter     = j.id_dokter_bedah', 'left')
            ->join('person.orang ob', 'ob.id_orang      = db.id_orang', 'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()
            ->getRowArray() ?? [];
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

    private function buildViewData(array $jadwal, array $record, string $formAction): array
    {
        return [
            'judul'       => ($formAction === '/submittambah/' ? 'Tambah ' : 'Ubah ') . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [[
                'title' => $formAction === '/submittambah/' ? 'Tambah' : 'Ubah',
                'icon'  => '',
            ]]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => $formAction,
            'baris'       => $record,
            'jadwal'      => $jadwal,
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

        return view('admin/operasi/tambah_catatan_paska_operasi', $this->buildViewData(
            $jadwal,
            [
                'id_jadwal'         => $idJadwal,
                'id_dokter_bedah'   => $jadwal['id_dokter_bedah'] ?? '',
                'nama_dokter_bedah' => $jadwal['nama_dokter_bedah'] ?? '',
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

        return view('admin/operasi/tambah_catatan_paska_operasi', $this->buildViewData(
            $jadwal,
            $record,
            '/submitedit/' . $id,
        ));
    }
}
