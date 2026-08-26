<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\Satuan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

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
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_satuan',   'ID'],
                [SHOW, REQUIRED, I::NAME,  'kode_satuan', 'Kode Satuan'],
                [SHOW, REQUIRED, I::NAME,  'nama_satuan', 'Nama Satuan'],
            ],
        );
    }

    // narrows the query-result union (bool|Query|BaseResult) that mago infers
    // for ->get()/->query(), matching ModelTemplate::guarded_get() convention.
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function guarded(mixed $result): \CodeIgniter\Database\BaseResult
    {
        assert($result instanceof \CodeIgniter\Database\BaseResult, 'Query gagal dieksekusi.');
        return $result;
    }

    /**
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function list(): ResponseInterface
    {
        $data = $this->guarded(
            $this->model
                ->builder()
                ->select('id_satuan, kode_satuan, nama_satuan')
                ->orderBy('nama_satuan', 'ASC')
                ->get(),
        )->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }

    // urut berdasarkan nama A-Z
    #[\Override]
    protected function before_read(): void
    {
        $this->model->set_order('nama_satuan', 'ASC');
    }
}
