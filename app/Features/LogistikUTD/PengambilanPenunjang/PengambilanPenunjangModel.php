<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PengambilanPenunjang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengambilanPenunjangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengambilanPenunjangDatabase(),
            [
                'id_pengambilan_penunjang' => V::DEFAULT(),
                'jumlah'                   => V::DEFAULT(),
                'harga_beli'               => V::DEFAULT(),
                'tanggal_pengambilan'      => V::DEFAULT(),
                'keterangan'               => V::DEFAULT(),
            ],
            [
                'id_barang'         => ['kode_barang', 'nama_barang'],
                'id_petugas_gudang' => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }

    /**
     * Mengambil katalog barang non medis beserta akumulasi total mutasi masuk, penggunaan, dan rusak
     * @return list<array<string, mixed>>
     */
    public function get_katalog_dan_stok_ruangan(): array
    {
        $tabelMasterBarang = 'inventori_non_medis.barang';

        return $this->db
            ->table($tabelMasterBarang)
            ->select(
                $tabelMasterBarang
                . '.id_barang, '
                . $tabelMasterBarang
                . '.kode_barang, '
                . $tabelMasterBarang
                . '.nama_barang',
            )
            ->select(
                '(SELECT pp.harga_beli 
                  FROM logistik_utd.pengambilan_penunjang pp 
                  WHERE pp.id_barang = '
                . $tabelMasterBarang
                . '.id_barang 
                  ORDER BY pp.id_pengambilan_penunjang DESC LIMIT 1) AS harga',
            )
            ->select('(SELECT COALESCE(SUM(pp.jumlah), 0) 
                  FROM logistik_utd.pengambilan_penunjang pp 
                  WHERE pp.id_barang = ' . $tabelMasterBarang . '.id_barang) AS total_masuk')
            ->select('(SELECT COALESCE(SUM(pd.jumlah), 0) 
                  FROM logistik_utd.penunjang_donor pd 
                  WHERE pd.id_barang = '
            . $tabelMasterBarang
            . '.id_barang) AS total_terpakai_donor')
            ->select('(SELECT COALESCE(SUM(pp.jumlah), 0) 
                  FROM logistik_utd.penunjang_pemisahan pp
                  WHERE pp.id_barang = '
            . $tabelMasterBarang
            . '.id_barang) AS total_terpakai_pemisahan')
            ->select('(SELECT COALESCE(SUM(py.jumlah), 0) 
                  FROM logistik_utd.penunjang_penyerahan py
                  WHERE py.id_barang = '
            . $tabelMasterBarang
            . '.id_barang) AS total_terpakai_penyerahan')
            ->select('(SELECT COALESCE(SUM(pr.jumlah), 0) 
                  FROM logistik_utd.penunjang_rusak_detail pr
                  WHERE pr.id_barang = ' . $tabelMasterBarang . '.id_barang) AS total_rusak')
            ->get()
            ->getResultArray();
    }
}
