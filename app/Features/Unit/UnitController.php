<?php
declare(strict_types=1);

namespace App\Features\Unit;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class UnitController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new UnitModel(),
            [
                ['Unit', 'unit'],
            ],
            'Unit',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_unit',               'ID Unit'],
                [SHOW, REQUIRED, I::TEXT,  'kode_unit',             'Kode Unit'],
                [SHOW, REQUIRED, I::TEXT,  'nama_unit',             'Nama Unit'],
                [SHOW, REQUIRED, I::MONEY, 'biaya_registrasi_baru', 'Biaya Registrasi Baru'],
                [SHOW, REQUIRED, I::MONEY, 'biaya_registrasi_lama', 'Biaya Registrasi Lama'],
            ],
        );
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    public function list(): ResponseInterface
    {
        $builder = $this->model
            ->db
            ->table('unit.unit')
            ->select('id_unit, kode_unit, nama_unit, biaya_registrasi_baru, biaya_registrasi_lama')
            ->orderBy('kode_unit');

        $exclude = (string) ($this->request->getGet('exclude') ?? '');
        if ($exclude !== '') {
            $ids = array_map('intval', explode(',', $exclude));
            $builder->whereNotIn('id_unit', $ids);
        }

        $data = $this->model->guarded_get($builder, 'list')->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}
