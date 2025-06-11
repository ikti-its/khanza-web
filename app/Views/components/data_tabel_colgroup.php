<colgroup>
    <?php 
        foreach ($widths as $width){
            if (!is_int($width)) {
                echo "Column width pada colgroup harus berupa integer";
                return false;
            }
            if(array_sum($widths) != 100){
                echo "Column width harus berjumlah 100";
                return false;
            }
        } 
    ?>
    <?php foreach($widths as $width): ?>
        <col width="<?= $width?>%">
    <?php endforeach; ?>
</colgroup>