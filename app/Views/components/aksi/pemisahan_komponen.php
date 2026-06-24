<?php
$isBerhasil     = isset($baris['id_status_pengambilan']) && (int)$baris['id_status_pengambilan'] === 1;
$belumDiproses  = !isset($baris['sudah_dipisahkan']) || $baris['sudah_dipisahkan'] === false;

$base_class = "flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm font-medium w-full text-left focus:outline-none ";

if ($isBerhasil && $belumDiproses):
?>
    <a href="<?= base_url('inventori-darah/pemisahan-komponen/tambah?pengambilan=' . $id) ?>" 
       class="<?= $base_class ?> text-gray-800 hover:bg-gray-100 focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300">
        Pemisahan Komponen
    </a>

<?php elseif ($isBerhasil && !$belumDiproses): ?>
    <span class="<?= $base_class ?> text-gray-400 line-through decoration-1 cursor-not-allowed bg-gray-50/40 dark:bg-neutral-800/40 dark:text-neutral-600">
        ✕ Sudah Dipisahkan
    </span>
<?php endif; ?>