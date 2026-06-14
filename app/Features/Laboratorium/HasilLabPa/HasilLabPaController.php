<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPa;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class HasilLabPaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabPaModel(),
            [
                ['Laboratorium', 'laboratorium'],
                ['Hasil Lab PA', 'hasil_lab_pa'],
            ],
            'Hasil Lab PA',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
             [
                [HIDE, OPTIONAL, I::INDEX, 'id_hasil_pa',           'ID Hasil PA'],
                [HIDE, REQUIRED, I::INDEX, 'id_permintaan_lab',     'ID Permintaan Lab'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_pa_item', 'ID Permintaan PA Item'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_pj',          'Kode Dokter PJ'],
                [SHOW, REQUIRED, I::TEXT,  'id_petugas_lab',        'Petugas Lab'],
                [SHOW, REQUIRED, I::DTIME, 'tgl_jam_hasil',         'Tanggal & Jam Hasil'],
                [SHOW, REQUIRED, I::TEXT,  'diagnosa_klinis',       'Diagnosa Klinis'],
                [SHOW, REQUIRED, I::TEXT,  'makroskopik',           'Makroskopik'],
                [SHOW, REQUIRED, I::TEXT,  'mikroskopik',           'Mikroskopik'],
                [SHOW, REQUIRED, I::TEXT,  'kesimpulan',            'Kesimpulan'],
                [SHOW, OPTIONAL, I::TEXT,  'kesan',                 'Kesan'],
            ],
        );
    }
}
