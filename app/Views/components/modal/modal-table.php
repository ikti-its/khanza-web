<div id="<?= $modalId ?>" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-6 w-full max-w-4xl min-h-[32rem] max-h-[90vh] shadow-2xl flex flex-col">

        <!-- Header -->
        <div class="flex justify-between items-center mb-2 border-b pb-2">
            <h2 class="text-lg font-bold text-gray-800"><?= $modalTitle ?></h2>
            <button onclick="close_<?= $modalId ?>()" class="text-gray-400 hover:text-red-600 text-2xl font-bold leading-none">&times;</button>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <!-- Search Inputs -->
            <div class="flex gap-3 w-full md:w-auto flex-1">
                <?php foreach ($searchInputs as $input): ?>
                    <input type="text" id="<?= $input['id'] ?>" placeholder="<?= $input['placeholder'] ?>"
                        class="w-full md:w-64 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm">
                <?php endforeach; ?>
            </div>

            <!-- Aksi -->
            <div class="flex gap-2">
                <?php foreach ($actions as $action): ?>
                    <?php if ($action['type'] === 'button'): ?>
                        <button onclick="<?= $action['onclick'] ?>"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 shadow transition">
                            <?= isset($action['icon']) ? renderIcon($action['icon']) : '' ?>
                            <?= $action['text'] ?>
                        </button>
                    <?php elseif ($action['type'] === 'link'): ?>
                        <a href="<?= $action['href'] ?>" target="_blank"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] shadow transition">
                            <?= isset($action['icon']) ? renderIcon($action['icon']) : '' ?>
                            <?= $action['text'] ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tabel -->
        <div class="border rounded-md overflow-auto grow">
            <table class="w-full text-sm text-gray-700 border border-gray-200">
                <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                    <tr>
                        <?php foreach ($headers as $h): ?>
                            <th class="p-4 border text-center text-base"><?= $h ?></th>
                        <?php endforeach; ?>
                        <th class="p-4 border text-center text-base">Aksi</th>
                    </tr>
                </thead>
                <tbody id="<?= $tableId ?>" class="[&>tr:nth-child(even)]:bg-gray-50">
                    <!-- AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <button id="prevPageBtn_<?= $modalId ?>" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" disabled>
                Sebelumnya
            </button>
            <span id="pageInfo_<?= $modalId ?>" class="text-gray-500">Halaman 1</span>
            <button id="nextPageBtn_<?= $modalId ?>" class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200">
                Berikutnya
            </button>
        </div>

    </div>
</div>

<!-- Load helper hanya sekali di layout utama, bukan di setiap modal -->
<script src="<?= base_url('js/modal-helper.js') ?>"></script>