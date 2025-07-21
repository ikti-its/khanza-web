<!-- Modal Pilih Dokter -->
<div id="modalDokter" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-6 w-full max-w-4xl min-h-[32rem] max-h-[90vh] shadow-2xl flex flex-col">

        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-2 border-b pb-2">
            <h2 class="text-lg font-bold text-gray-800">Pilih Dokter</h2>
            <button onclick="closeModalDokter()" class="text-gray-400 hover:text-red-600 text-2xl font-bold leading-none">
                &times;
            </button>
        </div>

        <!-- Toolbar: Filter Search + Aksi -->
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <!-- Filter Search -->
            <div class="flex gap-3 w-full md:w-auto flex-1">
                <input type="text" id="searchKodeDokter" placeholder="Cari kode dokter..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm"
                    oninput="handleSearchDokter()">
                <input type="text" id="searchNamaDokter" placeholder="Cari nama dokter..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm"
                    oninput="handleSearchDokter()">
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
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] shadow transition"
                    title="Cek Dokter Jaga">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Cek Daftar Dokter
                </a>
            </div>
        </div>

        <!-- Tabel Dokter -->
        <div class="border rounded-md overflow-auto grow">
            <table class="w-full text-sm text-gray-700 border border-gray-200">
                <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                    <tr>
                        <th class="p-4 border text-left text-base">Kode Dokter</th>
                        <th class="p-4 border text-left text-base">Nama Dokter</th>
                        <th class="p-4 border text-left text-base">Spesialis</th>
                        <th class="p-4 border text-center text-base">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dokterTable" class="[&>tr:nth-child(even)]:bg-gray-50">
                    <!-- AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <button id="prevPageDokterBtn" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" disabled>
                Sebelumnya
            </button>
            <span id="pageInfoDokter" class="text-gray-500">Halaman 1</span>
            <button id="nextPageDokterBtn" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200">
                Berikutnya
            </button>
        </div>
    </div>
</div>


<script>
    let dokterList = [];
    let filteredDokter = [];
    let currentPageDokter = 1;
    const rowsPerPageDokter = 15;

    function openModalDokter() {
        document.getElementById("modalDokter").classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
        fetchDokter();
    }

    function fetchDokter() {
        fetch("/modaldokter/list")
            .then((res) => res.json())
            .then((data) => {
                if (data.data && Array.isArray(data.data)) {
                    dokterList = data.data;
                    filteredDokter = [...dokterList];
                    currentPageDokter = 1;
                    renderPageDokter(currentPageDokter);
                } else {
                    renderEmptyDokterTable("Data tidak ditemukan");
                }
            })
            .catch(() => {
                renderEmptyDokterTable("Gagal memuat data");
            });
    }

    function renderEmptyDokterTable(msg) {
        document.getElementById("dokterTable").innerHTML = `
    <tr><td colspan="4" class="text-center py-20 text-red-500">${msg}</td></tr>
    <tr><td colspan="4" class="invisible"><div class="h-48"></div></td></tr>
  `;
        document.getElementById("pageInfoDokter").innerText = "Halaman 1";
    }

    function renderPageDokter(page) {
        const tbody = document.getElementById("dokterTable");
        tbody.innerHTML = "";

        const start = (page - 1) * rowsPerPageDokter;
        const end = start + rowsPerPageDokter;
        const pageData = filteredDokter.slice(start, end);

        if (pageData.length === 0) {
            renderEmptyDokterTable("Tidak ada hasil");
            return;
        }

        pageData.forEach((d, i) => {
            const isEven = i % 2 === 1;
            const row = `
      <tr class="transition ${isEven ? 'bg-gray-100' : ''} hover:bg-emerald-50">
        <td class="p-2 border">${d.kode_dokter}</td>
        <td class="p-2 border">${d.nama_dokter}</td>
        <td class="p-2 border">${d.spesialis}</td>
        <td class="p-2 border text-center">
          <button type="button"
            onclick="selectDokter('${d.kode_dokter}', '${d.nama_dokter}', '${d.spesialis}')"
            class="text-emerald-600 font-medium hover:underline">
            Pilih
          </button>
        </td>
      </tr>
    `;
            tbody.insertAdjacentHTML("beforeend", row);
        });

        if (pageData.length < 5) {
            tbody.insertAdjacentHTML("beforeend", `
      <tr><td colspan="4" class="invisible"><div class="h-48"></div></td></tr>
    `);
        }

        const totalPages = Math.ceil(filteredDokter.length / rowsPerPageDokter);
        document.getElementById("pageInfoDokter").innerText = `Halaman ${currentPageDokter} dari ${totalPages || 1}`;
        document.getElementById("prevPageDokterBtn").disabled = currentPageDokter === 1;
        document.getElementById("nextPageDokterBtn").disabled = currentPageDokter >= totalPages;
    }

    function handleSearchDokter() {
        const keywordKode = document.getElementById("searchKodeDokter").value.toLowerCase();
        const keywordNama = document.getElementById("searchNamaDokter").value.toLowerCase();

        filteredDokter = dokterList.filter((d) =>
            d.kode_dokter.toLowerCase().includes(keywordKode) &&
            d.nama_dokter.toLowerCase().includes(keywordNama)
        );

        currentPageDokter = 1;
        renderPageDokter(currentPageDokter);
    }

    document.getElementById("prevPageDokterBtn").addEventListener("click", () => {
        if (currentPageDokter > 1) {
            currentPageDokter--;
            renderPageDokter(currentPageDokter);
        }
    });

    document.getElementById("nextPageDokterBtn").addEventListener("click", () => {
        const totalPages = Math.ceil(filteredDokter.length / rowsPerPageDokter);
        if (currentPageDokter < totalPages) {
            currentPageDokter++;
            renderPageDokter(currentPageDokter);
        }
    });

    window.selectDokter = function(kode, nama, spesialis) {
        document.getElementById("kode_dokter").value = kode;
        document.getElementById("nama_dokter").value = nama;
        const spesialisInput = document.getElementById("spesialis");
        if (spesialisInput) spesialisInput.value = spesialis;
        closeModalDokter();
    };

    function closeModalDokter() {
        document.getElementById("modalDokter").classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
        document.getElementById("searchKodeDokter").value = "";
        document.getElementById("searchNamaDokter").value = "";
    }
</script>