<?php
declare(strict_types=1);

namespace App\Features\Donor\SkriningDonor;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class SkriningDonorModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkriningDonorDatabase(),
            [
                'id_skrining'      => V::DEFAULT(),
                'berat_badan'      => V::DEFAULT(),
                'sistolik'         => V::DEFAULT(),
                'diastolik'        => V::DEFAULT(),
                'nadi'             => V::DEFAULT(),
                'suhu_tubuh'       => V::DEFAULT(),
                'kadar_hemoglobin' => V::DEFAULT(),
            ],
            [
                'id_kunjungan'       => [
                    'nomor_kunjungan',
                    'id_pendonor' => [
                        'nomor_pendonor',
                        'id_orang' => ['nama']
                    ],
                ],
                'id_hasil_anamnesis' => ['nama_hasil'],
                'id_status_skrining' => ['nama_status_skrining'],
            ],
        );
    }

    /**
     * Menentukan status kelayakan donor berdasarkan Permenkes
     * @param array $rawPost
     * @return array ['status' => int, 'alasan' => array]
     */
    public function hitungOtomatisStatusSkrining(array $rawPost): array
    {
        $beratBadan = (float)  ($rawPost['berat_badan'] ?? 0);
        $sistolik   = (int)    ($rawPost['sistolik'] ?? 0);
        $diastolik  = (int)    ($rawPost['diastolik'] ?? 0);
        $nadi       = (int)    ($rawPost['nadi'] ?? 0);
        $suhu       = (float)  ($rawPost['suhu_tubuh'] ?? 0);
        $hb         = (float)  ($rawPost['kadar_hemoglobin'] ?? 0);
        $anamnesis  = (int)    ($rawPost['id_hasil_anamnesis'] ?? 0);

        $alasanGagal = [];

        if ($beratBadan < 45.0) {
            $alasanGagal[] = 'Berat Badan';
        }
        if ($sistolik < 90 || $sistolik > 160) {
            $alasanGagal[] = 'Tekanan Sistolik';
        }
        if ($diastolik < 60 || $diastolik > 100) {
            $alasanGagal[] = 'Tekanan Diastolik';
        }
        if (!empty($sistolik) && !empty($diastolik) && ($sistolik - $diastolik) <= 20) {
            $alasanGagal[] = 'Selisih Sistolik & Diastolik (wajib > 20 mmHg)';
        }
        if ($nadi < 50 || $nadi > 100) {
            $alasanGagal[] = 'Denyut Nadi';
        }
        if ($suhu < 36.5 || $suhu > 37.5) {
            $alasanGagal[] = 'Suhu Tubuh';
        }
        if ($hb < 12.5 || $hb > 17.0) {
            $alasanGagal[] = 'Kadar Hemoglobin (HB)';
        }

        if ($anamnesis !== 1) {
            $alasanGagal[] = 'Hasil Wawancara Anamnesis';
        }

        if (!empty($alasanGagal)) {
            return [
                'status' => 2,
                'alasan' => $alasanGagal
            ];
        }

        return [
            'status' => 1,
            'alasan' => []
        ];
    }
}
