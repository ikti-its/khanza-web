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
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_rad',   'ID Permintaan Rad'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_pj',        'ID Dokter PJ'],
                [SHOW, REQUIRED, I::TEXT,  'id_petugas_rad',      'ID Petugas Rad'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_perujuk',   'ID Dokter Perujuk'],
                [SHOW, REQUIRED, I::DATE,  'tgl_jam_hasil',       'Tanggal & Jam Hasil'],
                [SHOW, REQUIRED, I::TEXT,  'catatan',             'Catatan'],
            ],
        );
    }
}
