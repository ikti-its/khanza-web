<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Alamat;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class AlamatController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new AlamatModel(),
            [
                ['Lokasi', 'lokasi'],
                ['Alamat', 'alamat'],
            ],
            'Alamat',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, REQUIRED, I::INDEX,   'id_alamat',      'ID'],
                [SHOW, REQUIRED, I::SELECT,  'id_provinsi',    'Provinsi'],
                [SHOW, REQUIRED, I::SELECT,  'id_kota_lokal',  'Kota/Kabupaten'],
                [SHOW, REQUIRED, I::SELECT,  'id_kec_lokal',   'Kecamatan'],
                [SHOW, REQUIRED, I::SELECT,  'id_desa_lokal',  'Kelurahan/Desa'],
                [SHOW, REQUIRED, I::TEXT,    'rw',             'RW'],
                [SHOW, REQUIRED, I::TEXT,    'rt',             'RT'],
                [SHOW, REQUIRED, I::TEXT,    'alamat_lengkap', 'Alamat'],
            ],
        );
    }

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->model->db
            ->table('lokasi.alamat a')
            ->select('a.id_alamat, k.nama_kota, a.alamat_lengkap')
            ->join('lokasi.kota k', 'k.id_provinsi = a.id_provinsi AND k.id_kota_lokal = a.id_kota_lokal', 'left')
            ->orderBy('a.id_alamat')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}
