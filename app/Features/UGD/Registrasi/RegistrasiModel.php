<?php
declare(strict_types=1);

namespace App\Features\UGD\Registrasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RegistrasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RegistrasiDatabase(),
            [
                'id_registrasi'    => V::DEFAULT(),
                'nomor_reg'        => V::DEFAULT(),
                'nomor_rawat'      => V::DEFAULT(),
                'tanggal_reg'      => V::DEFAULT(),
                'biaya_registrasi' => V::DEFAULT(),
            ],
            [
                'id_dokter'        => [
                    'kode_dokter',
                    'id_orang' => ['nama'],
                    'spesialis',
                ],
                'id_pasien'        => [
                    'id_orang' => ['nama'],
                    'nomor_rm',
                ],
                'id_pj_pasien'     => ['nama'],
                'id_alamat_pj'     => ['alamat_lengkap'],
                'hubungan_pj'      => ['nama_hubungan_pj'],
                'jenis_bayar'      => ['nama_jenis_bayar'],
                'status_rawat'     => ['nama_status_rawat'],
                'status_bayar'     => ['nama_status_bayar'],
                'id_status_triase' => ['nama_status_triase'],
            ],
        );
    }

    /**
     * Memfilter data registrasi UGD berdasarkan status triase
     */
    public function applyFilter(string $filter): self
    {
        $statusMap = [
            'belum_ditriase' => 1,
            'sudah_ditriase' => 2,
        ];

        if (isset($statusMap[$filter])) {
            $this->set_filter('id_status_triase', $statusMap[$filter]);
        }

        return $this;
    }

    /**
     * Memperbarui status triase
     */
    public function updateStatusTriase(int|string $idRegistrasi, int $idStatusTriase): bool
    {
        return $this->db
            ->table('ugd.registrasi')
            ->where('id_registrasi', $idRegistrasi)
            ->update([
                'id_status_triase' => $idStatusTriase,
            ]);
    }
}
