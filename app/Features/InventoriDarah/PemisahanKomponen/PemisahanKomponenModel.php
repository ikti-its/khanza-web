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
     * Mengambil data pemisahan komponen
     * @param int $limit
     * @param int $offset
     * @return list<array<string, mixed>>
     */
    public function get_data_tabel(int $limit, int $offset): array
    {
        return $this->db
            ->table('inventori_darah.pemisahan_komponen pk')
            ->select([
                'pk.id_pemisahan',
                'pk.tanggal_pemisahan',
                'pd.nomor_pengambilan',
                'pd.no_bag',
                's.nama_shift',
                'o_petugas.nama AS nama_petugas'
            ])
            ->join('donor.pengambilan_darah pd', 'pd.id_pengambilan_darah = pk.id_pengambilan_darah', 'inner')
            ->join('operasional.shift s', 's.id_shift = pk.id_shift', 'left')
            ->join('role.petugas pt', 'pt.id_petugas = pk.id_petugas', 'inner')
            ->join('person.orang o_petugas', 'o_petugas.id_orang = pt.id_orang', 'inner')
            ->orderBy('pk.tanggal_pemisahan', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
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

    /**
     * Mengambil data hasil pemisahan
     */
    public function getHasilPemisahan(int|string $idPemisahan): array
    {
        return $this->db->table('inventori_darah.pemisahan_komponen_detail as pkd')
            ->select('pkd.*, k.kode_komponen, k.nama_komponen, k.masa_berlaku_hari')
            ->join('inventori_darah.komponen_darah k', 'k.id_komponen = pkd.id_komponen')
            ->where('pkd.id_pemisahan', $idPemisahan)
            ->get()
            ->getResultArray();
    }

    /**
     * Mengambil data penggunaan BHP medis pemisahan
     */
    public function getBhpMedisDetail(int|string $idPemisahan): array
    {
        $bhpMedis = $this->db->table('logistik_utd.medis_pemisahan')
            ->where('id_pemisahan', $idPemisahan)
            ->get()
            ->getResultArray();

        $modelMasterMedis = new \App\Features\InventoriMedis\DataBarang\DataBarangModel();
        foreach ($bhpMedis as $k => $v) {
            $masterItem = $modelMasterMedis->find($v['id_barang']);
            $bhpMedis[$k]['kode_barang'] = $masterItem['kode_barang'] ?? '-';
            $bhpMedis[$k]['nama_barang'] = $masterItem['nama'];
        }

        return $bhpMedis;
    }

    /**
     * Mengambil data penggunaan BHP non medis pemisahan
     */
    public function getBhpPenunjangDetail(int|string $idPemisahan): array
    {
        $bhpPenunjang = $this->db->table('logistik_utd.penunjang_pemisahan')
            ->where('id_pemisahan', $idPemisahan)
            ->get()
            ->getResultArray();

        $modelMasterPenunjang = new \App\Features\InventoriNonMedis\Barang\BarangModel();
        foreach ($bhpPenunjang as $k => $v) {
            $masterItem = $modelMasterPenunjang->find($v['id_barang']);
            $bhpPenunjang[$k]['kode_barang'] = $masterItem['kode_barang'] ?? '-';
            $bhpPenunjang[$k]['nama_barang'] = $masterItem['nama_barang'];
        }

        return $bhpPenunjang;
    }
}
