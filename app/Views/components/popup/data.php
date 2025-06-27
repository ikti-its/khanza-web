
<div class="space-y-4">
    <div>
        <?php 
            if(sizeof($kolom) !== sizeof($label)){
                echo 'Jumlah kolom dan label tidak sama';
                return false;
            }
            foreach($kolom as $k){
                if(!isset($baris[$k])){
                    echo 'Tidak ada kolom ' . $k . ' pada baris ' . $baris;
                    return false;
                }
            }
            for($i = 0; $i < sizeof($kolom); $i++){
                echo view('components/popup/baris', [
                    'baris' => $baris,
                    'kolom' => $kolom[$i],
                    'label' => $label[$i]
                ]);
            }
        ?>
    </div>
</div>
                    