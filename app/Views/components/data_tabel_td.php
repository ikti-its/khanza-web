<?php 
    $list_jenis = ['indeks', 'tanggal', 'jam', 'uang', 'status', 'nama', 'teks', 'jumlah', 'suhu'];
    foreach($data as $teks => $jenis){
        if(!in_array($jenis, $list_jenis)){
            echo "Jenis tidak ditemukan pada daftar";
            break;
        }

        if(!isset($tabel[$teks]) || $tabel[$teks] === null || $tabel[$teks] === ''){
            echo view('components/data_tabel_td_kosong');
            continue;
        }
        $view_data = [
            'tabel'  => $tabel,
            'row_id' => $row_id,
            'teks'   => $teks
        ];
        
        echo view('components/data_tabel_td_' . $jenis, $view_data);
    }
?>
