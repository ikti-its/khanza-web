<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefTindakanOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefTindakanOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefTindakanOperasiModel(),
            [
                ['Operasi',                    'operasi'],
                ['Referensi Tindakan Operasi', 'ref_tindakan_operasi'],
            ],
            'Referensi Tindakan Operasi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_tindakan',   'ID Tindakan'],
                [SHOW, REQUIRED, I::TEXT,  'kode_tindakan', 'Kode Tindakan'],
                [SHOW, REQUIRED, I::TEXT,  'nama_tindakan', 'Nama Tindakan'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_kelas_3', 'Tarif Kelas 3'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_kelas_2', 'Tarif Kelas 2'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_kelas_1', 'Tarif Kelas 1'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_vip',     'Tarif VIP'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_vvip',    'Tarif VVIP'],
            ],
        );
    }

    public function list()
    {
        $rows = $this->model
            ->db
            ->table('operasi.ref_tindakan_operasi')
            ->select(['id_tindakan', 'kode_tindakan', 'nama_tindakan', 'tarif_kelas_3'])
            ->orderBy('nama_tindakan', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}
