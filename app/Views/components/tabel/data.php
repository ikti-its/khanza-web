<!-- Table -->
<div class="overflow-x-auto w-full"
    style="max-height: 600px; overflow-y: auto;">                       
    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <?php 
            $VISIBLE = 0;
            $DISPLAY = 1;
            $KOLOM   = 2;
            $JENIS   = 3;
            // $REQUIRED= 4;
            // $OPSI    = 5;
            
            $data_visible = array_filter($konfig, fn($input) => $input[$VISIBLE]);
            
            // echo view('components/tabel/colgroup',[
            //     'kolom' => array_column($data_visible, $DISPLAY)
            // ]);
            
            echo view('components/tabel/thead',[
                'kolom' => array_merge(array_column($data_visible, $DISPLAY), ['Aksi'])
            ]);
        
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
                        echo view('components/popup/popup', [
                            'baris'  => $baris,
                            'id'     => $id,
                            'kolom'  => array_column($konfig, $KOLOM),
                            'label'  => array_column($konfig, $DISPLAY)
                        ]);
                    
                        echo '<tr>';
                            echo view('components/tabel/td', [
                                'modul_path' => $modul_path,
                                'baris'    => $baris,
                                'id'       => $id,
                                'kolom'    => array_column($data_visible, $KOLOM),
                                'jenis'    => array_column($data_visible, $JENIS), 
                            ]);
                            echo view('components/aksi/aksi', [
                                'modul_path' => $modul_path,
                                'id'         => $id,
                                'aksi'       => $aksi,
                                'baris'      => $baris
                            ]);                              
                        echo '</tr>';
                    }
                }
            echo '</tbody>';
        ?>
    </table>
</div>

<!-- End Table -->