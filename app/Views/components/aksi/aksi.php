<td class="size-px whitespace-nowrap">
    <div class="px-3 py-1.5 text-center inline-flex">
        <?php
        $data = [
            'id'         => $id,
            'modul_path' => $modul_path,
            'baris'      => $baris
        ];
        $aksi_list = ['notif', 'tambah', 'audit', 'cetak', 'tindakan', 'detail', 'detail2', 'ubah', 'hapus', 'validasi', 'ambulans', 'pilih'];
        if (ENVIRONMENT === 'development') {
            foreach ($aksi as $key => $value) {
                if (!in_array($key, $aksi_list)) {
                    echo 'Aksi ' . $key . ' tidak ditemukan dalam daftar aksi';
                    return;
                }
                if (!is_bool($value)) {
                    echo 'Value pada daftar aksi harus berupa boolean (true/false)';
                    return;
                }
            }
        }
        if (isset($aksi['tindakan']) &&  $aksi['tindakan'] === true) {
            switch ($modul_path) {
                case '/registrasi':
                    echo view('components/aksi/tindakan_registrasi', $data);
                    break;
                case '/rawatinap':
                    echo view('components/aksi/tindakan_rawatinap', $data);
                    break;
                default:
                    echo 'Aksi Tindakan belum dibuat pada modul ' . $modul_path;
                    break;
            }
        }
        if (isset($aksi['validasi']) && $aksi['validasi'] === true) {
            switch ($modul_path) {
                case '/permintaanreseppulang':
                    echo view('components/aksi/validasi_buatreseppulang', $data);
                    break;
                case '/resepobat':
                    echo view('components/aksi/validasi_resepobat', $data);
                    break;
                default:
                    echo 'Aksi Validasi belum dibuat pada modul ' . $modul_path;
                    break;
            }
        }
        if (isset($aksi['ambulans']) && $aksi['ambulans'] === true) {
            switch ($modul_path) {
                case '/rujukankeluar':
                    echo view('components/aksi/ambulans_rujukankeluar', $data);
                    break;
                default:
                    echo 'Aksi Panggil Ambulans belum dibuat pada modul ' . $modul_path;
                    break;
            }
        }
        if (isset($aksi['cetak'])  && $aksi['cetak']  === true) {
            echo view('components/aksi/cetak',  $data);
        }
        if (isset($aksi['detail']) && $aksi['detail'] === true) {
            echo view('components/aksi/detail', $data);
        }
        if (isset($aksi['pilih'])  && $aksi['pilih']  === true) {
            echo view('components/aksi/pilih',  $data);
        }
        if (isset($aksi['ubah'])   && $aksi['ubah']   === true) {
            echo view('components/aksi/ubah',   $data);
        }
        if (isset($aksi['hapus'])  && $aksi['hapus']  === true) {
            echo view('components/aksi/hapus',  $data);
        }
        if (isset($aksi['detail2']) && $aksi['detail2'] === true) {
            echo view('components/aksi/detail2', $data);
        }
        ?>
    </div>
</td>