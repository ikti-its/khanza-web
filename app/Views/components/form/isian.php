<?php
    // $VISIBLE = 0;
    $DISPLAY = 1;
    $KOLOM   = 2;
    $JENIS   = 3;
    $REQUIRED= 4;
    $OPSI    = 5;

    $list_jenis = ['indeks', 'tanggal', 'jam', 'uang', 'status', 'nama', 'teks', 'jumlah', 'kosong'];
    $len = sizeof($konfig);
    if($len % 2 !== 0){
        array_push($konfig, [0, '', '', 'kosong', 0]);
        $len++;
    }
    for($i = 0; $i < $len; $i++){
        $elem = $konfig[$i];

        if(sizeof($elem) < 5){
            echo "Data pada konfig kurang lengkap";
            return;
        }
        $display = $elem[$DISPLAY];
        $kolom = $elem[$KOLOM];
        if($baris !== '' && $kolom !=='' &&!isset($baris[$kolom])){
            echo "Tidak ditemukan kolom: " . $kolom . " pada baris";
            return;
        }
        $jenis    = $elem[$JENIS];
        if(!in_array($jenis, $list_jenis)){
            echo "Jenis: " . $jenis . " tidak ditemukan pada daftar";
            break;
        }
        $required = $elem[$REQUIRED];
        if(!in_array($required, [0, 1])){
            echo "Konfig required tidak dikenali: " . $display;
            return;
        }

        $is_left = $i % 2 === 0;
        if($is_left){echo '<div class="mb-5 sm:block md:flex items-center">';}

        echo view('components/form/label', [
            'is_left'  => $is_left,
            'display'  => $display,
            'required' => $required,
        ]);
        if(!isset($elem[$OPSI])){
            $elem[$OPSI] = null;
        }

        $opsi = $elem[$OPSI];
        // print_r($baris);
        echo view('components/form/isian/' . $jenis, [
            'id'    => '',
            'kolom' => $kolom,
            'value' => $baris[$kolom] ?? '',
            'req'   => $required,
            'opsi'  => $opsi,
        ]);

        if($i % 2 !== 0){echo '</div>';}
    }
?>
