<div id="modalItemPemeriksaanLab" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-6 w-full max-w-4xl min-h-[32rem] max-h-[90vh] shadow-2xl flex flex-col dark:bg-slate-900">

        <!-- Header -->
        <div class="flex justify-between items-center mb-2 border-b pb-2 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white" id="modalItemLabTitle">Pilih Item Pemeriksaan</h2>
            <button onclick="close_modalItemPemeriksaanLab()" class="text-gray-400 hover:text-red-600 text-2xl font-bold leading-none">&times;</button>
        </div>

        <!-- Search -->
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <div class="flex gap-3 w-full md:w-auto flex-1">
                <input type="text" id="searchKodeItemLab" placeholder="Cari kode periksa..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm dark:border-gray-600 dark:bg-slate-800 dark:text-white">
                <input type="text" id="searchNamaItemLab" placeholder="Cari nama item..."
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm dark:border-gray-600 dark:bg-slate-800 dark:text-white">
            </div>
        </div>

        <!-- Tabel -->
        <div class="border rounded-md overflow-auto grow">
            <table class="w-full text-sm text-gray-700 dark:text-gray-300 table-fixed">
                <colgroup>
                    <col class="w-10">
                    <col class="w-1/6">
                    <col class="w-1/2">
                    <col class="w-1/4">
                </colgroup>
                <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                    <tr>
                        <th class="p-4 border text-center w-10">
                            <input type="checkbox" id="checkAllItemLab" class="cursor-pointer">
                        </th>
                        <th class="p-4 border text-center">Kode Periksa</th>
                        <th class="p-4 border text-center">Nama Item</th>
                        <th class="p-4 border text-center">Tarif</th>
                    </tr>
                </thead>
                <tbody id="itemLabTableBody" class="[&>tr:nth-child(even)]:bg-gray-50 dark:[&>tr:nth-child(even)]:bg-slate-800">
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-400">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
            <button id="prevItemLabBtn" onclick="prevItemLabPage()"
                    class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50 dark:bg-slate-700 dark:hover:bg-slate-600" disabled>
                Sebelumnya
            </button>
            <span id="pageInfoItemLab" class="text-gray-500">Halaman 1</span>
            <button id="nextItemLabBtn" onclick="nextItemLabPage()"
                    class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600">
                Berikutnya
            </button>
        </div>

        <!-- Footer -->
        <div class="mt-2 flex justify-between items-center border-t pt-2 dark:border-gray-700">
            <span id="selectedItemLabCount" class="text-sm text-gray-500 dark:text-gray-400">0 item dipilih</span>
            <div class="flex gap-x-2">
                <button onclick="close_modalItemPemeriksaanLab()"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-slate-700 dark:text-white">
                    Batal
                </button>
                <button onclick="konfirmasi_modalItemPemeriksaanLab()"
                        class="px-4 py-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]">
                    Tambahkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let _itemLabAll       = [];
    let _itemLabFiltered  = [];
    let _itemLabSelected  = {}; // page-level state (item terpilih + parameter)
    let _itemLabStaged    = {}; // modal staging (checkbox saat modal terbuka)
    let _itemLabPage      = 1;
    let _itemLabKategori  = null; // id_kategori aktif saat modal dibuka
    const _itemLabPerPage = 10;

    /**
     * Buka modal item pemeriksaan lab.
     *
     * @param {number|string} idKategori  ID kategori lab (PK / PA / MB)
     * @param {string}        judulModal  Judul yang ditampilkan di header modal
     */
    function open_modalItemPemeriksaanLab(idKategori, judulModal = 'Pilih Item Pemeriksaan') {
        document.getElementById('modalItemPemeriksaanLab').classList.remove('hidden');
        document.getElementById('modalItemLabTitle').textContent = judulModal;

        // Jika kategori berubah, reset dan fetch ulang
        if (_itemLabKategori !== idKategori || _itemLabAll.length === 0) {
            _itemLabKategori = idKategori;
            _itemLabAll      = [];
            _itemLabStaged   = {};
            fetchItemLab();
        } else {
            renderItemLabTable();
        }
    }

    function close_modalItemPemeriksaanLab() {
        document.getElementById('modalItemPemeriksaanLab').classList.add('hidden');
    }

    function fetchItemLab() {
        const tbody = document.getElementById('itemLabTableBody');
        tbody.innerHTML = `<tr><td colspan="4" class="text-center p-4 text-gray-400">Memuat data...</td></tr>`;

        const url = _itemLabKategori
            ? `<?= site_url('laboratorium/referensi-item-pemeriksaan-lab/modal/list') ?>?id_kategori=${_itemLabKategori}`
            : `<?= site_url('laboratorium/referensi-item-pemeriksaan-lab/modal/list') ?>`;

        fetch(url)
            .then(res => res.json())
            .then(result => {
                _itemLabAll      = result.data || [];
                _itemLabFiltered = [..._itemLabAll];
                _itemLabPage     = 1;
                renderItemLabTable();
            })
            .catch(() => {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center p-4 text-red-500">Gagal memuat data</td></tr>`;
            });
    }

    function renderItemLabTable() {
        const tbody    = document.getElementById('itemLabTableBody');
        const start    = (_itemLabPage - 1) * _itemLabPerPage;
        const pageData = _itemLabFiltered.slice(start, start + _itemLabPerPage);

        if (pageData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center p-4 text-gray-400">Tidak ada data</td></tr>`;
            updateItemLabPagination();
            return;
        }

        tbody.innerHTML = pageData.map(item => {
            const checked = _itemLabStaged[item.id_item_lab] ? 'checked' : '';
            const tarif   = new Intl.NumberFormat('id-ID').format(item.tarif);
            return `
                <tr class="hover:bg-emerald-50 dark:hover:bg-slate-700 cursor-pointer"
                    onclick="toggleItemLab(${item.id_item_lab}, this)">
                    <td class="p-2 border text-center" onclick="event.stopPropagation()">
                        <input type="checkbox" data-id="${item.id_item_lab}"
                               ${checked}
                               onchange="toggleItemLab(${item.id_item_lab}, this.closest('tr'))"
                               class="cursor-pointer">
                    </td>
                    <td class="p-2 border text-center">${item.kode_periksa}</td>
                    <td class="p-2 border">${item.nama_item}</td>
                    <td class="p-2 border text-right">Rp ${tarif}</td>
                </tr>`;
        }).join('');

        updateItemLabPagination();
        updateSelectedItemLabCount();
    }

    function toggleItemLab(idItem, row) {
        const item     = _itemLabAll.find(i => String(i.id_item_lab) === String(idItem));
        const checkbox = row.querySelector(`input[data-id="${idItem}"]`);

        if (_itemLabStaged[idItem]) {
            delete _itemLabStaged[idItem];
            if (checkbox) checkbox.checked = false;
            row.classList.remove('bg-emerald-50', 'dark:bg-slate-700');
        } else {
            _itemLabStaged[idItem] = item;
            if (checkbox) checkbox.checked = true;
            row.classList.add('bg-emerald-50', 'dark:bg-slate-700');
        }
        updateSelectedItemLabCount();
    }

    function updateSelectedItemLabCount() {
        const count = Object.keys(_itemLabStaged).length;
        document.getElementById('selectedItemLabCount').textContent = `${count} item dipilih`;
    }

    function updateItemLabPagination() {
        const total = Math.ceil(_itemLabFiltered.length / _itemLabPerPage);
        document.getElementById('pageInfoItemLab').textContent  = `Halaman ${_itemLabPage} dari ${total || 1}`;
        document.getElementById('prevItemLabBtn').disabled       = _itemLabPage === 1;
        document.getElementById('nextItemLabBtn').disabled       = _itemLabPage >= total;
    }

    function prevItemLabPage() {
        if (_itemLabPage > 1) { _itemLabPage--; renderItemLabTable(); }
    }

    function nextItemLabPage() {
        const total = Math.ceil(_itemLabFiltered.length / _itemLabPerPage);
        if (_itemLabPage < total) { _itemLabPage++; renderItemLabTable(); }
    }

    document.addEventListener('DOMContentLoaded', function () {

        // Check all di halaman ini
        document.getElementById('checkAllItemLab').addEventListener('change', function () {
            const start    = (_itemLabPage - 1) * _itemLabPerPage;
            const pageData = _itemLabFiltered.slice(start, start + _itemLabPerPage);
            pageData.forEach(item => {
                if (this.checked) {
                    _itemLabStaged[item.id_item_lab] = item;
                } else {
                    delete _itemLabStaged[item.id_item_lab];
                }
            });
            renderItemLabTable();
        });

        // Search realtime
        ['searchKodeItemLab', 'searchNamaItemLab'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', function () {
                const kode = document.getElementById('searchKodeItemLab').value.toLowerCase();
                const nama = document.getElementById('searchNamaItemLab').value.toLowerCase();
                _itemLabFiltered = _itemLabAll.filter(i =>
                    i.kode_periksa.toLowerCase().includes(kode) &&
                    i.nama_item.toLowerCase().includes(nama)
                );
                _itemLabPage = 1;
                renderItemLabTable();
            });
        });
    });

    /**
     * Konfirmasi pilihan → callback ke halaman yang menggunakan modal ini.
     * Halaman wajib mendefinisikan fungsi `onItemLabSelected(selected)`.
     */
    function konfirmasi_modalItemPemeriksaanLab() {
        const selected = Object.values(_itemLabStaged);
        if (selected.length === 0) {
            alert('Pilih minimal satu item terlebih dahulu.');
            return;
        }
        if (typeof onItemLabSelected === 'function') {
            onItemLabSelected(selected);
        }
        close_modalItemPemeriksaanLab();
    }
</script>