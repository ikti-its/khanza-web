<?php

namespace App\Models;

use CodeIgniter\Model;

class RawatInapModel extends Model
{
    protected $table = 'sik.rawat_inap';
    protected $primaryKey = 'nomor_rawat';

    protected $allowedFields = [
        'nomor_rawat',
        'nomor_rm',
        'nama_pasien',
        'alamat_pasien',
        'penanggung_jawab'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
}
