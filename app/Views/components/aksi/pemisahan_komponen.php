<div class="w-full">
    <?php
    $isBerhasil = isset($baris['id_status_pengambilan']) && (int)$baris['id_status_pengambilan'] === 1;
    $belumDiproses = !isset($baris['sudah_dipisahkan']) || $baris['sudah_dipisahkan'] === false;
    
    if ($isBerhasil && $belumDiproses):
    ?>
        <div class="px-3 py-1.5">
            <a href="<?= base_url('inventori-darah/pemisahan-komponen/tambah?pengambilan=' . $id) ?>" 
               class="gap-x-1 text-sm text-teal-600 hover:underline font-semibold dark:text-teal-400">
                Pisahkan
            </a>
        </div>
    <?php elseif ($isBerhasil && !$belumDiproses): ?>
        <div class="px-3 py-1.5">
            <span title="Darah ini sudah dipisahkan sebelumnya"
                  class="text-sm text-gray-400 font-semibold cursor-not-allowed line-through decoration-1">
                Pisahkan
            </span>
        </div>
    <?php else: ?>
        <div class="px-3 py-1.5 relative inline-block text-center">
            <a class="text-sm font-semibold select-none pointer-events-none" style="visibility:hidden">Pisahkan</a>
            <span class="absolute inset-0 flex items-center justify-center text-sm text-gray-400 font-semibold italic dark:text-gray-500">
                -
            </span>
        </div>
    <?php endif; ?>
</div>