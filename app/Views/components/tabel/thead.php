<thead class="sticky top-0 z-10 bg-gray-50 dark:bg-slate-800">
    <tr>
        <?php        
            foreach ($kolom as $k) {
                echo view('components/tabel/th', [
                    'kolom' => esc($k)
                ]);
            }
        ?>
    </tr>
</thead>
