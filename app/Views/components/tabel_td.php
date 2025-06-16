<?php 
    $list_jenis = ['indeks', 'tanggal', 'jam', 'uang', 'status', 'nama', 'teks', 'jumlah', 'suhu'];
    for($i = 0; $i < sizeof($kolom); $i++){
        if(!in_array($jenis[$i], $list_jenis)){
            echo "Jenis tidak ditemukan pada daftar";
            break;
        }
        
        $elem = $baris[$kolom[$i]];
        if(!isset($elem) || $elem === null || $elem === ''){
            echo view('components/tabel_td_kosong');
            continue;
        }
    
        echo view('components/tabel_td_' . $jenis[$i], [
            'id'   => $id,
            'data' => $elem,
        ]);
    }
?>
