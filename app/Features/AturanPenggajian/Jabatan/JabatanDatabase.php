<?php
declare(strict_types=1);

namespace App\Features\AturanPenggajian\Jabatan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class JabatanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'penggajian',
            'jabatan',
            [
                'no_jabatan'    => T::ID(20),
                'jenis_jabatan' => T::TEXT(),
                'nama_jabatan'  => T::TEXT(),
                'jenjang'       => T::TEXT(),
                'tunjangan'     => T::MONEY(),
            ],
            'no_jabatan',
            [],
            [],
            true,
            'jabatan.csv',
        );
    }
}
