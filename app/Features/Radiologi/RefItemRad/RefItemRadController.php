<?php
declare(strict_types=1);

namespace App\Features\Radiologi\RefItemRad;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefItemRadController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefItemRadModel(),
            [
                ['Radiologi',                'radiologi'],
                ['Referensi Item Radiologi', 'ref_item_rad'],
            ],
            'Referensi Item Radiologi',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_item',          'ID Item'],
                [SHOW, REQUIRED, I::TEXT,  'kode_periksa',     'Kode Periksa'],
                [SHOW, REQUIRED, I::TEXT,  'nama_pemeriksaan', 'Nama Pemeriksaan'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_dasar',      'Tarif Dasar'],
                [SHOW, OPTIONAL, I::MONEY, 'tarif_baca',       'Tarif Baca Saja'],
            ],
        );
    }

    public function list()
    {
        $rows = $this->model->db
            ->table('radiologi.ref_item_rad')
            ->select(['id_item', 'kode_periksa', 'nama_pemeriksaan', 'tarif_dasar', 'tarif_baca'])
            ->orderBy('kode_periksa', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}
