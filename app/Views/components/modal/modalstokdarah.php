<?= view('components/modal/modal-table', [
    'modalId'      => 'modalStokDarah',
    'modalTitle'   => 'Cari Stok Kantong Darah',
    'headers'      => ['No. Kantong', 'Komponen Darah', 'Gol. Darah', 'Rhesus', 'Kadaluwarsa'],
    'tableId'      => 'stokDarahTable',
    'searchInputs' => [
        ['id' => 'searchNoKantong',   'placeholder' => 'Cari no. kantong...'],
        ['id' => 'searchNamaKomponen', 'placeholder' => 'Cari komponen...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalStokDarah()', 'icon' => 'refresh'],
        ['type' => 'button', 'text' => 'Masukkan Darah Terpilih', 'onclick' => 'submitModalChxDarah()', 'icon' => 'plus'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalStokDarah',
            tableId: 'stokDarahTable',
            url:     '<?= site_url('inventori-darah/stok-darah/modal/list') ?>', // Sesuaikan endpoint pencarian stok darah kelompokmu
            fields: ['no_kantong', 'nama_komponen', 'gol_darah', 'rhesus', 'tanggal_kadaluarsa'],
            searchIds: {
                searchNoKantong: 'no_kantong',
                searchNamaKomponen: 'nama_komponen'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                tambahBarisDarahPenyerahan(item);
            }
        });

        const observerDarah = new MutationObserver(() => {
            const tabelBody = document.getElementById('stokDarahTable');
            if (!tabelBody) return;
            
            tabelBody.querySelectorAll('tr').forEach(row => {
                const btnAksi = row.querySelector('td:last-child button');
                if (btnAksi && btnAksi.innerText.trim() === 'Pilih') {
                    const dataObjStr = btnAksi.getAttribute('data-json');
                    const itemData   = JSON.parse(dataObjStr);
                    
                    btnAksi.parentElement.innerHTML = `
                        <input type="checkbox" name="chx_darah_modal" 
                               value="${itemData.id_stok_darah}" 
                               data-payload='${dataObjStr}'
                               class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    `;
                }
            });
        });

        const targetNodeDarah = document.getElementById('stokDarahTable');
        if (targetNodeDarah) {
            observerDarah.observe(targetNodeDarah, { childList: true, subtree: true });
        }
    });

    function submitModalChxDarah() {
        const checkedBoxes = document.querySelectorAll('input[name="chx_darah_modal"]:checked');
        if (checkedBoxes.length === 0) {
            alert("Mohon centang minimal satu kantong darah terlebih dahulu.");
            return;
        }

        checkedBoxes.forEach(cb => {
            const item = JSON.parse(cb.getAttribute('data-payload'));
            tambahBarisDarahPenyerahan(item);
        });

        // Bersihkan centang
        checkedBoxes.forEach(cb => cb.checked = false);

        if (typeof close_modalStokDarah === 'function') {
            close_modalStokDarah();
        }
    }
</script>