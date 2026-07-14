<?php

$TEKS = 0;
$LINK = 1;
$ICON = 2;
$PREFIKS = 3;
$GRUP_PATH = 4;
$SUBMENU = 5;
foreach ($menu_list as $menu) {
    $teks = $menu[$TEKS];
    $link = $menu[$LINK];
    $icon = $menu[$ICON];
    $prefiks = $menu[$PREFIKS];
    $grup_path = $menu[$GRUP_PATH];
    $submenu = $menu[$SUBMENU];

    if (!($link === '' xor $submenu === [])) {
        echo 'Jika link menu diisi, maka submenu harus kosong, dan sebaliknya. ';
        echo 'Error pada data ' . $teks;
        continue;
    }

    if (!\App\Core\Auth\AccessMatrix::can_read($grup_path)) {
        continue;
    }

    echo '<li class="hs-accordion" id="olahpasien-accordion">';
    if ($submenu === []) {
        echo
            view('components/menu/menu_link', [
                'teks' => $teks,
                'icon' => $icon,
                'link' => $link,
                'prefiks' => $prefiks,
            ])
        ;
    } else {
        echo
            view('components/menu/menu_baris', [
                'teks' => $teks,
                'icon' => $icon,
                'submenu' => $submenu,
                'prefiks' => $prefiks,
            ])
        ;
    }
    echo '</li>';
}
