<thead class="bg-gray-50 dark:bg-slate-800">
    <tr>
        <!-- <th scope="col" class="ps-6 py-3 text-start">
            <label for="hs-at-with-checkboxes-main" class="flex">
                <input type="checkbox" class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-at-with-checkboxes-main">
                <span class="sr-only">Checkbox</span>
            </label>
        </th> -->
    <?php        
        foreach ($kolom as $k){
            echo view('components/tabel_th', [
                'kolom' => esc($k)
            ]);
        }
    ?>
    </tr>
</thead>