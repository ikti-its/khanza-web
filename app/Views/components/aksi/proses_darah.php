<?php
$data = [
    'id'         => $id,
    'modul_path' => $modul_path,
    'baris'      => $baris
];

$isBerhasil = isset($baris['id_status_pengambilan']) && (int)$baris['id_status_pengambilan'] === 1;

$pesanKunci = '';
if (!$isBerhasil) {
    if (isset($baris['id_status_pengambilan']) && $baris['id_status_pengambilan'] !== null && $baris['id_status_pengambilan'] !== '') {
        $pesanKunci = 'Aksi lab dikunci karena status pengambilan gagal.';
    } else {
        $pesanKunci = 'Aksi lab belum tersedia karena status pengambilan belum ditentukan.';
    }
}
?>

<style>
    /* Penanda sedang diklik/dibuka untuk tombol Proses Darah */
    .hs-dropdown.open > .proses-darah-trigger {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }
    .dark .hs-dropdown.open > .proses-darah-trigger {
        background-color: #404040;
        border-color: #525252;
    }
</style>

<div class="<?= $isBerhasil ? 'hs-dropdown' : '' ?> relative inline-flex px-2 py-1.5" <?= !$isBerhasil ? 'title="' . esc($pesanKunci) . '"' : '' ?>>

    <?php if ($isBerhasil): ?>
        <button id="hs-dropdown-olah-lab-trigger" type="button"
                class="proses-darah-trigger hs-dropdown-toggle inline-flex items-center gap-x-1 rounded-md border border-blue-200 px-2.5 py-1 text-sm font-semibold text-blue-600 dark:border-blue-900/60 dark:text-blue-400 focus:outline-none transition-colors">
            Proses Darah
            <svg class="hs-dropdown-open:rotate-180 size-4 text-blue-600 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>

        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-40 bg-white shadow-md rounded-lg p-1 space-y-0.5 mt-2 z-10 border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700 text-left" aria-labelledby="hs-dropdown-olah-lab-trigger">
            <?php 
            if (isset($aksi['pisah']) && $aksi['pisah'] === true) {
                echo view('components/aksi/pemisahan_komponen', $data);
            }
            if (isset($aksi['uji']) && $aksi['uji'] === true) {
                echo view('components/aksi/uji_imltd', $data);
            }
            ?>
        </div>

    <?php else: ?>
        <button type="button" disabled
                class="inline-flex items-center gap-x-1 rounded-md border border-gray-200 px-2.5 py-1 text-sm font-semibold text-gray-400 dark:border-neutral-700 dark:text-neutral-600 cursor-not-allowed select-none">
            Proses Darah
            <svg class="size-4 text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
    <?php endif; ?>

</div>