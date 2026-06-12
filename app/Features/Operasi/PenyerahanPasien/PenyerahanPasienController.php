<?php
declare(strict_types=1);

namespace App\Features\Operasi\PenyerahanPasien;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenyerahanPasienController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenyerahanPasienModel(),
            [
                ['Operasi',           'operasi'],
                ['Penyerahan Pasien', 'penyerahan_pasien'],
            ],
            'Penyerahan Pasien',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penyerahan',            'ID Penyerahan'],
                [SHOW, REQUIRED, I::INDEX,  'id_jadwal',                'Jadwal Operasi'],
                [SHOW, REQUIRED, I::DATE,   'waktu_masuk_asal',         'Waktu Masuk Asal'],
                [SHOW, REQUIRED, I::DATE,   'waktu_pindah',             'Waktu Pindah'],
                [SHOW, REQUIRED, I::INDEX,  'id_indikasi',              'Indikasi'],
                [SHOW, REQUIRED, I::TEXT,   'ket_indikasi',             'Keterangan Indikasi'],
                [SHOW, REQUIRED, I::INDEX,  'id_ruang_asal',            'Ruang Asal'],
                [SHOW, REQUIRED, I::INDEX,  'id_ruang_selanjutnya',     'Ruang Selanjutnya'],
                [SHOW, REQUIRED, I::INDEX,  'id_metode',                'Metode'],
                [SHOW, REQUIRED, I::TEXT,   'diagnosa_utama',           'Diagnosa Utama'],
                [SHOW, REQUIRED, I::TEXT,   'diagnosa_sekunder',        'Diagnosa Sekunder'],
                [SHOW, REQUIRED, I::TEXT,   'prosedur_dilakukan',       'Prosedur Dilakukan'],
                [SHOW, REQUIRED, I::TEXT,   'obat_diberikan',           'Obat Diberikan'],
                [SHOW, REQUIRED, I::TEXT,   'pemeriksaan_penunjang',    'Pemeriksaan Penunjang'],
                [SHOW, REQUIRED, I::SELECT, 'is_disetujui',             'Disetujui'],
                [SHOW, REQUIRED, I::TEXT,   'nama_pemberi_persetujuan', 'Nama Pemberi Persetujuan'],
                [SHOW, REQUIRED, I::INDEX,  'id_hubungan',              'Hubungan'],
                [SHOW, REQUIRED, I::INDEX,  'asal_id_keadaan',          'Keadaan Asal'],
                [SHOW, REQUIRED, I::NUMBER, 'asal_sistolik',            'Sistolik Asal'],
                [SHOW, REQUIRED, I::NUMBER, 'asal_diastolik',           'Diastolik Asal'],
                [SHOW, REQUIRED, I::NUMBER, 'asal_nadi',                'Nadi Asal'],
                [SHOW, REQUIRED, I::NUMBER, 'asal_respiratory_rate',    'Respiratory Rate Asal'],
                [SHOW, REQUIRED, I::TEMP,   'asal_suhu',                'Suhu Asal'],
                [SHOW, REQUIRED, I::TEXT,   'asal_keluhan',             'Keluhan Asal'],
                [SHOW, REQUIRED, I::INDEX,  'tiba_id_keadaan',          'Keadaan Tiba'],
                [SHOW, REQUIRED, I::NUMBER, 'tiba_sistolik',            'Sistolik Tiba'],
                [SHOW, REQUIRED, I::NUMBER, 'tiba_diastolik',           'Diastolik Tiba'],
                [SHOW, REQUIRED, I::NUMBER, 'tiba_nadi',                'Nadi Tiba'],
                [SHOW, REQUIRED, I::NUMBER, 'tiba_respiratory_rate',    'Respiratory Rate Tiba'],
                [SHOW, REQUIRED, I::TEMP,   'tiba_suhu',                'Suhu Tiba'],
                [SHOW, REQUIRED, I::TEXT,   'tiba_keluhan',             'Keluhan Tiba'],
                [SHOW, REQUIRED, I::INDEX,  'id_perawat_menyerahkan',   'Perawat Menyerahkan'],
                [SHOW, REQUIRED, I::INDEX,  'id_perawat_menerima',      'Perawat Menerima'],
            ],
        );
    }
}
