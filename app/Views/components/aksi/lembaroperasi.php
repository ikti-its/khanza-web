<div class="px-3 py-1.5">
    <?php if ((int) ($baris['id_status'] ?? 0) === 2 || (int) ($baris['id_status'] ?? 0) === 3 || (int) ($baris['id_status'] ?? 0) === 4): ?>
        <a href="/operasi/lembar-operasi/data?id_jadwal=<?= urlencode((string) $id) ?>"
           class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold">
            Lembar Operasi
        </a>
    <?php else: ?>
        <span class="text-sm font-semibold text-gray-400 cursor-not-allowed">Lembar Operasi</span>
    <?php endif; ?>
</div>
