<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabMb;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class HasilLabMbController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabMbModel(),
            [
                ['Laboratorium', 'laboratorium'],
                ['Hasil Lab MB', 'hasil_lab_mb'],
            ],
            'Hasil Lab MB',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_hasil_mb',           'ID Hasil MB'],
                [HIDE, REQUIRED, I::INDEX, 'id_permintaan_lab',     'ID Permintaan Lab'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_mb_item', 'ID Permintaan MB Item'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_pj',          'Kode Dokter PJ'],
                [SHOW, REQUIRED, I::TEXT,  'id_petugas_lab',        'Petugas Lab'],
                [SHOW, REQUIRED, I::DTIME, 'tgl_jam_hasil',         'Tanggal & Jam Hasil'],
            ],
        );
    }
}
