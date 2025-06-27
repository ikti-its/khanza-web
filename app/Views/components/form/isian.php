<?php
    // $VISIBLE = 0;
    $DISPLAY = 1;
    $KOLOM   = 2;
    $JENIS   = 3;
    $OPSI    = 4;

    $list_jenis = ['indeks', 'tanggal', 'jam', 'uang', 'status', 'nama', 'teks', 'jumlah', 'kosong'];
    $len = sizeof($konfig);
    if($len % 2 !== 0){
        array_push($konfig, [0, '', '', 'kosong']);
    }
    for($i = 0; $i < $len; $i++){
        $elem    = $konfig[$i];
        $display = $elem[$DISPLAY];
        $kolom   = $elem[$KOLOM];
        $jenis   = $elem[$JENIS];

        $is_left = $i % 2 === 0;

        if(!in_array($jenis, $list_jenis)){
            echo "Jenis tidak ditemukan pada daftar";
            break;
        }

        if($is_left){echo '<div class="mb-5 sm:block md:flex items-center">';}

        echo view('components/form/label', [
            'is_left' => $is_left,
            'display' => $display
        ]);
        if(!isset($elem[$OPSI])){
            $elem[$OPSI] = null;
        }

        $opsi = $elem[$OPSI];
        echo view('components/form/isian/' . $jenis, [
            'id'    => '',
            'kolom' => $kolom,
            'value' => '',
            'opsi'  => $opsi,
        ]);

        if($i % 2 !== 0){echo '</div>';}
    }
?>
