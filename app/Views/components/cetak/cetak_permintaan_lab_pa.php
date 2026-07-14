<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permintaan Pemeriksaan Lab PA</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body            { font-family: Arial, sans-serif; font-size: 13px; margin: 40px; color: #000; }
        .print-area     { border: 1px solid #000; padding: 30px; max-width: 800px; margin: auto; }
        .kop            { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 10px; }
        .kop .rs-nama   { font-size: 16px; font-weight: bold; }
        .kop .rs-sub    { font-size: 11px; color: #333; margin-top: 2px; }
        hr              { border: none; border-top: 2px solid #000; margin: 12px 0; }
        .judul          { text-align: center; font-weight: bold; font-size: 14px; margin: 16px 0 12px; text-decoration: underline; }
        .section-title  { font-weight: bold; font-size: 12px; margin: 12px 0 4px; text-decoration: underline; }
        .info-table     { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
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
$h   = $header   ?? [];
$sp  = $spesimen ?? [];
$tglFormatted      = !empty($h['tgl_permintaan'])          ? date('d-m-Y H:i', strtotime($h['tgl_permintaan']))          : '-';
$tglAmbilFormatted = !empty($sp['tgl_pengambilan_bahan'])  ? date('d-m-Y',      strtotime($sp['tgl_pengambilan_bahan'])) : '-';
$tglRiwFormatted   = !empty($sp['riwayat_tgl_sebelumnya']) ? date('d-m-Y',      strtotime($sp['riwayat_tgl_sebelumnya'])) : '-';

$adaRiwayat = !empty(trim($sp['riwayat_lokasi_lab'] ?? ''))
           || !empty(trim($sp['riwayat_no_pa_sebelumnya'] ?? ''))
           || !empty(trim($sp['riwayat_diagnosa_sebelumnya'] ?? ''))
           || !empty(trim($sp['riwayat_tgl_sebelumnya'] ?? ''));
?>

<div class="print-area">

    <div class="kop">
        <div class="rs-nama">RS Bhayangkara</div>
        <div class="rs-sub">Jl. Arif Rahman Hakim No. 213, Keputih, Sukolilo, Surabaya, Jawa Timur</div>
        <div class="rs-sub">Telp (031)123456789</div>
    </div>

    <div class="judul">PERMINTAAN PEMERIKSAAN LABORATORIUM PA</div>
    <hr>

    <!-- Identitas -->
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

    <!-- Spesimen -->
    <div class="section-title">Data Spesimen</div>
    <table class="info-table">
        <tr>
            <td class="label">Tgl. Pengambilan Bahan</td>
            <td class="sep">:</td>
            <td><?= esc($tglAmbilFormatted) ?></td>
            <td class="label">Metode Diperoleh</td>
            <td class="sep">:</td>
            <td><?= esc($sp['metode_diperoleh'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Lokasi Jaringan</td>
            <td class="sep">:</td>
            <td><?= esc($sp['lokasi_jaringan'] ?? '-') ?></td>
            <td class="label">Bahan Pengawet</td>
            <td class="sep">:</td>
            <td><?= esc($sp['bahan_pengawet'] ?? '-') ?></td>
        </tr>
    </table>

    <?php if ($adaRiwayat) : ?>
    <div class="section-title">Riwayat Pemeriksaan Sebelumnya</div>
    <table class="info-table">
        <tr>
            <td class="label">Lokasi Lab Sebelumnya</td>
            <td class="sep">:</td>
            <td><?= esc($sp['riwayat_lokasi_lab'] ?? '-') ?></td>
            <td class="label">Tanggal Sebelumnya</td>
            <td class="sep">:</td>
            <td><?= esc($tglRiwFormatted) ?></td>
        </tr>
        <tr>
            <td class="label">No. PA Sebelumnya</td>
            <td class="sep">:</td>
            <td><?= esc($sp['riwayat_no_pa_sebelumnya'] ?? '-') ?></td>
            <td class="label">Diagnosa Sebelumnya</td>
            <td class="sep">:</td>
            <td><?= esc($sp['riwayat_diagnosa_sebelumnya'] ?? '-') ?></td>
        </tr>
    </table>
    <?php endif; ?>

    <hr>

    <!-- Item Pemeriksaan -->
    <?php if (empty($items)) : ?>
        <p style="color:#888; font-style:italic;">Tidak ada item pemeriksaan.</p>
    <?php else : ?>
        <table class="item-table">
            <thead>
                <tr>
                    <th style="width:36px">No</th>
                    <th>Nama Pemeriksaan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item) : ?>
                <tr>
                    <td class="center"><?= $i + 1 ?></td>
                    <td><?= esc($item['nama_item']) ?></td>
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
