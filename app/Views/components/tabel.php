<!-- Table -->
<div style="overflow-x: auto; width: 100%;">
    <table id="myTable" style="min-width: 1500px; white-space: nowrap;" class="text-sm divide-y divide-gray-200 dark:divide-gray-700">

        <?php
        $VISIBLE = 0;
        $DISPLAY = 1;
        $KOLOM   = 2;
        $JENIS   = 3;
        // $OPSI    = 4;

        $data_visible = array_filter($konfig, fn($input) => $input[$VISIBLE]);

        // echo view('components/tabel_colgroup',[
        //     'kolom' => array_column($data_visible, $DISPLAY)
        // ]);

        echo view('components/tabel_thead', [
            'kolom' => array_column($data_visible, $DISPLAY)
        ]);


        echo '<tbody class="divide-y divide-gray-200 dark:divide-gray-700">';
        if (gettype($tabel) === 'array') {
            foreach ($tabel as $baris) {
                $id = $baris[$kolom_id];
                echo view('components/popup', [
                    'baris'  => $baris,
                    'id'     => $id,
                    'kolom'  => array_column($konfig, $KOLOM),
                    'label'  => array_column($konfig, $DISPLAY)
                ]);

                echo '<tr>';
                echo view('components/tabel_td', [
                    'baris'    => $baris,
                    'id'       => $id,
                    'kolom'    => array_column($data_visible, $KOLOM),
                    'jenis'    => array_column($data_visible, $JENIS),
                ]);
                echo view('components/aksi', [
                    'modul_path' => $modul_path,
                    'id'         => $id,
                    'aksi'       => $aksi
                ]);
                echo '</tr>';
            }
        }
        echo '</tbody>';
        ?>
    </table>
</div>

<!-- End Table -->