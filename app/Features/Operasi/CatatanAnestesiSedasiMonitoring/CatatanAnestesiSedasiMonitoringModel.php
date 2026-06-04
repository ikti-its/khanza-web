<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanAnestesiSedasiMonitoring;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class CatatanAnestesiSedasiMonitoringModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanAnestesiSedasiMonitoringDatabase(),
            [
                'id_monitoring_catatan' => V::DEFAULT(),
                'is_digunakan'          => V::DEFAULT(),
                'keterangan'            => V::DEFAULT(),
            ],
            [
                'id_catatan_anestesi' => [],
                'id_monitoring'       => ['nama_monitoring'],
            ],
        );
    }
}
