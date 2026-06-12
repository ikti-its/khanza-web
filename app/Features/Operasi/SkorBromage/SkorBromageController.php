<?php
declare(strict_types=1);

namespace App\Features\Operasi\SkorBromage;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class SkorBromageController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkorBromageModel(),
            [
                ['Operasi',      'operasi'],
                ['Skor Bromage', 'skor_bromage'],
            ],
            'Skor Bromage',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_skor_bromage',    'ID Skor Bromage'],
                [SHOW, REQUIRED, I::INDEX,  'id_jadwal',          'Jadwal Operasi'],
                [SHOW, REQUIRED, I::DATE,   'waktu_penilaian',    'Waktu Penilaian'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',         'Petugas'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter_anestesi', 'Dokter Anestesi'],
                [SHOW, REQUIRED, I::NUMBER, 'skor_bromage',       'Skor Bromage'],
                [SHOW, REQUIRED, I::SELECT, 'is_boleh_pindah',    'Boleh Pindah'],
                [SHOW, REQUIRED, I::TEXT,   'catatan_keluar',     'Catatan Keluar'],
                [SHOW, REQUIRED, I::TEXT,   'instruksi_rr',       'Instruksi RR'],
            ],
        );
    }
}
