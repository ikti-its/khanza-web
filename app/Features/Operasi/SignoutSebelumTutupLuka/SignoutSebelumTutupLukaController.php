<?php
declare(strict_types=1);

namespace App\Features\Operasi\SignoutSebelumTutupLuka;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class SignoutSebelumTutupLukaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SignoutSebelumTutupLukaModel(),
            [
                ['Operasi',                     'operasi'],
                ['Sign Out Sebelum Tutup Luka', 'signout_sebelum_tutupluka'],
            ],
            'Sign Out Sebelum Tutup Luka',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_signout',              'ID Sign Out'],
                [SHOW, REQUIRED, I::INDEX,  'id_jadwal',               'Jadwal Operasi'],
                [SHOW, REQUIRED, I::DATE,   'waktu_signout',           'Waktu Sign Out'],
                [SHOW, REQUIRED, I::INDEX,  'id_sn_cn',                'SN/CN'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_bedah',         'Dokter Bedah'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_anestesi',      'Dokter Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_tindakan',             'Tindakan'],
                [SHOW, REQUIRED, I::SELECT, 'is_nama_tindakan_sesuai', 'Nama Tindakan Sesuai'],
                [SHOW, REQUIRED, I::SELECT, 'is_kasa_lengkap',         'Kasa Lengkap'],
                [SHOW, REQUIRED, I::SELECT, 'is_instrumen_lengkap',    'Instrumen Lengkap'],
                [SHOW, REQUIRED, I::SELECT, 'is_alat_tajam_lengkap',   'Alat Tajam Lengkap'],
                [SHOW, REQUIRED, I::INDEX,  'id_label_spesimen',       'Label Spesimen'],
                [SHOW, REQUIRED, I::INDEX,  'id_formulir_spesimen',    'Formulir Spesimen'],
                [SHOW, REQUIRED, I::SELECT, 'is_konfirmasi_bedah',     'Konfirmasi Bedah'],
                [SHOW, REQUIRED, I::SELECT, 'is_konfirmasi_anestesi',  'Konfirmasi Anestesi'],
                [SHOW, REQUIRED, I::SELECT, 'is_konfirmasi_perawat',   'Konfirmasi Perawat'],
                [SHOW, REQUIRED, I::TEXT,   'catatan_pemulihan',       'Catatan Pemulihan'],
                [SHOW, REQUIRED, I::TEXT,   'id_perawat_ok',           'ID Perawat OK'],
            ],
        );
    }
}
