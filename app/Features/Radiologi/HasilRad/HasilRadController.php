<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRad;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class HasilRadController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadModel(),
            [
                ['Radiologi',       'radiologi'],
                ['Hasil Radiologi', 'hasil_rad'],
            ],
            'Hasil Radiologi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_hasil_rad',        'ID Hasil Radiologi'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_rad',   'No. Permintaan'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_pj',        'Dokter PJ'],
                [SHOW, REQUIRED, I::TEXT,  'id_petugas_rad',      'Petugas Rad'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_perujuk',   'Dokter Perujuk'],
                [SHOW, REQUIRED, I::DTIME, 'tgl_jam_hasil',       'Tanggal & Jam Hasil'],
                [SHOW, OPTIONAL, I::TEXT,  'catatan',             'Catatan'],
            ],
        );
    }
}
