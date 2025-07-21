<!-- Modal Pilih Instansi -->
<div id="modalInstansi" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-6 w-full max-w-4xl min-h-[32rem] max-h-[90vh] shadow-2xl flex flex-col">

        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-2 border-b pb-2">
            <h2 class="text-lg font-bold text-gray-800">Pilih Instansi Pasien</h2>
            <button onclick="closeModalInstansi()" class="text-gray-400 hover:text-red-600 text-2xl font-bold leading-none">
                &times;
            </button>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <!-- Filter Search -->
            <div class="flex gap-3 w-full md:w-auto flex-1">
                <input type="text" id="searchKodeInstansi" placeholder="Cari Kode Instansi..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm"
                    oninput="handleSearchInstansi()">
                <input type="text" id="searchNamaInstansi" placeholder="Cari Nama Instansi..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm"
                    oninput="handleSearchInstansi()">
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-2">
                <button onclick="fetchInstansi()"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 shadow transition"
                    title="Muat ulang data instansi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0112.72-5.303l1.28-1.28
                            M19.5 12a7.5 7.5 0 01-12.72 5.303l-1.28 1.28" />
                    </svg>
                    Refresh
                </button>

                <a href="/instansi"
                    target="_blank"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] shadow transition"
                    title="Tambah Instansi Baru">
                    + Tambah
                </a>
            </div>
        </div>

        <!-- Tabel Instansi -->
        <div class="border rounded-md overflow-auto grow">
            <table class="w-full text-sm text-gray-700 border border-gray-200">
                <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                    <tr>
                        <th class="p-4 border text-left text-base">Kode Instansi</th>
                        <th class="p-4 border text-left text-base">Nama Instansi</th>
                        <th class="p-4 border text-center text-base">Aksi</th>
                    </tr>
                </thead>
                <tbody id="instansiTable" class="[&>tr:nth-child(even)]:bg-gray-50">
                    <!-- AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <button id="prevPageInstansiBtn" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" disabled>
                Sebelumnya
            </button>
            <span id="pageInfoInstansi" class="text-gray-500">Halaman 1</span>
            <button id="nextPageInstansiBtn" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200">
                Berikutnya
            </button>
        </div>
    </div>
</div>



<script>
    let instansiData = [];
    let filteredInstansiData = [];
    let currentPageInstansi = 1;
    const rowsPerPageInstansi = 10;

    function openModalInstansi() {
        const modal = document.getElementById('modalInstansi');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        fetchInstansi();
    }

    function fetchInstansi() {
        fetch("/modalinstansi/list")
            .then(res => res.json())
            .then(data => {
                if (data.data && Array.isArray(data.data)) {
                    instansiData = data.data;
                    filteredInstansiData = [...instansiData];
                    currentPageInstansi = 1;
                    renderInstansiPage(currentPageInstansi);
                } else {
                    document.getElementById('instansiTable').innerHTML =
                        `<tr><td colspan="3" class="text-center p-2 text-red-500">Data tidak ditemukan</td></tr>`;
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('instansiTable').innerHTML =
                    `<tr><td colspan="3" class="text-center p-2 text-red-500">Gagal memuat data</td></tr>`;
            });
    }

    function renderInstansiPage(page) {
        const tbody = document.getElementById('instansiTable');
        tbody.innerHTML = "";

        const start = (page - 1) * rowsPerPageInstansi;
        const end = start + rowsPerPageInstansi;
        const pageData = filteredInstansiData.slice(start, end);

        if (pageData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-4 text-red-500">Tidak ada hasil</td></tr>`;
            for (let i = 0; i < rowsPerPageInstansi - 1; i++) {
                tbody.insertAdjacentHTML('beforeend', `
                <tr class="h-10">
                    <td colspan="3" class="p-0 border-0 invisible whitespace-nowrap">.</td>
                </tr>
            `);
            }
            updatePaginationInstansiControls();
            return;
        }

        pageData.forEach(instansi => {
            const row = `
            <tr class="transition hover:bg-emerald-50">
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
    }

    function updatePaginationInstansiControls() {
        const totalPages = Math.ceil(filteredInstansiData.length / rowsPerPageInstansi);
        document.getElementById('pageInfoInstansi').innerText = `Halaman ${currentPageInstansi} dari ${totalPages || 1}`;
        document.getElementById('prevPageInstansiBtn').disabled = currentPageInstansi === 1;
        document.getElementById('nextPageInstansiBtn').disabled = currentPageInstansi >= totalPages;
    }

    function handleSearchInstansi() {
        const keywordKode = document.getElementById('searchKodeInstansi').value.toLowerCase();
        const keywordNama = document.getElementById('searchNamaInstansi').value.toLowerCase();

        filteredInstansiData = instansiData.filter(i =>
            i.kode_instansi.toLowerCase().includes(keywordKode) &&
            i.nama_instansi.toLowerCase().includes(keywordNama)
        );

        currentPageInstansi = 1;
        renderInstansiPage(currentPageInstansi);
    }

    document.getElementById('prevPageInstansiBtn').addEventListener('click', () => {
        if (currentPageInstansi > 1) {
            currentPageInstansi--;
            renderInstansiPage(currentPageInstansi);
        }
    });

    document.getElementById('nextPageInstansiBtn').addEventListener('click', () => {
        const totalPages = Math.ceil(filteredInstansiData.length / rowsPerPageInstansi);
        if (currentPageInstansi < totalPages) {
            currentPageInstansi++;
            renderInstansiPage(currentPageInstansi);
        }
    });

    function closeModalInstansi() {
        document.getElementById('modalInstansi').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    window.selectInstansi = function(kodeInstansi, namaInstansi) {
        document.getElementById('perusahaan_pasien').value = kodeInstansi;

        const display = document.getElementById('perusahaan_pasien_display');
        if (display) {
            display.value = namaInstansi;
        }

        closeModalInstansi();
    };
</script>