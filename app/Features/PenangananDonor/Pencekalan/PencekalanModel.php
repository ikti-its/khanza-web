<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\Pencekalan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PencekalanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PencekalanDatabase(),
            [
                'id_pencekalan'   => V::DEFAULT(),
                'tanggal_mulai'   => V::DEFAULT(),
                'tanggal_selesai' => V::DEFAULT(),
                'keterangan'      => V::DEFAULT(),
            ],
            [
                'id_kunjungan'         => ['nomor_kunjungan'],
                'id_jenis_pencekalan'  => ['nama_jenis_pencekalan'],
                'id_shift'             => ['nama_shift'],
                'id_petugas'           => [
                    'id_orang' => ['nama']
                ],
                'id_status_pencekalan' => ['nama_status_pencekalan'],
            ],
        );
    }

    private const STATUS_AKTIF   = 1;
    private const STATUS_SELESAI = 2;

    /**
     * Menyinkronkan status pencekalan menjadi selesai jika tanggal selesai sudah lewat
     */
    public function sinkronkanStatusPencekalan(): void
    {
        $this->builder()
            ->set('id_status_pencekalan', self::STATUS_SELESAI)
            ->where('tanggal_selesai IS NOT NULL', null, false)
            ->where('tanggal_selesai <', date('Y-m-d'))
            ->where('id_status_pencekalan !=', self::STATUS_SELESAI)
            ->update();
    }

    /**
     * Mengatur status pencekalan default menjadi aktif
     */
    public function setStatusAktif(array &$data): void
    {
        $data['id_status_pencekalan'] = self::STATUS_AKTIF;
    }

    /**
     * Menentukan status pencekalan berdasarkan tanggal selesai
     */
    public function tentukanStatusPencekalan(?string $tanggalSelesai): int
    {
        if ($tanggalSelesai === null || $tanggalSelesai === '') {
            return self::STATUS_AKTIF;
        }

        return $tanggalSelesai < date('Y-m-d') ? self::STATUS_SELESAI : self::STATUS_AKTIF;
    }

    /**
     * Memfilter data pencekalan berdasarkan status
     */
    public function applyFilterStatus(string $filter): self
    {
        $statusMap = [
            'aktif'   => 1,
            'selesai' => 2,
        ];

        if (isset($statusMap[$filter])) {
            $this->set_filter('id_status_pencekalan', $statusMap[$filter]);
        }

        return $this;
    }
}
