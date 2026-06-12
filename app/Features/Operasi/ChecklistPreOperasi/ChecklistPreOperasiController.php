<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPreOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class ChecklistPreOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPreOperasiModel(),
            [
                ['Operasi',               'operasi'],
                ['Checklist Pre Operasi', 'checklist_pre_operasi'],
            ],
            'Checklist Pre Operasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_checklist',           'ID Checklist'],
                [SHOW, REQUIRED, I::INDEX,  'id_jadwal',              'Jadwal Operasi'],
                [SHOW, REQUIRED, I::DATE,   'waktu_checklist',        'Waktu Checklist'],
                [SHOW, REQUIRED, I::INDEX,  'id_sn_cn',               'SN/CN'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_bedah',        'Dokter Bedah'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_anestesi',     'Dokter Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_tindakan',            'Tindakan'],
                [SHOW, REQUIRED, I::SELECT, 'is_identitas_sesuai',    'Identitas Sesuai'],
                [SHOW, REQUIRED, I::INDEX,  'id_keadaan_umum',        'Keadaan Umum'],
                [SHOW, REQUIRED, I::INDEX,  'id_penandaan_area',      'Penandaan Area'],
                [SHOW, REQUIRED, I::SELECT, 'is_ijin_bedah',          'Ijin Bedah'],
                [SHOW, REQUIRED, I::SELECT, 'is_ijin_anestesi',       'Ijin Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_ijin_transfusi',      'Ijin Transfusi'],
                [SHOW, REQUIRED, I::INDEX,  'id_persiapan_darah',     'Persiapan Darah'],
                [SHOW, REQUIRED, I::TEXT,   'ket_persiapan_darah',    'Keterangan Persiapan Darah'],
                [SHOW, REQUIRED, I::INDEX,  'id_perlengkapan_khusus', 'Perlengkapan Khusus'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas_ruangan',     'Petugas Ruangan'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas_ok',          'Petugas OK'],
            ],
        );
    }
}
