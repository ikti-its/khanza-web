<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanAnestesiSedasiMonitoring;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class CatatanAnestesiSedasiMonitoringController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanAnestesiSedasiMonitoringModel(),
            [
                ['Operasi',                            'operasi'],
                ['Catatan Anestesi Sedasi Monitoring', 'catatan_anestesi_sedasi_monitoring'],
            ],
            'Catatan Anestesi Sedasi Monitoring',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_monitoring',       'ID Monitoring'],
                [HIDE, OPTIONAL, I::INDEX,  'id_catatan_anestesi', 'ID Catatan Anestesi'],
                [SHOW, REQUIRED, I::TEXT,   'nama_monitoring',     'Nama Monitoring'],
                [SHOW, REQUIRED, I::SELECT, 'is_digunakan',        'Digunakan'],
                [SHOW, OPTIONAL, I::TEXT,   'keterangan',          'Keterangan'],
            ],
        );
    }
}
