<?php
    $TEKS = 0;
    $LINK = 1;
    $ICON = 2;
    foreach($submenu as $s){
        $teks = $s[$TEKS];
        $link = $s[$LINK];
        $icon = $s[$ICON];
        echo view('components/menu/submenu_baris', [
            'teks'    => $teks,
            'link'    => $link,
            'icon'    => $icon,
            'prefiks' => $prefiks,
        ]);
    }
?>