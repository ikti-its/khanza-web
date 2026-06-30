<td class="size-px whitespace-nowrap">
    <div class="px-3 py-1.5 text-center inline-flex">
        <?php
        $data = [
            'id'         => $id,
            'modul_path' => $modul_path,
            'baris'      => $baris
        ];
        if (isset($child_link) && $child_link !== null) {
            $child_url   = $child_link['path'] . '/data?' . $child_link['fk'] . '=' . urlencode((string) $id);
            $child_label = $child_link['label'] ?? 'Detail';
            echo '<div class="px-3 py-1.5"><a href="' . $child_url . '" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold dark:text-blue-400">' . esc($child_label) . '</a></div>';
        }
        $aksi_list = ['notif', 'tambah', 'audit', 'cetak', 'tindakan', 'detail', 'detail2', 'ubah', 'hapus', 'validasi', 'ambulans', 'pilih', 'pisah', 'uji', 'registrasi', 'sampel', 'jadwalkan', 'lembaroperasi', 'kembali', 'bayar', 'filter'];
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
        if ((isset($aksi['pisah']) && $aksi['pisah'] === true) || (isset($aksi['uji']) && $aksi['uji'] === true)) {
            echo view('components/aksi/proses_darah', array_merge($data, ['aksi' => $aksi]));
        }
        if (isset($aksi['cetak'])  && $aksi['cetak']  === true) {
            echo view('components/aksi/cetak',  $data);
        }
        if (isset($aksi['bayar']) && $aksi['bayar'] === true) {
            echo view('components/aksi/bayar', $data);
        }
        if (isset($aksi['detail']) && $aksi['detail'] === true) {
            echo view('components/aksi/detail', $data);
        }
        if (isset($aksi['detail2']) && $aksi['detail2'] === true) {
            echo view('components/aksi/detail2', $data);
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
        if (isset($aksi['registrasi']) && $aksi['registrasi'] === true) {
            echo view('components/aksi/registrasi', $data);
        }
        if (isset($aksi['sampel']) && $aksi['sampel'] === true) {
            echo view('components/aksi/sampel', $data);
        }
        if (isset($aksi['jadwalkan']) && $aksi['jadwalkan'] === true) {
            echo view('components/aksi/jadwalkan', $data);
        }
        if (isset($aksi['lembaroperasi']) && $aksi['lembaroperasi'] === true) {
            echo view('components/aksi/lembaroperasi', $data);
        }
        ?>
    </div>
</td>