<?php
declare(strict_types=1);

namespace App\Features\Person\Orang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class OrangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new OrangModel(),
            [
                ['Person', 'person'],
                ['Orang', 'orang'],
            ],
            'Orang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_orang',          'ID Orang'],
                [SHOW, REQUIRED, I::TEXT,   'nik',               'NIK'],
                [SHOW, REQUIRED, I::NAME,   'nama',              'Nama'],
                [SHOW, REQUIRED, I::SELECT, 'id_jenis_kelamin',  'Jenis Kelamin'],
                [SHOW, REQUIRED, I::SELECT, 'id_agama',          'Agama'],
                [SHOW, REQUIRED, I::SELECT, 'id_pernikahan',     'Pernikahan'],
                [SHOW, REQUIRED, I::SELECT, 'id_golongan_darah', 'Golongan Darah'],
                [SHOW, REQUIRED, I::SELECT, 'id_alamat',         'Alamat'],
                [SHOW, REQUIRED, I::SELECT, 'tempat_lahir_kota', 'Tempat Lahir'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_lahir',     'Tanggal Lahir'],
            ],
        );
    }

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->model->db
            ->table('person.orang')
            ->select('id_orang, nik, nama')
            ->orderBy('nama')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}
