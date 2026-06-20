<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPenunjangUtd',
    'modalTitle'   => 'Cari BHP Non Medis Ruangan UTD',
    'headers'      => ['Kode Barang', 'Nama Barang', 'Harga', 'Stok Ruangan'],
    'tableId'      => 'penunjangUtdTable',
    'searchInputs' => [
        ['id' => 'searchKodePenunjang', 'placeholder' => 'Cari kode barang...'],
        ['id' => 'searchNamaPenunjang', 'placeholder' => 'Cari nama barang...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPenunjangUtd()', 'icon' => 'refresh'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const textCount = document.getElementById('selectedCount_modalPenunjangUtd');
        if (textCount) {
            textCount.classList.remove('hidden');
        }

        const btnCancel = document.getElementById('btnCancelModal_modalPenunjangUtd');
        if (btnCancel) {
            btnCancel.classList.remove('hidden');
        }
        
        const btnSubmit = document.getElementById('btnSubmitModal_modalPenunjangUtd');
        if (btnSubmit) {
            btnSubmit.classList.remove('hidden');
            btnSubmit.innerText = "Tambahkan";
            btnSubmit.onclick = submitModalChxPenunjang;
        }

        initModalList({
            modalId: 'modalPenunjangUtd',
            tableId: 'penunjangUtdTable',
            url:     '<?= site_url('logistik-utd/pengambilan-bhp-non-medis/modal/list') ?>',
            fields: ['kode_barang', 'nama_barang', 'harga_formatted', 'stok'],
            searchIds: {
                searchKodePenunjang: 'kode_barang',
                searchNamaPenunjang: 'nama_barang'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                tambahBarisBhpPenunjangRusak(item);
            }
        });

        const observer = new MutationObserver(() => {
            const tabelBody = document.getElementById('penunjangUtdTable');
            if (!tabelBody) return;
            
            tabelBody.querySelectorAll('tr').forEach(row => {
                const btnAksi = row.querySelector('td:last-child button');
                if (btnAksi && btnAksi.innerText.trim() === 'Pilih') {
                    const dataObjStr = btnAksi.getAttribute('data-json');
                    const itemData   = JSON.parse(dataObjStr);
                    
                    btnAksi.parentElement.innerHTML = `
                        <input type="checkbox" name="chx_penunjang_modal" 
                               value="${itemData.id_barang}" 
                               data-payload='${dataObjStr}'
                               onchange="updateCountPenunjang()"
                               class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    `;
                }
            });
        });

        const targetNode = document.getElementById('penunjangUtdTable');
        if (targetNode) {
            observer.observe(targetNode, { childList: true, subtree: true });
        }
    });

    function updateCountPenunjang() {
        const checkedCount = document.querySelectorAll('input[name="chx_penunjang_modal"]:checked').length;
        const textLabel = document.getElementById('selectedCount_modalPenunjangUtd');
        if (textLabel) {
            textLabel.textContent = `${checkedCount} item terpilih`;
        }
    }

    function submitModalChxPenunjang() {
        const checkedBoxes = document.querySelectorAll('input[name="chx_penunjang_modal"]:checked');
        if (checkedBoxes.length === 0) {
            alert("Mohon centang minimal satu barang penunjang terlebih dahulu.");
            return;
        }

        checkedBoxes.forEach(cb => {
            const item = JSON.parse(cb.getAttribute('data-payload'));
            tambahBarisBhpPenunjangRusak(item);
        });

        checkedBoxes.forEach(cb => cb.checked = false);

        updateCountPenunjang();

        if (typeof close_modalPenunjangUtd === 'function') {
            close_modalPenunjangUtd();
        }
    }
</script>