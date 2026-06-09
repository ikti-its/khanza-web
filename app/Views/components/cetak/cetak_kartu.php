<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cetak Kartu Pendonor</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }

    .card-container {
      width: 550px;
      background-color: #ffffff;
      border: 1px solid #999999;
      box-shadow: 5px 5px 0px rgba(0, 0, 0, 0.15);
      padding: 15px 30px;
      margin: 20px auto;
      box-sizing: border-box;
      position: relative;
    }

    .header {
      font-size: 16px;
      text-align: center;
      margin-bottom: 10px;
      line-height: 1.3;
    }

    .header strong {
      font-size: 17px;
      display: inline-block;
      padding-bottom: 2px;
    }

    .divider {
      border-top: 2px solid #1e4b4d;
      margin: 6px 0;
    }

    .info-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 16px;
      color: #000000;
      line-height: 1.6;
    }

    .info-table td {
      padding: 3px 0;
      vertical-align: top;
    }

    .info-table td.label {
      width: 140px;
    }

    .info-table td.colon {
      width: 25px;
      text-align: center;
    }

    .footer-section {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 10px;
      padding-top: 5px;
    }

    .blood-type {
      font-size: 35px;
      font-weight: bold;
      color: #000000;
      font-family: Arial, sans-serif;
    }

    .barcode-container {
      text-align: right;
    }

    .barcode-container img {
      height: 45px;
      max-width: 220px;
    }

    @media print {
      body {
        background-color: #ffffff;
        padding: 0;
        margin: 0;
      }

      .card-container {
        border: 1px solid #000000;
        box-shadow: none;
        margin: 0 auto;
        page-break-after: always;
      }

      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>

  <div class="card-container">
    <div class="header">
      <strong>RS Bhayangkara</strong><br>
      Jl. Arif Rahman Hakim No. 213, Keputih, Sukolilo,<br>
      Surabaya, Jawa Timur<br>
      Telp (0821)123456789
    </div>

    <div class="divider"></div>

    <table class="info-table">
      <tr>
        <td class="label">No. Pendonor</td>
        <td class="colon">:</td>
        <td><?= esc($baris['nomor_pendonor']) ?></td>
      </tr>
      <tr>
        <td class="label">Nama</td>
        <td class="colon">:</td>
        <td><?= esc($baris['nama']) ?></td>
      </tr>
      <tr>
        <td class="label">Jenis Kelamin</td>
        <td class="colon">:</td>
        <td><?= esc($baris['id_jenis_kelamin']) ?></td>
      </tr>
      <tr>
        <td class="label">NIK</td>
        <td class="colon">:</td>
        <td><?= esc($baris['nik']) ?></td>
      </tr>
      <tr>
        <td class="label">Tempat, Tgl Lahir</td>
        <td class="colon">:</td>
        <td>
            <?= esc($baris['tempat_lahir_kota']) ?>,
            <?= date('d/m/Y', strtotime($baris['tanggal_lahir'])) ?>
        </td>
      </tr>
      <tr>
        <td class="label">Alamat</td>
        <td class="colon">:</td>
        <td><?= esc($baris['id_alamat']) ?></td>
      </tr>
      <tr>
        <td class="label">No. Telepon</td>
        <td class="colon">:</td>
        <td><?= esc($baris['nomor_telepon'] ?? '-') ?></td>
      </tr>
    </table>

    <div class="footer-section">
      <div class="blood-type">
        <?= esc($baris['id_golongan_darah']) ?>(<?= esc($baris['id_rhesus']) ?>)
      </div>
      
      <div class="barcode-container">
        <img 
          src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?= urlencode($baris['nomor_pendonor']) ?>&scale=2&height=10"
          alt="Barcode ID Pendonor"
        />
      </div>
    </div>
  </div>

  <div class="no-print" style="text-align: center; margin-top: 20px;">
    <button onclick="window.print()" 
      style="background-color: #1e4b4d; color: white; padding: 10px 20px; font-size: 14px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
      🖨 Cetak Kartu
    </button>
  </div>

</body>
</html>
