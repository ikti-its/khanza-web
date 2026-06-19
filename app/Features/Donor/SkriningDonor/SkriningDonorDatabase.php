<?php
declare(strict_types=1);

namespace App\Features\Donor\SkriningDonor;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class SkriningDonorDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'donor',
            'skrining_donor',
            [
                'id_skrining'        => T::ID(100_000_000),
                'id_kunjungan'       => T::FK_AUTO(),
                'berat_badan'        => T::BODY(),
                'sistolik'           => T::VITAL(70, 250),
                'diastolik'          => T::VITAL(40, 150),
                'nadi'               => T::VITAL(30, 200),
                'suhu_tubuh'         => T::TEMP(),
                'kadar_hemoglobin'   => T::LAB(),
                'id_hasil_anamnesis' => T::FK_AUTO(),
                'id_status_skrining' => T::FK_AUTO(),
            ],
            'id_skrining',
            ['id_kunjungan'],
            [
                [
                    'id_kunjungan',
                    \App\Features\Donor\Kunjungan\KunjunganDatabase::class,
                    'id_kunjungan',
                ],
                [
                    'id_hasil_anamnesis',
                    \App\Features\Donor\HasilAnamnesis\HasilAnamnesisDatabase::class,
                    'id_hasil',
                ],
                [
                    'id_status_skrining',
                    \App\Features\Donor\StatusSkrining\StatusSkriningDatabase::class,
                    'id_status_skrining',
                ],
            ],
            false,
            'skrining_donor.csv',
        );
    }
}
