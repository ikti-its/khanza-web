<?php
declare(strict_types=1);

namespace App\Features\Operasi\PermintaanOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanOperasiModel(),
            [
                ['Operasi',            'operasi'],
                ['Permintaan Operasi', 'permintaan_operasi'],
            ],
            'Permintaan Operasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::JADWALKAN,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan', 'ID Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'nomor_reg',     'Nomor Registrasi'],
                [SHOW, REQUIRED, I::TEXT,   'id_dokter',     'Kode Dokter'],
                [SHOW, REQUIRED, I::SELECT, 'id_tindakan',   'Tindakan Operasi'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_minta', 'Tanggal Minta'],
                [SHOW, REQUIRED, I::SELECT, 'is_cito',       'CITO'],
            ],
        );
    }
}
