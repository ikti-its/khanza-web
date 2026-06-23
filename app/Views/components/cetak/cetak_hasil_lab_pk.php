<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Laboratorium PK</title>
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
        table.item-table        { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 12px; }
        table.item-table th,
        table.item-table td     { border: 1px solid #aaa; padding: 5px 7px; }
        table.item-table th     { background: #f0f0f0; font-weight: bold; text-align: center; }
        table.item-table td.center { text-align: center; }
        .item-header td         { font-weight: bold; background: #e8f0f0; }
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

    <div class="judul">HASIL PEMERIKSAAN LABORATORIUM PK</div>
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
        <table class="item-table">
            <thead>
                <tr>
                    <th>Nama Pemeriksaan / Parameter</th>
                    <th style="width:100px">Nilai Hasil</th>
                    <th style="width:70px">Satuan</th>
                    <th style="width:130px">Nilai Rujukan</th>
                    <th style="width:120px">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) : ?>
                <tr class="item-header">
                    <td colspan="5"><?= esc($item['nama_item']) ?></td>
                </tr>
                <?php foreach ($item['parameter'] ?? [] as $param) : ?>
                <tr>
                    <td></td>
                    <td style="padding-left:16px"><?= esc($param['nama_parameter']) ?></td>
                    <td class="center"><?= esc($param['nilai_hasil'] ?? '-') ?></td>
                    <td class="center"><?= esc($param['satuan'] ?? '-') ?></td>
                    <td><?= esc($param['nilai_rujukan'] ?? '-') ?></td>
                    <td><?= esc($param['keterangan_hasil'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer">
        <div>
            <p>Mengetahui,</p>
            <div class="ttd"><?= esc($h['nama_dokter_perujuk'] ?? '&nbsp;') ?></div>
        </div>
        <div style="text-align:center">
            <p>Dokter Penanggungjawab,</p>
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
