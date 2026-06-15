<?= view('components/modal/modal-table', [
    'modalId'      => 'modalMedisUtd',
    'modalTitle'   => 'Cari BHP Medis Ruangan UTD',
    'headers'      => ['Kode Barang', 'Nama Barang', 'Harga', 'Stok Ruangan'],
    'tableId'      => 'medisUtdTable',
    'searchInputs' => [
        ['id' => 'searchKodeMedis', 'placeholder' => 'Cari kode barang...'],
        ['id' => 'searchNamaMedis', 'placeholder' => 'Cari nama barang...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalMedisUtd()', 'icon' => 'refresh'],
        ['type' => 'button', 'text' => 'Masukkan Barang Terpilih', 'onclick' => 'submitModalChxMedis()', 'icon' => 'plus'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalMedisUtd',
            tableId: 'medisUtdTable',
            url:     '<?= site_url('logistik-utd/pengambilan-bhp-medis/modal/list') ?>',
            fields: ['kode_barang', 'nama_barang', 'harga_formatted', 'stok'],
            searchIds: {
                searchKodeMedis: 'kode_barang',
                searchNamaMedis: 'nama_barang'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                tambahBarisBhpRusak(item);
            }
        });

        const observer = new MutationObserver(() => {
            const tabelBody = document.getElementById('medisUtdTable');
            if (!tabelBody) return;
            
            tabelBody.querySelectorAll('tr').forEach(row => {
                const btnAksi = row.querySelector('td:last-child button');
                if (btnAksi && btnAksi.innerText.trim() === 'Pilih') {
                    const dataObjStr = btnAksi.getAttribute('data-json');
                    const itemData   = JSON.parse(dataObjStr);
                    
                    btnAksi.parentElement.innerHTML = `
                        <input type="checkbox" name="chx_medis_modal" 
                               value="${itemData.id_barang}" 
                               data-payload='${dataObjStr}'
                               class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    `;
                }
            });
        });

        const targetNode = document.getElementById('medisUtdTable');
        if (targetNode) {
            observer.observe(targetNode, { childList: true, subtree: true });
        }
    });

    function submitModalChxMedis() {
        const checkedBoxes = document.querySelectorAll('input[name="chx_medis_modal"]:checked');
        if (checkedBoxes.length === 0) {
            alert("Mohon centang minimal satu barang medis terlebih dahulu.");
            return;
        }

        checkedBoxes.forEach(cb => {
            const item = JSON.parse(cb.getAttribute('data-payload'));
            tambahBarisBhpRusak(item);
        });

        checkedBoxes.forEach(cb => cb.checked = false);

        if (typeof close_modalMedisUtd === 'function') {
            close_modalMedisUtd();
        }
    }
</script>