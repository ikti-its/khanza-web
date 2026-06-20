<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Pemeriksaan Radiologi</title>
    <style>
        body            { font-family: Arial, sans-serif; font-size: 13px; margin: 40px; color: #000; }
        .print-area     { border: 1px solid #000; padding: 30px; max-width: 800px; margin: auto; }
        /* .header     { text-align: center; font-size: 16px; font-weight: bold; line-height: 1.8; } */
        /* .header-sub { text-align: center; font-size: 12px; line-height: 1.6; margin-top: 4px; } */
        hr              { border: none; border-top: 2px solid #000; margin: 12px 0; }
        .judul          { text-align: center; font-weight: bold; font-size: 14px; margin: 16px 0 12px; text-decoration: underline; }
        .info-table     { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-table td                  { padding: 3px 0; vertical-align: top; }
        .info-table .label              { width: 140px; }
        .info-table .sep                { width: 24px; }
        .info-table tr td:nth-child(4)  { padding-left: 24px; }
        .ekspertise-item       { margin-bottom: 20px; }
        .ekspertise-item .nama { font-weight: bold; margin-bottom: 6px; }
        .ekspertise-item .isi  { white-space: pre-wrap; line-height: 1.7; border-left: 3px solid #ccc; padding-left: 12px; }
        .empty-ekspertise      { color: #555; font-style: italic; }
        .footer         { margin-top: 40px; text-align: right; }
        .footer .ttd    { margin-top: 60px; }
        .print-btn      { display: block; text-align: center; margin-top: 24px; }
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
$pasien       = $detailPermintaan ?? [];
$tglHasil     = $hasilRad['tgl_jam_hasil'] ?? '';
$tglFormatted = $tglHasil ? date('d-m-Y H:i', strtotime($tglHasil)) : '-';
// $org = $organisasi ?? [];
?>

<div class="print-area">

    <?php /* Uncomment kalo data organisasi sudah ada:
    <div class="header"><?= esc($org['nama'] ?? '') ?></div>
    <div class="header-sub"><?= esc($org['alamat'] ?? '') ?></div>
    <hr>
    */ ?>

    <div class="judul">HASIL PEMERIKSAAN RADIOLOGI</div>

    <hr>

    <!-- Identitas Pasien -->
    <table class="info-table">
        <tr>
            <td class="label">No. Permintaan</td>
            <td class="sep">:</td>
            <td><?= esc($pasien['no_permintaan'] ?? '-') ?></td>
            <td class="label">Tanggal Hasil</td>
            <td class="sep">:</td>
            <td><?= esc($tglFormatted) ?></td>
        </tr>
        <tr>
            <td class="label">No. Registrasi</td>
            <td class="sep">:</td>
            <td><?= esc($pasien['nomor_reg'] ?? '-') ?></td>
            <td class="label">Dokter PJ</td>
            <td class="sep">:</td>
            <td><?= esc($namaDokterPj ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Nama Pasien</td>
            <td class="sep">:</td>
            <td><?= esc($pasien['nama_pasien'] ?? '-') ?></td>
            <td class="label">Dokter Perujuk</td>
            <td class="sep">:</td>
            <td><?= esc($pasien['nama_dokter_perujuk'] ?? '-') ?></td>
        </tr>
    </table>

    <hr>

    <!-- Hasil Ekspertise Per Item -->
    <?php if (empty($tindakanList)) : ?>
        <p class="empty-ekspertise">Tidak ada data tindakan.</p>
    <?php else : ?>
        <?php foreach ($tindakanList as $t) : ?>
            <div class="ekspertise-item">
                <div class="nama"><?= esc($t['nama_pemeriksaan']) ?></div>
                <?php if (!empty(trim($t['hasil_ekspertise'] ?? ''))) : ?>
                    <div class="isi"><?= esc($t['hasil_ekspertise']) ?></div>
                <?php else : ?>
                    <div class="isi empty-ekspertise">Belum ada hasil ekspertise.</div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty(trim($hasilRad['catatan'] ?? ''))) : ?>
        <hr>
        <p><strong>Catatan:</strong> <?= esc($hasilRad['catatan']) ?></p>
    <?php endif; ?>

    <!-- Tanda Tangan -->
    <div class="footer">
        <p>Penanggung Jawab</p>
        <div class="ttd">
            <?= esc($namaDokterPj ?? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') ?>
        </div>
    </div>

</div>

<div class="print-btn">
    <button onclick="window.print()"
        style="background-color: #1e4b4d; color: white; padding: 10px 20px; font-size: 14px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
        🖨 Cetak Hasil
    </button>
</div>

</body>
</html>
