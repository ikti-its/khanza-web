<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreInduksi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PengkajianPreInduksiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengkajianPreInduksiModel(),
            [
                ['Operasi',                'operasi'],
                ['Pengkajian Pre Induksi', 'pengkajian_pre_induksi'],
            ],
            'Pengkajian Pre Induksi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_pengkajian',             'ID Pengkajian'],
                [SHOW, REQUIRED, I::INDEX,  'id_jadwal',                 'Jadwal Operasi'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_anestesi',        'Dokter Anestesi'],
                [SHOW, REQUIRED, I::DATE,   'waktu_pengkajian',          'Waktu Pengkajian'],
                [SHOW, REQUIRED, I::NUMBER, 'sistolik',                  'Sistolik'],
                [SHOW, REQUIRED, I::NUMBER, 'diastolik',                 'Diastolik'],
                [SHOW, REQUIRED, I::NUMBER, 'nadi',                      'Nadi'],
                [SHOW, REQUIRED, I::NUMBER, 'respiratory_rate',          'Respiratory Rate'],
                [SHOW, REQUIRED, I::TEMP,   'suhu',                      'Suhu'],
                [SHOW, REQUIRED, I::TEXT,   'elektrokardiogram',         'Elektrokardiogram'],
                [SHOW, REQUIRED, I::TEXT,   'vital_lain_lain',           'Vital Lain-lain'],
                [SHOW, REQUIRED, I::SELECT, 'is_sesuai_asesmen',         'Sesuai Asesmen'],
                [SHOW, REQUIRED, I::TEXT,   'perencanaan',               'Perencanaan'],
                [SHOW, REQUIRED, I::TEXT,   'infus_perifer',             'Infus Perifer'],
                [SHOW, REQUIRED, I::TEXT,   'kateter_vena_sentral_cvc',  'Kateter Vena Sentral (CVC)'],
                [SHOW, REQUIRED, I::INDEX,  'id_posisi',                 'Posisi'],
                [SHOW, REQUIRED, I::INDEX,  'id_premedikasi',            'Premedikasi'],
                [SHOW, REQUIRED, I::TEXT,   'ket_premedikasi',           'Keterangan Premedikasi'],
                [SHOW, REQUIRED, I::INDEX,  'id_induksi',                'Induksi'],
                [SHOW, REQUIRED, I::TEXT,   'ket_induksi',               'Keterangan Induksi'],
                [SHOW, REQUIRED, I::SELECT, 'is_intubasi_sesudah_tidur', 'Intubasi Sesudah Tidur'],
                [SHOW, REQUIRED, I::SELECT, 'is_intubasi_oral',          'Intubasi Oral'],
                [SHOW, REQUIRED, I::SELECT, 'is_intubasi_tracheostomi',  'Intubasi Tracheostomi'],
                [SHOW, REQUIRED, I::TEXT,   'intubasi_keterangan',       'Keterangan Intubasi'],
                [SHOW, REQUIRED, I::TEXT,   'sulit_ventilasi',           'Sulit Ventilasi'],
                [SHOW, REQUIRED, I::TEXT,   'sulit_intubasi',            'Sulit Intubasi'],
                [SHOW, REQUIRED, I::TEXT,   'ventilasi_keterangan',      'Keterangan Ventilasi'],
                [SHOW, REQUIRED, I::TEXT,   'teknik_regional_jenis',     'Teknik Regional Jenis'],
                [SHOW, REQUIRED, I::TEXT,   'teknik_regional_lokasi',    'Teknik Regional Lokasi'],
                [SHOW, REQUIRED, I::TEXT,   'teknik_regional_jarum',     'Teknik Regional Jarum'],
                [SHOW, REQUIRED, I::SELECT, 'is_kateter',                'Kateter'],
                [SHOW, REQUIRED, I::NUMBER, 'kateter_fiksasi_cm',        'Kateter Fiksasi (cm)'],
                [SHOW, REQUIRED, I::TEXT,   'obat_obatan',               'Obat-obatan'],
                [SHOW, REQUIRED, I::TEXT,   'komplikasi',                'Komplikasi'],
                [SHOW, REQUIRED, I::TEXT,   'hasil',                     'Hasil'],
            ],
        );
    }
}
