<!-- Table -->
<div class="overflow-x-auto w-full">                       
    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <?php 
            $VISIBLE = 0;
            $DISPLAY = 1;
            $KOLOM   = 2;
            $JENIS   = 3;
            

            $data_visible = array_filter($data, fn($input) => $input[$VISIBLE]);
            
            echo view('components/tabel_colgroup',[
                'kolom' => array_column($data_visible, $DISPLAY)
            ]);
            
            echo view('components/tabel_thead',[
                'kolom' => array_column($data_visible, $DISPLAY)
            ]);
        

        echo '<tbody class="divide-y divide-gray-200 dark:divide-gray-700">';
            foreach($tabel as $baris){
                $id = $baris[$kolom_id];
                echo view('components/popup', [
                    'baris'  => $baris,
                    'id'     => $id,
                    'kolom'  => array_column($data, $KOLOM),
                    'label'  => array_column($data, $DISPLAY)
                ]);
            
                echo '<tr>';
                    echo view('components/tabel_td', [
                        'baris'    => $baris,
                        'id'       => $id,
                        'kolom'    => array_column($data_visible, $KOLOM),
                        'jenis'    => array_column($data_visible, $JENIS), 
                    ]);
                    echo view('components/aksi', [
                        'api_url' => $api_url,
                        'id'      => $id,
                        'aksi'    => $aksi
                    ]);                              
                echo '</tr>';
                
            }
            
        echo '</tbody>';
        ?>
    </table>
</div>

<!-- End Table -->

