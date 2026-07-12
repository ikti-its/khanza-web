<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PermintaanDarah;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanDarahModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanDarahDatabase(),
            [
                'id_permintaan'      => V::DEFAULT(),
                'no_permintaan'      => V::DEFAULT(),
                'tanggal_permintaan' => V::DEFAULT(),
            ],
            [
                'id_registrasi'        => [
                    'nomor_rawat',
                    'id_pasien' => [
                        'nomor_rm',
                        'id_orang' => ['nama'],
                    ],
                ],
                'id_dokter_pengirim'   => [
                    'id_orang' => ['nama'],
                ],
                'id_status_permintaan' => ['nama_status_permintaan'],
            ],
        );
    }

    /**
     * Status permintaan darah yang detailnya masih boleh diubah
     */
    public const STATUS_BELUM_DIPROSES = 1;

    /**
     * Memastikan detail permintaan darah hanya boleh diubah jika status masih Belum Diproses
     * @param string|int $idPermintaan
     * @return bool
     */
    public function isDetailBisaDiubah(string|int $idPermintaan): bool
    {
        $row = $this->find($idPermintaan);

        return (int) ($row['id_status_permintaan'] ?? self::STATUS_BELUM_DIPROSES) === self::STATUS_BELUM_DIPROSES;
    }

    /**
     * Mengambil data permintaan darah
     * @param int $limit
     * @param int $offset
     * @param bool $hanyaBelumTerpenuhi
     * @return list<array<string, mixed>>
     */
    public function get_data_tabel(int $limit = 0, int $offset = 0, bool $hanyaBelumTerpenuhi = false): array
    {
        $builder = $this->db
            ->table('pelayanan_darah.permintaan_darah pd')
            ->select("
                pd.id_permintaan,
                pd.no_permintaan,
                reg.nomor_rawat,
                pas.nomor_rm,
                CONCAT(pas.nomor_rm, ' / ', reg.nomor_rawat) AS identitas_rawat,
                o.nama,
                pd.tanggal_permintaan,
                sp.nama_status_permintaan,
                sp.nama_status_permintaan AS status
            ")
            ->join('registrasi.registrasi reg', 'reg.id_registrasi = pd.id_registrasi', 'inner')
            ->join('role.pasien pas', 'pas.id_pasien = reg.id_pasien', 'inner')
            ->join('person.orang o', 'o.id_orang = pas.id_orang', 'inner')
            ->join('pelayanan_darah.status_permintaan sp', 'sp.id_status_permintaan = pd.id_status_permintaan', 'left');

        if ($hanyaBelumTerpenuhi) {
            $builder->where('pd.id_status_permintaan !=', 3);
        }

        $builder->orderBy('pd.tanggal_permintaan', 'ASC');

        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }
}
