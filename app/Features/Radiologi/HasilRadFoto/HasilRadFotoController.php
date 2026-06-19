<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRadFoto;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class HasilRadFotoController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadFotoModel(),
            [
                ['Radiologi',            'radiologi'],
                ['Foto Hasil Radiologi', 'foto-hasil-radiologi'],
            ],
            'Foto Hasil Radiologi',
            [
                A::READ,
                A::CREATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_rad_foto',  'ID Foto'],
                [HIDE, REQUIRED, I::INDEX, 'id_hasil_rad', 'Hasil Radiologi'],
                [SHOW, REQUIRED, I::TEXT,  'nama_file',    'Nama File'],
                [SHOW, REQUIRED, I::DTIME, 'tgl_upload',   'Waktu Upload'],
            ],
        );
    }
}
