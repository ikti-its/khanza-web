<form action="<?= site_url($url) ?>" onsubmit="return validateForm()" method="post">
    <?php
        echo csrf_field();

        // $VISIBLE = 0;
        $DISPLAY = 1;
        $KOLOM   = 2;
        $JENIS   = 3;
        $OPSI    = 4;

        $list_jenis = ['indeks', 'tanggal', 'jam', 'uang', 'status', 'nama', 'teks', 'jumlah'];
        $len = sizeof($data);
        if($len % 2 !== 0){
            array_push($data, [0, '', '', 'kosong']);
        }
        for($i = 0; $i < $len; $i++){
            $elem    = $data[$i];
            $display = $elem[$DISPLAY];
            $kolom   = $elem[$KOLOM];
            $jenis   = $elem[$JENIS];

            $is_left = $i % 2 === 0;

            if(!in_array($jenis, $list_jenis)){
                echo "Jenis tidak ditemukan pada daftar";
                break;
            }

            if($is_left){echo '<div class="mb-5 sm:block md:flex items-center">';}

            echo view('components/form_isian_label', [
                'is_left' => $is_left,
                'display' => $display
            ]);
            
            if($jenis === 'status'){
                $opsi = $elem[$OPSI];
                echo view('components/form_isian_' . $jenis, [
                    'kolom'   => $kolom,
                    'opsi'    => $opsi
                ]);
            } else {
                echo view('components/form_isian_' . $jenis, [
                    'kolom'   => $kolom
                ]);
            }

            if($i % 2 !== 0){echo '</div>';}
        }
        echo view('components/form_submit_button')
    ?>
</form>

<?php 
    echo view('components/form_isian', ['data' => $data]);
?>