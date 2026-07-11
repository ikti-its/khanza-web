<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\Satuan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class SatuanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SatuanModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Satuan',              'satuan'],
            ],
            'Satuan',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_satuan',   'ID'],
                [SHOW, REQUIRED, I::NAME,  'kode_satuan', 'Kode Satuan'],
                [SHOW, REQUIRED, I::NAME,  'nama_satuan', 'Nama Satuan'],
            ],
        );
    }

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->model
            ->builder()
            ->select('id_satuan, kode_satuan, nama_satuan')
            ->orderBy('nama_satuan', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }

    // urut berdasarkan nama A-Z
    protected function before_read(): void
    {
        $this->model->set_order('nama_satuan', 'ASC');
    }
}
