<select 
    id="<?= $id ?>"
    name="<?= $kolom ?>"
    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" 
    <?= $req  === 1   ? 'required' : ''; ?>>

    <?php
        array_unshift($opsi, ["-- Pilih --", '']);
        foreach($opsi as $o){
            $selected = '';
            if($value === $o[0]){
                $selected = "selected";
            }
            echo '<option value="' . $o[1] . '" ' . $selected . '>'. $o[0]  . '</option>';
        }
    ?>
</select>