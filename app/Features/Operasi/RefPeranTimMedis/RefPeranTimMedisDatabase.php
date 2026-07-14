<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefPeranTimMedis;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

/**
 * Referensi peran anggota tim medis tambahan pada jadwal operasi.
 * Kolom `kode` mengikuti nama kolom tagihan_operasi (tanpa prefix "id_")
 * agar bisa dipetakan langsung saat membuat tagihan. Operator 1 dan
 * Dokter Anestesi tidak ada di sini karena sudah diwakili field
 * Dokter Bedah dan Dokter Anestesi di jadwal.
 * Kolom `jenis` ('dokter'|'petugas') menandai anggota tim jenis apa yang
 * boleh mengisi peran tsb, dipakai JadwalOperasiController untuk validasi.
 */
final class RefPeranTimMedisDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'ref_peran_tim_medis',
            [
                'id_peran'   => T::ID(100),
                'kode'       => T::TEXT(),
                'nama_peran' => T::TEXT(),
                'jenis'      => T::TEXT(),
            ],
            'id_peran',
            ['kode'],
            [],
            true,
            'peran_tim_medis.csv',
        );
    }
}
