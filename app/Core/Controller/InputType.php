<?php
declare(strict_types=1);

namespace App\Core\Controller;

enum InputType: string
{
    case INDEX    = 'indeks';
    case FLOAT    = 'desimal';
    case MONEY    = 'uang';
    case EMPTY    = 'kosong';
    case DTIME    = 'tanggal_jam';
    case DATE     = 'tanggal';
    case TIME     = 'jam';
    case NAME     = 'nama';
    case TEXT     = 'teks';
    case BOOL     = 'bool';
    case TEMP     = 'suhu';
    case NUMBER   = 'jumlah';
    case SELECT   = 'status';
    case PASSW    = 'password';
    case READONLY = 'readonly';
    case MODAL    = 'modal';
}
