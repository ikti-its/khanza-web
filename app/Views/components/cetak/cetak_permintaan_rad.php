<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permintaan Pemeriksaan Radiologi</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body            { font-family: Arial, sans-serif; font-size: 13px; margin: 40px; color: #000; }
        .print-area     { border: 1px solid #000; padding: 30px; max-width: 800px; margin: auto; }
        /* .header     { text-align: center; font-size: 16px; font-weight: bold; line-height: 1.8; } */
        /* .header-sub { text-align: center; font-size: 12px; line-height: 1.6; margin-top: 4px; } */
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
$reg          = $detailRegistrasi ?? [];
$tglPermintaan = $permintaan['tgl_jam_permintaan'] ?? '';
$tglFormatted  = $tglPermintaan ? date('d-m-Y H:i', strtotime($tglPermintaan)) : '-';
// $org = $organisasi ?? [];
?>

<div class="print-area">

    <?php /* Uncomment blok berikut saat data organisasi sudah tersedia:
    <div class="header"><?= esc($org['nama'] ?? '') ?></div>
    <div class="header-sub"><?= esc($org['alamat'] ?? '') ?></div>
    <hr>
    */ ?>

    <div class="judul">PERMINTAAN PEMERIKSAAN RADIOLOGI</div>

    <hr>

    <!-- Identitas Pasien -->
    <table class="info-table">
        <tr>
            <td class="label">No. Permintaan</td>
            <td class="sep">:</td>
            <td><strong><?= esc($permintaan['no_permintaan'] ?? '-') ?></strong></td>
            <td class="label">Tanggal</td>
            <td class="sep">:</td>
            <td><?= esc($tglFormatted) ?></td>
        </tr>
        <tr>
            <td class="label">No. Registrasi</td>
            <td class="sep">:</td>
            <td><?= esc($permintaan['nomor_reg'] ?? '-') ?></td>
            <td class="label">No. Rekam Medis</td>
            <td class="sep">:</td>
            <td><?= esc($reg['nomor_rm'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Nama Pasien</td>
            <td class="sep">:</td>
            <td><?= esc($reg['nama'] ?? '-') ?></td>
            <td class="label">Dokter Perujuk</td>
            <td class="sep">:</td>
            <td><?= esc($reg['nama_dokter'] ?? '-') ?></td>
        </tr>
        <?php if (!empty(trim($permintaan['indikasi_klinis'] ?? ''))) : ?>
        <tr>
            <td class="label">Indikasi Klinis</td>
            <td class="sep">:</td>
            <td colspan="4"><?= esc($permintaan['indikasi_klinis']) ?></td>
        </tr>
        <?php endif; ?>
        <?php if (!empty(trim($permintaan['informasi_tambahan'] ?? ''))) : ?>
        <tr>
            <td class="label">Informasi Tambahan</td>
            <td class="sep">:</td>
            <td colspan="4"><?= esc($permintaan['informasi_tambahan']) ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <hr>

    <!-- Item Pemeriksaan -->
    <?php if (empty($itemList)) : ?>
        <p style="color:#888; font-style:italic;">Tidak ada item pemeriksaan.</p>
    <?php else : ?>
        <table class="item-table">
            <thead>
                <tr>
                    <th style="width:36px">No</th>
                    <th style="width:110px">Kode</th>
                    <th>Nama Pemeriksaan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itemList as $i => $item) : ?>
                <tr>
                    <td class="center"><?= $i + 1 ?></td>
                    <td class="center"><?= esc($item['kode_periksa']) ?></td>
                    <td><?= esc($item['nama_pemeriksaan']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Tanda Tangan -->
    <div class="footer">
        <p>Dokter Perujuk,</p>
        <div class="ttd"><?= esc($reg['nama_dokter'] ?? '&nbsp;') ?></div>
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
