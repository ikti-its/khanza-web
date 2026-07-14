<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Skrining Rawat Jalan</title>
    <style>
        body        { font-family: Arial, sans-serif; font-size: 13px; margin: 40px; color: #000; }
        .print-area { border: 1px solid #000; padding: 30px; max-width: 800px; margin: auto; }
        .kop            { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 10px; }
        .kop .rs-nama   { font-size: 16px; font-weight: bold; }
        .kop .rs-sub    { font-size: 11px; color: #333; margin-top: 2px; }
        hr          { border: none; border-top: 2px solid #000; margin: 12px 0; }
        .judul      { text-align: center; font-weight: bold; font-size: 15px; margin: 12px 0; text-decoration: underline; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-table td          { padding: 3px 0; vertical-align: top; }
        .info-table .label      { width: 160px; }
        .info-table .sep        { width: 20px; }
        .section-title          { font-weight: bold; margin: 14px 0 6px; font-size: 13px; }
        .item-table             { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .item-table td          { padding: 4px 6px; vertical-align: top; border: 1px solid #ccc; }
        .item-table .col-label  { width: 50%; font-weight: bold; background: #f5f5f5; }
        .keputusan              { font-size: 14px; font-weight: bold; text-align: center; padding: 8px; border: 2px solid #000; margin: 14px 0; }
        .footer                 { margin-top: 40px; text-align: right; }
        .footer .ttd            { margin-top: 60px; }
        .print-btn              { display: block; text-align: center; margin-top: 24px; }
        .print-btn button {
            padding: 8px 24px; font-size: 14px; cursor: pointer;
            background: #0A2D27; color: #ACF2E7;
            border: none; border-radius: 6px;
        }
        @media print {
            .print-btn { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

<?php
$b  = $baris      ?? [];
$dp = $dataPasien ?? [];

$tglSkrining = !empty($b['tgl_skrining']) ? date('d-m-Y', strtotime($b['tgl_skrining'])) : '-';
$jamSkrining = !empty($b['jam_skrining']) ? substr($b['jam_skrining'], 0, 5) : '-';
$tglLahir    = !empty($dp['tanggal_lahir']) ? date('d-m-Y', strtotime($dp['tanggal_lahir'])) : '-';

$boolLabel = fn($v) => ((int)$v === 1 || $v === true || $v === 'true') ? 'Ya' : 'Tidak';
?>

<div class="print-area">

    <div class="kop">
        <div class="rs-nama">RS Bhayangkara</div>
        <div class="rs-sub">Jl. Arif Rahman Hakim No. 213, Keputih, Sukolilo, Surabaya, Jawa Timur</div>
        <div class="rs-sub">Telp (031)123456789</div>
    </div>

    <div class="judul">LEMBAR SKRINING RAWAT JALAN</div>

    <hr>

    <!-- Waktu Skrining -->
    <table class="info-table">
        <tr>
            <td class="label">Tanggal Skrining</td>
            <td class="sep">:</td>
            <td><?= esc($tglSkrining) ?></td>
            <td class="label">Jam Skrining</td>
            <td class="sep">:</td>
            <td><?= esc($jamSkrining) ?></td>
        </tr>
    </table>

    <hr>

    <!-- Identitas Pasien -->
    <div class="section-title">Identitas Pasien</div>
    <table class="info-table">
        <tr>
            <td class="label">No. Rekam Medis</td>
            <td class="sep">:</td>
            <td><?= esc($b['no_rm'] ?? '-') ?></td>
            <td class="label">Tanggal Lahir</td>
            <td class="sep">:</td>
            <td><?= esc($tglLahir) ?></td>
        </tr>
        <tr>
            <td class="label">Nama Pasien</td>
            <td class="sep">:</td>
            <td><?= esc($dp['nama'] ?? '-') ?></td>
            <td class="label">Jenis Kelamin</td>
            <td class="sep">:</td>
            <td><?= esc($dp['jenis_kelamin'] ?? '-') ?></td>
        </tr>
    </table>
    
    <hr>

    <!-- Parameter Skrining -->
    <div class="section-title">Parameter Skrining</div>
    <table class="item-table">
        <tr>
            <td class="col-label">Kesadaran</td>
            <td><?= esc($b['kesadaran'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="col-label">Pernafasan</td>
            <td><?= esc($b['pernafasan'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="col-label">Skala Nyeri</td>
            <td><?= esc($b['skala_nyeri'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="col-label">Nyeri Dada</td>
            <td><?= esc($b['nyeri_dada'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="col-label">Batuk</td>
            <td><?= esc($b['kategori_batuk'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="col-label">Geriatri</td>
            <td><?= esc($boolLabel($b['is_geriatri'] ?? 0)) ?></td>
        </tr>
        <tr>
            <td class="col-label">Risiko Jatuh</td>
            <td><?= esc($boolLabel($b['is_risiko_jatuh'] ?? 0)) ?></td>
        </tr>
    </table>

    <!-- Keputusan -->
    <div class="keputusan">
        KEPUTUSAN: <?= esc(strtoupper($b['skrining_keputusan'] ?? '-')) ?>
    </div>

    <!-- TTD Petugas -->
    <div class="footer">
        <p>Petugas Skrining</p>
        <div class="ttd">
            <?= esc($b['id_petugas_nama'] ?? '-') ?>
        </div>
    </div>

</div>

<div class="print-btn">
    <button onclick="window.print()">🖨 Cetak Lembar Skrining</button>
</div>

</body>
</html>