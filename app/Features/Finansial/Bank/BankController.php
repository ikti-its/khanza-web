<?php
declare(strict_types=1);

namespace App\Features\Finansial\Bank;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class BankController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new BankModel(),
            [
                ['Finansial', 'finansial'],
                ['Bank',      'bank'],
            ],
            'Bank',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::TEXT, 'kode_bank',      'Kode'],
                [SHOW, REQUIRED, I::TEXT, 'nama_bank',      'Bank'],
                [SHOW, REQUIRED, I::TEXT, 'sebutan',        'Singkatan'],
                [SHOW, REQUIRED, I::TEXT, 'nama_pemilik',   'Pemilik'],
                [SHOW, REQUIRED, I::TEXT, 'nama_prinsip',   'Prinsip'],
                [SHOW, REQUIRED, I::BOOL, 'is_bank_devisa', 'Bank Devisa'],
                [SHOW, REQUIRED, I::TEXT, 'mobile_app',     'Aplikasi Mobile'],
                [SHOW, REQUIRED, I::TEXT, 'link_playstore', 'Playstore'],
            ],
        );
    }

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->model
            ->builder()
            ->select('id_bank, nama_bank')
            ->where('id_bank >', 0)
            ->orderBy('nama_bank', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}
