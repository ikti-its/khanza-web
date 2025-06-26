<td class="size-px whitespace-nowrap">
    <div class="px-3 py-1.5 text-center inline-flex">
        <?php
            $data = [
                'id'         => $id,
                'modul_path' => $modul_path,
                'baris'      => $baris   
            ];
            if($aksi['cetak'] === true){
                echo view('components/aksi_cetak', $data);
            }
            if($aksi['tindakan'] === true){
                switch($modul_path){
                    case '/registrasi':
                        echo view('components/aksi_tindakan_registrasi', $data);
                        break;
                    case '/rawatinap':
                        echo view('components/aksi_tindakan_rawatinap', $data);
                        break;
                    default:
                        break;
                }
            }
            if($aksi['detail'] === true){
                echo view('components/aksi_detail', $data);
            }
            if($aksi['ubah'] === true){
                echo view('components/aksi_ubah', $data);
            }
            if($aksi['hapus'] === true){
                echo view('components/aksi_hapus', $data);
            }
        ?>
    </div>
</td>