<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?php
                echo csrf_field();

                // CREATE: carry FK params from parent list page URL (?id_permintaan=5)
                foreach (request()->getGet() as $key => $val) {
                    echo '<input type="hidden" name="' . esc($key) . '" value="' . esc((string) $val) . '">';
                }

                // UPDATE: preserve HIDE fields from the existing record (not rendered in form)
                if (is_array($baris) && !empty($baris)) {
                    $visible_columns = array_column($konfig, 2);
                    foreach ($baris as $col => $val) {
                        if (!in_array($col, $visible_columns, true) && $val !== null) {
                            echo '<input type="hidden" name="' . esc($col) . '" value="' . esc((string) $val) . '">';
                        }
                    }
                }

                echo view('components/form/isian', ['konfig' => $konfig, 'baris' => $baris ??  '']);
                echo view('components/form/submit_button')
            ?>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->

<?php
$included_modals = [];
foreach ($konfig as $field) {
    if (!isset($field[5]['modal'])) continue;
    $modal_name = $field[5]['modal'];
    if (in_array($modal_name, $included_modals, true)) continue;
    $included_modals[] = $modal_name;
    echo view('components/modal/' . $modal_name);
}
?>

<script>
    // Autofill: select[data-autofill-map] → multiple inputs
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('select[data-autofill-map]').forEach(function (sel) {
            var map = {};
            try { map = JSON.parse(sel.getAttribute('data-autofill-map')); } catch (e) {}
            function sync() {
                var opt = sel.options[sel.selectedIndex];
                for (var target in map) {
                    var input = document.querySelector('[name="' + target + '"]');
                    if (input) input.value = opt ? (opt.getAttribute('data-' + map[target]) || '') : '';
                }
            }
            sel.addEventListener('change', sync);
            sync();
        });
    });

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Isi semua field.");
                return false;
            }
        }
        var submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        // Ubah teks tombol menjadi sesuatu yang menunjukkan proses sedang berlangsung, misalnya "Menyimpan..."
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }
</script>
<?= $this->endSection(); ?>