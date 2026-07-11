<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PengambilanMedis;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengambilanMedisModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengambilanMedisDatabase(),
            [
                'id_pengambilan_medis' => V::DEFAULT(),
                'jumlah'               => V::DEFAULT(),
                'harga_beli'           => V::DEFAULT(),
                'nama_bangsal'         => V::DEFAULT(),
                'tanggal_pengambilan'  => V::DEFAULT(),
                'keterangan'           => V::DEFAULT(),
                'nomor_batch'          => V::DEFAULT(),
                'nomor_faktur'         => V::DEFAULT(),
            ],
            [
                'id_barang' => ['kode_barang', 'nama'],
            ],
        );
    }

    /**
     * Mengambil katalog barang medis beserta akumulasi total mutasi masuk, penggunaan, dan rusak
     */
    public function get_katalog_dan_stok_ruangan(): array
    {
        $tabelMasterBarang = 'inventori_medis.data_barang';

        return $this->db
            ->table($tabelMasterBarang)
            ->select(
                $tabelMasterBarang
                . '.id_barang, '
                . $tabelMasterBarang
                . '.kode_barang, '
                . $tabelMasterBarang
                . '.nama as nama_barang',
            )
            ->select(
                '(SELECT pm.harga_beli 
                  FROM logistik_utd.pengambilan_medis pm 
                  WHERE pm.id_barang = '
                . $tabelMasterBarang
                . '.id_barang 
                  ORDER BY pm.id_pengambilan_medis DESC LIMIT 1) AS harga',
            )
            ->select('(SELECT COALESCE(SUM(pm.jumlah), 0)
                  FROM logistik_utd.pengambilan_medis pm
                  WHERE pm.id_barang = ' . $tabelMasterBarang . '.id_barang) AS total_masuk')
            ->select('(SELECT COALESCE(SUM(md.jumlah), 0)
                  FROM logistik_utd.medis_donor md 
                  WHERE md.id_barang = '
            . $tabelMasterBarang
            . '.id_barang) AS total_terpakai_donor')
            ->select('(SELECT COALESCE(SUM(mp.jumlah), 0)
                  FROM logistik_utd.medis_pemisahan mp
                  WHERE mp.id_barang = '
            . $tabelMasterBarang
            . '.id_barang) AS total_terpakai_pemisahan')
            ->select('(SELECT COALESCE(SUM(my.jumlah), 0)
                  FROM logistik_utd.medis_penyerahan my
                  WHERE my.id_barang = '
            . $tabelMasterBarang
            . '.id_barang) AS total_terpakai_penyerahan')
            ->select('(SELECT COALESCE(SUM(mr.jumlah), 0)
                  FROM logistik_utd.medis_rusak_detail mr
                  WHERE mr.id_barang = ' . $tabelMasterBarang . '.id_barang) AS total_rusak')
            ->get()
            ->getResultArray();
    }
}
