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
                
                // ==========================================
                // 1. TAHAP PRA-OPERASI (PRE-OP)
                // ==========================================
                \App\Features\Operasi\PengkajianPreop\PengkajianPreopController::class,
                \App\Features\Operasi\PengkajianPreAnestesi\PengkajianPreAnestesiController::class,
                \App\Features\Operasi\ChecklistPreOperasi\ChecklistPreOperasiController::class,
                \App\Features\Operasi\ChecklistPreOperasiPenunjang\ChecklistPreOperasiPenunjangController::class => 'HIDE',

                // ==========================================
                // 2. TAHAP INTRABEDAH (SEBELUM INDUKSI & INSISI)
                // ==========================================
                \App\Features\Operasi\SigninSebelumAnestesi\SigninSebelumAnestesiController::class,
                \App\Features\Operasi\PengkajianPreInduksi\PengkajianPreInduksiController::class,
                \App\Features\Operasi\PengkajianPreInduksiAirway\PengkajianPreInduksiAirwayController::class => 'HIDE',
                \App\Features\Operasi\TimeOutSebelumInsisi\TimeOutSebelumInsisiController::class,
                \App\Features\Operasi\TimeOutSebelumInsisiPenunjang\TimeOutSebelumInsisiPenunjangController::class => 'HIDE',
                \App\Features\Operasi\CatatanAnestesiSedasi\CatatanAnestesiSedasiController::class,
                \App\Features\Operasi\CatatanAnestesiSedasiAlat\CatatanAnestesiSedasiAlatController::class => 'HIDE',
                \App\Features\Operasi\CatatanAnestesiSedasiMonitoring\CatatanAnestesiSedasiMonitoringController::class => 'HIDE',

                // ==========================================
                // 3. TAHAP INTRABEDAH (SEBELUM TUTUP LUKA)
                // ==========================================
                \App\Features\Operasi\SignoutSebelumTutupLuka\SignoutSebelumTutupLukaController::class,

                // ==========================================
                // 4. TAHAP PASKA OPERASI (POST-OP / RECOVERY ROOM)
                // ==========================================
                \App\Features\Operasi\CatatanPaskaOperasi\CatatanPaskaOperasiController::class,
                \App\Features\Operasi\ChecklistPostop\ChecklistPostopController::class,
                \App\Features\Operasi\ChecklistPostopDrain\ChecklistPostopDrainController::class => 'HIDE',
                \App\Features\Operasi\ChecklistPostopPenunjang\ChecklistPostopPenunjangController::class => 'HIDE',
                \App\Features\Operasi\SkorAldrette\SkorAldretteController::class,
                \App\Features\Operasi\SkorSteward\SkorStewardController::class,
                \App\Features\Operasi\SkorBromage\SkorBromageController::class,

                // ==========================================
                // 5. TRANSFER ANTAR RUANG (PENYERAHAN PASIEN)
                // ==========================================
                \App\Features\Operasi\PenyerahanPasien\PenyerahanPasienController::class,
                \App\Features\Operasi\PenyerahanPasienPeralatan\PenyerahanPasienPeralatanController::class => 'HIDE',

                // ==========================================
                // 6. DATA REFERENSI (MASTER DATA)
                // ==========================================
                \App\Features\Operasi\RefAlatAnestesi\RefAlatAnestesiController::class,
                \App\Features\Operasi\RefAldretteAktivitas\RefAldretteAktivitasController::class,
                \App\Features\Operasi\RefAldretteKesadaran\RefAldretteKesadaranController::class,
                \App\Features\Operasi\RefAldretteRespirasi\RefAldretteRespirasiController::class,
                \App\Features\Operasi\RefAldretteTekananDarah\RefAldretteTekananDarahController::class,
                \App\Features\Operasi\RefAldretteWarnaKulit\RefAldretteWarnaKulitController::class,
                \App\Features\Operasi\RefAngkaAsa\RefAngkaAsaController::class,
                \App\Features\Operasi\RefBromage\RefBromageController::class,
                \App\Features\Operasi\RefHubunganKeluarga\RefHubunganKeluargaController::class,
                \App\Features\Operasi\RefIndikasiPindah\RefIndikasiPindahController::class,
                \App\Features\Operasi\RefInduksi\RefInduksiController::class,
                \App\Features\Operasi\RefJenisAirway\RefJenisAirwayController::class,
                \App\Features\Operasi\RefJenisPenunjang\RefJenisPenunjangController::class,
                \App\Features\Operasi\RefJenisSedasi\RefJenisSedasiController::class,
                \App\Features\Operasi\RefKeadaanUmum\RefKeadaanUmumController::class,
                \App\Features\Operasi\RefKeadaanUmumTransfer\RefKeadaanUmumTransferController::class,
                \App\Features\Operasi\RefKesadaran\RefKesadaranController::class,
                \App\Features\Operasi\RefKesadaranPascaop\RefKesadaranPascaopController::class,
                \App\Features\Operasi\RefKesiapanAnestesi\RefKesiapanAnestesiController::class,
                \App\Features\Operasi\RefKetersediaanStatus\RefKetersediaanStatusController::class,
                \App\Features\Operasi\RefMetodeTransfer\RefMetodeTransferController::class,
                \App\Features\Operasi\RefMonitoringAnestesi\RefMonitoringAnestesiController::class,
                \App\Features\Operasi\RefObatBebas\RefObatBebasController::class,
                \App\Features\Operasi\RefPeralatanTransfer\RefPeralatanTransferController::class,
                \App\Features\Operasi\RefPosisiPasien\RefPosisiPasienController::class,
                \App\Features\Operasi\RefPremedikasi\RefPremedikasiController::class,
                \App\Features\Operasi\RefRencanaAnestesi\RefRencanaAnestesiController::class,
                \App\Features\Operasi\RefStatusOperasi\RefStatusOperasiController::class,
                \App\Features\Operasi\RefStatusPenayangan\RefStatusPenayanganController::class,
                \App\Features\Operasi\RefStatusSpesimen\RefStatusSpesimenController::class,
                \App\Features\Operasi\RefStewardKesadaran\RefStewardKesadaranController::class,
                \App\Features\Operasi\RefStewardMotorik\RefStewardMotorikController::class,
                \App\Features\Operasi\RefStewardRespirasi\RefStewardRespirasiController::class,
                \App\Features\Operasi\RefTindakanOperasi\RefTindakanOperasiController::class,
                \App\Features\Operasi\RefWarnaUrine\RefWarnaUrineController::class,
            ],
            'operasi.svg',
        );
    }
}

