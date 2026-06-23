<?php $statusFinal = (int)($baris['id_status_permintaan'] ?? 0) >= 3; ?>

<?php if (!$statusFinal): ?>
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
            <div class="flex justify-between items-center">
                <button type="button"
                        onclick="cetakSampelBarcode('<?= esc($baris['no_permintaan'] ?? '', 'js') ?>','<?= esc($baris['nomor_rm'] ?? '', 'js') ?>','<?= esc($baris['nama'] ?? '', 'js') ?>','<?= esc($baris['tanggal_lahir'] ?? '', 'js') ?>')"
                        class="px-3 py-1.5 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 font-semibold">
                    Cetak Sampel
                </button>
                <div class="flex gap-x-2">
                    <button type="button" onclick="closeSampelModal(<?= $id ?>)"
                            class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-3 py-1.5 text-sm font-semibold text-green bg-amber-500 hover:bg-amber-600 rounded-lg">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="px-3 py-1.5">
    <?php if ($statusFinal): ?>
        <span class="gap-x-1 text-sm text-gray-400 font-semibold cursor-not-allowed dark:text-gray-600"
              title="Tidak dapat diubah — status <?= esc($baris['nama_status'] ?? 'selesai') ?>">
            Sampel
        </span>
    <?php else: ?>
        <button type="button" onclick="openSampelModal(<?= $id ?>)"
                class="gap-x-1 text-sm text-amber-600 decoration-2 hover:underline font-semibold dark:text-amber-400">
            Sampel
        </button>
    <?php endif; ?>
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
    window.cetakSampelBarcode = function (noPermintaan, nomorRm, nama, tanggalLahir) {
        const barcodeUrl = 'https://bwipjs-api.metafloor.com/?bcid=code128&text='
            + encodeURIComponent(noPermintaan)
            + '&scale=3&height=12&includetext';
        const win = window.open('', '_blank', 'width=440,height=420');
        win.document.write(
            '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Label Sampel</title>'
            + '<style>'
            + 'body{font-family:Arial,sans-serif;display:flex;flex-direction:column;align-items:center;padding:24px;margin:0;background:#fff;}'
            + '.label{border:2px solid #333;padding:16px 24px;text-align:center;min-width:280px;}'
            + '.label img{max-width:280px;height:auto;}'
            + '.label .no{font-size:13px;margin:6px 0 8px;letter-spacing:1px;font-weight:bold;}'
            + '.label .info{font-size:12px;text-align:left;border-top:1px solid #ccc;padding-top:8px;margin-top:4px;line-height:1.8;}'
            + '.label .info span{font-weight:bold;}'
            + 'button{margin-top:16px;background:#1e4b4d;color:#fff;padding:8px 20px;font-size:14px;border:none;border-radius:4px;cursor:pointer;}'
            + '@media print{button{display:none!important;}}'
            + '</style></head><body>'
            + '<div class="label">'
            + '<img src="' + barcodeUrl + '" alt="barcode">'
            + '<p class="no" id="lbl-no"></p>'
            + '<div class="info">'
            + '<div>No. RM&nbsp;&nbsp;&nbsp;: <span id="lbl-rm"></span></div>'
            + '<div>Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span id="lbl-nama"></span></div>'
            + '<div>Tgl. Lahir: <span id="lbl-tgl"></span></div>'
            + '</div>'
            + '</div>'
            + '<button onclick="window.print()">&#128438; Cetak</button>'
            + '</body></html>'
        );
        win.document.close();
        // win.document.getElementById('lbl-no').textContent   = noPermintaan;
        win.document.getElementById('lbl-rm').textContent   = nomorRm   || '-';
        win.document.getElementById('lbl-nama').textContent = nama       || '-';
        win.document.getElementById('lbl-tgl').textContent  = tanggalLahir
            ? new Date(tanggalLahir).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'})
            : '-';
    };
}
</script>
