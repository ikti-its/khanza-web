<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\RefParameterPemeriksaanLab;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class RefParameterPemeriksaanLabController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefParameterPemeriksaanLabModel(),
            [
                ['Laboratorium',                    'laboratorium'],
                ['Referensi Parameter Pemeriksaan', 'ref_parameter_pemeriksaan_lab'],
            ],
            'Referensi Parameter Pemeriksaan Lab',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_parameter',   'ID Parameter'],
                [SHOW, REQUIRED, I::INDEX, 'id_item_lab',    'Item Lab'],
                [SHOW, REQUIRED, I::TEXT,  'nama_parameter', 'Nama Parameter'],
                [SHOW, OPTIONAL, I::TEXT,  'satuan',         'Satuan'],
                [SHOW, OPTIONAL, I::TEXT,  'nilai_rujukan',  'Nilai Rujukan'],
                [SHOW, REQUIRED, I::MONEY, 'biaya_item',     'Biaya Item'],
            ],
        );
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    public function list(): ResponseInterface
    {
        $idItemLab = (string) ($this->request->getGet('id_item_lab') ?? '');

        $builder = $this->model
            ->db
            ->table('laboratorium.ref_parameter_pemeriksaan_lab')
            ->select([
                'id_parameter',
                'id_item_lab',
                'nama_parameter',
                'satuan',
                'nilai_rujukan',
                'biaya_item',
            ]);

        if ($idItemLab !== '') {
            $builder->where('id_item_lab', (int) $idItemLab);
        }

        $rows = $this->model->guarded_get($builder->orderBy('id_parameter', 'ASC'), 'list')->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}
