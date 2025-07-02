<div class="px-3 py-1.5">
    <button
        type="button"
        class="text-sm text-green-600 decoration-2 hover:underline font-semibold btn-pilih-instansi"
        data-id="<?= esc($baris['kode_instansi'] ?? '') ?>"
        title="Pilih Instansi Ini">
        Pilih
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-pilih-instansi').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const idInstansi = this.getAttribute('data-id');
                localStorage.setItem('kode_instansi_terpilih', idInstansi);
                window.location.href = '/masterpasien/tambah-pasien';
            });
        });
    });
</script>