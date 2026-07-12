<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreInduksiAirway;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PengkajianPreInduksiAirwayController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengkajianPreInduksiAirwayModel(),
            [
                ['Operasi',                       'operasi'],
                ['Pengkajian Pre Induksi Airway', 'pengkajian_pre_induksi_airway'],
            ],
            'Pengkajian Pre Induksi Airway',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_airway',     'ID Airway'],
                [HIDE, OPTIONAL, I::INDEX,  'id_pengkajian', 'ID Pengkajian'],
                [SHOW, REQUIRED, I::TEXT,   'jenis_airway',  'Jenis Airway'],
                [SHOW, REQUIRED, I::TEXT,   'nomor',         'Nomor'],
                [SHOW, REQUIRED, I::TEXT,   'jenis',         'Jenis'],
                [SHOW, REQUIRED, I::NUMBER, 'fiksasi_cm',    'Fiksasi (cm)'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',    'Keterangan'],
            ],
        );
    }
}
