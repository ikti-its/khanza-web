<div class="w-full">
    <?php
    $statusBayar = (int)($baris['id_status_pembayaran'] ?? 1); 
    
    if ($statusBayar === 1) :
    ?>
        <div class="px-3 py-1.5">
            <form action="<?= $modul_path ?>/bayar/<?= $id ?>" method="post"
                  onsubmit="return confirm('Apakah Anda yakin pasien ini sudah menyelesaikan pembayaran di kasir?')">
                <?= csrf_field() ?>
                <button type="submit"
                        class="gap-x-1 text-sm text-emerald-600 decoration-2 hover:underline font-semibold dark:text-emerald-400">
                    Konfirmasi Bayar
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="px-3 py-1.5">
            <a class="text-sm text-emerald-600 font-semibold" style="visibility:hidden">Konfirmasi Bayar</a>
        </div>
    <?php endif; ?>
</div>