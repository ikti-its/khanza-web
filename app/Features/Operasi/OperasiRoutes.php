<?php

declare(strict_types=1);

namespace App\Features\Operasi;

use App\Core\Route\RouteTemplate;

final class OperasiRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Operasi',
            [
                \App\Features\Operasi\PermintaanOperasi\PermintaanOperasiController::class,
                \App\Features\Operasi\JadwalOperasi\JadwalOperasiController::class,
                \App\Features\Operasi\PapanJadwalOperasi\PapanJadwalOperasiController::class,
                \App\Features\Operasi\TagihanOperasi\TagihanOperasiController::class,
                \App\Features\Operasi\PaketTindakanOperasi\PaketTindakanOperasiController::class => 'HIDE',
                \App\Features\Operasi\LembarOperasi\LembarOperasiController::class               => 'HIDE',

                // ==========================================
                // 1. TAHAP PRA-OPERASI (PRE-OP)
                // ==========================================
                \App\Features\Operasi\PengkajianPreop\PengkajianPreopController::class                           => 'HIDE',
                \App\Features\Operasi\PengkajianPreAnestesi\PengkajianPreAnestesiController::class               => 'HIDE',
                \App\Features\Operasi\ChecklistPreOperasi\ChecklistPreOperasiController::class                   => 'HIDE',
                \App\Features\Operasi\ChecklistPreOperasiPenunjang\ChecklistPreOperasiPenunjangController::class => 'HIDE',

                // ==========================================
                // 2. TAHAP INTRABEDAH (SEBELUM INDUKSI & INSISI)
                // ==========================================
                \App\Features\Operasi\SigninSebelumAnestesi\SigninSebelumAnestesiController::class                     => 'HIDE',
                \App\Features\Operasi\PengkajianPreInduksi\PengkajianPreInduksiController::class                       => 'HIDE',
                \App\Features\Operasi\PengkajianPreInduksiAirway\PengkajianPreInduksiAirwayController::class           => 'HIDE',
                \App\Features\Operasi\TimeOutSebelumInsisi\TimeOutSebelumInsisiController::class                       => 'HIDE',
                \App\Features\Operasi\TimeOutSebelumInsisiPenunjang\TimeOutSebelumInsisiPenunjangController::class     => 'HIDE',
                \App\Features\Operasi\CatatanAnestesiSedasi\CatatanAnestesiSedasiController::class                     => 'HIDE',
                \App\Features\Operasi\CatatanAnestesiSedasiAlat\CatatanAnestesiSedasiAlatController::class             => 'HIDE',
                \App\Features\Operasi\CatatanAnestesiSedasiMonitoring\CatatanAnestesiSedasiMonitoringController::class => 'HIDE',

                // ==========================================
                // 3. TAHAP INTRABEDAH (SEBELUM TUTUP LUKA)
                // ==========================================
                \App\Features\Operasi\SignoutSebelumTutupLuka\SignoutSebelumTutupLukaController::class => 'HIDE',

                // ==========================================
                // 4. TAHAP PASKA OPERASI (POST-OP / RECOVERY ROOM)
                // ==========================================
                \App\Features\Operasi\CatatanPaskaOperasi\CatatanPaskaOperasiController::class           => 'HIDE',
                \App\Features\Operasi\ChecklistPostop\ChecklistPostopController::class                   => 'HIDE',
                \App\Features\Operasi\ChecklistPostopDrain\ChecklistPostopDrainController::class         => 'HIDE',
                \App\Features\Operasi\ChecklistPostopPenunjang\ChecklistPostopPenunjangController::class => 'HIDE',
                \App\Features\Operasi\SkorAldrette\SkorAldretteController::class                         => 'HIDE',
                \App\Features\Operasi\SkorSteward\SkorStewardController::class                           => 'HIDE',
                \App\Features\Operasi\SkorBromage\SkorBromageController::class                           => 'HIDE',

                // ==========================================
                // 5. TRANSFER ANTAR RUANG (PENYERAHAN PASIEN)
                // ==========================================
                \App\Features\Operasi\PenyerahanPasien\PenyerahanPasienController::class                   => 'HIDE',
                \App\Features\Operasi\PenyerahanPasienPeralatan\PenyerahanPasienPeralatanController::class => 'HIDE',

                // ==========================================
                // 6. DATA REFERENSI (MASTER DATA)
                // ==========================================
                \App\Features\Operasi\RefAlatAnestesi\RefAlatAnestesiController::class                 => 'HIDE',
                \App\Features\Operasi\RefAldretteAktivitas\RefAldretteAktivitasController::class       => 'HIDE',
                \App\Features\Operasi\RefAldretteKesadaran\RefAldretteKesadaranController::class       => 'HIDE',
                \App\Features\Operasi\RefAldretteRespirasi\RefAldretteRespirasiController::class       => 'HIDE',
                \App\Features\Operasi\RefAldretteTekananDarah\RefAldretteTekananDarahController::class => 'HIDE',
                \App\Features\Operasi\RefAldretteWarnaKulit\RefAldretteWarnaKulitController::class     => 'HIDE',
                \App\Features\Operasi\RefAngkaAsa\RefAngkaAsaController::class                         => 'HIDE',
                \App\Features\Operasi\RefBromage\RefBromageController::class                           => 'HIDE',
                \App\Features\Operasi\RefHubunganKeluarga\RefHubunganKeluargaController::class         => 'HIDE',
                \App\Features\Operasi\RefIndikasiPindah\RefIndikasiPindahController::class             => 'HIDE',
                \App\Features\Operasi\RefInduksi\RefInduksiController::class                           => 'HIDE',
                \App\Features\Operasi\RefJenisAirway\RefJenisAirwayController::class                   => 'HIDE',
                \App\Features\Operasi\RefJenisPenunjang\RefJenisPenunjangController::class             => 'HIDE',
                \App\Features\Operasi\RefJenisSedasi\RefJenisSedasiController::class                   => 'HIDE',
                \App\Features\Operasi\RefKeadaanUmum\RefKeadaanUmumController::class                   => 'HIDE',
                \App\Features\Operasi\RefKeadaanUmumTransfer\RefKeadaanUmumTransferController::class   => 'HIDE',
                \App\Features\Operasi\RefKategoriOperasi\RefKategoriOperasiController::class           => 'HIDE',
                \App\Features\Operasi\RefKomponenJasa\RefKomponenJasaController::class                 => 'HIDE',
                \App\Features\Operasi\RefKesadaran\RefKesadaranController::class                       => 'HIDE',
                \App\Features\Operasi\RefKesadaranPascaop\RefKesadaranPascaopController::class         => 'HIDE',
                \App\Features\Operasi\RefKesiapanAnestesi\RefKesiapanAnestesiController::class         => 'HIDE',
                \App\Features\Operasi\RefKetersediaanStatus\RefKetersediaanStatusController::class     => 'HIDE',
                \App\Features\Operasi\RefMetodeTransfer\RefMetodeTransferController::class             => 'HIDE',
                \App\Features\Operasi\RefMonitoringAnestesi\RefMonitoringAnestesiController::class     => 'HIDE',
                \App\Features\Operasi\RefObatBebas\RefObatBebasController::class                       => 'HIDE',
                \App\Features\Operasi\RefPeralatanTransfer\RefPeralatanTransferController::class       => 'HIDE',
                \App\Features\Operasi\RefPosisiPasien\RefPosisiPasienController::class                 => 'HIDE',
                \App\Features\Operasi\RefPremedikasi\RefPremedikasiController::class                   => 'HIDE',
                \App\Features\Operasi\RefRencanaAnestesi\RefRencanaAnestesiController::class           => 'HIDE',
                \App\Features\Operasi\RefStatusOperasi\RefStatusOperasiController::class               => 'HIDE',
                \App\Features\Operasi\RefStatusPenayangan\RefStatusPenayanganController::class         => 'HIDE',
                \App\Features\Operasi\RefStatusSpesimen\RefStatusSpesimenController::class             => 'HIDE',
                \App\Features\Operasi\RefStewardKesadaran\RefStewardKesadaranController::class         => 'HIDE',
                \App\Features\Operasi\RefStewardMotorik\RefStewardMotorikController::class             => 'HIDE',
                \App\Features\Operasi\RefStewardRespirasi\RefStewardRespirasiController::class         => 'HIDE',
                \App\Features\Operasi\RefTindakanOperasi\RefTindakanOperasiController::class           => 'HIDE',
                \App\Features\Operasi\RefSlotOperasi\RefSlotOperasiController::class                   => 'HIDE',
                \App\Features\Operasi\RefWarnaUrine\RefWarnaUrineController::class                     => 'HIDE',
            ],
            'operasi.svg',
        );
    }
}
