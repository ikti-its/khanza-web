<td class="size-px whitespace-nowrap">
    <div class="px-3 py-1.5 text-center inline-flex">
        <?php
            $data = [
                'id'         => $id,
                'modul_path' => $modul_path,
                'baris'      => $baris   
            ];
            $aksi_list = ['cetak', 'tindakan', 'detail', 'ubah', 'hapus', 'validasi'];
            if(ENVIRONMENT === 'development'){
                foreach($aksi as $key => $value){
                    if(!in_array($key, $aksi_list)){
                        echo 'Aksi ' . $key . ' tidak ditemukan dalam daftar aksi';
                        return;
                     }
                    if(!is_bool($value)){
                        echo 'Value pada daftar aksi harus berupa boolean (true/false)';
                        return;
                    }   
                }
            }
            if($aksi['tindakan'] === true){
                switch($modul_path){
                    case '/registrasi':
                        echo view('components/aksi/tindakan_registrasi', $data);
                        break;
                    case '/rawatinap':
                        echo view('components/aksi/tindakan_rawatinap', $data);
                        break;
                    default:
                        break;
                }
            }
            if(isset($aksi['validasi']) && $aksi['validasi'] === true){
                switch($modul_path){
                    case '/permintaanreseppulang':
                        echo view('components/aksi/validasi_buatreseppulang', $data);
                        break;
                    default:
                        break;
                }

            }
            if($aksi['cetak']  === true){echo view('components/aksi/cetak',  $data);}
            if($aksi['detail'] === true){echo view('components/aksi/detail', $data);}
            if($aksi['ubah']   === true){echo view('components/aksi/ubah',   $data);}
            if($aksi['hapus']  === true){echo view('components/aksi/hapus',  $data);}
        ?>
    </div>
</td>