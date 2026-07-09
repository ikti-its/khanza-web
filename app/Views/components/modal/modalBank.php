<?= view('components/modal/modal-table', [
    'modalId'      => 'modalBank',
    'modalTitle'   => 'Pilih Bank',
    'headers'      => ['Nama Bank'],
    'tableId'      => 'bankTable',
    'searchInputs' => [
        ['id' => 'searchNamaBank', 'placeholder' => 'Cari nama bank...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalBank()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalBank',
            tableId:     'bankTable',
            url:         '<?= site_url('finansial/bank/modal/list') ?>',
            fields:      ['nama_bank'],
            searchIds: {
                searchNamaBank: 'nama_bank',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillBank(item);
            },
        });
    });
</script>
