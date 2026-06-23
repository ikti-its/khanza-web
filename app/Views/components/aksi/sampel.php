<div id="modalSampel_<?= $id ?>" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 w-80">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Konfirmasi Pengambilan Sampel</h3>
        <form action="<?= $modul_path ?>/sampel/<?= $id ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-5">
                <input type="datetime-local" name="tgl_jam_sampel" id="sampelDatetime_<?= $id ?>"
                       required
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-slate-50">
            </div>
            <div class="flex justify-end gap-x-2">
                <button type="button" onclick="closeSampelModal(<?= $id ?>)"
                class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400">
                Batal
            </button>
            <button type="submit"
                    class="px-3 py-1.5 text-sm font-semibold text-green bg-amber-500 hover:bg-amber-600 rounded-lg">
                Simpan
            </button>
            </div>
        </form>
    </div>
</div>

<div class="px-3 py-1.5">
    <button type="button" onclick="openSampelModal(<?= $id ?>)"
            class="gap-x-1 text-sm text-amber-600 decoration-2 hover:underline font-semibold dark:text-amber-400">
        Sampel
    </button>
</div>

<script>
if (!window._sampelModalFn) {
    window._sampelModalFn = true;
    window.openSampelModal = function (id) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('sampelDatetime_' + id).value = now.toISOString().slice(0, 16);
        document.getElementById('modalSampel_' + id).classList.remove('hidden');
    };
    window.closeSampelModal = function (id) {
        document.getElementById('modalSampel_' + id).classList.add('hidden');
    };
}
</script>
