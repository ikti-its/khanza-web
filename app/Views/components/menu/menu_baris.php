<li class="hs-accordion" id="users-accordion">
    <?= view('components/menu/menu_button', [
        'teks'    => $teks,
        'icon'    => $icon,
    ]) ?>
    <div id="users-accordion-sub-1" class="border-[#F1F1F1] border-l-[2px] mt-2 hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
        <ul class="ps-2">
            <?= view('components/menu/submenu', [
                'submenu' => $submenu,
                'prefiks' => $prefiks,
            ]) ?>      
        </ul>
    </div>
</li>