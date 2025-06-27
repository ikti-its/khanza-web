<select 
    id="<? $id ?>"
    name="<? $kolom ?>"
    value="<?= $value ?>"
    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" 
    required>

    <?php
        array_unshift($opsi, ["-- Pilih --", '']);
        foreach($opsi as $o){
            echo '<option value="'.$o[1].'">'.$o[0].'</option>';
        }
    ?>
</select>