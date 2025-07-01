<form action="<?= $modul_path . '/hapus/' . $id ?>" method="POST">
    <?= csrf_field() ?>
    <div class="w-full sm:flex justify-center">
        <input type="hidden" name="_method" value="DELETE">
        <button onclick="closeModal('modelConfirm-<?= $id ?>')" class="w-full text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center justify-center px-3 py-2.5 text-center mr-2">
            Hapus
        </button>
        <a onclick="closeModal('modelConfirm-<?= $id ?>')" class="w-full text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center justify-center rounded-lg text-base px-3 py-2.5 text-center" data-modal-toggle="delete-user-modal">
            Batal
        </a>
    </div>
</form>