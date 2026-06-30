<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">

        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200"><?= esc($judul) ?></h2>
        </div>

        <!-- Filter -->
        <div class="mb-4 flex flex-wrap gap-3 items-center">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">Poli:</label>
                <select id="filterPoli"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 bg-white dark:bg-slate-800 dark:border-gray-600 dark:text-white focus:ring-teal-500 focus:border-teal-500 min-w-[200px]"
                        onchange="loadData()">
                    <option value="">-- Semua Poli --</option>
                    <?php foreach ($unit_list as $unit) : ?>
                        <option value="<?= esc($unit['id_unit']) ?>"><?= esc($unit['nama_unit']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">Tanggal:</label>
                <input type="date" id="filterTanggal"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 bg-white dark:bg-slate-800 dark:border-gray-600 dark:text-white focus:ring-teal-500 focus:border-teal-500"
                       onchange="loadData()">
            </div>
            <span id="loadingIndicator" class="hidden text-sm text-gray-400 italic">Memuat...</span>
        </div>

        <!-- Filter Status -->
        <div class="mb-5 flex flex-wrap gap-2 items-center">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</span>
            <button onclick="setStatus('')"  id="btn-status-"  class="status-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors border-gray-300 bg-gray-100 text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">Semua</button>
            <button onclick="setStatus('1')" id="btn-status-1" class="status-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors border-gray-300 text-gray-600 dark:border-gray-600 dark:text-gray-400">Belum Diperiksa</button>
            <button onclick="setStatus('2')" id="btn-status-2" class="status-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors border-gray-300 text-gray-600 dark:border-gray-600 dark:text-gray-400">Sedang Diperiksa</button>
            <button onclick="setStatus('3')" id="btn-status-3" class="status-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors border-gray-300 text-gray-600 dark:border-gray-600 dark:text-gray-400">Selesai</button>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-xs uppercase">
                    <tr>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700 text-center">No.</th>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700 text-center">No. Registrasi</th>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700 text-center">No. RM</th>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700">Nama Pasien</th>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700">Dokter</th>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700">Poli Tujuan</th>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700 text-center">Status Poli</th>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700 text-center">Tgl. Registrasi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr id="emptyRow">
                        <td colspan="7" class="py-10 text-center text-gray-400 italic dark:text-gray-500">
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p id="totalCount" class="mt-3 text-xs text-gray-400 dark:text-gray-500"></p>
    </div>
</div>

<script>
    const modulPath  = '<?= esc($modul_path) ?>';
    let activeStatus = '';

    const statusConfig = {
        1: { label: 'Belum Diperiksa',  cls: 'text-red-600 dark:text-red-400' },
        2: { label: 'Sedang Diperiksa', cls: 'text-yellow-600 dark:text-yellow-400' },
        3: { label: 'Selesai',          cls: 'text-green-600 dark:text-green-400' },
    };

    const activeCls   = 'bg-[#0A2D27] text-[#ACF2E7] border-[#0A2D27]';
    const inactiveCls = 'bg-white text-gray-600 border-gray-300 dark:bg-slate-800 dark:border-gray-600 dark:text-gray-400';

    function setStatus(val) {
        activeStatus = val;
        document.querySelectorAll('.status-btn').forEach(btn => {
            btn.className = 'status-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors ' + inactiveCls;
        });
        const active = document.getElementById(`btn-status-${val}`);
        if (active) active.className = 'status-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors ' + activeCls;
        loadData();
    }

    function statusBadge(idStatus, namaStatus) {
        if (statusConfig[idStatus]) {
            const cfg = statusConfig[idStatus];
            return `<span class="text-xs font-medium ${cfg.cls}">${cfg.label}</span>`;
        }
        const nama = (namaStatus ?? '').toLowerCase();
        let cls = 'text-gray-500 dark:text-gray-400';
        if (nama.includes('belum'))        cls = 'text-red-600 dark:text-red-400';
        else if (nama.includes('sedang'))  cls = 'text-yellow-600 dark:text-yellow-400';
        else if (nama.includes('selesai')) cls = 'text-green-600 dark:text-green-400';
        return `<span class="text-xs font-medium ${cls}">${namaStatus ?? '-'}</span>`;
    }

    function formatTanggal(dtStr) {
        if (!dtStr) return '-';
        const d = new Date(dtStr);
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    async function loadData() {
        const idUnit   = document.getElementById('filterPoli').value;
        const tanggal  = document.getElementById('filterTanggal').value;
        const indicator = document.getElementById('loadingIndicator');
        const tbody     = document.getElementById('tableBody');
        const counter   = document.getElementById('totalCount');

        indicator.classList.remove('hidden');
        tbody.innerHTML = `<tr><td colspan="8" class="py-10 text-center text-gray-400 italic dark:text-gray-500">Memuat data...</td></tr>`;
        counter.textContent = '';

        try {
            const params = new URLSearchParams();
            if (idUnit)       params.set('id_unit', idUnit);
            if (tanggal)      params.set('tanggal', tanggal);
            if (activeStatus) params.set('status',  activeStatus);
            const res  = await fetch(`${modulPath}/modal/list?${params.toString()}`);
            const json = await res.json();
            renderTable(json.data ?? []);
        } catch {
            tbody.innerHTML = `<tr><td colspan="7" class="py-10 text-center text-red-400 italic">Gagal memuat data.</td></tr>`;
        } finally {
            indicator.classList.add('hidden');
        }
    }

    function renderTable(rows) {
        const tbody   = document.getElementById('tableBody');
        const counter = document.getElementById('totalCount');

        if (!rows.length) {
            tbody.innerHTML = `<tr><td colspan="8" class="py-10 text-center text-gray-400 italic dark:text-gray-500">Tidak ada data registrasi rawat jalan.</td></tr>`;
            counter.textContent = '';
            return;
        }

        tbody.innerHTML = rows.map((r, i) => `
            <tr class="${i % 2 === 0 ? '' : 'bg-gray-50 dark:bg-slate-800/50'} hover:bg-teal-50 dark:hover:bg-teal-900/10 transition-colors">
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center font-bold text-gray-500 dark:text-gray-400">${i + 1}</td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center font-mono text-xs">${r.nomor_reg ?? '-'}</td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center font-mono text-xs">${r.nomor_rm ?? '-'}</td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 font-medium">${r.nama_pasien ?? '-'}</td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700">${r.nama_dokter ?? '-'}</td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700">${r.nama_unit ?? '-'}</td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center">
                    ${statusBadge(r.id_status_poli, r.nama_status_poli)}
                </td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center text-xs">${formatTanggal(r.tanggal_reg)}</td>
            </tr>
        `).join('');

        counter.textContent = `Menampilkan ${rows.length} data registrasi rawat jalan.`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('filterTanggal').value = new Date().toISOString().split('T')[0];
        setStatus('');
    });
</script>

<?= $this->endSection(); ?>