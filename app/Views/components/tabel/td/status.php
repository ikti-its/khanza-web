<?php
/**
 * @var string|int $id
 * @var string     $elem - Teks status (e.g. "Draf", "Disetujui", "Ditolak")
 */

// Mapping warna badge berdasarkan teks status
$status_lower = strtolower(trim($elem));

// Default: abu-abu (Draf/Draft/unknown)
$bg_color  = '#F1F1F1';
$dot_color = '#535353';
$text_color = '#374151';

if (in_array($status_lower, ['proses permintaan', 'proses pengajuan', 'diproses', 'proses penerimaan'])) {
    $bg_color   = '#FEF3C7';
    $dot_color  = '#D97706';
    $text_color = '#92400E';
} elseif (in_array($status_lower, ['disetujui', 'dikonfirmasi', 'diterima', 'selesai'])) {
    $bg_color   = '#D1FAE5';
    $dot_color  = '#059669';
    $text_color = '#065F46';
} elseif (in_array($status_lower, ['ditolak', 'dibatalkan'])) {
    $bg_color   = '#FEE2E2';
    $dot_color  = '#DC2626';
    $text_color = '#991B1B';
} elseif ($status_lower === 'masuk') {
    $bg_color   = '#DBEAFE';
    $dot_color  = '#2563EB';
    $text_color = '#1E40AF';
} elseif ($status_lower === 'keluar') {
    $bg_color   = '#FED7AA';
    $dot_color  = '#EA580C';
    $text_color = '#9A3412';
} elseif ($status_lower === 'opname') {
    $bg_color   = '#E9D5FF';
    $dot_color  = '#7C3AED';
    $text_color = '#5B21B6';
} elseif (is_numeric($elem)) {
    $num = (int) $elem;
    if ($num > 0) {
        $bg_color   = '#DBEAFE';
        $dot_color  = '#2563EB';
        $text_color = '#1E40AF';
    } elseif ($num < 0) {
        $bg_color   = '#FEE2E2';
        $dot_color  = '#DC2626';
        $text_color = '#991B1B';
    } else {
        $bg_color   = 'transparent';
        $dot_color  = 'transparent';
        $text_color = '#374151';
    }
} elseif (str_starts_with($elem, '+') && is_numeric(substr($elem, 1))) {
    $bg_color   = '#DBEAFE';
    $dot_color  = '#2563EB';
    $text_color = '#1E40AF';
}
?>
<td class="size-px w-48 whitespace-nowrap">
    <div class="px-6 py-3 text-center">
        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-semibold"
            style="background-color: <?= $bg_color ?>; color: <?= $text_color ?>;"
            data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $id ?>"
            data-id="<?= $id ?>">
            <?= $elem ?>
        </span>
    </div>
</td>
