<div class="w-full">
    <?php if (strtolower($baris['pengantaran']) === 'ambulans'): ?>
        <div class="px-3 py-1.5">
            <a id="btn-panggil-<?= $baris['nomor_rujuk'] ?>"
                href="javascript:void(0);" 
                class="text-sm text-blue-600 hover:underline font-semibold"
                onclick="requestAmbulanceModal('<?= esc($baris['nomor_rujuk']) ?>')">
                Panggil Ambulans
            </a>
        </div>
    <?php else : ?>
        <div class="px-3 py-1.5">
            <a class="text-sm text-blue-600 hover:underline font-semibold" style="visibility:hidden">
                Panggil Ambulans</a>
        </div>
    <?php endif ?>
</div>
