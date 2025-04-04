<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Rujukan</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    .print-area { border: 1px solid #000; padding: 30px; max-width: 800px; margin: auto; }
    .header { text-align: center; font-size: 18px; font-weight: bold; line-height: 1.5; }
    .right { text-align: right; margin-top: 10px; }
    .meta { margin-top: 30px; }
    .indent { margin-left: 40px; }
    .info { margin-top: 20px; }
    .signature { text-align: right; margin-top: 80px; }
    .signature-name { text-align: right; margin-top: 60px; }
    .underline { text-decoration: underline; }
    @media print {
      button { display: none; }
    }
  </style>
</head>
<body>

  <div class="print-area" id="printThis">
    <div class="header">
      RS SIMRS KHANZA<br>
      GUWOSARI, Pajangan, Bantul<br>
      Hp: 08562675039, 085296559963<br>
      Email : khanzasoftmedia@gmail.com
    </div>

    <hr>

    <p class="right">Pajangan, <?= date('d-m-Y', strtotime($rujukan['tanggal_rujuk'])) ?></p>

    <div class="meta">
      <p>Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= esc($rujukan['nomor_rujuk']) ?></p>
      <p>Hal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: SURAT RUJUKAN</p>
    </div>

    <br>
    <p>Kepada Yth.</p>
    <p><?= esc($rujukan['tempat_rujuk']) ?></p>
    <p>Di Tempat</p>

    <br>
    <p>Bersama ini kami beritahukan bahwa kami telah merawat / memeriksa pasien berikut ini.</p>

    <div class="info indent">
      <p>Tanggal Rawat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= date('d-m-Y', strtotime($rujukan['tanggal_rujuk'])) ?></p>
      <p>Nama Pasien&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= esc($rujukan['nama_pasien']) ?></p>
      <p>No. Rekam Medis&nbsp;&nbsp;: <?= esc($rujukan['nomor_rm']) ?></p>
      <p>Alamat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: - </p>
      <p>Diagnosa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= esc($rujukan['keterangan_diagnosa']) ?></p>
      <p>Tindakan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </p>
      <p>Terapi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: -</p>
    </div>

    <br>
    <p>Demikianlah riwayat perawatan selama di RS SIMRS KHANZA dengan diagnosa akhir <?= esc($rujukan['keterangan_diagnosa']) ?>.</p>
    <p>Atas kerjasamanya kami ucapkan terima kasih</p>

    <div class="signature">
      RS SIMRS KHANZA<br>
      Dokter yang merawat,
    </div>

    <div class="signature-name">
      ( &nbsp;&nbsp;&nbsp;&nbsp;<?= esc($rujukan['dokter_perujuk']) ?> &nbsp;&nbsp;&nbsp;&nbsp; )
    </div>
  </div>

  <div style="text-align: center; margin-top: 20px;">
    <button onclick="window.print()">🖨️ Cetak Surat</button>
  </div>

</body>
</html>
