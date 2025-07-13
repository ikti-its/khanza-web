<!-- Modal Pilih Pasien -->
<div id="modalPasien" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-4 w-full max-w-md max-h-[75vh] overflow-y-auto shadow-lg">
        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-semibold text-gray-800">Pilih Pasien</h2>
            <div class="flex items-center gap-3">
                <!-- Tombol Tambah -->
                <a href="/masterpasien/tambah-pasien"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] rounded-md shadow transition"
                    title="Tambah Pasien Baru">
                    + Tambah
                </a>
                <!-- Tombol Close -->
                <button type="button" onclick="closeModalPasien()" class="text-gray-500 hover:text-red-600 text-xl font-bold">
                    &times;
                </button>
            </div>
        </div>

        <!-- Tabel Data Pasien -->
        <table class="w-full text-sm text-center text-gray-700 border border-gray-300">
            <thead style="background-color: #E6F2EF;">
                <tr>
                    <th class="p-2 border">No. RM</th>
                    <th class="p-2 border">Nama Pasien</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody id="pasienTable">
                <!-- Data AJAX -->
            </tbody>
        </table>
    </div>
</div>

<script>
    function openModalPasien() {
        const modal = document.getElementById('modalPasien');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        fetch("/modalpasien/list")
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('pasienTable');
                tbody.innerHTML = "";

                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(pasien => {
                        const row = `
                            <tr class="border-b">
                                <td class="p-2 border">${pasien.no_rkm_medis}</td>
                                <td class="p-2 border">${pasien.nm_pasien}</td>
                                <td class="p-2 border text-center">
                                    <button type="button" onclick="selectPasien('${pasien.no_rkm_medis}')" style="color:#0A2D27" class="hover:underline">Pilih</button>
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
                document.getElementById('pasienTable').innerHTML =
                    `<tr><td colspan="3" class="text-center p-2 text-red-500">Gagal memuat data</td></tr>`;
            });
    }

    function closeModalPasien() {
        document.getElementById('modalPasien').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    window.selectPasien = function(nomorRM) {
        const input = document.querySelector('input[name="no_rkm_medis"]');
        input.value = nomorRM;
        input.dispatchEvent(new Event('blur')); // tetap trigger autofill
        window.closeModalPasien();
    };
</script>