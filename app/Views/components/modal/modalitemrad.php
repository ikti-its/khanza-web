<div id="modalItemRad" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-6 w-full max-w-4xl min-h-[32rem] max-h-[90vh] shadow-2xl flex flex-col dark:bg-slate-900">

        <!-- Header -->
        <div class="flex justify-between items-center mb-2 border-b pb-2 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">Pilih Item Radiologi</h2>
            <button onclick="close_modalItemRad()" class="text-gray-400 hover:text-red-600 text-2xl font-bold leading-none">&times;</button>
        </div>

        <!-- Search -->
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <div class="flex gap-3 w-full md:w-auto flex-1">
                <input type="text" id="searchKodeItem" placeholder="Cari kode periksa..."
                    class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm dark:border-gray-600 dark:bg-slate-800 dark:text-white">
                <input type="text" id="searchNamaItem" placeholder="Cari nama pemeriksaan..."
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
                            <input type="checkbox" id="checkAllItem" class="cursor-pointer">
                        </th>
                        <th class="p-4 border text-center">Kode Periksa</th>
                        <th class="p-4 border text-center">Nama Pemeriksaan</th>
                        <th class="p-4 border text-center">Tarif Dasar</th>
                    </tr>
                </thead>
                <tbody id="itemRadTableBody" class="[&>tr:nth-child(even)]:bg-gray-50 dark:[&>tr:nth-child(even)]:bg-slate-800">
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-400">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
            <button id="prevItemBtn" onclick="prevItemPage()"
                    class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50 dark:bg-slate-700 dark:hover:bg-slate-600" disabled>
                Sebelumnya
            </button>
            <span id="pageInfoItem" class="text-gray-500">Halaman 1</span>
            <button id="nextItemBtn" onclick="nextItemPage()"
                    class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600">
                Berikutnya
            </button>
        </div>

        <!-- Footer -->
        <div class="mt-2 flex justify-between items-center border-t pt-2 dark:border-gray-700">
            <span id="selectedItemCount" class="text-sm text-gray-500 dark:text-gray-400">0 item dipilih</span>
            <div class="flex gap-x-2">
                <button onclick="close_modalItemRad()"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-slate-700 dark:text-white">
                    Batal
                </button>
                <button onclick="konfirmasi_modalItemRad()"
                        class="px-4 py-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]">
                    Tambahkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let _itemRadAll      = [];
    let _itemRadFiltered = [];
    let _itemRadSelected = {};
    let _itemRadPage     = 1;
    const _itemRadPerPage = 10;

    function open_modalItemRad() {
        document.getElementById('modalItemRad').classList.remove('hidden');
        if (_itemRadAll.length === 0) {
            fetchItemRad();
        } else {
            renderItemRadTable();
        }
    }

    function close_modalItemRad() {
        document.getElementById('modalItemRad').classList.add('hidden');
    }

    function fetchItemRad() {
        const tbody = document.getElementById('itemRadTableBody');
        tbody.innerHTML = `<tr><td colspan="4" class="text-center p-4 text-gray-400">Memuat data...</td></tr>`;

        fetch('/radiologi/referensi-item-radiologi/modal/list')
            .then(res => res.json())
            .then(result => {
                _itemRadAll      = result.data || [];
                _itemRadFiltered = [..._itemRadAll];
                _itemRadPage     = 1;
                renderItemRadTable();
            })
            .catch(() => {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center p-4 text-red-500">Gagal memuat data</td></tr>`;
            });
    }

    function renderItemRadTable() {
        const tbody    = document.getElementById('itemRadTableBody');
        const start    = (_itemRadPage - 1) * _itemRadPerPage;
        const pageData = _itemRadFiltered.slice(start, start + _itemRadPerPage);

        if (pageData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center p-4 text-gray-400">Tidak ada data</td></tr>`;
            updateItemRadPagination();
            return;
        }

        tbody.innerHTML = pageData.map(item => {
            const checked = _itemRadSelected[item.id_item] ? 'checked' : '';
            const tarif   = new Intl.NumberFormat('id-ID').format(item.tarif_dasar);
            return `
                <tr class="hover:bg-emerald-50 dark:hover:bg-slate-700 cursor-pointer"
                    onclick="toggleItemRad(${item.id_item}, this)">
                    <td class="p-2 border text-center" onclick="event.stopPropagation()">
                        <input type="checkbox" data-id="${item.id_item}"
                               ${checked}
                               onchange="toggleItemRad(${item.id_item}, this.closest('tr'))"
                               class="cursor-pointer">
                    </td>
                    <td class="p-2 border text-center">${item.kode_periksa}</td>
                    <td class="p-2 border">${item.nama_pemeriksaan}</td>
                    <td class="p-2 border text-right">Rp ${tarif}</td>
                </tr>`;
        }).join('');

        updateItemRadPagination();
        updateSelectedCount();
    }

    function toggleItemRad(idItem, row) {
        const item     = _itemRadAll.find(i => String(i.id_item) === String(idItem));
        const checkbox = row.querySelector(`input[data-id="${idItem}"]`);

        if (_itemRadSelected[idItem]) {
            delete _itemRadSelected[idItem];
            if (checkbox) checkbox.checked = false;
            row.classList.remove('bg-emerald-50', 'dark:bg-slate-700');
        } else {
            _itemRadSelected[idItem] = item;
            if (checkbox) checkbox.checked = true;
            row.classList.add('bg-emerald-50', 'dark:bg-slate-700');
        }
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const count = Object.keys(_itemRadSelected).length;
        document.getElementById('selectedItemCount').textContent = `${count} item dipilih`;
    }

    function updateItemRadPagination() {
        const total = Math.ceil(_itemRadFiltered.length / _itemRadPerPage);
        document.getElementById('pageInfoItem').textContent = `Halaman ${_itemRadPage} dari ${total || 1}`;
        document.getElementById('prevItemBtn').disabled = _itemRadPage === 1;
        document.getElementById('nextItemBtn').disabled = _itemRadPage >= total;
    }

    function prevItemPage() {
        if (_itemRadPage > 1) { _itemRadPage--; renderItemRadTable(); }
    }

    function nextItemPage() {
        const total = Math.ceil(_itemRadFiltered.length / _itemRadPerPage);
        if (_itemRadPage < total) { _itemRadPage++; renderItemRadTable(); }
    }

    // Check all di halaman ini
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('checkAllItem').addEventListener('change', function () {
            const start    = (_itemRadPage - 1) * _itemRadPerPage;
            const pageData = _itemRadFiltered.slice(start, start + _itemRadPerPage);
            pageData.forEach(item => {
                if (this.checked) {
                    _itemRadSelected[item.id_item] = item;
                } else {
                    delete _itemRadSelected[item.id_item];
                }
            });
            renderItemRadTable();
        });

        // Search realtime
        ['searchKodeItem', 'searchNamaItem'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', function () {
                const kode = document.getElementById('searchKodeItem').value.toLowerCase();
                const nama = document.getElementById('searchNamaItem').value.toLowerCase();
                _itemRadFiltered = _itemRadAll.filter(i =>
                    i.kode_periksa.toLowerCase().includes(kode) &&
                    i.nama_pemeriksaan.toLowerCase().includes(nama)
                );
                _itemRadPage = 1;
                renderItemRadTable();
            });
        });
    });

    // Konfirmasi pilihan → render ke tabel di halaman
    function konfirmasi_modalItemRad() {
        const selected = Object.values(_itemRadSelected);
        if (selected.length === 0) {
            alert('Pilih minimal satu item terlebih dahulu.');
            return;
        }
        if (typeof renderItemRadTerpilih === 'function') {
            renderItemRadTerpilih(selected);
        }
        close_modalItemRad();
    }
</script>