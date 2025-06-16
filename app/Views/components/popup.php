<div id="hs-vertically-centered-scrollable-modal-<?= $id ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <?php 
                    echo view('components/popup_judul',    ['id' => $id]);
                    echo view('components/popup_tombol_x', ['id' => $id]);
                ?>
            </div>
            <div class="p-4">
                <?php
                    echo view('components/popup_data', [
                        'baris' => $baris,
                        'kolom' => $kolom,
                        'label' => $label
                    ]);
                    echo view('components/popup_tombol_tutup', ['id' => $id]);
                ?>
            </div>
        </div>
    </div>
</div>
