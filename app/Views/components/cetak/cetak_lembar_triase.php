<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= esc($judul) ?></title>
  <style>
    <?php
      $warnaKeputusanTeks = "#ffffff";

      $warnaKeputusanBg = match ((int) $skala_final) {
          1       => "#dc2626", // Immediate/Segera (red-600)
          2       => "#ff9100", // Emergensi (orange)
          3       => "#d49100", // Urgensi (amber)
          4       => "#10b981", // Semi Urgensi (emerald)
          default => "#64748b", // Non Urgensi (slate)
      };
    ?>

    body {
      font-family: Arial, Helvetica, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 850px;
      background-color: #ffffff;
      border: 1px solid #000000;
      padding: 20px;
      margin: 0 auto;
      box-sizing: border-box;
    }

    /* =========================================================================
       1. HEADER / KOP REKAM MEDIS GABUNGAN
       ========================================================================= */
    .kop-table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #000000;
    }

    .kop-table td {
      padding: 6px;
      vertical-align: middle;
      border: 1px solid #000000;
    }

    .logo-cell {
      width: 12%;
      text-align: center;
    }

    .logo-placeholder {
      width: 65px;
      height: 65px;
      border: 2px solid #555;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    .rs-info-cell {
      width: 53%;
      text-align: center;
      line-height: 1.3;
    }

    .rs-info-cell h2 {
      margin: 0 0 3px 0;
      font-size: 18px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .rs-info-cell p {
      margin: 0;
      font-size: 11px;
      color: #333;
    }

    .pasien-meta-cell {
      width: 35%;
      font-size: 12px;
      padding: 0 !important;
    }

    .pasien-meta-table {
      width: 100%;
      border-collapse: collapse;
    }

    .pasien-meta-table td {
      border: none !important;
      padding: 3px 6px !important;
    }

    .pasien-meta-table td.label {
      width: 100px;
    }

    /* =========================================================================
       2. BANNER JUDUL TRIASE
       ========================================================================= */
    .banner-title {
      background-color: <?= $warnaKeputusanBg ?>;
      color: <?= $warnaKeputusanTeks ?>;
      text-align: center;
      font-weight: bold;
      font-size: 14px;
      padding: 6px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-top: -1px;
      border: 1px solid #000000;
    }

    .banner-sub {
      text-align: center;
      font-size: 12px;
      font-style: italic;
      padding: 5px;
      border-left: 1px solid #000000;
      border-right: 1px solid #000000;
      border-bottom: 1px solid #000000;
    }

    /* =========================================================================
       3. STRUKTUR TABEL TRANSAKSI KLINIS
       ========================================================================= */
    .form-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .form-table td, .form-table th {
      border: 1px solid #000000;
      padding: 6px 8px;
      font-size: 12px;
      vertical-align: top;
    }

    .bg-header-gray {
      background-color: #f2ebd9;
      font-weight: bold;
      text-align: center;
      text-transform: uppercase;
      width: 50%;
    }

    .w-label-left {
      width: 25%;
      font-weight: bold;
    }

    /* =========================================================================
       4. KESIMPULAN HASIL KEPUTUSAN TRIASE (DINAMIS SINKRON WARNA)
       ========================================================================= */
    .result-banner {
      background-color: <?= $warnaKeputusanBg ?>;
      color: <?= $warnaKeputusanTeks ?>;
      font-weight: bold;
      text-align: center;
      text-transform: uppercase;
      font-size: 13px;
    }

    /* =========================================================================
       5. AREA VALIDASI TANDA TANGAN & QR CODE
       ========================================================================= */
    .footer-flex {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-top: 15px;
      padding: 0 10px;
    }

    .ttd-box {
      text-align: center;
      font-size: 12px;
      line-height: 1.4;
      width: 220px;
    }

    .ttd-space {
      height: 55px;
    }

    /* =========================================================================
       6. PRINT MEDIA SENSOR CONTROLLER
       ========================================================================= */
    @media print {
      body {
        background-color: #ffffff;
        padding: 0;
        margin: 0;
      }
      .container {
        border: none;
        padding: 0;
        width: 100%;
      }
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    
    <table class="kop-table">
      <tr>
        <td class="logo-cell">
          <div class="logo-placeholder">➕</div>
        </td>
        <td class="rs-info-cell">
          <h2>RS Bhayangkara Surabaya</h2>
          <p>Jl. Arif Rahman Hakim No. 213, Keputih, Sukolilo, Surabaya, Jawa Timur</p>
          <p>E-mail: utd.rsbhayangkara@gmail.com | Telp: (031) 123456789</p>
        </td>
        <td class="pasien-meta-cell">
          <table class="pasien-meta-table">
            <tr>
              <td class="label">Nomor RM</td>
              <td>: <strong><?= esc($baris['nomor_rm'] ?? '-') ?></strong></td>
            </tr>
            <tr>
              <td class="label">Nama Pasien</td>
              <td>: <?= esc($baris['nama_pasien'] ?? '-') ?></td>
            </tr>
            <tr>
              <td class="label">Tanggal Lahir</td>
              <td>: <?= !empty($baris['tanggal_lahir']) ? date('d-m-Y', strtotime($baris['tanggal_lahir'])) : '-' ?></td>
            </tr>
            <tr>
              <td class="label">Jenis Kelamin</td>
              <td>: <?= esc($baris['id_jenis_kelamin'] ?? '-') ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <div class="banner-title">
        Triase Pasien Gawat Darurat
    </div>
    <div class="banner-sub">
        Triase dilakukan segera setelah pasien datang dan sebelum pasien/keluarga mendaftar di TPP IGD
    </div>

    <table class="form-table">
      <tr>
        <td style="width: 50%; font-weight: bold;">
          Tanggal Kunjungan: <?= !empty($baris['tanggal_kunjungan']) ? date('d-m-Y', strtotime($baris['tanggal_kunjungan'])) : '-' ?>
        </td>
        <td style="width: 50%; font-weight: bold;">
          Pukul: <?= !empty($baris['tanggal_kunjungan']) ? date('H:i:s', strtotime($baris['tanggal_kunjungan'])) : '--:--:--' ?>
        </td>
      </tr>
      <tr>
        <td><strong>Cara Datang:</strong> <?= esc($baris['id_cara_masuk'] ?? '-') ?></td>
        <td><strong>Alat Transportasi / Alasan:</strong> <?= esc($baris['id_alat_transportasi'] ?? '-') ?> / <?= esc($baris['id_alasan_kedatangan'] ?? '-') ?></td>
      </tr>
      <tr>
        <td colspan="2"><strong>Macam Kasus:</strong> <?= esc($baris['nama_macam_kasus'] ?? '-') ?></td>
      </tr>
      
      <tr>
        <td class="bg-header-gray">Keterangan Administrasi & Fisik</td>
        <td class="bg-header-gray">Triase <?= ucfirst($tab_aktif) ?> Spesifik</td>
      </tr>

      <tr>
        <td>
          <strong>Keterangan Kedatangan:</strong><br>
          <?= esc($baris['keterangan_kedatangan'] ?? '-') ?>
        </td>
        <td>
          <?php if ($tab_aktif === 'primer'): ?>
            <strong>KELUHAN UTAMA:</strong><br>
            <?= esc($baris['keluhan_utama'] ?? '-') ?>
          <?php else: ?>
            <strong>ANAMNESA SINGKAT:</strong><br>
            <?= esc($baris['anamnesa_singkat'] ?? '-') ?>
          <?php endif; ?>
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <strong>TANDA VITAL (VITAL SIGNS):</strong><br>
          Suhu (&deg;C): <strong><?= esc($baris['suhu'] ?? '-') ?></strong> | 
          Nyeri (0-10): <strong><?= esc($baris['nyeri'] ?? '-') ?></strong> | 
          Tensi (mmHg): <strong><?= esc($baris['sistolik'] ?? '-') ?> / <?= esc($baris['diastolik'] ?? '-') ?></strong> | 
          Nadi (/menit): <strong><?= esc($baris['nadi'] ?? '-') ?></strong> | 
          Saturasi O2 (%): <strong><?= esc($baris['saturasi_o2'] ?? '-') ?></strong> | 
          Respirasi (/menit): <strong><?= esc($baris['pernapasan'] ?? '-') ?></strong>
        </td>
      </tr>

      <?php if ($tab_aktif === 'primer'): ?>
        <tr>
          <td colspan="2">
            <strong>KEBUTUHAN KHUSUS PASIEN:</strong> <?= esc($baris['id_kebutuhan_khusus'] ?? '-') ?>
          </td>
        </tr>
      <?php endif; ?>

      <tr>
        <td colspan="2" style="padding: 0;">
          <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr style="background-color: #fcfcfc;">
              <td style="width: 40%; font-weight: bold; border-top: none; border-left: none; border-bottom: none;">Kategori Pemeriksaan</td>
              <td style="width: 60%; font-weight: bold; border-top: none; border-right: none; border-bottom: none;">Parameter Klinis Terpilih</td>
            </tr>
            <?php if (!empty($list_kriteria)): ?>
              <?php foreach ($list_kriteria as $kriteria): ?>
                <tr>
                  <td style="border-left: none; border-bottom: none; text-transform: uppercase; font-size: 11px; color:#555;">
                    <?= esc($kriteria['nama_pemeriksaan']) ?>
                  </td>
                  <td style="border-right: none; border-bottom: none; font-weight: 500;">
                    ☑️ <?= esc($kriteria['pengkajian']) ?> 
                    <span style="font-size: 10px; color: #777;">(Skala <?= esc($kriteria['id_tingkat_skala']) ?>)</span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="2" style="text-align: center; font-style: italic; color: #aaa; border-left:none; border-right:none; border-bottom:none;">
                  Tidak ada indikator penilaian skala klinis yang dipilih.
                </td>
              </tr>
            <?php endif; ?>
          </table>
        </td>
      </tr>

      <tr>
        <td class="w-label-left">ASSESSMENT TRIASE FINAL</td>
        <td class="result-banner">
            SKALA KEDARURATAN <?= esc($skala_final ?? '-') ?> 
            <?php 
              if ($skala_final == 1) echo "(IMMEDIATE / SEGERA)";
              elseif ($skala_final == 2) echo "(EMERGENSI)";
              elseif ($skala_final == 3) echo "(URGENSI)";
              elseif ($skala_final == 4) echo "(SEMI URGENSI)";
              elseif ($skala_final == 5) echo "(NON URGENSI)";
            ?>
        </td>
      </tr>
      <tr>
        <td class="w-label-left">PLAN / KEPUTUSAN</td>
        <td>
            <?= esc(($baris['id_plan_primer'] !== '') ? $baris['id_plan_primer'] : ($baris['id_plan_sekunder'] ?? '-')) ?>
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <strong>CATATAN PETUGAS TRIASE:</strong><br>
          <?= esc($baris['catatan'] ?? '-') ?>
        </td>
      </tr>
    </table>

    <div class="footer-flex">
      <div></div>
      
      <div class="ttd-box">
        Tanggal & Jam Evaluasi: <?= !empty($baris['tanggal_triase']) ? date('d-m-Y H:i', strtotime($baris['tanggal_triase'])) : '-' ?><br>
        <strong>Petugas Triase <?= ucfirst($tab_aktif) ?></strong>
        <div class="ttd-space"></div>
        <u><strong><?= esc($baris['nama_petugas'] ?? 'Dokter Jaga IGD') ?></strong></u><br>
        <span>SIP/NIP Petugas</span>
      </div>
    </div>

  </div>

  <div class="no-print" style="text-align: center; margin-top: 25px;">
    <button onclick="window.history.back()" 
      style="background-color: #64748b; color: white; padding: 10px 22px; font-size: 13px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; margin-right: 10px;">
      ⬅️ Kembali
    </button>
    <button onclick="window.print()" 
      style="background-color: #008060; color: white; padding: 10px 25px; font-size: 13px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
      🖨 Cetak Lembar Triase
    </button>
  </div>

</body>
</html>