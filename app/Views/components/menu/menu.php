<?php
    $userDetails = session()->get('user_details');

    // dd($userDetails);
    // Initialize defaults
    $role = null;
    $foto = '/img/default.png';
    $email = 'Guest';

    $persetujuanrole   = [1337, 1, 2, 4001, 5001];
    $petugasrole       = [1337, 1, 2, 4001, 5001];
    $petugasdokterrole = [1337, 1, 2, 3, 4001, 5001];
    $dokterrole        = [1337, 1, 3, 4001, 5001];

    if (is_array($userDetails)) {
        $role  = $userDetails['role']  ?? null;
        // $foto  = $userDetails['foto']  ?? $foto;
        $email = $userDetails['email'] ?? $email;
    }
?>

<?php
    $TEKS     = 0;
    $LINK     = 1;
    $ICON     = 2;
    $PREFIKS  = 3;
    $ROLELIST = 4;
    $SUBMENU  = 5; 
    foreach($menu_list as $menu){
        $teks     = $menu[$TEKS];
        $link     = $menu[$LINK];
        $icon     = $menu[$ICON];
        $prefiks  = $menu[$PREFIKS];
        $rolelist = $menu[$ROLELIST];
        $submenu  = $menu[$SUBMENU];
        
        if(!($link === '' xor $submenu === [] )){
            echo 'Jika link menu diisi, maka submenu harus kosong, dan sebaliknya. ';
            echo 'Error pada data ' . $teks;
            continue;
        }

        if (!in_array($role, $rolelist)){
            continue;
        }

        echo '<li class="hs-accordion" id="olahpasien-accordion">';
        if($submenu === []){
            echo view('components/menu/menu_link', [
                'teks'    => $teks,
                'icon'    => $icon,
                'link'    => $link,
                'prefiks' => $prefiks,
            ]);
        } else {
            echo view('components/menu/menu_baris', [
                'teks'    => $teks,
                'icon'    => $icon,
                'submenu' => $submenu,
                'prefiks' => $prefiks,
            ]);
        }
        echo '</li>';
    }
?>