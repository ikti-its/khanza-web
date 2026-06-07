<?php
declare(strict_types=1);

namespace App\Features\Poliklinik;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PoliklinikController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PoliklinikModel(),
            [
                ['Poliklinik', 'poliklinik'],
            ],
            'Poliklinik',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_poliklinik',         'ID Poliklinik'],
                [SHOW, REQUIRED, I::TEXT,  'kode_poliklinik',       'Kode Poliklinik'],
                [SHOW, REQUIRED, I::TEXT,  'nama_poliklinik',       'Nama Poliklinik'],
                [SHOW, REQUIRED, I::MONEY, 'biaya_registrasi_baru', 'Biaya Registrasi Baru'],
                [SHOW, REQUIRED, I::MONEY, 'biaya_registrasi_lama', 'Biaya Registrasi Lama'],
            ],
        );
    }
}