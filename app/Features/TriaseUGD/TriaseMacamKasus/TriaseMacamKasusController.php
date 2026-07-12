<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\TriaseMacamKasus;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class TriaseMacamKasusController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TriaseMacamKasusModel(),
            [
                ['Triase UGD',         'triase_ugd'],
                ['Triase Macam Kasus', 'triase_macam_kasus'],
            ],
            'Triase Macam Kasus',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_macam_kasus',   'ID Macam Kasus'],
                [SHOW, REQUIRED, I::TEXT,  'kode_macam_kasus', 'Kode'],
                [SHOW, REQUIRED, I::TEXT,  'nama_macam_kasus', 'Macam Kasus'],
            ],
        );
    }

    /**
     * OVERRIDE: Mengurutkan data sebelum ditampilkan
     */
    #[\Override]
    protected function before_read(): void
    {
        $this->model->set_order('kode_macam_kasus', 'ASC');
    }

    /**
     * Menampilkan data modal triase macam kasus
     */
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model
            ->builder()
            ->select("
                {$tabel}.id_macam_kasus,
                {$tabel}.kode_macam_kasus,
                {$tabel}.nama_macam_kasus
            ")
            ->orderBy("{$tabel}.kode_macam_kasus", 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data,
        ]);
    }
}
