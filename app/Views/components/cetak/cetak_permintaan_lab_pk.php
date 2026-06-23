<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permintaan Pemeriksaan Lab PK</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body            { font-family: Arial, sans-serif; font-size: 13px; margin: 40px; color: #000; }
        .print-area     { border: 1px solid #000; padding: 30px; max-width: 800px; margin: auto; }
        hr              { border: none; border-top: 2px solid #000; margin: 12px 0; }
        .judul          { text-align: center; font-weight: bold; font-size: 14px; margin: 16px 0 12px; text-decoration: underline; }
        .info-table     { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-table td          { padding: 3px 0; vertical-align: top; }
        .info-table .label      { width: 180px; }
        .info-table .sep        { width: 16px; }
        .info-table tr td:nth-child(4) { padding-left: 24px; }
        table.item-table        { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 12px; }
        table.item-table th,
        table.item-table td     { border: 1px solid #aaa; padding: 6px 8px; }
        table.item-table th     { background: #f0f0f0; font-weight: bold; text-align: center; }
        table.item-table td.center { text-align: center; }
        .footer         { margin-top: 40px; text-align: right; }
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
$tglFormatted  = !empty($h['tgl_permintaan']) ? date('d-m-Y H:i', strtotime($h['tgl_permintaan'])) : '-';
?>

<div class="print-area">

    <div class="judul">PERMINTAAN PEMERIKSAAN LABORATORIUM PK</div>
    <hr>

    <table class="info-table">
        <tr>
            <td class="label">No. Permintaan</td>
            <td class="sep">:</td>
            <td><strong><?= esc($h['no_permintaan'] ?? '-') ?></strong></td>
            <td class="label">Tanggal</td>
            <td class="sep">:</td>
            <td><?= esc($tglFormatted) ?></td>
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
            <td><?= esc($h['nama_dokter'] ?? '-') ?></td>
        </tr>
        <?php if (!empty(trim($h['indikasi_klinis'] ?? ''))) : ?>
        <tr>
            <td class="label">Indikasi Klinis</td>
            <td class="sep">:</td>
            <td colspan="4"><?= esc($h['indikasi_klinis']) ?></td>
        </tr>
        <?php endif; ?>
        <?php if (!empty(trim($h['informasi_tambahan'] ?? ''))) : ?>
        <tr>
            <td class="label">Informasi Tambahan</td>
            <td class="sep">:</td>
            <td colspan="4"><?= esc($h['informasi_tambahan']) ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <hr>

    <?php if (empty($items)) : ?>
        <p style="color:#888; font-style:italic;">Tidak ada item pemeriksaan.</p>
    <?php else : ?>
        <table class="item-table">
            <thead>
                <tr>
                    <th style="width:36px">No</th>
                    <th>Nama Pemeriksaan</th>
                    <th>Parameter</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item) : ?>
                <tr>
                    <td class="center"><?= $i + 1 ?></td>
                    <td><?= esc($item['nama_item']) ?></td>
                    <td>
                        <?php foreach ($item['parameter'] ?? [] as $param) : ?>
                            <?= esc($param['nama_parameter']) ?><?= !empty($param['satuan']) ? ' (' . esc($param['satuan']) . ')' : '' ?><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer">
        <p>Dokter Perujuk,</p>
        <div class="ttd"><?= esc($h['nama_dokter'] ?? '&nbsp;') ?></div>
    </div>

</div>

<div class="print-btn">
    <button onclick="window.print()"
        style="background:#1e4b4d; color:#fff; padding:10px 24px; font-size:14px; border:none; border-radius:4px; cursor:pointer; font-weight:bold;">
        🖨 Cetak Permintaan
    </button>
</div>

</body>
</html>
