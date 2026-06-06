<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PenyerahanDarah;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PenyerahanDarahDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'pelayanan_darah',
            'penyerahan_darah',
            [
                'id_penyerahan'        => T::ID(30_000_000),
                'no_penyerahan'        => T::RECORD(20),
                'id_permintaan'        => T::FK_AUTO(),
                'tanggal_penyerahan'   => T::DTIME(),
                'id_shift'             => T::FK_AUTO(),
                'id_petugas_cross'     => T::FK_AUTO(),
                'keterangan'           => T::NOTE()->nullable(),
                'id_status_pembayaran' => T::FK_AUTO(),
                'id_rekening'          => T::FK_AUTO(),
                'pengambil_darah'      => T::NAME(50),
                'alamat_pengambil'     => T::TEXT(),
                'id_penanggung_jawab'  => T::FK_AUTO(),
                'besar_ppn'            => T::PERCENT(),
            ],
            'id_penyerahan',
            ['no_penyerahan'],
            [
                [
                    'id_permintaan',
                    \App\Features\PelayananDarah\PermintaanDarah\PermintaanDarahDatabase::class,
                    'id_permintaan',
                ],
                [
                    'id_shift',
                    \App\Features\Operasional\Shift\ShiftDatabase::class,
                    'id_shift',
                ],
                [
                    'id_petugas_cross',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
                [
                    'id_status_pembayaran',
                    \App\Features\PelayananDarah\StatusPembayaran\StatusPembayaranDatabase::class,
                    'id_status_pembayaran',
                ],
                [
                    'id_rekening',
                    \App\Features\Finansial\Rekening\RekeningDatabase::class,
                    'id_rekening',
                ],
                [
                    'id_penanggung_jawab',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
            ],
            false,
            'penyerahan_darah.csv',
        );
    }
}
