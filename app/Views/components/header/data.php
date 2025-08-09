<!-- Header -->
<div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
    <?= view('components/header/judul', [
            'judul' => $judul
        ]) ?>
    <div class="flex gap-x-6 justify-center items-center">
        <?php 
            if(isset($aksi['notif']) && $aksi['notif'] === true){
                echo view('components/header/notif_button');
                echo '<div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>';
            }
            if(isset($aksi['tambah']) && $aksi['tambah'] === true){
                echo view('components/header/tambah_button', [
                    'link' => $modul_path . '/tambah'
                ]);
            }
            if(isset($aksi['audit']) && $aksi['audit'] === true){
                echo view('components/header/audit_button', [
                    'link' => $modul_path . '/audit'
                ]);
            }
        ?>
    </div>
</div>

<!-- End Header -->