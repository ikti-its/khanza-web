<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanPaskaOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class CatatanPaskaOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanPaskaOperasiModel(),
            [
                ['Operasi',               'operasi'],
                ['Catatan Paska Operasi', 'catatan_paska_operasi'],
            ],
            'Catatan Paska Operasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_catatan_paska',        'ID Catatan Paska'],
                [SHOW, REQUIRED, I::INDEX, 'id_jadwal',               'Jadwal Operasi'],
                [SHOW, REQUIRED, I::INDEX, 'id_dokter_bedah',         'Dokter Bedah'],
                [SHOW, REQUIRED, I::DATE,  'waktu_penilaian',         'Waktu Penilaian'],
                [SHOW, REQUIRED, I::TEXT,  'instruksi_rawat',         'Instruksi Rawat'],
                [SHOW, REQUIRED, I::TEXT,  'instruksi_cairan',        'Instruksi Cairan'],
                [SHOW, REQUIRED, I::TEXT,  'instruksi_antibiotik',    'Instruksi Antibiotik'],
                [SHOW, REQUIRED, I::TEXT,  'instruksi_analgetik',     'Instruksi Analgetik'],
                [SHOW, REQUIRED, I::TEXT,  'instruksi_medikamentosa', 'Instruksi Medikamentosa'],
                [SHOW, REQUIRED, I::TEXT,  'instruksi_diet',          'Instruksi Diet'],
                [SHOW, REQUIRED, I::TEXT,  'instruksi_penunjang',     'Instruksi Penunjang'],
                [SHOW, REQUIRED, I::TEXT,  'instruksi_transfusi',     'Instruksi Transfusi'],
                [SHOW, REQUIRED, I::TEXT,  'instruksi_lainnya',       'Instruksi Lainnya'],
            ],
        );
    }
}
