<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefSlotOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class RefSlotOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSlotOperasiModel(),
            [
                ['Operasi',             'operasi'],
                ['Slot Jadwal Operasi', 'ref_slot_operasi'],
            ],
            'Slot Jadwal Operasi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_slot',    'ID Slot'],
                [SHOW, REQUIRED, I::TEXT,  'nama_slot',  'Slot'],
                [SHOW, REQUIRED, I::TIME,  'waktu_slot', 'Waktu'],
            ],
        );
    }

    public function list(): ResponseInterface
    {
        $rows = $this->model
            ->db
            ->table('operasi.ref_slot_operasi')
            ->select(['id_slot', 'nama_slot', 'waktu_slot'])
            ->orderBy('id_slot', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}
