<div class="px-3 py-1.5">
    <button
        class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" 
        onclick="validateResep('<?= $baris['no_resep'] ?>')"
        <?php if($baris['validasi'] === true) echo 'style="visibility:hidden"' ?>>
        Validasi
    </button>
</div>

<script>
    function validateResep(noResep) {
    if (!confirm("Yakin ingin memvalidasi resep ini?")) return;

    fetch(`http://127.0.0.1:8080/v1/resep-obat/${noResep}/validasi`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer <?= session('jwt_token') ?>'
        },
        body: JSON.stringify({ validasi: true })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Berhasil divalidasi');
            location.reload(); // ⏮ Refresh to see updated status
        } else {
            alert('Gagal memvalidasi resep');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan');
    });
}
</script>