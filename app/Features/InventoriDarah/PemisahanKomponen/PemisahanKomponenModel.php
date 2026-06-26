<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\PemisahanKomponen;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PemisahanKomponenModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PemisahanKomponenDatabase(),
            [
                'id_pemisahan'      => V::DEFAULT(),
                'tanggal_pemisahan' => V::DEFAULT(),
            ],
            [
                'id_pengambilan_darah' => ['nomor_pengambilan'],
                'id_shift'             => ['nama_shift'],
                'id_petugas'           => [
                    'id_orang' => ['nama']
                ],
            ],
        );
    }

    /**
     * Memastikan tanggal input pemisahan valid
     * @param string $idPengambilan
     * @param string $tanggalPemisahanInput
     * @return void
     * @throws \InvalidArgumentException
     */
    public function validasiTanggalPemisahan(string $idPengambilan, string $tanggalPemisahanInput): void
    {
        $pengambilan = $this->db->table('donor.pengambilan_darah')
            ->select('tanggal_pengambilan')
            ->where('id_pengambilan_darah', $idPengambilan)
            ->get()
            ->getRowArray();

        if ($pengambilan && !empty($pengambilan['tanggal_pengambilan'])) {
            $tglPengambilan = new \DateTime($pengambilan['tanggal_pengambilan']);
            $tglPemisahan   = new \DateTime($tanggalPemisahanInput);

            if ($tglPemisahan < $tglPengambilan) {
                throw new \InvalidArgumentException('Gagal Menyimpan! Tanggal pemisahan komponen tidak boleh mendahului tanggal pengambilan darah.');
            }
        }
    }
}
