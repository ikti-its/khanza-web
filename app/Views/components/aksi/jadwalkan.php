<div class="px-3 py-1.5">
    <?php if (!empty($baris['id_jadwal']) && $baris['id_status'] == 1): ?>
        <a href="/operasi/jadwal-operasi/edit/<?= $baris['id_jadwal'] ?>"
           class="gap-x-1 text-sm text-purple-600 decoration-2 hover:underline font-semibold">
            Jadwalkan
        </a>
    <?php else: ?>
        <span class="text-sm font-semibold text-gray-400">Terjadwal</span>
    <?php endif; ?>
</div>