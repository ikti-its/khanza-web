<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanAnestesiSedasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class CatatanAnestesiSedasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanAnestesiSedasiModel(),
            [
                ['Operasi',                 'operasi'],
                ['Catatan Anestesi Sedasi', 'catatan_anestesi_sedasi'],
            ],
            'Catatan Anestesi Sedasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_catatan_anestesi',  'ID Catatan Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_jadwal',            'Jadwal Operasi'],
                [SHOW, REQUIRED, I::DATE,   'waktu_catatan',        'Waktu Catatan'],
                [SHOW, REQUIRED, I::TEXT,   'diagnosa_pra_bedah',   'Diagnosa Pra Bedah'],
                [SHOW, REQUIRED, I::INDEX,  'id_tindakan',          'Tindakan'],
                [SHOW, REQUIRED, I::TEXT,   'diagnosa_paska_bedah', 'Diagnosa Paska Bedah'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_anestesi',   'Dokter Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_bedah',      'Dokter Bedah'],
                [SHOW, REQUIRED, I::INDEX,  'id_perawat_anestesi',  'Perawat Anestesi'],
                [SHOW, REQUIRED, I::INDEX,  'id_perawat_bedah',     'Perawat Bedah'],
                [SHOW, REQUIRED, I::TIME,   'jam_pengkajian',       'Jam Pengkajian'],
                [SHOW, REQUIRED, I::INDEX,  'id_kesadaran',         'Kesadaran'],
                [SHOW, REQUIRED, I::NUMBER, 'sistolik',             'Sistolik'],
                [SHOW, REQUIRED, I::NUMBER, 'diastolik',            'Diastolik'],
                [SHOW, REQUIRED, I::NUMBER, 'nadi',                 'Nadi'],
                [SHOW, REQUIRED, I::NUMBER, 'respiratory_rate',     'Respiratory Rate'],
                [SHOW, REQUIRED, I::TEMP,   'suhu',                 'Suhu'],
                [SHOW, REQUIRED, I::NUMBER, 'saturasi_o2',          'Saturasi O2'],
                [SHOW, REQUIRED, I::NUMBER, 'tinggi_badan_cm',      'Tinggi Badan (cm)'],
                [SHOW, REQUIRED, I::NUMBER, 'berat_badan_kg',       'Berat Badan (kg)'],
                [SHOW, REQUIRED, I::INDEX,  'id_golongan_darah',    'Golongan Darah'],
                [SHOW, REQUIRED, I::INDEX,  'id_rhesus',            'Rhesus'],
                [SHOW, REQUIRED, I::NUMBER, 'hemoglobin',           'Hemoglobin'],
                [SHOW, REQUIRED, I::NUMBER, 'hematokrit',           'Hematokrit'],
                [SHOW, REQUIRED, I::NUMBER, 'leukosit',             'Leukosit'],
                [SHOW, REQUIRED, I::NUMBER, 'trombosit',            'Trombosit'],
                [SHOW, REQUIRED, I::NUMBER, 'bleeding_time_bt',     'Bleeding Time (BT)'],
                [SHOW, REQUIRED, I::NUMBER, 'clotting_time_ct',     'Clotting Time (CT)'],
                [SHOW, REQUIRED, I::NUMBER, 'gula_darah_sewaktu',   'Gula Darah Sewaktu'],
                [SHOW, REQUIRED, I::TEXT,   'klinis_lain_lain',     'Klinis Lain-lain'],
                [SHOW, REQUIRED, I::INDEX,  'id_asa',               'ASA'],
                [SHOW, REQUIRED, I::SELECT, 'is_alergi',            'Alergi'],
                [SHOW, REQUIRED, I::TEXT,   'ket_alergi',           'Keterangan Alergi'],
                [SHOW, REQUIRED, I::TEXT,   'penyulit_pra',         'Penyulit Pra'],
                [SHOW, REQUIRED, I::SELECT, 'is_lanjut_tindakan',   'Lanjut Tindakan'],
                [SHOW, REQUIRED, I::INDEX,  'id_jenis_sedasi',      'Jenis Sedasi'],
                [SHOW, REQUIRED, I::TEXT,   'ket_sedasi',           'Keterangan Sedasi'],
                [SHOW, REQUIRED, I::SELECT, 'is_epidural',          'Epidural'],
                [SHOW, REQUIRED, I::SELECT, 'is_spinal',            'Spinal'],
                [SHOW, REQUIRED, I::SELECT, 'is_anestesi_umum',     'Anestesi Umum'],
                [SHOW, REQUIRED, I::TEXT,   'ket_anestesi_umum',    'Keterangan Anestesi Umum'],
                [SHOW, REQUIRED, I::SELECT, 'is_blok_perifer',      'Blok Perifer'],
                [SHOW, REQUIRED, I::TEXT,   'ket_blok_perifer',     'Keterangan Blok Perifer'],
                [SHOW, REQUIRED, I::SELECT, 'is_batal_tindakan',    'Batal Tindakan'],
                [SHOW, REQUIRED, I::TEXT,   'alasan_batal',         'Alasan Batal'],
            ],
        );
    }
}
