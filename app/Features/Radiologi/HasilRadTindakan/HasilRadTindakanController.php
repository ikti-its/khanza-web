<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRadTindakan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class HasilRadTindakanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadTindakanModel(),
            [
                ['Radiologi',                'radiologi'],
                ['Hasil Radiologi Tindakan', 'hasil_rad_tindakan'],
            ],
            'Hasil Radiologi Tindakan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_hasil_tindakan',       'ID Hasil Tindakan'],
                [SHOW, REQUIRED, I::INDEX,  'id_hasil_rad',            'ID Hasil Rad'],
                [SHOW, REQUIRED, I::INDEX,  'id_item_rad',             'Item Radiologi'],
                [SHOW, OPTIONAL, I::TEXT,   'proyeksi',                'Proyeksi'],
                [SHOW, OPTIONAL, I::NUMBER, 'kilovoltage_kv',          'Kilovoltage (kV)'],
                [SHOW, OPTIONAL, I::NUMBER, 'milliampere_second_mas',  'Milliampere Second (mAs)'],
                [SHOW, OPTIONAL, I::NUMBER, 'focus_film_distance_ffd', 'Focus Film Distance (FFD)'],
                [SHOW, OPTIONAL, I::NUMBER, 'back_scatter_factor_bsf', 'Back Scatter Factor (BSF)'],
                [SHOW, OPTIONAL, I::TEXT,   'inaktivasi',              'Inaktivasi'],
                [SHOW, OPTIONAL, I::NUMBER, 'jumlah_penyinaran',       'Jumlah Penyinaran'],
                [SHOW, OPTIONAL, I::TEXT,   'dosis_radiasi',           'Dosis Radiasi'],
                [SHOW, OPTIONAL, I::TEXT,   'hasil_ekspertise',        'Hasil Ekspertise'],
                [HIDE, OPTIONAL, I::INDEX,  'id_template_rad',         'ID Template Rad'],
            ],
        );
    }
}
