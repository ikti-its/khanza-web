<?php
declare(strict_types=1);

namespace App\Core\Controller;

enum ActionType: string
{
    case READ = 'baca';
    case CREATE = 'tambah';
    case UPDATE = 'ubah';
    case DELETE = 'hapus';
    case AUDIT  = 'audit';
    case PRINT  = 'cetak';
    case SEPARATE = 'pisah';
    case TEST = 'uji';
}