<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PermintaanDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanDarahModel(),
            [
                ['Pelayanan Darah',  'pelayanan_darah'],
                ['Permintaan Darah', 'permintaan_darah'],
            ],
            'Permintaan Darah',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'no_permintaan',        'Nomor Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'id_rawat_inap',        'ID Rawat Inap'],
                [SHOW, REQUIRED, I::TEXT,   'id_dokter_pengirim',   'ID Dokter Pengirim'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_permintaan',   'Tanggal Permintaan'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_permintaan', 'ID Status Permintaan'],
            ],
        );
    }
}
