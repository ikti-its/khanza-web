<?php
/**
 * Timeline Component — Progress tracking untuk Permintaan Barang.
 *
 * @var array $tracking — output dari get_permintaan_tracking()
 */

$steps = $tracking['steps'] ?? [];
if (empty($steps)) return;

$total_steps = count($steps);
?>

<div class="mt-6 mb-2" style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
    <!-- Header -->
    <div style="display:flex; align-items:center; gap:8px; border-bottom:1px solid #e5e7eb; padding-bottom:12px; margin-bottom:20px;">
        <svg style="width:16px; height:16px; color:#0d9488;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
        </svg>
        <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Progress Permintaan</span>
    </div>

    <!-- Timeline Steps -->
    <div style="display:flex; align-items:flex-start; justify-content:space-between; position:relative;">
        <?php foreach ($steps as $i => $step): ?>
            <?php
            $status = $step['status'] ?? 'waiting';

            // Warna dot
            $dot_bg = match($status) {
                'done'   => '#10b981',
                'active' => '#3b82f6',
                'failed' => '#ef4444',
                default  => '#d1d5db',
            };

            // Warna teks status
            $status_color = match($status) {
                'done'   => '#065f46',
                'active' => '#1e40af',
                'failed' => '#991b1b',
                default  => '#9ca3af',
            };

            // Warna label
            $label_color = match($status) {
                'waiting' => '#9ca3af',
                default   => '#1f2937',
            };

            // Icon SVG
            $icon_html = match($status) {
                'done'   => '<svg style="width:14px;height:14px;" fill="white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>',
                'active' => '<span style="width:8px;height:8px;background:white;border-radius:50%;display:block;"></span>',
                'failed' => '<svg style="width:14px;height:14px;" fill="white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>',
                default  => '<span style="width:8px;height:8px;background:white;border-radius:50%;display:block;opacity:0.5;"></span>',
            };

            // Connector line color (berdasarkan status step SEBELUMNYA)
            $connector_color = '#e5e7eb';
            if ($i > 0) {
                $prev_status = $steps[$i - 1]['status'] ?? 'waiting';
                $connector_color = match($prev_status) {
                    'done'   => '#10b981',
                    'failed' => '#fca5a5',
                    default  => '#e5e7eb',
                };
            }
            ?>

            <div style="flex:1; display:flex; flex-direction:column; align-items:center; text-align:center; position:relative;">
                <!-- Connector line -->
                <?php if ($i > 0): ?>
                    <div style="position:absolute; top:16px; right:50%; width:100%; height:2px; background:<?= $connector_color ?>; z-index:0;"></div>
                <?php endif; ?>

                <!-- Dot -->
                <div style="position:relative; z-index:1; width:32px; height:32px; border-radius:50%; background:<?= $dot_bg ?>; display:flex; align-items:center; justify-content:center; box-shadow:0 1px 3px rgba(0,0,0,0.1);<?= $status === 'active' ? ' animation:pulse 2s infinite;' : '' ?>">
                    <?= $icon_html ?>
                </div>

                <!-- Label -->
                <?php if (!empty($step['link'])): ?>
                <a href="<?= base_url($step['link']) ?>" style="margin-top:8px; font-size:12px; font-weight:700; color:<?= $label_color ?>; line-height:1.2; text-decoration:underline; text-underline-offset:2px;"><?= esc($step['label']) ?></a>
                <?php else: ?>
                <p style="margin-top:8px; font-size:12px; font-weight:700; color:<?= $label_color ?>; line-height:1.2;"><?= esc($step['label']) ?></p>
                <?php endif; ?>

                <!-- Status -->
                <p style="margin-top:2px; font-size:11px; font-weight:500; color:<?= $status_color ?>;"><?= esc($step['status_label']) ?></p>

                <!-- Tanggal -->
                <?php if (!empty($step['date'])): ?>
                    <p style="margin-top:2px; font-size:10px; color:#9ca3af;"><?= date('d M Y', strtotime($step['date'])) ?></p>
                <?php endif; ?>

                <!-- PIC -->
                <?php if (!empty($step['pic'])): ?>
                    <p style="margin-top:1px; font-size:10px; color:#6b7280; max-width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= esc($step['pic']) ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
</style>
