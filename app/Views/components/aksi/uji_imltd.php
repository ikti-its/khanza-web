<div class="w-full">
    <?php
    $isBerhasil = isset($baris['id_status_pengambilan']) && (int)$baris['id_status_pengambilan'] === 1;
    $belumDiproses = !isset($baris['sudah_diuji']) || $baris['sudah_diuji'] === false;
    
    if ($isBerhasil && $belumDiproses):
    ?>
        <div class="px-3 py-1.5">
            <a href="<?= base_url('uji-darah/hasil-uji-saring/tambah?pengambilan=' . $id) ?>"
               class="gap-x-1 text-sm text-indigo-600 hover:underline font-semibold dark:text-indigo-400">
                Uji
            </a>
        </div>
    <?php elseif ($isBerhasil && !$belumDiproses): ?>
        <div class="px-3 py-1.5">
            <span title="Darah ini sudah diuji sebelumnya"
                  class="text-sm text-gray-400 font-semibold cursor-not-allowed line-through decoration-1">
                Uji
            </span>
        </div>
    <?php else: ?>
        <div class="px-3 py-1.5 relative inline-block text-center">
            <a class="text-sm font-semibold select-none pointer-events-none" style="visibility:hidden">Uji</a>
            <span class="absolute inset-0 flex items-center justify-center text-sm text-gray-400 font-semibold italic dark:text-gray-500">
                -
            </span>
        </div>
    <?php endif; ?>
</div>