<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?php
$isCito     = filter_var($jadwal['is_cito'] ?? false, FILTER_VALIDATE_BOOLEAN);
$idStatus   = (int)  ($jadwal['id_status'] ?? 0);
$namaStatus = $jadwal['nama_status'] ?? '-';

$statusColor = match($idStatus) {
    1 => 'bg-gray-100   text-gray-800   dark:bg-gray-700      dark:text-gray-300',
    2 => 'bg-blue-100   text-blue-800   dark:bg-blue-900/30   dark:text-blue-400',
    3 => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
    4 => 'bg-green-100  text-green-800  dark:bg-green-900/30  dark:text-green-400',
    5 => 'bg-red-100    text-red-800    dark:bg-red-900/30    dark:text-red-400',
    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
};

$labelClass    = 'text-sm text-gray-500 dark:text-gray-400';
$valueClass    = 'text-sm font-medium text-gray-900 dark:text-white';

$gridData = [
    'Tindakan Operasi' => $jadwal['nama_tindakan']        ?? '-',
    'Dokter Peminta'   => $jadwal['nama_dokter_peminta']  ?? '-',
    'Tanggal Minta'    => $jadwal['tanggal_minta']        ?? '-',
    'Ruangan'          => $jadwal['nama_ruangan']         ?? '-',
    'Dokter Bedah'     => $jadwal['nama_dokter_bedah']    ?? '-',
    'Dokter Anestesi'  => $jadwal['nama_dokter_anestesi'] ?? '-',
    'Tanggal Operasi'  => $jadwal['tanggal']              ?? '-',
    'Waktu Mulai'      => $jadwal['waktu_mulai']          ?? '-',
    'Waktu Selesai'    => $jadwal['waktu_selesai']        ?? '-',
];

$iconCheck  = '<svg class="w-4 h-4 text-green-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
$iconCircle = '<svg class="w-4 h-4 text-gray-300 dark:text-gray-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>';
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto space-y-4">

    <?= view('components/form/judul', ['judul' => $judul]) ?>

    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">

        <!-- Top row: pasien + status + CITO -->
        <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                    <?= esc($jadwal['nama_pasien'] ?? '-') ?>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    No. Registrasi: <?= esc($jadwal['nomor_reg'] ?? '-') ?>
                </p>
                <?php if (!empty($jadwal['nomor_operasi'])): ?>
                <p class="text-sm font-mono text-gray-500 dark:text-gray-400 mt-0.5">
                    No. Operasi: <?= esc($jadwal['nomor_operasi']) ?>
                </p>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-x-2">
                <?php if ($isCito): ?>
                    <span class="inline-flex items-center gap-x-1.5 py-1 px-3 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        CITO
                    </span>
                <?php endif; ?>
                <span class="inline-flex items-center py-1 px-3 text-sm font-semibold rounded-full <?= $statusColor ?>">
                    <?= esc($namaStatus) ?>
                </span>
            </div>
        </div>

        <!-- Info grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4 mb-6">
            <?php foreach ($gridData as $label => $val): ?>
                <div>
                    <p class="<?= $labelClass ?>"><?= $label ?></p>
                    <p class="<?= $valueClass ?>"><?= esc($val) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom row: status form + tagihan button -->
        <div class="flex flex-wrap items-center justify-between gap-4 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
            <?php if ($idStatus < 4): ?>
            <form action="<?= esc($mulai_url) ?>" method="post"
                  class="flex flex-wrap items-center gap-x-3"
                  onsubmit="return confirmStatus(this)">
                <?= csrf_field() ?>
                <label class="text-sm text-gray-500 dark:text-gray-400 flex-shrink-0">Ubah Status:</label>
                <select name="id_status" required
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 bg-slate-50 dark:border-gray-600 dark:text-white dark:bg-slate-700">
                    <option value="">— Pilih —</option>
                    <?php if ($idStatus < 3): ?>
                        <option value="3">Proses Operasi</option>
                    <?php endif; ?>
                    <?php if ($idStatus < 4): ?>
                        <option value="4">Selesai</option>
                    <?php endif; ?>
                    <option value="5">Dibatalkan</option>
                </select>
                <button type="submit"
                        class="py-2 px-4 inline-flex items-center text-sm font-semibold rounded-lg bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]">
                    Simpan
                </button>
            </form>
            <?php else: ?>
            <div></div>
            <?php endif; ?>

            <?php if ($tagihan_id): ?>
            <a href="/operasi/tagihan-operasi/edit/<?= $tagihan_id ?>"
               class="inline-flex items-center gap-x-2 py-2 px-4 text-sm font-semibold rounded-lg border border-green-600 text-green-700 hover:bg-green-50 dark:border-green-500 dark:text-green-400 dark:hover:bg-green-900/20 transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Edit Tagihan
            </a>
            <?php else: ?>
            <a href="/operasi/tagihan-operasi/tambah?id_jadwal=<?= $id_jadwal ?>"
               class="inline-flex items-center gap-x-2 py-2 px-4 text-sm font-semibold rounded-lg bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Tagihan
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Form Checklist -->
    <div class="space-y-4">
        <?php foreach ($forms as $fase => $items): ?>
            <div class="bg-white rounded-xl shadow-sm dark:bg-slate-900 border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-slate-800">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                        <?= esc($fase) ?>
                    </h3>
                </div>
                <ul class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php foreach ($items as $form): 
                        $filled = $form['record_id'] !== null;
                        $href   = $filled ? esc($form['ubah']) . '/' . $form['record_id'] : esc($form['tambah']) . '?id_jadwal=' . $id_jadwal;
                        $colorT = $filled ? 'text-gray-500 dark:text-gray-400' : 'text-gray-800 dark:text-gray-200';
                        $colorL = $filled ? 'text-gray-400 hover:text-blue-600 dark:text-gray-500' : 'text-blue-600 hover:underline dark:text-blue-400';
                    ?>
                        <li class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                            <div class="flex items-center gap-x-3">
                                <?= $filled ? $iconCheck : $iconCircle ?>
                                <span class="text-sm <?= $colorT ?>">
                                    <?= esc($form['label']) ?>
                                </span>
                            </div>
                            <a href="<?= $href ?>" class="text-sm font-semibold <?= $colorL ?>">
                                <?= $filled ? 'Ubah' : 'Isi' ?> &rarr;
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="flex justify-end">
        <?= view('components/header/kembali') ?>
    </div>

</div>

<script>
function confirmStatus(form) {
    const sel = form.querySelector('[name="id_status"]');
    const label = sel?.selectedOptions[0]?.text;
    return label ? confirm(`Yakin ingin mengubah status operasi menjadi "${label}"?`) : false;
}

(function () {
    const key = 'back_jadwal_<?= $id_jadwal ?>';
    const ref = document.referrer;
    // Only store referrer when coming from outside lembar-operasi (genuine navigation, not post-redirect)
    if (ref && !ref.includes('lembar-operasi')) {
        sessionStorage.setItem(key, ref);
    }
    const backUrl = sessionStorage.getItem(key);
    if (backUrl) {
        document.querySelectorAll('a[href="javascript:history.back()"]').forEach(a => {
            a.href = backUrl;
        });
    }
})();
</script>

<?= $this->endSection(); ?>
