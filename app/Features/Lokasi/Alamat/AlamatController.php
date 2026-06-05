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
                [SHOW, REQUIRED, I::INDEX, 'id_alamat',      'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_provinsi',  'Provinsi'],
                [SHOW, REQUIRED, I::TEXT,  'nama_kota',      'Kota/Kabupaten'],
                [SHOW, REQUIRED, I::TEXT,  'nama_kecamatan', 'Kecamatan'],
                [SHOW, REQUIRED, I::TEXT,  'nama_desa',      'Kelurahan/Desa'],
                [SHOW, REQUIRED, I::TEXT,  'rw',             'RW'],
                [SHOW, REQUIRED, I::TEXT,  'rt',             'RT'],
                [SHOW, REQUIRED, I::TEXT,  'alamat_lengkap', 'Alamat'],
            ],
        );
    }
}
