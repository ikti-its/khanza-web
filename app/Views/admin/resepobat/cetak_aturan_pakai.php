<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Label Resep Obat</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .label-container {
      width: 380px;
      border: 2px solid #1e4b4d;
      padding: 12px 14px;
      margin: 20px auto;
    }

    .header {
      text-align: center;
      margin-bottom: 10px;
    }

    .header strong {
      font-size: 14px;
    }

    .divider {
      border-top: 2px solid #1e4b4d;
      margin: 6px 0;
    }

    .content {
      font-size: 14px;
      line-height: 1.4;
    }

    .content b {
      display: inline-block;
      min-width: 80px;
    }

    .barcode {
      text-align: left;
      margin-top: 4px;
    }

    .bottom-text {
      text-align: center;
      font-size: 16px;
      margin-top: 8px;
    }

    @media print {
      body {
        margin: 0;
      }

      .label-container {
        page-break-after: always;
        border: none;
      }
    }

    @media print {
    button {
        display: none !important;
    }
    }

  </style>
</head>
<body>

<?php foreach ($resep_dokter as $resep): ?>
  <div class="label-container">
    <div class="header">
      <strong>Rs Pelita Insani</strong><br>
      Jl. Sekumpul No.66, Martapura, Kalimantan<br>
      Telp (0511)4722210, 4722220
    </div>

    <div class="divider"></div>

    <div class="header">
      <strong>INSTALASI FARMASI</strong><br>
      Resep: <strong><?= $resep['no_resep'] ?></strong><br>
      Tgl: <strong><?= date('Y-m-d', strtotime($resep['tgl_perawatan'])) ?></strong> 
      Jam: <strong><?= $resep['jam'] ?></strong>
    </div>

    <div class="content">
      <b>No. RM:</b> <?= $resep['nomor_rm'] ?><br>
      <b>Nama:</b> <?= strtoupper($resep['nama_pasien']) ?><br>
      <b>Alamat:</b> <?= $resep['alamat_pasien'] ?><br>
      <b>Penanggung Jawab:</b> <?= $resep['penanggung_jawab'] ?><br>
      <div class="barcode" style="text-align:center; margin-top: 8px;">
        <img 
            src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?= $resep['no_resep'] ?>&scale=1.2&includetext&height=8"
            alt="Barcode for <?= $resep['no_resep'] ?>" 
            style="max-width: 100%; height: auto;"
        />
        </div>


      <?= $resep['nama_obat'] ?> <span style="float: right;"><?= $resep['jumlah'] ?> <?= isset($resep['satuan']) ? $resep['satuan'] : 'unit' ?></span>
    </div>

    <div class="bottom-text">
      <?= $resep['aturan_pakai'] ?>
    </div>
  </div>
<?php endforeach; ?>

<div style="text-align: center; margin-top: 20px;">
  <button onclick="window.print()" 
    style="background-color: #1e4b4d; color: white; padding: 10px 20px; font-size: 14px; border: none; border-radius: 4px; cursor: pointer;">
    🖨 Cetak Label
  </button>
</div>


</body>
</html>
