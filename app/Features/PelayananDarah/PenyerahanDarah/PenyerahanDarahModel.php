<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PenyerahanDarah;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PenyerahanDarahModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenyerahanDarahDatabase(),
            [
                'id_penyerahan'      => V::DEFAULT(),
                'no_penyerahan'      => V::DEFAULT(),
                'tanggal_penyerahan' => V::DEFAULT(),
                'keterangan'         => V::DEFAULT(),
                'pengambil_darah'    => V::DEFAULT(),
                'alamat_pengambil'   => V::DEFAULT(),
                'besar_ppn'          => V::DEFAULT(),
            ],
            [
                'id_permintaan'        => ['no_permintaan'],
                'id_shift'             => ['nama_shift'],
                'id_petugas_cross'     => [
                    'id_orang' => ['nama'],
                ],
                'id_status_pembayaran' => ['nama_status_pembayaran'],
                'id_rekening'          => ['nama_akun'],
                'id_penanggung_jawab'  => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }

    /**
     * Validasi akumulasi kuota kantong darah penyerahan vs permintaan
     * @param int $idPermintaan
     * @param array|null $stokDarahTerpilih
     * @return int Akumulasi total kantong setelah ditambah data baru
     * @throws \RuntimeException
     */
    public function validasiDanHitungKuota(int $idPermintaan, null|array $stokDarahTerpilih): int
    {
        $totalDiminta =
            (int) $this->db
                ->table('pelayanan_darah.permintaan_darah_detail')
                ->where('id_permintaan', $idPermintaan)
                ->selectSum('jumlah')
                ->get()
                ->getRowArray()['jumlah'] ?? 0;

        if ($totalDiminta <= 0) {
            throw new \RuntimeException(
                'Gagal menyimpan! Jumlah kantong pada detail dokumen permintaan darah bernilai 0.',
            );
        }

        $listIdPenyerahanLama = $this->db
            ->table('pelayanan_darah.penyerahan_darah')
            ->where('id_permintaan', $idPermintaan)
            ->select('id_penyerahan')
            ->get()
            ->getResultArray();

        $totalSudahDiserahkan = 0;
        if (!empty($listIdPenyerahanLama)) {
            $arrayIdPenyerahanLama = array_column($listIdPenyerahanLama, 'id_penyerahan');

            $totalSudahDiserahkan = (int) $this->db
                ->table('pelayanan_darah.penyerahan_darah_detail')
                ->whereIn('id_penyerahan', $arrayIdPenyerahanLama)
                ->countAllResults();
        }

        $jumlahBaruDiinput = !empty($stokDarahTerpilih) && is_array($stokDarahTerpilih) ? count($stokDarahTerpilih) : 0;
        $akumulasiMasaDepan = $totalSudahDiserahkan + $jumlahBaruDiinput;

        if ($totalSudahDiserahkan >= $totalDiminta) {
            throw new \RuntimeException(
                'Gagal menyimpan! Seluruh kantong darah untuk permintaan ini sudah diserahkan sebelumnya.',
            );
        }

        if ($akumulasiMasaDepan > $totalDiminta) {
            $sisaKuota = $totalDiminta - $totalSudahDiserahkan;
            throw new \RuntimeException(
                "Gagal menyimpan! Jumlah darah yang dimasukkan melebihi batas kuota dokter. Sisa darah yang boleh diserahkan hanya: {$sisaKuota} kantong.",
            );
        }

        return $akumulasiMasaDepan;
    }

    /**
     * Sinkronisasi pembaruan status dokumen permintaan darah secara otomatis
     * @param int $idPermintaan
     */
    public function sinkronisasiStatusPermintaan(int $idPermintaan): void
    {
        $totalDiminta =
            (int) $this->db
                ->table('pelayanan_darah.permintaan_darah_detail')
                ->where('id_permintaan', $idPermintaan)
                ->selectSum('jumlah')
                ->get()
                ->getRowArray()['jumlah'] ?? 0;

        $listIdPenyerahan = $this->db
            ->table('pelayanan_darah.penyerahan_darah')
            ->where('id_permintaan', $idPermintaan)
            ->select('id_penyerahan')
            ->get()
            ->getResultArray();

        $totalDiserahkan = 0;
        if (!empty($listIdPenyerahan)) {
            $arrayId         = array_column($listIdPenyerahan, 'id_penyerahan');
            $totalDiserahkan = (int) $this->db
                ->table('pelayanan_darah.penyerahan_darah_detail')
                ->whereIn('id_penyerahan', $arrayId)
                ->countAllResults();
        }

        $idStatusTerbaru = 1;
        if ($totalDiserahkan >= $totalDiminta && $totalDiminta > 0) {
            $idStatusTerbaru = 3;
        } elseif ($totalDiserahkan > 0) {
            $idStatusTerbaru = 2;
        }

        $this->db
            ->table('pelayanan_darah.permintaan_darah')
            ->where('id_permintaan', $idPermintaan)
            ->update(['id_status_permintaan' => $idStatusTerbaru]);
    }

    /**
     * Mengambil data penggunaan BHP medis penyerahan
     */
    public function getBhpMedisDetail(int|string $idPenyerahan): array
    {
        $bhpMedis = $this->db
            ->table('logistik_utd.medis_penyerahan')
            ->where('id_penyerahan', $idPenyerahan)
            ->get()
            ->getResultArray();

        $modelMasterMedis = new \App\Features\InventoriMedis\DataBarang\DataBarangModel();
        foreach ($bhpMedis as $k => $v) {
            $masterItem                  = $modelMasterMedis->find($v['id_barang']);
            $bhpMedis[$k]['kode_barang'] = $masterItem['kode_barang'] ?? '-';
            $bhpMedis[$k]['nama_barang'] = $masterItem['nama'] ?? '-';
        }

        return $bhpMedis;
    }

    /**
     * Mengambil data penggunaan BHP non medis penyerahan
     */
    public function getBhpPenunjangDetail(int|string $idPenyerahan): array
    {
        $bhpPenunjang = $this->db
            ->table('logistik_utd.penunjang_penyerahan')
            ->where('id_penyerahan', $idPenyerahan)
            ->get()
            ->getResultArray();

        $modelMasterPenunjang = new \App\Features\InventoriNonMedis\Barang\BarangModel();
        foreach ($bhpPenunjang as $k => $v) {
            $masterItem                      = $modelMasterPenunjang->find($v['id_barang']);
            $bhpPenunjang[$k]['kode_barang'] = $masterItem['kode_barang'] ?? '-';
            $bhpPenunjang[$k]['nama_barang'] = $masterItem['nama_barang'] ?? '-';
        }

        return $bhpPenunjang;
    }
}
