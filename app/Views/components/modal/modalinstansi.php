<!-- Modal Pilih Instansi -->
<div id="modalInstansi" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-4 w-full max-w-md max-h-[75vh] overflow-y-auto shadow-lg">
        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-semibold text-gray-800">Pilih Instansi Pasien</h2>
            <div class="flex items-center gap-3">
                <!-- Tombol Tambah -->
                <a href="/instansi"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] rounded-md shadow transition"
                    title="Tambah Instansi Baru">
                    + Tambah
                </a>
                <!-- Tombol Close -->
                <button type="button" onclick="closeModalInstansi()" class="text-gray-500 hover:text-red-600 text-xl font-bold">
                    &times;
                </button>
            </div>
        </div>

        <!-- Tabel Data Instansi -->
        <table class="w-full text-sm text-center text-gray-700 border border-gray-300">
            <thead style="background-color: #E6F2EF;">
                <tr>
                    <th class="p-2 border">Kode Instansi</th>
                    <th class="p-2 border">Nama Instansi</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody id="instansiTable">
                <!-- Data AJAX -->
            </tbody>
        </table>
    </div>
</div>


<script>
    function openModalInstansi() {
        const modal = document.getElementById('modalInstansi');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        fetch("/modalinstansi/list")
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('instansiTable');
                tbody.innerHTML = "";

                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(instansi => {
                        const row = `
                            <tr class="border-b">
                                <td class="p-2 border">${instansi.kode_instansi}</td>
                                <td class="p-2 border">${instansi.nama_instansi}</td>
                                <td class="p-2 border text-center">
                                    <button type="button"
                                        onclick="selectInstansi('${instansi.kode_instansi}', '${instansi.nama_instansi}')"
                                        class="text-[#0A2D27] hover:underline">
                                        Pilih
                                    </button>
                                </td>
                            </tr>`;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="3" class="text-center p-2 text-red-500">Data tidak ditemukan</td></tr>`;
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('instansiTable').innerHTML =
                    `<tr><td colspan="3" class="text-center p-2 text-red-500">Gagal memuat data</td></tr>`;
            });
    }

    function closeModalInstansi() {
        document.getElementById('modalInstansi').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    window.selectInstansi = function(kodeInstansi, namaInstansi) {
        // Inject ke input hidden (yang dikirim ke backend)
        document.getElementById('perusahaan_pasien').value = kodeInstansi;

        // Tampilkan ke user (optional, kalau ada)
        const display = document.getElementById('perusahaan_pasien_display');
        if (display) {
            display.value = namaInstansi;
        }

        closeModalInstansi();
    };
</script>