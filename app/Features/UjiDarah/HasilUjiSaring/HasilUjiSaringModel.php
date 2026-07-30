<?php
declare(strict_types=1);

namespace App\Features\UjiDarah\HasilUjiSaring;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilUjiSaringModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilUjiSaringDatabase(),
            [
                'id_uji_saring' => V::DEFAULT(),
                'tanggal_uji'   => V::DEFAULT(),
                'hbsag'         => V::DEFAULT(),
                'hcv'           => V::DEFAULT(),
                'hiv'           => V::DEFAULT(),
                'sifilis'       => V::DEFAULT(),
                'malaria'       => V::DEFAULT(),
            ],
            [
                'id_pengambilan_darah' => ['nomor_pengambilan', 'no_bag'],
                'id_metode_uji'        => ['nama_metode'],
                'id_petugas'           => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }

    /**
     * Memastikan tanggal input uji saring valid
     * @param string $idPengambilan
     * @param string $tanggalUjiInput
     * @return void
     * @throws \InvalidArgumentException
     */
    public function validasiTanggalUji(string $idPengambilan, string $tanggalUjiInput): void
    {
        $pengambilan = $this->db
            ->table('donor.pengambilan_darah')
            ->select('tanggal_pengambilan')
            ->where('id_pengambilan_darah', $idPengambilan)
            ->get()
            ->getRowArray();
        
        if (!$pengambilan || empty($pengambilan['tanggal_pengambilan'])) {
            throw new \InvalidArgumentException(
                'Gagal menyimpan! Tanggal pengambilan darah tidak ditemukan.',
            );
        }

        try {
            $tglPengambilan = (new \DateTimeImmutable(
                $pengambilan['tanggal_pengambilan']
            ))->setTime(0, 0, 0);

            $tglUji = (new \DateTimeImmutable(
                $tanggalUjiInput
            ))->setTime(0, 0, 0);

            $hariIni = new \DateTimeImmutable('today');
        } catch (\Exception) {
            throw new \InvalidArgumentException(
                'Gagal menyimpan! Format tanggal pengambilan darah atau tanggal uji saring tidak valid.',
            );
        }

        if ($tglUji < $tglPengambilan) {
            throw new \InvalidArgumentException(
                'Gagal menyimpan! Tanggal uji saring tidak boleh mendahului tanggal pengambilan darah.',
            );
        }

        if ($tglUji > $hariIni) {
            throw new \InvalidArgumentException(
                'Gagal menyimpan! Tanggal uji saring tidak boleh melebihi tanggal hari ini.',
            );
        }
    }
}
