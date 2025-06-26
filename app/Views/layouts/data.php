@ -1,666 +1,658 @@
<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <!-- <div class="max-w-[85rem] w-full py-6 lg:py-3"> -->
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-hidden">
            <div class="px-4 w-full overflow-x-auto">

                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
                    <?php
                    echo view('components/data_header', [
                        'judul'      => $judul,
                        'modul_path' => $modul_path
                    ]);
                    echo view('components/search_bar');

                    echo view('components/tabel', [
                        'modul_path' => $modul_path,
                        'tabel'      => $tabel,
                        'kolom_id'   => $kolom_id,
                        'konfig'     => $konfig,
                        'aksi'       => $aksi
                    ]);

                    echo view('components/footer', [
                        'meta_data'  => $meta_data,
                        'modul_path' => $modul_path
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<?= $this->endSection(); ?>