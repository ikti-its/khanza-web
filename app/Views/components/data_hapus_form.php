<div id="modelConfirm-<?= $row_id ?>" class="fixed hidden z-[70] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">
        <?= view('components/data_hapus_form_tombol_close', ['row_id' => $row_id]) ?>
        <div class="p-6 pt-0 text-center">
            <?= view('components/data_hapus_form_svg') ?>
            
            Hapus data
            
            <h3 class="text-xl text-wrap font-normal text-gray-500 mt-5 mb-6">
                Apakah anda yakin untuk menghapus data ini? AAAA
            </h3>
            
            <?= view('components/data_hapus_form_opsi', [
                'row_id'  => $row_id,
                'api_url' => $api_url
            ]) ?>
        </div>
    </div>
</div>
