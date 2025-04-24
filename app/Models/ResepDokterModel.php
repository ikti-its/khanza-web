<?php

namespace App\Models;

use CodeIgniter\Model;

class ResepDokterModel extends Model
{
    protected $table = 'sik.resep_dokter';
    protected $primaryKey = 'no_resep'; // if no unique key, this is optional

    protected $allowedFields = [
        'no_resep',
        'kode_barang',
        'jumlah',
        'aturan_pakai',
        'embalase',
        'tuslah'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
}
