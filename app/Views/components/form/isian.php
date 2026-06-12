<?php
    /**
     * @var list<array{
     *      0:0|1,
     *      1:string,
     *      2:string,
     *      3:string,
     *      4:0|1,
     *      5?:list<string>}> $konfig
     * @var array<string, string|int|float> $row
     */

    $row = (isset($baris) && is_array($baris)) ? $baris : [];

    $allowed_type = ['indeks', 'tanggal', 'jam', 'uang', 'status', 'nama', 'teks', 'jumlah', 'suhu', 'kosong', 'desimal', 'tanggal_jam', 'bool', 'readonly', 'modal'];
    $len = sizeof($konfig);
    if ($len % 2 !== 0) {
        array_push($konfig, [0, '', '', 'kosong', 0, []]);
        $len++;
    }
    for ($i = 0; $i < $len; $i++) {
        $elem = $konfig[$i];
        [$_visible, $display, $column, $type, $required] = $elem;
        $option = [];
        if(isset($elem[5])) $option = $elem[5];

        if (sizeof($elem) < 5) {
            echo "Data pada konfig kurang lengkap";
            return;
        }
        
        if ($row !== [] && $column !== '' && !array_key_exists($column, $row)) {
            if ($_visible !== FORM_ONLY) {
                echo "Tidak ditemukan kolom: " . $column . " pada baris";
                return;
            }
        }

        if (!in_array($type, $allowed_type)) {
            echo "Jenis: " . $type . " tidak ditemukan pada daftar";
            break;
        }

        if (!in_array($required, [0, 1])) {
            echo "Konfig required tidak dikenali: " . $display;
            return;
        }

        $is_left = $i % 2 === 0;
        if ($is_left) {
            echo '<div class="mb-5 sm:block md:flex items-center">';
        }

        echo view('components/form/label', [
            'is_left'  => $is_left,
            'display'  => $display,
            'required' => $required,
        ]);

        echo view('components/form/isian/' . $type, [
            'id'     => $column,
            'column' => $column,
            'value'  => $row[$column] ?? '',
            'req'    => $required,
            'opsi'   => $option,
            'row'    => $row,
        ]);

        if ($i % 2 !== 0) {
            echo '</div>';
        }
    }
