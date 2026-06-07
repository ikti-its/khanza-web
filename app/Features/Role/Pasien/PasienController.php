<?php
declare(strict_types=1);

namespace App\Features\Role\Pasien;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PasienController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PasienModel(),
            [
                ['Role',   'role'],
                ['Pasien', 'pasien'],
            ],
            'Pasien',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_pasien', 'ID Pasien'],
                [HIDE, OPTIONAL, I::INDEX, 'id_orang',  'ID Orang'],
                [SHOW, REQUIRED, I::TEXT,  'nomor_rm',  'No. Rekam Medis'],
            ],
        );
    }

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $builder = $this->model->db
            ->table('role.pasien p')
            ->select('p.nomor_rm, o.nama, o.nik, o.tanggal_lahir, jk.nama_jenis_kelamin as jenis_kelamin')
            ->join('person.orang o', 'o.id_orang = p.id_orang')
            ->join('person.jenis_kelamin jk', 'jk.id_jenis_kelamin = o.id_jenis_kelamin', 'left');

        $data = $builder->get()->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}