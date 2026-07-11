<?php
declare(strict_types=1);

namespace App\Features\Role\RiwayatTanggalDonor;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RiwayatTanggalDonorModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RiwayatTanggalDonorDatabase(),
            [
                'id_riwayat'    => V::DEFAULT(),
                'tanggal_donor' => V::DEFAULT(),
                'start_valid'   => V::DEFAULT(),
                'end_valid'     => V::DEFAULT(),
            ],
            [
                'id_pendonor' => [
                    'nomor_pendonor',
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }

    /**
     * Mengambil data riwayat tanggal donor untuk tabel utama
     * @param int|null $limit
     * @param int $offset
     * @return list<array<string, mixed>>
     */
    public function get_data_tabel(null|int $limit = null, int $offset = 0): array
    {
        $builder = $this->db
            ->table('role.riwayat_tanggal_donor rtd')
            ->select([
                'rtd.id_riwayat',
                'rtd.id_pendonor',
                'p.nomor_pendonor',
                'o.nama',
                'rtd.tanggal_donor',
                'rtd.start_valid',
                'rtd.end_valid',
            ])
            ->join('role.pendonor p', 'p.id_pendonor = rtd.id_pendonor', 'inner')
            ->join('person.orang o', 'o.id_orang = p.id_orang', 'inner')
            ->orderBy('rtd.id_pendonor', 'ASC')
            ->orderBy('rtd.start_valid', 'DESC')
            ->orderBy('rtd.id_riwayat', 'DESC');

        if ($limit !== null && $limit > 0) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Mengambil riwayat tanggal donor yang sedang aktif
     * @param int|string $idPendonor
     * @return array<string, mixed>|null
     */
    public function getRiwayatAktif(int|string $idPendonor): null|array
    {
        $row = $this
            ->builder()
            ->where('id_pendonor', $idPendonor)
            ->where('end_valid IS NULL', null, false)
            ->orderBy('start_valid', 'DESC')
            ->orderBy('id_riwayat', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    /**
     * Mencatat tanggal donor baru dan menutup riwayat aktif sebelumnya
     * @param int|string $idPendonor
     * @param string|null $tanggalDonor
     * @param string|null $startValid
     */
    public function catatTanggalDonor(
        int|string $idPendonor,
        null|string $tanggalDonor,
        null|string $startValid = null,
    ): void {
        $tanggalDonor = $this->normalisasiTanggal($tanggalDonor);
        $startValid   ??= date('Y-m-d H:i:s');

        $riwayatAktif = $this->getRiwayatAktif($idPendonor);
        $tanggalAktif = $this->normalisasiTanggal($riwayatAktif['tanggal_donor'] ?? null);

        if (!empty($riwayatAktif) && $tanggalAktif === $tanggalDonor) {
            return;
        }

        $this
            ->builder()
            ->where('id_pendonor', $idPendonor)
            ->where('end_valid IS NULL', null, false)
            ->update(['end_valid' => $startValid]);

        $this->insert([
            'id_pendonor'   => $idPendonor,
            'tanggal_donor' => $tanggalDonor,
            'start_valid'   => $startValid,
            'end_valid'     => null,
        ]);
    }

    /**
     * Mengembalikan riwayat aktif ke tanggal donor pada riwayat sebelumnya
     * @param int|string $idPendonor
     * @param string|null $startValid
     * @return string|null
     */
    public function rollbackKeRiwayatSebelumnya(int|string $idPendonor, null|string $startValid = null): null|string
    {
        $startValid   ??= date('Y-m-d H:i:s');
        $riwayatAktif = $this->getRiwayatAktif($idPendonor);

        if (empty($riwayatAktif)) {
            return null;
        }

        $this->builder()->where('id_riwayat', $riwayatAktif['id_riwayat'])->update(['end_valid' => $startValid]);

        $riwayatSebelumnya = $this
            ->builder()
            ->where('id_pendonor', $idPendonor)
            ->where('id_riwayat !=', $riwayatAktif['id_riwayat'])
            ->orderBy('start_valid', 'DESC')
            ->orderBy('id_riwayat', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        $tanggalRollback = $this->normalisasiTanggal($riwayatSebelumnya['tanggal_donor'] ?? null);

        $this->insert([
            'id_pendonor'   => $idPendonor,
            'tanggal_donor' => $tanggalRollback,
            'start_valid'   => $startValid,
            'end_valid'     => null,
        ]);

        return $tanggalRollback;
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
