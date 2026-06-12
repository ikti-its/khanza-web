<?php
declare(strict_types=1);

namespace App\Features\Operasi\TimeOutSebelumInsisi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class TimeOutSebelumInsisiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TimeOutSebelumInsisiModel(),
            [
                ['Operasi',                 'operasi'],
                ['Time Out Sebelum Insisi', 'time_out_sebelum_insisi'],
            ],
            'Time Out Sebelum Insisi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_timeout',              'ID Time Out'],
                [SHOW, REQUIRED, I::INDEX,  'id_jadwal',               'Jadwal Operasi'],
                [SHOW, REQUIRED, I::DATE,   'waktu_timeout',           'Waktu Time Out'],
                [SHOW, REQUIRED, I::INDEX,  'id_sn_cn',                'SN/CN'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_bedah',         'Dokter Bedah'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_anestesi',      'Dokter Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_tindakan',             'Tindakan'],
                [SHOW, REQUIRED, I::SELECT, 'is_identitas_sesuai',     'Identitas Sesuai'],
                [SHOW, REQUIRED, I::SELECT, 'is_tindakan_sesuai',      'Tindakan Sesuai'],
                [SHOW, REQUIRED, I::SELECT, 'is_area_insisi_sesuai',   'Area Insisi Sesuai'],
                [SHOW, REQUIRED, I::INDEX,  'id_penandaan_area',       'Penandaan Area'],
                [SHOW, REQUIRED, I::NUMBER, 'perkiraan_waktu_jam',     'Perkiraan Waktu (jam)'],
                [SHOW, REQUIRED, I::SELECT, 'is_antibiotik',           'Antibiotik'],
                [SHOW, REQUIRED, I::TEXT,   'nama_antibiotik',         'Nama Antibiotik'],
                [SHOW, REQUIRED, I::TIME,   'waktu_antibiotik',        'Waktu Antibiotik'],
                [SHOW, REQUIRED, I::TEXT,   'antisipasi_hilang_darah', 'Antisipasi Hilang Darah'],
                [SHOW, REQUIRED, I::INDEX,  'id_hal_khusus',           'Hal Khusus'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan_hal_khusus',   'Keterangan Hal Khusus'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_steril',          'Tanggal Steril'],
                [SHOW, REQUIRED, I::SELECT, 'is_steril_dikonfirmasi',  'Steril Dikonfirmasi'],
                [SHOW, REQUIRED, I::SELECT, 'is_verifikasi_preop',     'Verifikasi Pre Op'],
                [SHOW, REQUIRED, I::TEXT,   'id_perawat_ok',           'ID Perawat OK'],
            ],
        );
    }
}
