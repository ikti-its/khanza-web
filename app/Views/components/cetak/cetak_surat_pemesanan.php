<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Pemesanan <?= esc($header['no_pengadaan'] ?? '') ?></title>
  <link rel="icon" type="image/jpeg" href="<?= base_url('img/omnia.jpeg') ?>">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: Arial, sans-serif;
      font-size: 13px;
      background: #f4f4f4;
      padding: 20px;
    }

    .dokumen {
      width: 740px;
      background: #fff;
      border: 1px solid #aaa;
      padding: 24px 32px;
      margin: 0 auto;
    }

    /* === KOP SURAT === */
    .kop {
      display: flex;
      align-items: center;
      border-bottom: 3px double #000;
      padding-bottom: 10px;
      margin-bottom: 16px;
      gap: 16px;
    }
    .kop-teks { line-height: 1.5; }
    .kop-teks .rs-nama { font-size: 16px; font-weight: bold; }
    .kop-teks .rs-sub  { font-size: 12px; color: #333; }

    /* === JUDUL === */
    .judul {
      text-align: center;
      font-size: 15px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 4px;
    }
    .judul-sub {
      text-align: center;
      font-size: 12px;
      color: #555;
      margin-bottom: 16px;
    }

    /* === INFO HEADER === */
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4px 24px;
      margin-bottom: 16px;
    }
    .info-baris { display: flex; gap: 6px; font-size: 12px; }
    .info-label { min-width: 110px; }
    .info-titik { flex-shrink: 0; }

    /* === TABEL BARANG === */
    table.barang {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
    }
    table.barang th,
    table.barang td {
      border: 1px solid #999;
      padding: 5px 8px;
    }
    table.barang th {
      background: #f0f0f0;
      text-align: center;
      font-weight: bold;
      font-size: 12px;
    }
    table.barang td { vertical-align: top; font-size: 12px; }
    table.barang td.center { text-align: center; }
    table.barang td.right  { text-align: right; }
    table.barang tfoot td  { font-weight: bold; background: #fafafa; }

    /* === REKENING === */
    .rekening-area {
      margin-top: 10px;
      font-size: 12px;
      border: 1px dashed #aaa;
      border-radius: 4px;
      padding: 8px 12px;
      background: #fafafa;
    }
    .rekening-area .rek-title {
      font-weight: bold;
      margin-bottom: 4px;
      font-size: 12px;
      color: #333;
    }

    /* === CATATAN === */
    .catatan-area {
      margin-top: 10px;
      font-size: 12px;
      color: #444;
    }

    /* === TTD === */
    .ttd-area {
      display: flex;
      justify-content: space-between;
      margin-top: 32px;
      gap: 16px;
    }
    .ttd-box {
      text-align: center;
      flex: 1;
    }
    .ttd-box .ttd-garis {
      margin-top: 60px;
      border-top: 1px solid #000;
      padding-top: 4px;
      font-size: 12px;
    }

    /* === TOMBOL CETAK === */
    .no-print {
      text-align: center;
      margin-top: 20px;
    }

    @media print {
      body { background: #fff; padding: 0; }
      .dokumen { border: none; box-shadow: none; margin: 0; width: 100%; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>

<div class="dokumen">

  <!-- KOP SURAT -->
  <div class="kop">
    <div class="kop-teks">
      <div class="rs-nama">RS Bhayangkara</div>
      <div class="rs-sub">Jl. Arif Rahman Hakim No. 213, Keputih, Sukolilo, Surabaya, Jawa Timur</div>
      <div class="rs-sub">Telp (031)123456789</div>
    </div>
  </div>

  <!-- JUDUL -->
  <div class="judul">Surat Pemesanan</div>
  <div class="judul-sub">Purchase Order</div>

  <!-- INFO HEADER -->
  <div class="info-grid">
    <div>
      <div class="info-baris">
        <span class="info-label">No. Pemesanan</span>
        <span class="info-titik">:</span>
        <span><strong><?= esc($header['no_pengadaan'] ?? '-') ?></strong></span>
      </div>
      <div class="info-baris">
        <span class="info-label">Tanggal</span>
        <span class="info-titik">:</span>
        <span>
          <?php
            $tgl = $header['tanggal'] ?? '';
            echo esc($tgl ? date('d/m/Y', strtotime($tgl)) : '-');
          ?>
        </span>
      </div>
      <?php if (!empty($header['no_pengajuan'])): ?>
      <div class="info-baris">
        <span class="info-label">Ref. Pengajuan</span>
        <span class="info-titik">:</span>
        <span><?= esc($header['no_pengajuan']) ?></span>
      </div>
      <?php endif; ?>
      <?php if (!empty($header['nama_petugas'])): ?>
      <div class="info-baris">
        <span class="info-label">Petugas Gudang</span>
        <span class="info-titik">:</span>
        <span><?= esc($header['nama_petugas']) ?></span>
      </div>
      <?php endif; ?>
    </div>
    <div>
      <div class="info-baris">
        <span class="info-label">Kepada Yth.</span>
        <span class="info-titik">:</span>
        <span><strong><?= esc($header['nama_suplier'] ?? '-') ?></strong></span>
      </div>
      <?php if (!empty($header['alamat'])): ?>
      <div class="info-baris">
        <span class="info-label">Alamat</span>
        <span class="info-titik">:</span>
        <span><?= esc($header['alamat']) ?></span>
      </div>
      <?php endif; ?>
      <?php if (!empty($header['no_telp'])): ?>
      <div class="info-baris">
        <span class="info-label">No. Telp</span>
        <span class="info-titik">:</span>
        <span><?= esc($header['no_telp']) ?></span>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- TABEL BARANG -->
  <?php $total = 0; ?>
  <table class="barang">
    <thead>
      <tr>
        <th style="width:32px">No</th>
        <th>Nama Barang</th>
        <th style="width:70px">Satuan</th>
        <th style="width:55px">Qty</th>
        <th style="width:110px">Harga Satuan</th>
        <th style="width:120px">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr>
        <td colspan="6" class="center" style="color:#888">Tidak ada item</td>
      </tr>
      <?php else: ?>
      <?php foreach ($items as $i => $item):
        $harga    = (float) ($item['harga_satuan'] ?? 0);
        $qty      = (float) ($item['qty'] ?? 0);
        $subtotal = $harga * $qty;
        $total   += $subtotal;
      ?>
      <tr>
        <td class="center"><?= $i + 1 ?></td>
        <td><?= esc($item['nama_barang'] ?? '-') ?></td>
        <td class="center"><?= esc($item['nama_satuan'] ?? '-') ?></td>
        <td class="center"><?= number_format($qty, 0, ',', '.') ?></td>
        <td class="right">Rp <?= number_format($harga, 0, ',', '.') ?></td>
        <td class="right">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5" class="right">Total</td>
        <td class="right">Rp <?= number_format($total, 0, ',', '.') ?></td>
      </tr>
    </tfoot>
  </table>

  <!-- INFORMASI PEMBAYARAN -->
  <?php
    $no_rek   = $header['nomor_rekening'] ?? '';
    $nm_akun  = $header['nama_akun'] ?? '';
    $nm_bank  = $header['nama_bank'] ?? '';
    $has_rek  = ($no_rek !== '' && $no_rek !== '-') || ($nm_bank !== '');
  ?>
  <?php if ($has_rek): ?>
  <div class="rekening-area">
    <div class="rek-title">Informasi Pembayaran</div>
    <?php if ($nm_bank !== ''): ?>
    <div class="info-baris">
      <span class="info-label">Bank</span>
      <span class="info-titik">:</span>
      <span><?= esc($nm_bank) ?></span>
    </div>
    <?php endif; ?>
    <?php if ($no_rek !== '' && $no_rek !== '-'): ?>
    <div class="info-baris">
      <span class="info-label">No. Rekening</span>
      <span class="info-titik">:</span>
      <span><strong><?= esc($no_rek) ?></strong></span>
    </div>
    <?php endif; ?>
    <?php if ($nm_akun !== '' && $nm_akun !== '-'): ?>
    <div class="info-baris">
      <span class="info-label">Atas Nama</span>
      <span class="info-titik">:</span>
      <span><?= esc($nm_akun) ?></span>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- CATATAN -->
  <?php if (!empty($header['catatan'])): ?>
  <div class="catatan-area">
    <strong>Catatan:</strong> <?= esc($header['catatan']) ?>
  </div>
  <?php endif; ?>

  <!-- TTD -->
  <div class="ttd-area">
    <div class="ttd-box">
      <div>Mengetahui,</div>
      <div class="ttd-garis">Bagian Keuangan</div>
    </div>
    <div class="ttd-box">
      <div>Hormat Kami,</div>
      <div class="ttd-garis">Kepala Gudang</div>
    </div>
  </div>

</div>

<!-- TOMBOL CETAK -->
<div class="no-print" style="text-align:center; margin-top:20px">
  <button onclick="window.print()"
    style="background:#1e4b4d; color:#fff; padding:10px 24px; font-size:14px; border:none; border-radius:4px; cursor:pointer; font-weight:bold;">
    Cetak Surat Pemesanan
  </button>
</div>

</body>
</html>
