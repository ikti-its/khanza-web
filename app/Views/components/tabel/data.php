<!-- Table -->
<div class="overflow-x-auto w-full"
    style="max-height: 600px; overflow-y: auto;">
    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <?php
            $VISIBLE = 0;
            $DISPLAY = 1;
            $KOLOM   = 2;
            $JENIS   = 3;

            $has_row_alert = !empty($row_alert)
                && isset($row_alert['value'], $row_alert['threshold']);

            $data_visible = array_filter($konfig, fn($input) => $input[$VISIBLE]);

            $has_child_link = isset($child_link) && $child_link !== null;

            $kolom_header = array_column($data_visible, $DISPLAY);
            if (!empty($aksi) || $has_child_link) $kolom_header[] = 'Aksi';
            echo view('components/tabel/thead', ['kolom' => $kolom_header]);

            echo '<tbody class="divide-y divide-gray-200 dark:divide-gray-700">';
                if(gettype($tabel) === 'array'){
                    foreach($tabel as $baris){
                        if (is_array($kolom_id)) {
                            $idParts = [];
                            foreach ($kolom_id as $key) {
                                $idParts[] = $baris[$key];
                            }
                            $id = implode('/', $idParts);
                        } else {
                            $id = $baris[$kolom_id];
                        }

                        $is_alert = $has_row_alert
                            && isset($baris[$row_alert['value']], $baris[$row_alert['threshold']])
                            && (float)$baris[$row_alert['value']] < (float)$baris[$row_alert['threshold']];

                        echo view('components/popup/popup', [
                            'baris'  => $baris,
                            'id'     => $id,
                            'kolom'  => array_column($konfig, $KOLOM),
                            'label'  => array_column($konfig, $DISPLAY)
                        ]);

                        $tr_style = $is_alert
                            ? 'background-color:#fff7ed; border-left:4px solid #f97316;'
                            : 'border-left:4px solid transparent;';
                        echo "<tr style=\"{$tr_style}\">";
                            echo view('components/tabel/td', [
                                'modul_path' => $modul_path,
                                'baris'    => $baris,
                                'id'       => $id,
                                'kolom'    => array_column($data_visible, $KOLOM),
                                'jenis'    => array_column($data_visible, $JENIS),
                            ]);
                            if (!empty($aksi) || $has_child_link) echo view('components/aksi/aksi', [
                                'modul_path' => $modul_path,
                                'id'         => $id,
                                'aksi'       => $aksi,
                                'baris'      => $baris,
                                'child_link' => $child_link ?? null,
                            ]);
                        echo '</tr>';
                    }
                }
            echo '</tbody>';
        ?>
    </table>
</div>

<!-- End Table -->