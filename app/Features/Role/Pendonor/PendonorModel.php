<?php
declare(strict_types=1);

namespace App\Features\Role\Pendonor;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PendonorModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PendonorDatabase(),
            [
                'id_pendonor'            => V::DEFAULT(),
                'nomor_pendonor'         => V::DEFAULT(),
                'nomor_telepon'          => V::DEFAULT(),
                'tanggal_donor_terakhir' => V::DEFAULT(),
            ],
            [
                'id_orang'  => [
                    'nama',
                    'nik',
                    'id_jenis_kelamin'  => ['nama_jenis_kelamin'],
                    'tempat_lahir_kota' => ['nama_kota'],
                    'tanggal_lahir',
                    'id_alamat'         => ['alamat_lengkap'],
                    'id_golongan_darah' => ['nama_golongan_darah'],
                ],
                'id_rhesus' => ['kode_rhesus'],
            ],
        );
    }

    /**
     * Mengambil data pendonor
     * @param int|null $limit
     * @param int $offset
     * @return list<array<string, mixed>>
     */
    public function get_data_tabel(null|int $limit = null, int $offset = 0): array
    {
        $builder = $this->db
            ->table('role.pendonor p')
            ->select([
                'p.id_pendonor',
                'p.nomor_pendonor',
                'p.nomor_telepon',
                'p.tanggal_donor_terakhir',
                'p.id_rhesus',
                'o.nama',
                'o.nik',
                'o.id_jenis_kelamin',
                'o.id_golongan_darah',
                'o.tanggal_lahir',
                'jk.nama_jenis_kelamin',
                'g.nama_golongan_darah',
                'r.kode_rhesus',
            ])
            ->join('person.orang o', 'o.id_orang = p.id_orang', 'inner')
            ->join('person.jenis_kelamin jk', 'jk.id_jenis_kelamin = o.id_jenis_kelamin', 'left')
            ->join('darah.golongan_darah g', 'g.id_golongan_darah = o.id_golongan_darah', 'left')
            ->join('darah.rhesus r', 'r.id_rhesus = p.id_rhesus', 'left')
            ->orderBy('p.nomor_pendonor', 'DESC');

        if ($limit !== null && $limit > 0) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Memastikan usia Calon Pendonor minimal 17 tahun
     * @param string $tanggalLahir
     * @throws \RuntimeException
     */
    public function validasiUsiaMinimal(string $tanggalLahir): void
    {
        $tglLahir    = new \DateTime($tanggalLahir);
        $tglSekarang = new \DateTime(); // Hari ini

        $selisih   = $tglLahir->diff($tglSekarang);
        $tahunUmur = (int) $selisih->format('%r%y');

        if ($tahunUmur < 17) {
            throw new \RuntimeException(
                "Gagal Menyimpan! Usia calon pendonor saat ini adalah {$tahunUmur} Tahun. Syarat menjadi pendonor darah adalah minimal berusia 17 Tahun.",
            );
        }
    }

    /**
     * Mengubah tanggal donor terakhir dan mencatat riwayat masa berlakunya
     * @param int|string $idPendonor
     * @param string|null $tanggalDonor
     * @param string|null $waktuValid
     */
    public function setTanggalDonorTerakhir(
        int|string $idPendonor,
        null|string $tanggalDonor,
        null|string $waktuValid = null,
    ): void {
        $tanggalDonor = $this->normalisasiTanggal($tanggalDonor);

        $modelRiwayat = new \App\Features\Role\RiwayatTanggalDonor\RiwayatTanggalDonorModel();
        $modelRiwayat->catatTanggalDonor($idPendonor, $tanggalDonor, $waktuValid);

        $this->update($idPendonor, [
            'tanggal_donor_terakhir' => $tanggalDonor,
        ]);
    }

    /**
     * Mengembalikan tanggal donor terakhir ke riwayat sebelumnya
     * @param int|string $idPendonor
     * @param string|null $waktuValid
     */
    public function rollbackTanggalDonorTerakhir(int|string $idPendonor, null|string $waktuValid = null): void
    {
        $modelRiwayat    = new \App\Features\Role\RiwayatTanggalDonor\RiwayatTanggalDonorModel();
        $tanggalRollback = $modelRiwayat->rollbackKeRiwayatSebelumnya($idPendonor, $waktuValid);

        $this->update($idPendonor, [
            'tanggal_donor_terakhir' => $tanggalRollback,
        ]);
    }

    /**
     * Menyamakan format tanggal menjadi YYYY-MM-DD atau null
     */
    private function normalisasiTanggal(null|string $tanggal): null|string
    {
        if ($tanggal === null || $tanggal === '') {
            return null;
        }

        return date('Y-m-d', strtotime($tanggal));
    }
}
