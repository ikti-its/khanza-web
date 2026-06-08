<?php
declare(strict_types=1);

namespace App\Features\Role\Petugas;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PetugasController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PetugasModel(),
            [
                ['Role',    'role'],
                ['Petugas', 'petugas'],
            ],
            'Petugas',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_petugas', 'ID Petugas'],
                [SHOW, REQUIRED, I::INDEX, 'id_orang',   'ID Orang'],
                [SHOW, OPTIONAL, I::TEXT,  'deskripsi',  'Deskripsi'],
            ],
        );
    }

    /**
     * Menampilkan data modal petugas
     */
    public function list()
    {
        $data = $this->model->builder()
            ->select('
                role.petugas.id_petugas,
                role.petugas.deskripsi,
                person.orang.nama
            ')
            ->join('person.orang', 'person.orang.id_orang = role.petugas.id_orang', 'inner')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}