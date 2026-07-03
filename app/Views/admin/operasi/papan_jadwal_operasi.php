<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-full py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <!-- Date filter -->
        <div class="flex items-center gap-3 mb-5">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">Tanggal</label>
            <input type="date" id="filterTanggal" value="<?= esc($tanggal) ?>"
                   class="border border-gray-300 text-gray-900 text-sm rounded-lg px-2 py-1.5 dark:bg-slate-800 dark:border-gray-600 dark:text-white cursor-pointer"
                   onchange="filterByDate(this.value)">
            <span class="text-sm text-gray-500 dark:text-gray-400 hidden sm:inline">
                <?= date('l, d F Y', strtotime($tanggal)) ?>
            </span>
        </div>

<?php if (empty($ruangans)): ?>
            <p class="text-sm text-gray-400 dark:text-gray-500 italic">Tidak ada data ruangan operasi (kode OK*).</p>
        <?php else: ?>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full text-xs" style="border-collapse:collapse;">
                <colgroup>
                    <col style="width:52px;min-width:52px;">
                    <?php foreach ($ruangans as $_): ?>
                    <col style="width:170px;min-width:170px;">
                    <?php endforeach; ?>
                </colgroup>
                <thead>
                    <tr style="border-bottom:2px solid #d1d5db;">
                        <th class="sticky left-0 z-10 bg-gray-50 dark:bg-slate-800 px-2 py-2.5 text-left"
                            style="border-right:1px solid #d1d5db;">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Waktu</span>
                        </th>
                        <?php foreach ($ruangans as $ruangan): ?>
                        <th class="bg-gray-50 dark:bg-slate-800 px-2 py-2 text-center"
                            style="border-right:1px solid #d1d5db;">
                            <div class="text-[11px] font-semibold text-gray-700 dark:text-gray-200 leading-tight">
                                <?= esc($ruangan['nama_ruangan']) ?>
                            </div>
                            <div class="text-[10px] font-normal text-gray-400 dark:text-gray-500 mt-0.5">
                                <?= esc($ruangan['kode_ruangan']) ?>
                            </div>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($slots as $slot):
                    $idSlot = (int) $slot['id_slot'];
                    $isHour = (substr($slot['waktu_slot'] ?? '', 3, 2) === '00');

                    // Inline border styles on <tr> interfere with rowspan cells,
                    // so borders are placed on individual <td> elements only.
                ?>
                <tr style="height:22px;">

                    <!-- Time label — always renders, carries the hour-separator border -->
                    <td class="sticky left-0 z-10 bg-white dark:bg-slate-900 px-2 py-0 whitespace-nowrap font-mono leading-[22px]"
                        style="border-right:1px solid #d1d5db;
                               <?= $isHour
                                   ? 'border-top:2px solid #9ca3af; font-size:11px; font-weight:600; color:#374151;'
                                   : 'border-top:1px solid #f3f4f6; font-size:10px; color:#9ca3af;' ?>">
                        <?= esc(substr($slot['nama_slot'], 0, 5)) ?>
                    </td>

                    <!-- Room cells -->
                    <?php foreach ($ruangans as $ruangan):
                        $idRuangan = (int) $ruangan['id_ruangan'];
                        $span      = $spans[$idSlot][$idRuangan] ?? 1;

                        if ($span === 0) continue;

                        $jadwal = $grid[$idSlot][$idRuangan] ?? null;

                        if ($jadwal === null): ?>
                            <td style="border-right:1px solid #f3f4f6;
                                       <?= $isHour ? 'border-top:2px solid #e5e7eb;' : 'border-top:1px solid #f9fafb;' ?>
                                       background:#fff;"
                                <?= $span > 1 ? "rowspan=\"{$span}\"" : '' ?>></td>

                        <?php else:
                            $isCito   = filter_var($jadwal['is_cito'] ?? false, FILTER_VALIDATE_BOOLEAN);
                            $status   = (int) ($jadwal['id_status'] ?? 2);
                            $idJadwal = (int) $jadwal['id_jadwal'];

                            [$bg, $border, $text] = match($status) {
                                4       => ['#d1fae5', '#34d399', '#065f46'],
                                3       => ['#fef3c7', '#f59e0b', '#78350f'],
                                default => ['#dbeafe', '#60a5fa', '#1e3a5f'],
                            };
                            $leftBorder = $isCito ? 'border-left:4px solid #ef4444;' : "border-left:1px solid {$border};";
                        ?>
                            <td rowspan="<?= $span ?>"
                                style="border-top:1px solid <?= $border ?>;
                                       border-right:1px solid #e5e7eb;
                                       border-bottom:2px solid <?= $border ?>;
                                       <?= $leftBorder ?>
                                       background:<?= $bg ?>;
                                       color:<?= $text ?>;
                                       vertical-align:middle;
                                       padding:0;">
                                <a href="<?= site_url("operasi/lembar-operasi/data?id_jadwal={$idJadwal}") ?>"
                                   class="block group" style="padding:4px 6px; height:100%;">
                                    <?php if ($span === 1): ?>
                                        <p class="font-semibold leading-tight group-hover:underline" style="font-size:10px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            <?= esc($jadwal['nama_pasien'] ?? '—') ?>
                                            <?php if ($isCito): ?>
                                                <span style="color:#dc2626; font-weight:700; font-size:9px; margin-left:2px;">CITO</span>
                                            <?php endif; ?>
                                        </p>
                                    <?php else: ?>
                                        <p class="font-semibold leading-tight group-hover:underline" style="font-size:11px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            <?= esc($jadwal['nama_pasien'] ?? '—') ?>
                                            <?php if ($isCito): ?>
                                                <span style="color:#dc2626; font-weight:700; font-size:9px; margin-left:2px;">CITO</span>
                                            <?php endif; ?>
                                        </p>
                                        <p style="font-size:10px; opacity:0.7; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px;">
                                            <?= esc($jadwal['nama_tindakan'] ?? '') ?>
                                        </p>
                                        <?php if ($span >= 3): ?>
                                        <p style="font-size:10px; opacity:0.55; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            <?= esc($jadwal['nama_dokter_bedah'] ?? '') ?>
                                        </p>
                                        <?php endif; ?>
                                        <?php if ($span >= 4): ?>
                                        <p style="font-size:9px; opacity:0.45; margin-top:3px; font-family:monospace;">
                                            <?= substr($jadwal['waktu_mulai'] ?? '', 0, 5) ?> – <?= substr($jadwal['waktu_selesai'] ?? '', 0, 5) ?>
                                        </p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </a>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterByDate(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('tanggal', val);
    window.location.href = url.toString();
}
</script>

<?= $this->endSection(); ?>