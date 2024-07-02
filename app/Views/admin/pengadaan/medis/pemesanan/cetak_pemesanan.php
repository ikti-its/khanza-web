<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - <?= $pemesanan_medis_data['no_pemesanan'] ?></title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px solid #000;
        }
    </style>
</head>

<body>

    <table class="custom-table">
        <tr>
            <td valign="top"><img src="resources/150x150.png" /></td>
            <td align="right">
                <h3>Rumah Sakit</h3>
                <pre style="margin: 0; white-space: pre-wrap;">
                alamat
                no_telp
                email</pre>
            </td>
        </tr>
    </table>

    <table style="margin-top:5px; width: 100%; ">
        <tr>
            <td>
                <h3 style="margin-top: 0; text-align: center;">SURAT PEMESANAN BARANG</h3>
            </td>
        </tr>
        <tr>
            <td><strong>Kepada:</strong> <?php foreach ($supplier_data as $supplier) {
                                                if ($pemesanan_medis_data['id_supplier'] === $supplier['id']) {
                                                    echo $supplier['nama'];
                                                }
                                            } ?></td>
        </tr>
        <tr>
            <td><strong>No. Pesanan:</strong> <?= $pemesanan_medis_data['no_pemesanan'] ?></td>
        </tr>
        <tr>
            <td><strong>Harap dikirim barang-barang di bawah ini:</strong></td>
        </tr>
    </table>

    <table class="custom-table" width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            foreach ($pesanan_data as $pesanan) {
                foreach ($medis_data as $medis) {
                    foreach ($satuan_data as $satuan) {
                        if ($pesanan['id_barang_medis'] === $medis['id'] && $pesanan['satuan'] === $satuan['id']) {
            ?>
                            <tr>

                                <th scope="row"><?php echo $counter; ?></th>
                                <td><?php echo $medis['nama']; ?></td>
                                <td align="right"><?php echo $pesanan['jumlah_pesanan'] . " " . $satuan['nama'];  ?></td>
                                <td align="right"><?php echo number_format($pesanan['harga_satuan_pemesanan'], 2); ?></td>
                                <td align="right"><?php echo number_format($pesanan['total_per_item'], 2); ?></td>

                            </tr>
            <?php
                            $counter++;
                        }
                    }
                }
            }
            ?>
            <!-- <tr>
                <th scope="row">1</th>
                <td>Playstation IV - Black</td>
                <td align="right">1</td>
                <td align="right">1400.00</td>
                <td align="right">1400.00</td>
            </tr>
            <tr>
                <th scope="row">1</th>
                <td>Metal Gear Solid - Phantom</td>
                <td align="right">1</td>
                <td align="right">105.00</td>
                <td align="right">105.00</td>
            </tr>
            <tr>
                <th scope="row">1</th>
                <td>Final Fantasy XV - Game</td>
                <td align="right">1</td>
                <td align="right">130.00</td>
                <td align="right">130.00</td>
            </tr> -->
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td align="right">Subtotal</td>
                <td align="right"></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Diskon</td>
                <td align="right"><?= $pemesanan_medis_data['diskon_jumlah'] ?></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Pajak</td>
                <td align="right"><?= $pemesanan_medis_data['pajak_jumlah'] ?></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Total</td>
                <td align="right" class="gray"><?= $pemesanan_medis_data['total_pemesanan'] ?></td>
            </tr>

        </tfoot>
    </table>

    <table>
        <tr>
            <td>
                <strong>Catatan:</strong>
                <pre style="font-size: 11px; margin: 0; white-space: pre-wrap;">1. Barang yang dikirim jika tidak sesuai pesanan akan dikembalikan
2. Jika terjadi retur barang, Nota retur Faktur Pajak dibuat supplier sesuai dengan bulan penerbitan faktur pajak awal (saat menerima PO/pesanan barang)
3. Pada saat menukarkan faktur, harap melampirkan surat pemesanan barang / PO Lembar penerimaan barang / LPB yang asli, dan lembar faktur pajak yang asli
4. Nomor PO harap dicantumkan pada setiap lembar penerimaan barang
5. Tukar faktur di Tim pembelian setiap hari kamis jam 08:00 - 12:00
6. Pembayaran tagihan di Bag. Keuangan setiap hari Rabu (09:00 - 11:00)
7. Kecuali dinyatakan lain, PO berlaku paling lama 7 (tujuh) hari dari tanggal PO (untuk nomor disesuaikan dengan perjanjian supplier)</pre>
            </td>
        </tr>

    </table>

    <table style="margin-top:5px; width: 100%; ">
        <tr>
            <td><strong>Tanggal Cetak:</strong> <?php echo date("d-m-Y"); ?></td>
            <td align="right"><strong>Surabaya, <?php
                                                $tanggal_pesan = $pemesanan_medis_data['tanggal_pesan']; // Ambil tanggal dari data

                                                // Ubah format tanggal dari Y-m-d ke d-m-Y
                                                $tanggal_pesan_format = date('d-m-Y', strtotime($tanggal_pesan));

                                                echo $tanggal_pesan_format; // Output: 19-06-2024 (contoh tanggal)
                                                ?></strong></td>
        </tr>
    </table>
    <table style="text-align:center; margin-top:5px; width: 100%; ">
        <tr>
            <td style="width: 25%; "><strong>Mengetahui</td>
            <td style="width: 25%; "><strong>Supplier</td>
            <td style="width: 25%; "><strong>Tim Pembelian</td>
            <td style="width: 25%; " align="right"><strong>Kepala Bagian Keuangan</td>
        </tr>
        <br>
        <br>
        <br>
        <br>
        <br>
        <tr>
            <?php foreach ($persetujuan_data as $persetujuan) {

                if ($pemesanan_medis_data['id_pengajuan'] === $persetujuan['id_pengajuan']) { ?>
                    <td style="width: 25%; "><strong><?php foreach ($pegawai_data as $pegawai) {
                                                            if ($persetujuan['id_apoteker'] === $pegawai['id']) {
                                                                echo $pegawai['nama'];
                                                            }
                                                        } ?></td>
                    <td style="width: 25%; "><strong>nama</td>
                    <td style="width: 25%; "><strong><?php foreach ($pegawai_data as $pegawai) {
                                                            if ($pemesanan_medis_data['id_pegawai'] === $pegawai['id']) {
                                                                echo $pegawai['nama'];
                                                            }
                                                        } ?></td>
                    <td style="width: 25%; "><strong><?php foreach ($pegawai_data as $pegawai) {
                                                            if ($persetujuan['id_keuangan'] === $pegawai['id']) {
                                                                echo $pegawai['nama'];
                                                            }
                                                        } ?></td>
            <?php }
            } ?>
        </tr>
    </table>
</body>

</html>