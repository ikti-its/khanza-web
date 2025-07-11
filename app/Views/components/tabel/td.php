<?php 
    $list_jenis = ['indeks', 'tanggal', 'jam', 'uang', 'status', 'nama', 'teks', 'jumlah', 'suhu', 'bool'];
    for($i = 0; $i < sizeof($kolom); $i++){
        if(!in_array($jenis[$i], $list_jenis)){
            echo 'Jenis tidak ditemukan pada daftar';
            break;
        }
        if(!array_key_exists($kolom[$i], $baris)){
            echo 'Tidak ada kolom: ' . $kolom[$i] . ' pada baris: ' . json_encode($baris);
            break;
        }
        $elem = $baris[$kolom[$i]];
        $data = [
            'id'   => $id,
            'elem' => $elem,
        ];
        if(!isset($elem) || $elem === null || $elem === ''){
            echo view('components/tabel/td/kosong');
            continue;
        }
        if($modul_path === '/resepobat' && $kolom[$i] !== 'validasi'){
            echo view('components/tabel/td/indeks_resep_obat', $data);
            continue;
        } 

        echo view('components/tabel/td/' . $jenis[$i], $data);
    }
?>
