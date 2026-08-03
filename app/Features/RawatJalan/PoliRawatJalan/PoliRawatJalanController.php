<?php
declare(strict_types=1);

namespace App\Features\RawatJalan\PoliRawatJalan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use App\Features\Registrasi\Registrasi\RegistrasiModel;
use CodeIgniter\HTTP\ResponseInterface;

final class PoliRawatJalanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RegistrasiModel(),
            [
                ['Rawat Jalan',      'rawat_jalan'],
                ['Poli Rawat Jalan', 'poli_rawat_jalan'],
            ],
            'Poli Rawat Jalan',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_registrasi', 'ID'],
                [SHOW, OPTIONAL, I::TEXT,  'nomor_reg',     'No. Registrasi'],
            ],
        );
    }

    #[\Override]
    final public function index(): string
    {
        $unitList = $this->model
            ->db
            ->table('unit.unit')
            ->select(['id_unit', 'nama_unit'])
            ->orderBy('nama_unit', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/rekam_medis/poli_rawat_jalan', [
            'judul'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs,
            'modul_path'  => $this->get_uri_path(),
            'unit_list'   => $unitList,
        ]);
    }

    public function list(): ResponseInterface
    {
        $idUnit = $this->request->getGet('id_unit');
        $dari   = $this->request->getGet('dari');
        $sampai = $this->request->getGet('sampai');
        $status = $this->request->getGet('status');

        $builder = $this->model
            ->db
            ->table('registrasi.registrasi r')
            ->select([
                'r.nomor_reg',
                'r.tanggal_reg',
                'p.nomor_rm',
                'o.nama AS nama_pasien',
                'u.nama_unit',
                'od.nama AS nama_dokter',
                'sp.nama_status_poli',
                'r.status_poli AS id_status_poli',
            ])
            ->join('role.pasien p', 'p.id_pasien       = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang        = p.id_orang', 'left')
            ->join('unit.unit u', 'u.id_unit         = r.unit', 'left')
            ->join('role.dokter d', 'd.id_dokter       = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang       = d.id_orang', 'left')
            ->join('registrasi.status_poli sp', 'sp.id_status_poli = r.status_poli', 'left')
            ->where('r.status_rawat', 2)
            ->orderBy('r.tanggal_reg', 'ASC');

        if ($idUnit !== null && $idUnit !== '') {
            $builder->where('r.unit', (int) $idUnit);
        }

        if ($dari !== null && $dari !== '') {
            $builder->where('DATE(r.tanggal_reg) >=', $dari);
        }

        if ($sampai !== null && $sampai !== '') {
            $builder->where('DATE(r.tanggal_reg) <=', $sampai);
        }

        if ($status !== null && $status !== '') {
            $builder->where('r.status_poli', (int) $status);
        }

        return $this->response->setJSON(['data' => $builder->get()->getResultArray()]);
    }

    public function detail(int|string $id): string|\CodeIgniter\HTTP\ResponseInterface
    {
        if ($id !== 'skrining') {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'not found']);
        }

        $noRm = trim($this->request->getGet('no_rm') ?? '');
        if ($noRm === '') {
            return $this->response->setJSON(['data' => null]);
        }

        $row = $this->model
            ->db
            ->table('skrining_rawat_jalan.skrining_rawat_jalan s')
            ->select([
                's.tgl_skrining',
                's.jam_skrining',
                'k.kesadaran',
                'p.pernafasan',
                'sn.skala_nyeri',
                'nd.nyeri_dada',
                'b.kategori_batuk',
                's.is_geriatri',
                's.is_risiko_jatuh',
                'u.nama_unit',
                'kp.skrining_keputusan',
                'o.nama AS nama_petugas',
            ])
            ->join('skrining_rawat_jalan.ref_skrining_kesadaran k', 'k.id_kesadaran   = s.id_kesadaran', 'left')
            ->join('skrining_rawat_jalan.ref_skrining_pernafasan p', 'p.id_pernafasan  = s.id_pernafasan', 'left')
            ->join('skrining_rawat_jalan.ref_skrining_skala_nyeri sn', 'sn.id_skala_nyeri = s.id_skala_nyeri', 'left')
            ->join('skrining_rawat_jalan.ref_skrining_nyeri_dada nd', 'nd.id_nyeri_dada  = s.id_nyeri_dada', 'left')
            ->join('skrining_rawat_jalan.ref_skrining_batuk b', 'b.id_batuk        = s.id_batuk', 'left')
            ->join('unit.unit u', 'u.id_unit         = s.id_unit', 'left')
            ->join('skrining_rawat_jalan.ref_skrining_keputusan kp', 'kp.id_keputusan   = s.id_keputusan', 'left')
            ->join('role.petugas pt', 'pt.id_petugas     = s.id_petugas', 'left')
            ->join('person.orang o', 'o.id_orang        = pt.id_orang', 'left')
            ->where('s.no_rm', $noRm)
            ->orderBy('s.tgl_skrining', 'DESC')
            ->orderBy('s.jam_skrining', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $this->response->setJSON(['data' => $row ?? null]);
    }
}
