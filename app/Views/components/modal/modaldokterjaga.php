<!-- Modal Pilih Dokter Jaga -->
<div id="modalDokterJaga" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-6 w-full max-w-4xl min-h-[32rem] max-h-[90vh] shadow-2xl flex flex-col">

        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-2 border-b pb-2">
            <h2 class="text-lg font-bold text-gray-800">Pilih Dokter Jaga</h2>
            <button onclick="closeModalDokterJaga()" class="text-gray-400 hover:text-red-600 text-2xl font-bold leading-none">
                &times;
            </button>
        </div>

        <!-- Toolbar: Filter Search + Aksi -->
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <!-- Filter Search -->
            <div class="flex gap-3 w-full md:w-auto flex-1">
                <input type="text" id="searchNama" placeholder="Cari nama dokter..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm"
                    oninput="handleSearch()">
                <input type="text" id="searchPoli" placeholder="Cari poliklinik..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm"
                    oninput="handleSearch()">
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-2">
                <!-- Refresh -->
                <button onclick="fetchDokterJaga()"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 shadow transition"
                    title="Muat ulang data">
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

                <!-- Cek Dokter Jaga -->
                <a href="/dokterjaga"
                    target="_blank"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] shadow transition"
                    title="Cek Dokter Jaga">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Cek Dokter Jaga
                </a>
            </div>
        </div>

        <!-- Tabel Dokter Jaga -->
        <div class="border rounded-md overflow-auto grow">
            <table class="w-full text-sm text-gray-700 border border-gray-200">
                <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                    <tr>
                        <th class="p-4 border text-left text-base">Kode Dokter</th>
                        <th class="p-4 border text-left text-base">Nama Dokter</th>
                        <th class="p-4 border text-left text-base">Poliklinik</th>
                        <th class="p-4 border text-center text-base">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dokterJagaTable" class="[&>tr:nth-child(even)]:bg-gray-50">
                    <!-- AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <button id="prevPageBtn" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" disabled>
                Sebelumnya
            </button>
            <span id="pageInfo" class="text-gray-500">Halaman 1</span>
            <button id="nextPageBtn" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200">
                Berikutnya
            </button>
        </div>
    </div>
</div>


<script>
    let dokterData = [];
    let filteredData = [];
    let currentPage = 1;
    const rowsPerPage = 10;

    function openModalDokterJaga() {
        document.getElementById("modalDokterJaga").classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
        fetchDokterJaga();
    }

    function fetchDokterJaga() {
        fetch("/modaldokterjaga/list")
            .then(res => res.json())
            .then(data => {
                if (data.data && Array.isArray(data.data)) {
                    dokterData = data.data;
                    filteredData = [...dokterData];
                    currentPage = 1;
                    renderPage(currentPage);
                } else {
                    renderEmptyTable("Data tidak ditemukan");
                }
            })
            .catch(err => {
                console.error(err);
                renderEmptyTable("Gagal memuat data");
            });
    }

    function renderPage(page) {
        const tbody = document.getElementById('dokterJagaTable');
        tbody.innerHTML = "";

        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageData = filteredData.slice(start, end);

        if (pageData.length === 0) {
            tbody.innerHTML = `
            <tr><td colspan="4" class="text-center py-20 text-red-500">Tidak ada hasil</td></tr>
            <tr><td colspan="4" class="invisible"><div class="h-48"></div></td></tr>
        `;
            updatePaginationControls();
            return;
        }

        pageData.forEach((dokter, i) => {
            const isEven = i % 2 === 1;
            const nama = escapeQuotes(dokter.nama_dokter);
            const poli = escapeQuotes(dokter.poliklinik);
            const kode = escapeQuotes(dokter.kode_dokter);

            const row = `
            <tr class="transition ${isEven ? 'bg-gray-100' : ''} hover:bg-emerald-50">
                <td class="p-2 border">${kode}</td>
                <td class="p-2 border">${nama}</td>
                <td class="p-2 border">${poli}</td>
                <td class="p-2 border text-center">
                    <button type="button" onclick="selectDokterJaga('${nama}', '${poli}', '${kode}')" class="text-emerald-600 font-medium hover:underline">
                        Pilih
                    </button>
                </td>
            </tr>`;

            tbody.insertAdjacentHTML('beforeend', row);
        });

        const dummyCount = rowsPerPage - pageData.length;
        for (let i = 0; i < dummyCount; i++) {
            tbody.insertAdjacentHTML('beforeend', `
            <tr class="h-10">
                <td colspan="4" class="p-0 border-0 invisible whitespace-nowrap"></td>
            </tr>
        `);
        }

        updatePaginationControls();
    }

    function updatePaginationControls() {
        const totalPages = Math.ceil(filteredData.length / rowsPerPage);
        document.getElementById('pageInfo').innerText = `Halaman ${currentPage} dari ${totalPages || 1}`;
        document.getElementById('prevPageBtn').disabled = currentPage === 1;
        document.getElementById('nextPageBtn').disabled = currentPage >= totalPages;
    }

    function handleSearch() {
        const keywordNama = document.getElementById('searchNama').value.toLowerCase();
        const keywordPoli = document.getElementById('searchPoli').value.toLowerCase();

        filteredData = dokterData.filter(d =>
            d.nama_dokter.toLowerCase().includes(keywordNama) &&
            d.poliklinik.toLowerCase().includes(keywordPoli)
        );

        currentPage = 1;
        renderPage(currentPage);
    }

    function escapeQuotes(str) {
        return String(str)
            .replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/"/g, '\\"');
    }

    document.getElementById('prevPageBtn').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderPage(currentPage);
        }
    });

    document.getElementById('nextPageBtn').addEventListener('click', () => {
        const totalPages = Math.ceil(filteredData.length / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            renderPage(currentPage);
        }
    });

    window.selectDokterJaga = function(nama, poli, kode) {
        document.getElementById('kode_dokter').value = kode;
        document.getElementById('nama_dokter').value = nama;
        document.getElementById('poliklinik').value = poli;
        closeModalDokterJaga();
    };

    function closeModalDokterJaga() {
        document.getElementById('modalDokterJaga').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('searchNama').value = '';
        document.getElementById('searchPoli').value = '';
    }

    function renderEmptyTable(msg) {
        document.getElementById('dokterJagaTable').innerHTML =
            `<tr><td colspan="4" class="text-center p-2 text-red-500">${msg}</td></tr>`;
    }
</script>