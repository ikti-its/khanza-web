<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\RefItemPemeriksaanLab;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class RefItemPemeriksaanLabController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefItemPemeriksaanLabModel(),
            [
                ['Laboratorium',               'laboratorium'],
                ['Referensi Item Pemeriksaan', 'ref_item_pemeriksaan_lab'],
            ],
            'Referensi Item Pemeriksaan Lab',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_item_lab',  'ID Item Lab'],
                [SHOW, REQUIRED, I::INDEX, 'id_kategori',  'Kategori'],
                [SHOW, REQUIRED, I::TEXT,  'kode_periksa', 'Kode Periksa'],
                [SHOW, REQUIRED, I::TEXT,  'nama_item',    'Nama Item'],
                [SHOW, REQUIRED, I::MONEY, 'tarif',        'Tarif'],
            ],
        );
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    public function list(): ResponseInterface
    {
        $idKategori = (string) ($this->request->getGet('id_kategori') ?? '');

        $builder = $this->model
            ->db
            ->table('laboratorium.ref_item_pemeriksaan_lab')
            ->select(['id_item_lab', 'kode_periksa', 'nama_item', 'tarif']);

        if ($idKategori !== '' && $idKategori !== '0') {
            $builder->where('id_kategori', (int) $idKategori);
        }

        $rows = $this->model->guarded_get($builder, 'list')->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}
