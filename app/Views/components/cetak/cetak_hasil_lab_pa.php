<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Laboratorium PA</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body            { font-family: Arial, sans-serif; font-size: 13px; margin: 40px; color: #000; }
        .print-area     { border: 1px solid #000; padding: 30px; max-width: 860px; margin: auto; }
        hr              { border: none; border-top: 2px solid #000; margin: 12px 0; }
        .judul          { text-align: center; font-weight: bold; font-size: 14px; margin: 16px 0 12px; text-decoration: underline; }
        .info-table     { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-table td          { padding: 3px 0; vertical-align: top; }
        .info-table .label      { width: 180px; }
        .info-table .sep        { width: 16px; }
        .info-table tr td:nth-child(4) { padding-left: 24px; }
        .item-block     { border: 1px solid #ccc; border-radius: 4px; padding: 12px 14px; margin-bottom: 14px; }
        .item-title     { font-weight: bold; font-size: 13px; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .field-table    { width: 100%; border-collapse: collapse; font-size: 12px; }
        .field-table td { padding: 3px 0; vertical-align: top; }
        .field-table .label { width: 160px; }
        .field-table .sep   { width: 12px; }
        .footer         { margin-top: 40px; display: flex; justify-content: space-between; }
        .footer .ttd    { margin-top: 60px; }
        .print-btn      { display: block; text-align: center; margin-top: 24px; }
        @media print {
            .print-btn { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

<?php
$h             = $header ?? [];
$tglPermintaan = !empty($h['tgl_permintaan']) ? date('d-m-Y H:i', strtotime($h['tgl_permintaan'])) : '-';
$tglHasil      = !empty($tgl_jam_hasil)        ? date('d-m-Y H:i', strtotime($tgl_jam_hasil))        : '-';
?>

<div class="print-area">

    <div class="judul">HASIL PEMERIKSAAN LABORATORIUM PA</div>
    <hr>

    <table class="info-table">
        <tr>
            <td class="label">No. Permintaan</td>
            <td class="sep">:</td>
            <td><strong><?= esc($h['no_permintaan'] ?? '-') ?></strong></td>
            <td class="label">Tgl. Permintaan</td>
            <td class="sep">:</td>
            <td><?= esc($tglPermintaan) ?></td>
        </tr>
        <tr>
            <td class="label">No. Registrasi</td>
            <td class="sep">:</td>
            <td><?= esc($h['nomor_reg'] ?? '-') ?></td>
            <td class="label">No. Rekam Medis</td>
            <td class="sep">:</td>
            <td><?= esc($h['nomor_rm'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Nama Pasien</td>
            <td class="sep">:</td>
            <td><?= esc($h['nama_pasien'] ?? '-') ?></td>
            <td class="label">Dokter Perujuk</td>
            <td class="sep">:</td>
            <td><?= esc($h['nama_dokter_perujuk'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Dokter PJ</td>
            <td class="sep">:</td>
            <td><?= esc($nama_dokter_pj ?? '-') ?></td>
            <td class="label">Tanggal Hasil</td>
            <td class="sep">:</td>
            <td><?= esc($tglHasil) ?></td>
        </tr>
        <tr>
            <td class="label">Petugas Lab</td>
            <td class="sep">:</td>
            <td><?= esc($nama_petugas ?? '-') ?></td>
            <td></td><td></td><td></td>
        </tr>
    </table>

    <hr>

    <?php if (empty($items)) : ?>
        <p style="color:#888; font-style:italic;">Tidak ada hasil pemeriksaan.</p>
    <?php else : ?>
        <?php foreach ($items as $i => $item) : ?>
        <div class="item-block">
            <div class="item-title">
                <?= esc($item['nama_item']) ?>
            </div>
            <table class="field-table">
                <tr>
                    <td class="label">Diagnosa Klinis</td>
                    <td class="sep">:</td>
                    <td><?= nl2br(esc($item['diagnosa_klinis'] ?? '-')) ?></td>
                </tr>
                <tr>
                    <td class="label">Makroskopik</td>
                    <td class="sep">:</td>
                    <td><?= nl2br(esc($item['makroskopik'] ?? '-')) ?></td>
                </tr>
                <tr>
                    <td class="label">Mikroskopik</td>
                    <td class="sep">:</td>
                    <td><?= nl2br(esc($item['mikroskopik'] ?? '-')) ?></td>
                </tr>
                <tr>
                    <td class="label">Kesimpulan</td>
                    <td class="sep">:</td>
                    <td><?= nl2br(esc($item['kesimpulan'] ?? '-')) ?></td>
                </tr>
                <?php if (!empty(trim($item['kesan'] ?? ''))) : ?>
                <tr>
                    <td class="label">Kesan</td>
                    <td class="sep">:</td>
                    <td><?= nl2br(esc($item['kesan'])) ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="footer">
        <div>
            <p>Mengetahui,</p>
            <div class="ttd"><?= esc($h['nama_dokter_perujuk'] ?? '&nbsp;') ?></div>
        </div>
        <div style="text-align:center">
            <p>Dokter Penanggung Jawab,</p>
            <div class="ttd"><?= esc($nama_dokter_pj ?? '&nbsp;') ?></div>
        </div>
        <div style="text-align:right">
            <p>Petugas Lab,</p>
            <div class="ttd"><?= esc($nama_petugas ?? '&nbsp;') ?></div>
        </div>
    </div>

</div>

<div class="print-btn">
    <button onclick="window.print()"
        style="background:#1e4b4d; color:#fff; padding:10px 24px; font-size:14px; border:none; border-radius:4px; cursor:pointer; font-weight:bold;">
        🖨 Cetak Hasil
    </button>
</div>

</body>
</html>
