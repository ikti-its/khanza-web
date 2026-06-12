<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPostop;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class ChecklistPostopController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPostopModel(),
            [
                ['Operasi',                'operasi'],
                ['Checklist Post Operasi', 'checklist_postop'],
            ],
            'Checklist Post Operasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_checklist_post',    'ID Checklist Post'],
                [SHOW, REQUIRED, I::INDEX,  'id_jadwal',            'Jadwal Operasi'],
                [SHOW, REQUIRED, I::DATE,   'waktu_checklist',      'Waktu Checklist'],
                [SHOW, REQUIRED, I::INDEX,  'id_sn_cn',             'SN/CN'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_bedah',      'Dokter Bedah'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_anestesi',   'Dokter Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_tindakan',          'Tindakan'],
                [SHOW, REQUIRED, I::INDEX,  'id_kesadaran_pascaop', 'Kesadaran Pasca Op'],
                [SHOW, REQUIRED, I::TEXT,   'jenis_cairan_infus',   'Jenis Cairan Infus'],
                [SHOW, REQUIRED, I::INDEX,  'id_jaringan_pa_vc',    'Jaringan PA/VC'],
                [SHOW, REQUIRED, I::INDEX,  'id_kateter_urine',     'Kateter Urine'],
                [SHOW, REQUIRED, I::TIME,   'waktu_pasang_kateter', 'Waktu Pasang Kateter'],
                [SHOW, REQUIRED, I::INDEX,  'id_warna_urine',       'Warna Urine'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah_urine_cc',      'Jumlah Urine (cc)'],
                [SHOW, REQUIRED, I::TEXT,   'catatan_luka_operasi', 'Catatan Luka Operasi'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas_anestesi',  'Petugas Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas_ok',        'Petugas OK'],
            ],
        );
    }
}
