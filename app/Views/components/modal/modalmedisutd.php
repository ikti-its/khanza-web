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
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const textCount = document.getElementById('selectedCount_modalMedisUtd');
        if (textCount) {
            textCount.classList.remove('hidden');
        }

        const btnCancel = document.getElementById('btnCancelModal_modalMedisUtd');
        if (btnCancel) {
            btnCancel.classList.remove('hidden');
        }
        
        const btnSubmit = document.getElementById('btnSubmitModal_modalMedisUtd');
        if (btnSubmit) {
            btnSubmit.classList.remove('hidden');
            btnSubmit.innerText = "Tambahkan";
            btnSubmit.onclick = submitModalChxMedis;
        }

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
                               onchange="updateCountMedis()"
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

    function updateCountMedis() {
        const checkedCount = document.querySelectorAll('input[name="chx_medis_modal"]:checked').length;
        const textLabel = document.getElementById('selectedCount_modalMedisUtd');
        if (textLabel) {
            textLabel.textContent = `${checkedCount} item terpilih`;
        }
    }

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

        updateCountMedis();

        if (typeof close_modalMedisUtd === 'function') {
            close_modalMedisUtd();
        }
    }
</script>