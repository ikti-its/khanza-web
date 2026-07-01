<?php
$successMessage = session()->getFlashdata('success');
$errorMessage   = session()->getFlashdata('error');

$pencekalanUrl     = session()->get('pencekalan_url');
$pencekalanMessage = session()->get('pencekalan_message');

$currentPath = service('uri')->getPath();
$isFormPencekalan = str_contains($currentPath, 'penanganan-donor/pencekalan/tambah');
?>

<?php if (!empty($pencekalanUrl) && !$isFormPencekalan): ?>
    <script>
        const wajibPencekalanUrl = <?= json_encode($pencekalanUrl) ?>;
        const wajibPencekalanMessage = <?= json_encode($pencekalanMessage ?? 'Data pencekalan wajib dilengkapi terlebih dahulu.') ?>;

        function showWajibPencekalanPopup() {
            Swal.fire({
                icon: 'warning',
                title: 'Hasil Reaktif Terdeteksi',
                text: wajibPencekalanMessage,
                confirmButtonText: 'Isi Data Pencekalan',
                showCancelButton: false,
                showCloseButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = wajibPencekalanUrl;
                }
            });
        }

        showWajibPencekalanPopup();

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                showWajibPencekalanPopup();
            }
        });
    </script>
<?php elseif ($successMessage): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: <?= json_encode($successMessage) ?>,
            confirmButtonText: 'Oke',
            customClass: {
                confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2'
            },
            buttonsStyling: false
        });
    </script>
<?php endif; ?>

<?php if ($errorMessage): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: <?= json_encode($errorMessage) ?>,
            confirmButtonText: 'Tutup',
            customClass: {
                confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2'
            },
            buttonsStyling: false
        });
    </script>
<?php endif; ?>