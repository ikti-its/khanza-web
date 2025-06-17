<td class="size-px whitespace-nowrap">
    <div class="px-3 py-1.5 text-center inline-flex">
        <?php
            $data = [
                'id'      => $id,
                'api_url' => $api_url   
            ];
            if($aksi['cetak'] === true){
                echo view('components/aksi_cetak', $data);
            }
            if($aksi['tindakan'] === true){
                echo view('components/aksi_tindakan', $data);
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