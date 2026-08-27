<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Tabel Barang kustom: kolom checkbox "pengajuan cepat" untuk item di bawah stok minimum.
     Sengaja tidak reuse components/tabel/data.php & td.php (dipakai semua modul lain). -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-hidden">
            <div class="px-4 w-full overflow-x-auto">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
                    <?php
                        echo view('components/header/data', [
                            'judul'      => $judul,
                            'modul_path' => $modul_path,
                            'aksi'       => $aksi,
                        ]);
                        echo view('components/header/search_bar');
                    ?>

                    <!-- Aksi cepat: buat Pengajuan Barang dari item yang dicentang -->
                    <div class="py-2 flex justify-end">
                        <button type="button" id="btnAjukanPengajuan" onclick="ajukanPengajuanCepat()" disabled
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none transition-all shadow-sm">
                            Ajukan Pengajuan (<span id="countTerpilih">0</span>)
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto w-full" style="max-height: 600px; overflow-y: auto;">
                        <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-slate-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3" style="position: sticky; top: 0; background-color: white; padding: 8px;">
                                        <div class="flex justify-center gap-x-2">
                                            <span class="text-xs tracking-wide text-[#666] dark:text-gray-200">Pilih</span>
                                        </div>
                                    </th>
                                    <?php foreach (['Kode Barang', 'Nama Barang', 'Satuan', 'Jenis', 'Stok', 'Harga Satuan'] as $h): ?>
                                    <th scope="col" class="px-6 py-3" style="position: sticky; top: 0; background-color: white; padding: 8px;">
                                        <div class="flex justify-center gap-x-2">
                                            <span class="text-xs tracking-wide text-[#666] dark:text-gray-200"><?= esc($h) ?></span>
                                        </div>
                                    </th>
                                    <?php endforeach; ?>
                                    <?php if (!empty($aksi)): ?>
                                    <th scope="col" class="px-6 py-3" style="position: sticky; top: 0; background-color: white; padding: 8px;">
                                        <div class="flex justify-center gap-x-2">
                                            <span class="text-xs tracking-wide text-[#666] dark:text-gray-200">Aksi</span>
                                        </div>
                                    </th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php foreach ($tabel as $baris): ?>
                                <?php
                                    $stok         = (float) ($baris['stok'] ?? 0);
                                    $stok_minimum = $baris['stok_minimum'] ?? null;
                                    $eligible     = $stok_minimum !== null && $stok < (float) $stok_minimum;
                                    $tr_style     = $eligible
                                        ? 'background-color:#fff7ed; border-left:4px solid #f97316;'
                                        : 'border-left:4px solid transparent;';
                                ?>
                                <tr style="<?= $tr_style ?>">
                                    <td class="h-px w-16 whitespace-nowrap">
                                        <div class="px-6 py-3 text-center">
                                            <?php if ($eligible): ?>
                                            <input type="checkbox" class="chk-barang" value="<?= (int) $baris['id_barang'] ?>" onchange="updateHitungTerpilih()">
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="h-px w-40 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= esc($baris['kode_barang'] ?? '-') ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= esc($baris['nama_barang'] ?? '-') ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-40 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= esc($baris['nama_satuan'] ?? '-') ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-40 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= esc($baris['nama_jenis_barang'] ?? '-') ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-28 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= (int) $stok ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-40 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200">Rp <?= number_format((float) ($baris['harga_satuan'] ?? 0), 0, ',', '.') ?></span>
                                        </div>
                                    </td>
                                    <?php if (!empty($aksi)): ?>
                                    <?= view('components/aksi/aksi', [
                                        'modul_path' => $modul_path,
                                        'id'         => $baris['id_barang'],
                                        'aksi'       => $aksi,
                                        'baris'      => $baris,
                                        'child_link' => null,
                                    ]) ?>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?= view('components/footer/footer', ['meta_data' => $meta_data, 'modul_path' => $modul_path]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateHitungTerpilih() {
        var checked = document.querySelectorAll('.chk-barang:checked');
        document.getElementById('countTerpilih').textContent = checked.length;
        document.getElementById('btnAjukanPengajuan').disabled = checked.length === 0;
    }

    function ajukanPengajuanCepat() {
        var checked = document.querySelectorAll('.chk-barang:checked');
        if (checked.length === 0) return;
        var ids = Array.prototype.map.call(checked, function (c) { return c.value; });
        window.location.href = '<?= site_url('inventori-non-medis/pengajuan-barang/tambah') ?>?prefill_barang=' + ids.join(',');
    }
</script>

<?= $this->endSection(); ?>
