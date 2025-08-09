<?php

namespace App\Models;

use CodeIgniter\Model;

class RujukanModel extends Model
{
    protected $table = 'sik.rujukan_keluar'; // 👈 Replace this
    protected $primaryKey = 'nomor_rawat';

    protected $allowedFields = [
        'nomor_rujuk',
        'nomor_rm',
        'nama_pasien',
        'tempat_rujuk',
        'tanggal_rujuk',
        'jam_rujuk',
        'keterangan_diagnosa',
        'dokter_perujuk',
        'kategori_rujuk',
        'pengantaran',
        'keterangan',
    ];
}

