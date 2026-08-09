<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Kota;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class KotaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KotaModel(),
            [
                ['Lokasi', 'lokasi'],
                ['Kota', 'kota'],
            ],
            'Kota',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_kota',       'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_provinsi', 'Provinsi'],
                [SHOW, REQUIRED, I::TEXT,  'id_kota_lokal', 'Kode Lokal'],
                [SHOW, REQUIRED, I::TEXT,  'nama_kota',     'Kota'],
            ],
        );
    }

    /**
     * Menampilkan data modal kota
     *
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function list(): ResponseInterface
    {
        return $this->response->setJSON([
            'data' => $this->model()->get_data_tabel(),
        ]);
    }

    private function model(): KotaModel
    {
        assert($this->model instanceof KotaModel);
        return $this->model;
    }
}
