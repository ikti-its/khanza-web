<?php
declare(strict_types=1);

namespace App\Features\Donor\PengambilanDarah;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use CodeIgniter\Database\Exceptions\DatabaseException;

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
                    'id_orang' => ['nama'],
                ],
                'id_status_pengambilan' => ['nama_status_pengambilan'],
            ],
        );
    }

    /**
     * Mengambil data pengambilan darah
     * @param int $limit
     * @param int $offset
     * @return list<array<string, mixed>>
     * 
     * @throws DatabaseException
     */
    public function get_data_tabel(int $limit, int $offset): array
    {
        $query = $this->db
            ->table('donor.pengambilan_darah pd')
            ->select([
                'pd.id_pengambilan_darah',
                'pd.nomor_pengambilan',
                'pd.no_bag',
                'pd.tanggal_pengambilan',
                'o.nama',
                'jb.nama_jenis_bag',
                'sp.id_status_pengambilan',
                'sp.nama_status_pengambilan',
            ])
            ->join('donor.kunjungan k', 'k.id_kunjungan = pd.id_kunjungan', 'inner')
            ->join('role.pendonor p', 'p.id_pendonor = k.id_pendonor', 'inner')
            ->join('person.orang o', 'o.id_orang = p.id_orang', 'inner')
            ->join('donor.jenis_bag jb', 'jb.id_jenis_bag = pd.id_jenis_bag', 'left')
            ->join('donor.status_pengambilan sp', 'sp.id_status_pengambilan = pd.id_status_pengambilan', 'left')
            ->orderBy('pd.tanggal_pengambilan', 'DESC')
            ->limit($limit, $offset)
            ->get();
        
        if ($query === false) {
            return [];
        }

        /** @var list<array<string, mixed>> $data */
        $data = $query->getResultArray();

        return $data;
    }

    /**
     * Memastikan tanggal input pengambilan darah valid
     * @param string $tanggalInput
     * @throws \InvalidArgumentException
     */
    public function validasiTanggalOperasional(string $tanggalInput): void
    {
        $tglDonasi   = new \DateTime($tanggalInput);
        $tglSekarang = new \DateTime();

        if ($tglDonasi->format('Y-m-d') > $tglSekarang->format('Y-m-d')) {
            throw new \InvalidArgumentException(
                'Gagal Menyimpan! Tanggal pengambilan darah tidak boleh melebihi waktu saat ini.',
            );
        }
    }

    /**
     * Memperbarui tanggal donor terakhir pendonor berdasarkan status pengambilan
     * @param int $idStatusPengambilan
     * @param array<string, mixed> $dataPengambilan
     * @param array<string, mixed>|null $dataPengambilanSebelumnya
     */
    public function syncTanggalDonorTerakhir(
        int $idStatusPengambilan,
        array $dataPengambilan,
        null|array $dataPengambilanSebelumnya = null,
    ): void {
        $idKunjungan = (int) ($dataPengambilan['id_kunjungan'] ?? $dataPengambilanSebelumnya['id_kunjungan'] ?? 0);

        if (empty($idKunjungan)) {
            return;
        }

        $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
        $kunjunganRow   = $modelKunjungan->find($idKunjungan);

        if (empty($kunjunganRow['id_pendonor'])) {
            return;
        }

        $idPendonor    = (int) $kunjunganRow['id_pendonor'];
        $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
        $dataPendonor  = $modelPendonor->find($idPendonor) ?? [];

        $tanggalAktif = null;
        if (isset($dataPendonor['tanggal_donor_terakhir']) && is_string($dataPendonor['tanggal_donor_terakhir'])) {
            $tanggalAktif   = $this->normalisasiTanggal($dataPendonor['tanggal_donor_terakhir']);
        }

        $tanggalPengambilan = null;
        if (isset($dataPengambilan['tanggal_pengambilan']) && is_string($dataPengambilan['tanggal_pengambilan'])) {
            $tanggalPengambilan   = $this->normalisasiTanggal($dataPengambilan['tanggal_pengambilan']);
        }

        $tanggalSebelumnya = null;
        if (isset($dataPengambilanSebelumnya['tanggal_pengambilan']) && is_string($dataPengambilanSebelumnya['tanggal_pengambilan'])) {
            $tanggalSebelumnya   = $this->normalisasiTanggal($dataPengambilanSebelumnya['tanggal_pengambilan']);
        }

        $statusSebelumnya   = (int) (
            $dataPengambilanSebelumnya['id_status_pengambilan'] ?? $dataPengambilan['id_status_pengambilan'] ?? 0
        );

        if ($idStatusPengambilan === 1) {
            $tanggalDonorBaru = $tanggalPengambilan ?? date('Y-m-d');

            if ($tanggalAktif === null || $tanggalDonorBaru >= $tanggalAktif || $tanggalAktif === $tanggalSebelumnya) {
                $modelPendonor->setTanggalDonorTerakhir($idPendonor, $tanggalDonorBaru);
            }

            return;
        }

        if ($statusSebelumnya !== 1) {
            return;
        }

        $tanggalYangDibatalkan = $tanggalSebelumnya ?? $tanggalPengambilan;

        if ($tanggalAktif !== null && $tanggalYangDibatalkan !== null && $tanggalAktif === $tanggalYangDibatalkan) {
            $modelPendonor->rollbackTanggalDonorTerakhir($idPendonor);
        }
    }

    /**
     * Menyamakan format tanggal menjadi YYYY-MM-DD atau null
     */
    private function normalisasiTanggal(null|string $tanggal): null|string
    {
        if ($tanggal === null || $tanggal === '') {
            return null;
        }

        $timestamp = strtotime($tanggal);

        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d', $timestamp);
    }

    /**
     * Memeriksa apakah kantong darah sudah pernah dipisahkan
     * @param string|int $idPengambilanDarah
     * @return bool
     * 
     * @throws DatabaseException
     */
    public function apakahSudahDipisahkan(string|int $idPengambilanDarah): bool
    {
        return (
            $this->db
                ->table('inventori_darah.pemisahan_komponen')
                ->where('id_pengambilan_darah', $idPengambilanDarah)
                ->countAllResults() > 0
        );
    }

    /**
     * Memeriksa apakah kantong darah sudah pernah melalui Uji Saring IMLTD
     * @param string|int $idPengambilanDarah
     * @return bool
     * 
     * @throws DatabaseException
     */
    public function apakahSudahDiuji(string|int $idPengambilanDarah): bool
    {
        return (
            $this->db
                ->table('uji_darah.hasil_uji_saring')
                ->where('id_pengambilan_darah', $idPengambilanDarah)
                ->countAllResults() > 0
        );
    }

    /**
     * Mengambil daftar nomor bag yang sudah terpakai
     * @param int|string|null $excludeId ID pengambilan darah yang dikecualikan (dipakai saat mode ubah)
     * @return list<string>
     * 
     * @throws DatabaseException
     */
    public function getDaftarNoBagTerpakai(null|int|string $excludeId = null): array
    {
        $builder = $this->db
            ->table('donor.pengambilan_darah')
            ->select('no_bag');

        if ($excludeId !== null) {
            $builder->where('id_pengambilan_darah !=', $excludeId);
        }

        $query = $builder->get();

        if ($query === false) {
            return [];
        }

        /** @var list<array<string, mixed>> $rows */
        $rows = $query->getResultArray();

        $noBagTerpakai = [];

        foreach ($rows as $row) {
            if (isset($row['no_bag']) && is_string($row['no_bag'])) {
                $noBagTerpakai[] = $row['no_bag'];
            }
        }

        return $noBagTerpakai;
    }

    /**
     * Mengambil data penggunaan BHP medis donor
     * @return list<array<string, mixed>>
     * 
     * @throws DatabaseException
     */
    public function getBhpMedisDetail(int|string $idPengambilan): array
    {
        $query = $this->db
            ->table('logistik_utd.medis_donor')
            ->where('id_pengambilan_darah', $idPengambilan)
            ->get();
        
        if ($query === false) {
            return [];
        }

        /** @var list<array<string, mixed>> $bhpMedis */
        $bhpMedis = $query->getResultArray();

        $modelMasterMedis = new \App\Features\InventoriMedis\DataBarang\DataBarangModel();
        foreach ($bhpMedis as $k => $v) {
            $idBarang = isset($v['id_barang']) ? (int) $v['id_barang'] : null;
            if (!is_int($idBarang)) {
                continue;
            }

            $masterItem = $modelMasterMedis->find($idBarang);
            if (!is_array($masterItem)) {
                continue;
            }

            $bhpMedis[$k]['kode_barang'] = $masterItem['kode_barang'] ?? '-';
            $bhpMedis[$k]['nama_barang'] = $masterItem['nama'] ?? '-';
        }

        return $bhpMedis;
    }

    /**
     * Mengambil data penggunaan BHP non medis donor
     * @return list<array<string, mixed>>
     *
     * @throws DatabaseException
     */
    public function getBhpPenunjangDetail(int|string $idPengambilan): array
    {
        $query = $this->db
            ->table('logistik_utd.penunjang_donor')
            ->where('id_pengambilan_darah', $idPengambilan)
            ->get();
        
        if ($query === false) {
            return [];
        }

        /** @var list<array<string, mixed>> $bhpPenunjang */
        $bhpPenunjang = $query->getResultArray();

        $modelMasterPenunjang = new \App\Features\InventoriNonMedis\Barang\BarangModel();
        foreach ($bhpPenunjang as $k => $v) {
            $idBarang = isset($v['id_barang']) ? (int) $v['id_barang'] : null;
            if (!is_int($idBarang)) {
                continue;
            }

            $masterItem = $modelMasterPenunjang->find($idBarang);
            if (!is_array($masterItem)) {
                continue;
            }

            $bhpPenunjang[$k]['kode_barang'] = $masterItem['kode_barang'] ?? '-';
            $bhpPenunjang[$k]['nama_barang'] = $masterItem['nama_barang'] ?? '-';
        }

        return $bhpPenunjang;
    }
}
