<?php
$no_rm  = urlencode($baris['no_rm']  ?? '');
$id_unit = (int) ($baris['id_unit'] ?? 0);

$registrasi_url = (isset($baris['id_keputusan']) && (int) $baris['id_keputusan'] === 2)
    ? '/unit-gawat-darurat/registrasi/tambah?no_rm=' . $no_rm
    : '/registrasi/registrasi/tambah?no_rm=' . $no_rm . ($id_unit > 0 ? '&unit=' . $id_unit : '');
?>
<div class="px-3 py-1.5">
    <a href="<?= $registrasi_url ?>" class="gap-x-1 text-sm text-green-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
        Registrasi
    </a>
</div>
