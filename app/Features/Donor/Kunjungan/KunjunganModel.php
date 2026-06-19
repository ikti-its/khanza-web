<?php
declare(strict_types=1);

namespace App\Features\Donor\Kunjungan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class KunjunganModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KunjunganDatabase(),
            [
                'id_kunjungan'      => V::DEFAULT(),
                'nomor_antrian'     => V::DEFAULT(),
                'nomor_kunjungan'   => V::DEFAULT(),
                'tanggal_kunjungan' => V::DEFAULT(),
            ],
            [
                'id_pendonor' => [
                    'nomor_pendonor',
                    'id_orang'  => [
                        'nama',
                        'nik',
                        'tanggal_lahir',
                        'id_jenis_kelamin'  => ['nama_jenis_kelamin'],
                        'id_golongan_darah' => ['nama_golongan_darah']
                    ],
                    'id_rhesus' => ['kode_rhesus']
                ],
            ],
        );
    }

    /**
     * Memeriksa apakah pendonor lolos syarat interval jeda donor UTD
     * @param int|string $idPendonor
     * @param string $tglKunjunganInput
     * @return array ['status' => bool, 'message' => string]
     */
    public function cekIntervalMedis(int|string $idPendonor, string $tglKunjunganInput): array
    {
        $minimalJedaHari = 60;

        $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
        $dataPendonor  = $modelPendonor->find($idPendonor);

        if ($dataPendonor && !empty($dataPendonor['tanggal_donor_terakhir'])) {
            $tglDonorTerakhir = new \DateTime($dataPendonor['tanggal_donor_terakhir']);
            $tglKunjunganBaru = new \DateTime($tglKunjunganInput);

            $selisih = $tglDonorTerakhir->diff($tglKunjunganBaru);
            $jumlahHari = (int) $selisih->format('%r%a');

            if ($jumlahHari < $minimalJedaHari) {
                $sisaHari = $minimalJedaHari - $jumlahHari;
                $konversiBulan = (int) ($minimalJedaHari / 30);
                
                return [
                    'status'  => false,
                    'message' => "Gagal Mendaftarkan Kunjungan! Kebijakan UTD menetapkan syarat interval jeda minimal {$konversiBulan} bulan ({$minimalJedaHari} hari). Calon pendonor baru bisa donor kembali dalam {$sisaHari} hari lagi."
                ];
            }
        }

        return ['status' => true, 'message' => ''];
    }
}
