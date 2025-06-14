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
      <?= $organisasi['nama'] ?? 'Rumah Sakit' ?><br>
      <?= $organisasi['alamat'] ?? '-' ?><br>
      <?= $organisasi['no_telp'] ?? 'Hp: 08562675039, 085296559963' ?><br>
      Email : rumahsakitbhayangkara@gmail.com
    </div>

    <hr>

    <p class="right">Surabaya, <?php
function formatTanggalIndo($tanggal)
{
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $date = new DateTime($tanggal);
    $day = $date->format('j');
    $month = (int) $date->format('n');
    $year = $date->format('Y');

    return "$day {$bulan[$month]} $year";
}
?>

<?= formatTanggalIndo($rujukan['tanggal_rujuk']) ?></p>

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
    <p>Mohon pemeriksaan dan penanganan lebih lanjut penderita :</p>

    <div class="info indent">
      <p>Tanggal Rawat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= formatTanggalIndo($rujukan['tanggal_rujuk']) ?></p>
      <p>Nama Pasien&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= esc($rujukan['nama_pasien']) ?></p>
      <p>No. Rekam Medis&nbsp;&nbsp;: <?= esc($rujukan['nomor_rm']) ?></p>
      <p>Alamat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= $rawatinap['alamat_pasien'] ?? '-' ?> </p>
      <p>Diagnosa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= esc($rujukan['keterangan_diagnosa']) ?></p>
      <?php foreach ($tindakanList as $t): ?>
      <p>Tindakan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= $t['nama_tindakan'] ?? '-' ?> </p>
      <?php endforeach; ?>
<?php if (!empty($obatList)): ?>
    <p>Terapi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
        <?php
        $terapiList = array_map(function($obat) {
            $nama = $obat['nama_obat'] ?? $obat['kode_obat'];
            $jumlah = $obat['jumlah'] ?? '-';
            $satuan = $obat['satuan'] ?? '';
            return "{$nama} - {$jumlah} {$satuan}";
        }, $obatList);
        echo implode(', ', $terapiList);
        ?>
    </p>
<?php else: ?>
    <p>Terapi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: -</p>
<?php endif; ?>

    </div>

    <br>
    <p>Demikianlah riwayat perawatan selama di RS SIMRS KHANZA dengan diagnosa akhir <?= esc($rujukan['keterangan_diagnosa']) ?>.</p>
    <p>Atas kerjasamanya kami ucapkan terima kasih</p>

    <div class="signature">
      <?= $organisasi['nama'] ?? 'Rumah Sakit' ?><br>
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
