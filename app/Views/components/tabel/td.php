<?php
/** @var array<int, string>    $kolom      Nama-nama kolom yang akan dirender */
/** @var array<int, string>    $jenis      Tipe setiap kolom (indeks, teks, uang, ...) */
/** @var array<string, mixed>  $baris      Satu baris data dari findAll() */
/** @var int|string            $id         Nilai primary key baris ini */
/** @var string                $modul_path Path URI modul saat ini (e.g. '/resepobat') */

    $list_jenis = ['indeks', 'tanggal', 'jam', 'uang', 'status', 'nama', 'teks', 'jumlah', 'suhu', 'bool', 'desimal', 'tanggal_jam', 'kosong', 'readonly'];
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
        if($modul_path === '/uji-darah/hasil-uji-saring' && in_array($kolom[$i], ['hbsag', 'hcv', 'hiv', 'sifilis', 'malaria'])) {
            $data['is_lab'] = true;
            echo view('components/tabel/td/bool', $data);
            continue;
        }

        $jenis_render = in_array($jenis[$i], ['kosong', 'readonly']) ? 'teks' : $jenis[$i];
        echo view('components/tabel/td/' . $jenis_render, $data);
    }
?>
