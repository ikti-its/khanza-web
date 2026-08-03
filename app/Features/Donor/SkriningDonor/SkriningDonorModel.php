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
                'id_skrining'       => V::DEFAULT(),
                'berat_badan'       => V::DEFAULT(),
                'sistolik'          => V::DEFAULT(),
                'diastolik'         => V::DEFAULT(),
                'nadi'              => V::DEFAULT(),
                'suhu_tubuh'        => V::DEFAULT(),
                'kadar_hemoglobin'  => V::DEFAULT(),
                'jawaban_kuesioner' => V::DEFAULT(),
            ],
            [
                'id_kunjungan'       => [
                    'nomor_kunjungan',
                    'id_pendonor' => [
                        'nomor_pendonor',
                        'id_orang' => ['nama'],
                    ],
                ],
                'id_hasil_anamnesis' => ['nama_hasil'],
                'id_status_skrining' => ['nama_status_skrining'],
            ],
        );
    }

    /**
     * Mengambil data skrining donor
     * @param int $limit
     * @param int $offset
     * @return list<array<string, mixed>>
     */
    public function get_data_tabel(int $limit, int $offset): array
    {
        return $this->db
            ->table('donor.skrining_donor sd')
            ->select([
                'sd.id_skrining',
                'k.nomor_kunjungan',
                'p.nomor_pendonor',
                'o.nama',
                'ss.nama_status_skrining',
            ])
            ->join('donor.kunjungan k', 'k.id_kunjungan = sd.id_kunjungan', 'inner')
            ->join('role.pendonor p', 'p.id_pendonor = k.id_pendonor', 'inner')
            ->join('person.orang o', 'o.id_orang = p.id_orang', 'inner')
            ->join('donor.status_skrining ss', 'ss.id_status_skrining = sd.id_status_skrining', 'left')
            ->orderBy('k.tanggal_kunjungan', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    private function toFloat(mixed $value): float
    {
        if (!is_numeric($value)) {
            return 0.0;
        }

        return (float) $value;
    }

    private function toInt(mixed $value): int
    {
        if (!is_numeric($value)) {
            return 0;
        }

        return (int) $value;
    }

    /**
     * Menentukan status kelayakan donor berdasarkan Permenkes
     * @param array $rawPost
     * @return array ['status' => int, 'alasan' => array]
     */
    public function hitungOtomatisStatusSkrining(array $rawPost): array
    {
        $beratBadan = $this->toFloat($rawPost['berat_badan'] ?? null);
        $sistolik   = $this->toInt($rawPost['sistolik'] ?? null);
        $diastolik  = $this->toInt($rawPost['diastolik'] ?? null);
        $nadi       = $this->toInt($rawPost['nadi'] ?? null);
        $suhu       = $this->toFloat($rawPost['suhu_tubuh'] ?? null);
        $hb         = $this->toFloat($rawPost['kadar_hemoglobin'] ?? null);
        $anamnesis  = $this->toInt($rawPost['id_hasil_anamnesis'] ?? null);

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
        if ($sistolik !== 0 && $diastolik !== 0 && ($sistolik - $diastolik) <= 20) {
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

        if ($alasanGagal !== []) {
            return [
                'status' => 2,
                'alasan' => $alasanGagal,
            ];
        }

        return [
            'status' => 1,
            'alasan' => [],
        ];
    }
}
