<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreop;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PengkajianPreopController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengkajianPreopModel(),
            [
                ['Operasi',                'operasi'],
                ['Pengkajian Pre-Operasi', 'pengkajian_pre_op'],
            ],
            'Pengkajian Pre-Operasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_pengkajian_pre',      'ID Pengkajian Pre'],
                [SHOW, REQUIRED, I::INDEX, 'id_jadwal',              'Jadwal Operasi'],
                [SHOW, REQUIRED, I::INDEX, 'id_dokter_bedah',        'Dokter Bedah'],
                [SHOW, REQUIRED, I::TIME,  'waktu_pengkajian',       'Waktu Pengkajian'],
                [SHOW, REQUIRED, I::TEXT,  'ringkasan_klinik',       'Ringkasan Klinik'],
                [SHOW, REQUIRED, I::TEXT,  'pemeriksaan_fisik',      'Pemeriksaan Fisik'],
                [SHOW, REQUIRED, I::TEXT,  'pemeriksaan_diagnostik', 'Pemeriksaan Diagnostik'],
                [SHOW, REQUIRED, I::TEXT,  'diagnosa_pre_operasi',   'Diagnosa Pre-Operasi'],
                [SHOW, REQUIRED, I::TEXT,  'rencana_tindakan',       'Rencana Tindakan'],
                [SHOW, REQUIRED, I::TEXT,  'persiapan_khusus',       'Persiapan Khusus'],
                [SHOW, REQUIRED, I::TEXT,  'terapi_pre_operasi',     'Terapi Pre-Operasi'],
            ],
        );
    }
}
