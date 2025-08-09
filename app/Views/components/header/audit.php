<!-- Header -->
<div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
    <?= view('components/header/judul', [
        'judul' => $judul
    ]) ?>
    <div class="flex gap-x-6 justify-center items-center">
        <?= view('components/header/kembali', [
            'link' => $modul_path
        ]) ?>
    </div>
</div>

<!-- End Header -->