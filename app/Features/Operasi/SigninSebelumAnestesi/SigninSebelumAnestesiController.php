<?php
declare(strict_types=1);

namespace App\Features\Operasi\SigninSebelumAnestesi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class SigninSebelumAnestesiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SigninSebelumAnestesiModel(),
            [
                ['Operasi',                  'operasi'],
                ['Sign In Sebelum Anestesi', 'signin_sebelum_anestesi'],
            ],
            'Sign In Sebelum Anestesi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_signin',                 'ID Sign In'],
                [SHOW, REQUIRED, I::INDEX,  'id_jadwal',                 'Jadwal Operasi'],
                [SHOW, REQUIRED, I::DATE,   'waktu_signin',              'Waktu Sign In'],
                [SHOW, REQUIRED, I::INDEX,  'id_sn_cn',                  'SN/CN'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_bedah',           'Dokter Bedah'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_anestesi',        'Dokter Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_tindakan',               'Tindakan'],
                [SHOW, REQUIRED, I::SELECT, 'is_identitas_sesuai',       'Identitas Sesuai'],
                [SHOW, REQUIRED, I::TEXT,   'alergi',                    'Alergi'],
                [SHOW, REQUIRED, I::INDEX,  'id_penandaan_area',         'Penandaan Area'],
                [SHOW, REQUIRED, I::SELECT, 'is_resiko_aspirasi',        'Risiko Aspirasi'],
                [SHOW, REQUIRED, I::TEXT,   'rencana_aspirasi',          'Rencana Aspirasi'],
                [SHOW, REQUIRED, I::SELECT, 'is_resiko_hilang_darah',    'Risiko Hilang Darah'],
                [SHOW, REQUIRED, I::TEXT,   'jalur_iv_line',             'Jalur IV Line'],
                [SHOW, REQUIRED, I::TEXT,   'rencana_hilang_darah',      'Rencana Hilang Darah'],
                [SHOW, REQUIRED, I::INDEX,  'id_kesiapan_anestesi',      'Kesiapan Anestesi'],
                [SHOW, REQUIRED, I::TEXT,   'rencana_kesiapan_anestesi', 'Rencana Kesiapan Anestesi'],
                [SHOW, REQUIRED, I::TEXT,   'id_perawat_ok',             'ID Perawat OK'],
            ],
        );
    }
}
