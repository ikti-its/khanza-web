<div class="flex">
    <?php
        echo view('components/notif_konten_tab', [
            'id'   => 'stok-tab',
            'teks' => 'Stok',
        ]);
        echo view('components/notif_konten_tab', [
            'id'   => 'kadaluwarsa-tab',
            'teks' => 'Kadaluwarsa',
        ]);
    ?>
</div>
