<?php
declare(strict_types=1);

namespace App\Features\Operasi\PaketTindakanOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PaketTindakanOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PaketTindakanOperasiModel(),
            [
                ['Operasi',                'operasi'],
                ['Paket Tindakan Operasi', 'paket_tindakan_operasi'],
            ],
            'Paket Tindakan Operasi',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_paket',      'ID Paket'],
                [SHOW, REQUIRED, I::INDEX,  'id_tindakan',   'Tindakan Operasi'],
                [SHOW, REQUIRED, I::SELECT, 'id_komponen',   'Komponen Jasa'],
                [SHOW, REQUIRED, I::MONEY,  'tarif_kelas_1', 'Tarif Kelas 1'],
                [SHOW, REQUIRED, I::MONEY,  'tarif_kelas_2', 'Tarif Kelas 2'],
                [SHOW, REQUIRED, I::MONEY,  'tarif_kelas_3', 'Tarif Kelas 3'],
                [SHOW, REQUIRED, I::MONEY,  'tarif_vip',     'Tarif VIP'],
                [SHOW, REQUIRED, I::MONEY,  'tarif_vvip',    'Tarif VVIP'],
            ],
        );
    }

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $idTindakan = (int) $this->request->getGet('id_tindakan');
        if ($idTindakan < 1) {
            return $this->response->setJSON(['data' => []]);
        }

        $rows = $this->model
            ->db
            ->table('operasi.paket_tindakan_operasi p')
            ->select(['p.id_paket', 'p.id_tindakan', 'k.nama_komponen', 'p.tarif_kelas_3', 'ti.nama_tindakan'])
            ->join('operasi.ref_komponen_jasa k', 'k.id_komponen  = p.id_komponen', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan = p.id_tindakan', 'left')
            ->where('p.id_tindakan', $idTindakan)
            ->orderBy('p.id_komponen', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }

    public function listByTindakan(int $idTindakan)
    {
        $rows = $this->model
            ->db
            ->table('operasi.paket_tindakan_operasi p')
            ->select([
                'p.id_paket',
                'rk.nama_komponen',
                'p.tarif_kelas_3',
                'p.tarif_kelas_2',
                'p.tarif_kelas_1',
                'p.tarif_vip',
                'p.tarif_vvip',
            ])
            ->join('operasi.ref_komponen_jasa rk', 'rk.id_komponen = p.id_komponen', 'left')
            ->where('p.id_tindakan', $idTindakan)
            ->orderBy('p.id_komponen', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}
