
<div class="space-y-4">
    <div>
        <?php 
            if(count($kolom) !== count($label)){
                echo 'Jumlah kolom dan label tidak sama';
                return;
            }
            for($i = 0; $i < sizeof($kolom); $i++){
                if(!array_key_exists($kolom[$i], $baris)){
                    echo 'Tidak ada kolom: ' . $kolom[$i] . ' pada baris: ' . json_encode($baris);
                    return;
                }
                echo view('components/popup/baris', [
                    'baris' => $baris,
                    'kolom' => $kolom[$i],
                    'label' => $label[$i]
                ]);
            }
        ?>
    </div>
</div>
                    