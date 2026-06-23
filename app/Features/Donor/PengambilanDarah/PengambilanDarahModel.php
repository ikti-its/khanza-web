<?php
declare(strict_types=1);

namespace App\Features\Donor\PengambilanDarah;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengambilanDarahModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengambilanDarahDatabase(),
            [
                'id_pengambilan_darah' => V::DEFAULT(),
                'nomor_pengambilan'    => V::DEFAULT(),
                'tanggal_pengambilan'  => V::DEFAULT(),
                'no_bag'               => V::DEFAULT(),
            ],
            [
                'id_kunjungan'          => ['nomor_kunjungan'],
                'id_shift'              => ['nama_shift'],
                'id_jenis_bag'          => ['nama_jenis_bag'],
                'id_jenis_donor'        => ['nama_jenis_donor'],
                'id_lokasi_pengambilan' => ['nama_lokasi'],
                'id_petugas'            => [
                    'id_orang' => ['nama']
                ],
                'id_status_pengambilan' => ['nama_status_pengambilan'],
            ],
        );
    }

    /**
     * Memastikan tanggal input pengambilan darah valid
     * @param string $tanggalInput
     * @throws \RuntimeException
     */
    public function validasiTanggalOperasional(string $tanggalInput): void
    {
        $tglDonasi   = new \DateTime($tanggalInput);
        $tglSekarang = new \DateTime();

        if ($tglDonasi->format('Y-m-d') > $tglSekarang->format('Y-m-d')) {
            throw new \RuntimeException("Gagal Menyimpan! Tanggal pengambilan darah tidak boleh melebihi waktu saat ini.");
        }

        $selisih = $tglDonasi->diff($tglSekarang);
        $selisihHari = (int) $selisih->format('%r%a');

        if ($selisihHari > 2) {
            throw new \RuntimeException("Gagal Menyimpan! Batas keterlambatan input data pengambilan darah untuk petugas maksimal adalah 2 hari ke belakang. Jika ingin menginput data Mobile Unit yang lebih lama, harap laporkan ke Supervisor / Kepala Ruangan.");
        }
    }

    /**
     * Memperbarui tanggal donor terakhir pada entitas Pendonor
     * @param int $idStatusPengambilan
     * @param array $dataPengambilan
     * @param string|int $idPengambilanDarah
     */
    public function syncTanggalDonorTerakhir(int $idStatusPengambilan, array $dataPengambilan, string|int $idPengambilanDarah): void
    {
        $idKunjungan = $dataPengambilan['id_kunjungan'] ?? null;

        if (empty($idKunjungan)) {
            return;
        }

        $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
        $kunjunganRow   = $modelKunjungan->find($idKunjungan);

        if (empty($kunjunganRow['id_pendonor'])) {
            return;
        }

        $idPendonor    = $kunjunganRow['id_pendonor'];
        $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();

        if ($idStatusPengambilan === 1) {
            $modelPendonor->update($idPendonor, [
                'tanggal_donor_terakhir' => $dataPengambilan['tanggal_pengambilan'] ?? date('Y-m-d')
            ]);
        }
        else {
            $riwayatSuksesTerakhir = $this->builder()
                ->select('donor.pengambilan_darah.tanggal_pengambilan')
                ->join('donor.kunjungan', 'donor.kunjungan.id_kunjungan = donor.pengambilan_darah.id_kunjungan', 'inner')
                ->where('donor.kunjungan.id_pendonor', $idPendonor)
                ->where('donor.pengambilan_darah.id_status_pengambilan', 1)
                ->where('donor.pengambilan_darah.id_pengambilan_darah !=', $idPengambilanDarah)
                ->orderBy('donor.pengambilan_darah.tanggal_pengambilan', 'DESC')
                ->limit(1)
                ->get()
                ->getRowArray();

            $tanggalRollback = $riwayatSuksesTerakhir ? $riwayatSuksesTerakhir['tanggal_pengambilan'] : null;

            $modelPendonor->update($idPendonor, [
                'tanggal_donor_terakhir' => $tanggalRollback
            ]);
        }
    }

    /**
     * Memeriksa apakah kantong darah sudah pernah dipisahkan
     * @param string|int $idPengambilanDarah
     * @return bool
     */
    public function apakahSudahDipisahkan(string|int $idPengambilanDarah): bool
    {
        return $this->db->table('inventori_darah.pemisahan_komponen')
            ->where('id_pengambilan_darah', $idPengambilanDarah)
            ->countAllResults() > 0;
    }

    /**
     * Memeriksa apakah kantong darah sudah pernah melalui Uji Saring IMLTD
     * @param string|int $idPengambilanDarah
     * @return bool
     */
    public function apakahSudahDiuji(string|int $idPengambilanDarah): bool
    {
        return $this->db->table('uji_darah.hasil_uji_saring')
            ->where('id_pengambilan_darah', $idPengambilanDarah)
            ->countAllResults() > 0;
    }
}
