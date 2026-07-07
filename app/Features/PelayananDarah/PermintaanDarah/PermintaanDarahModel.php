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
                        'id_orang' => ['nama']
                    ],
                ],
                'id_dokter_pengirim'   => [
                    'id_orang' => ['nama']
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

        return (int)($row['id_status_permintaan'] ?? self::STATUS_BELUM_DIPROSES) === self::STATUS_BELUM_DIPROSES;
    }
}
