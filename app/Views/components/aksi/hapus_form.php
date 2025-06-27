<div id="modelConfirm-<?= $id ?>" class="fixed hidden z-[70] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">
        <?= view('components/aksi/hapus_form_close', ['id' => $id]) ?>
        <div class="p-6 pt-0 text-center">
            <?= view('components/aksi/hapus_form_svg') ?>
            
            Hapus data
            
            <h3 class="text-xl text-wrap font-normal text-gray-500 mt-5 mb-6">
                Apakah anda yakin untuk menghapus data ini?
            </h3>
            
            <?= view('components/aksi/hapus_form_opsi', [
                'modul_path' => $modul_path,
                'id'         => $id
                
            ]) ?>
        </div>
    </div>
</div>
