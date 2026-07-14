<?php
$nilai = $baris[$kolom] ?? '';
$nilai = is_array($nilai) ? reset($nilai) : $nilai;
if ($nilai === 't')
    $nilai = 'Ya';
if ($nilai === 'f')
    $nilai = 'Tidak';
// Popup tidak menerima info jenis kolom, jadi masker berdasarkan nama
if ($kolom === 'password' && $nilai !== '')
    $nilai = '••••••••';
// Nilai bertipe tanggal dari DB berformat ISO (Y-m-d[ H:i:s+tz]); tampilkan sebagai d-m-Y agar konsisten dengan tabel
if (is_string($nilai) && preg_match('/^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::\d{2}(?:\.\d+)?)?(?:[+-]\d{2}(?::?\d{2})?)?)?$/', $nilai, $m))
    $nilai = "{$m[3]}-{$m[2]}-{$m[1]}" . (isset($m[4]) ? " {$m[4]}:{$m[5]}" : '');
?>
<div class="mb-5 sm:block">
    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
        <?= $label ?>
    </label>
    <input type="text" value="<?= $nilai ?>"
        class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
</div>