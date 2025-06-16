<colgroup>
    <?php 
        $widths = array_map('strlen', $kolom);
        $lebar  = array_sum($widths);
        $percentage_width = [];

        foreach($widths as $w){
            array_push($percentage_width, (float) $w  / $lebar * 100);
        }
        
        // foreach($percentage_width as $w){
        //     echo $w . ' ';
        //     echo '<col width="' . $w * 100 . '%">';
        // }
    ?>
</colgroup>
