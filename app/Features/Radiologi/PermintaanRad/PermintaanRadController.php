<?php
declare(strict_types=1);

namespace App\Features\Radiologi\PermintaanRad;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanRadController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanRadModel(),
            [
                ['Radiologi',            'radiologi'],
                ['Permintaan Radiologi', 'permintaan_rad'],
            ],
            'Permintaan Radiologi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'no_permintaan',        'No. Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'nomor_reg',            'Nomor Registrasi'],
                [SHOW, REQUIRED, I::TEXT,   'kode_dokter_perujuk',  'Kode Dokter Perujuk'],
                [SHOW, REQUIRED, I::DTIME,  'tgl_jam_permintaan',   'Tanggal Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'informasi_tambahan',   'Informasi Tambahan'],
                [SHOW, REQUIRED, I::TEXT,   'indikasi_klinis',      'Indikasi Klinis'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_permintaan', 'Status Permintaan'],
                [SHOW, REQUIRED, I::SELECT, 'id_item_rad',          'Item Radiologi'],
            ],
        );
    }
}
