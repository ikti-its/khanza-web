<?php
declare(strict_types=1);

namespace App\Features\Operasi\JadwalOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class JadwalOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JadwalOperasiModel(),
            [
                ['Operasi',        'operasi'],
                ['Jadwal Operasi', 'jadwal_operasi'],
            ],
            'Jadwal Operasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_jadwal',          'ID Jadwal'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan',      'Permintaan Operasi'],
                [SHOW, REQUIRED, I::INDEX, 'id_ruangan',         'Ruangan'],
                [SHOW, REQUIRED, I::INDEX, 'id_tindakan',        'Tindakan'],
                [SHOW, REQUIRED, I::INDEX, 'id_dokter_bedah',    'Dokter Bedah'],
                [SHOW, REQUIRED, I::INDEX, 'id_dokter_anestesi', 'Dokter Anestesi'],
                [SHOW, REQUIRED, I::DATE,  'tanggal',            'Tanggal'],
                [SHOW, REQUIRED, I::TIME,  'waktu_mulai',        'Waktu Mulai'],
                [SHOW, REQUIRED, I::TIME,  'waktu_selesai',      'Waktu Selesai'],
                [SHOW, REQUIRED, I::INDEX, 'id_status',          'Status'],
            ],
        );
    }
}
