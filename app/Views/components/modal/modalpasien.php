<!-- Modal Pilih Pasien -->
<div id="modalPasien" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-6 w-full max-w-4xl min-h-[32rem] max-h-[90vh] shadow-2xl flex flex-col">

        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-2 border-b pb-2">
            <h2 class="text-lg font-bold text-gray-800">Pilih Pasien</h2>
            <button onclick="closeModalPasien()" class="text-gray-400 hover:text-red-600 text-2xl font-bold leading-none">
                &times;
            </button>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <!-- Filter Search -->
            <div class="flex gap-3 w-full md:w-auto flex-1">
                <input type="text" id="searchNoRM" placeholder="Cari No. RM..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm"
                    oninput="handleSearchPasien()">
                <input type="text" id="searchNamaPasien" placeholder="Cari nama pasien..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm"
                    oninput="handleSearchPasien()">
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-2">
                <button onclick="fetchPasien()"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 shadow transition"
                    title="Muat ulang data pasien">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.5 12a7.5 7.5 0 0112.72-5.303l1.28-1.28
               M19.5 12a7.5 7.5 0 01-12.72 5.303l-1.28 1.28" />
                    </svg>
                    Refresh
                </button>
                <a href="/masterpasien/tambah-pasien"
                    target="_blank"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] shadow transition"
                    title="Tambah Pasien Baru">
                    + Tambah
                </a>

            </div>
        </div>

        <!-- Tabel Pasien -->
        <div class="border rounded-md overflow-auto grow">
            <table class="w-full text-sm text-gray-700 border border-gray-200">
                <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                    <tr>
                        <th class="p-4 border text-left text-base">No. RM</th>
                        <th class="p-4 border text-left text-base">Nama Pasien</th>
                        <th class="p-4 border text-center text-base">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pasienTable" class="[&>tr:nth-child(even)]:bg-gray-50">
                    <!-- AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <button id="prevPagePasienBtn" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" disabled>
                Sebelumnya
            </button>
            <span id="pageInfoPasien" class="text-gray-500">Halaman 1</span>
            <button id="nextPagePasienBtn" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200">
                Berikutnya
            </button>
        </div>
    </div>
</div>


<script>
    let pasienData = [];
    let filteredPasienData = [];
    let currentPagePasien = 1;
    const rowsPerPagePasien = 10;

    function openModalPasien() {
        const modal = document.getElementById('modalPasien');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        fetchPasien();
    }

    function fetchPasien() {
        fetch("/modalpasien/list")
            .then(res => res.json())
            .then(data => {
                if (data.data && Array.isArray(data.data)) {
                    pasienData = data.data;
                    filteredPasienData = [...pasienData];
                    currentPagePasien = 1;
                    renderPasienPage(currentPagePasien);
                } else {
                    document.getElementById('pasienTable').innerHTML =
                        `<tr><td colspan="3" class="text-center p-2 text-red-500">Data tidak ditemukan</td></tr>`;
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('pasienTable').innerHTML =
                    `<tr><td colspan="3" class="text-center p-2 text-red-500">Gagal memuat data</td></tr>`;
            });
    }

    function renderPasienPage(page) {
        const tbody = document.getElementById('pasienTable');
        tbody.innerHTML = "";

        const start = (page - 1) * rowsPerPagePasien;
        const end = start + rowsPerPagePasien;
        const pageData = filteredPasienData.slice(start, end);

        if (pageData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-4 text-red-500">Tidak ada hasil</td></tr>`;
            for (let i = 0; i < rowsPerPagePasien - 1; i++) {
                tbody.insertAdjacentHTML('beforeend', `
                <tr class="h-10">
                    <td colspan="3" class="p-0 border-0 invisible whitespace-nowrap">.</td>
                </tr>
            `);
            }
            updatePaginationPasienControls();
            return;
        }

        pageData.forEach(pasien => {
            const row = `
            <tr class="transition hover:bg-emerald-50">
                <td class="p-2 border">${pasien.no_rkm_medis}</td>
                <td class="p-2 border">${pasien.nm_pasien}</td>
                <td class="p-2 border text-center">
                    <button type="button" onclick="selectPasien('${pasien.no_rkm_medis}')" style="color:#0A2D27" class="hover:underline">Pilih</button>
                </td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    function updatePaginationPasienControls() {
        const totalPages = Math.ceil(filteredPasienData.length / rowsPerPagePasien);
        document.getElementById('pageInfoPasien').innerText = `Halaman ${currentPagePasien} dari ${totalPages || 1}`;
        document.getElementById('prevPagePasienBtn').disabled = currentPagePasien === 1;
        document.getElementById('nextPagePasienBtn').disabled = currentPagePasien >= totalPages;
    }

    function handleSearchPasien() {
        const keywordNama = document.getElementById('searchNamaPasien').value.toLowerCase();
        const keywordNoRM = document.getElementById('searchNoRM').value.toLowerCase();

        filteredPasienData = pasienData.filter(p =>
            p.nm_pasien.toLowerCase().includes(keywordNama) &&
            p.no_rkm_medis.toLowerCase().includes(keywordNoRM)
        );

        currentPagePasien = 1;
        renderPasienPage(currentPagePasien);
    }

    document.getElementById('prevPagePasienBtn').addEventListener('click', () => {
        if (currentPagePasien > 1) {
            currentPagePasien--;
            renderPasienPage(currentPagePasien);
        }
    });

    document.getElementById('nextPagePasienBtn').addEventListener('click', () => {
        const totalPages = Math.ceil(filteredPasienData.length / rowsPerPagePasien);
        if (currentPagePasien < totalPages) {
            currentPagePasien++;
            renderPasienPage(currentPagePasien);
        }
    });

    function closeModalPasien() {
        document.getElementById('modalPasien').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    window.selectPasien = function(nomorRM) {
        const input = document.querySelector('input[name="no_rkm_medis"]');
        input.value = nomorRM;
        input.dispatchEvent(new Event('blur'));
        closeModalPasien();
    };
</script>