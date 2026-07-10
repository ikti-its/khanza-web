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
                <input type="date" id="filterTanggalDari"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 bg-white dark:bg-slate-800 dark:border-gray-600 dark:text-white focus:ring-teal-500 focus:border-teal-500"
                       onchange="loadData()">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">s.d.</label>
                <input type="date" id="filterTanggalSampai"
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
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700 text-center">Nama Pasien</th>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700 text-center">Dokter</th>
                        <th class="p-3 border-b border-gray-200 dark:border-gray-700 text-center">Poli Tujuan</th>
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

<!-- Modal Skrining -->
<div id="modalSkrining" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all max-w-md sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-start py-3 px-4 border-b dark:border-neutral-700">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Hasil Skrining</p>
                    <h3 id="modalSkriningTitle" class="font-bold text-gray-800 dark:text-white"></h3>
                </div>
                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                        data-hs-overlay="#modalSkrining">
                    <span class="sr-only">Close</span>
                    <img src="<?= base_url('svg/form/popup_tombol_x.svg') ?>">
                </button>
            </div>
            <div class="p-4">
                <div id="modalSkriningBody"></div>
            </div>
        </div>
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
        const dari     = document.getElementById('filterTanggalDari').value;
        const sampai   = document.getElementById('filterTanggalSampai').value;
        const indicator = document.getElementById('loadingIndicator');
        const tbody     = document.getElementById('tableBody');
        const counter   = document.getElementById('totalCount');

        indicator.classList.remove('hidden');
        tbody.innerHTML = `<tr><td colspan="8" class="py-10 text-center text-gray-400 italic dark:text-gray-500">Memuat data...</td></tr>`;
        counter.textContent = '';

        try {
            const params = new URLSearchParams();
            if (idUnit)       params.set('id_unit',  idUnit);
            if (dari)         params.set('dari',     dari);
            if (sampai)       params.set('sampai',   sampai);
            if (activeStatus) params.set('status',   activeStatus);
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
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center font-medium">
                    <button onclick="showSkrining('${r.nomor_rm}', '${(r.nama_pasien ?? '').replace(/'/g, "\\'")}')"
                            class="text-left hover:underline hover:text-teal-700 dark:hover:text-teal-400 transition-colors">
                        ${r.nama_pasien ?? '-'}
                    </button>
                </td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center">${r.nama_dokter ?? '-'}</td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center">${r.nama_unit ?? '-'}</td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center">
                    ${statusBadge(r.id_status_poli, r.nama_status_poli)}
                </td>
                <td class="p-3 border-b border-gray-100 dark:border-gray-700 text-center text-xs">${formatTanggal(r.tanggal_reg)}</td>
            </tr>
        `).join('');

        counter.textContent = `Menampilkan ${rows.length} data registrasi rawat jalan.`;
    }

    // ── Skrining Modal ────────────────────────────────────────────
    async function showSkrining(noRm, namaPasien) {
        const title = document.getElementById('modalSkriningTitle');
        const body  = document.getElementById('modalSkriningBody');

        title.textContent = namaPasien;
        body.innerHTML = '<p class="text-sm text-gray-400 italic">Memuat...</p>';
        window.HSOverlay.open('#modalSkrining');

        try {
            const res  = await fetch(`${modulPath}/skrining?no_rm=${encodeURIComponent(noRm)}`);
            const json = await res.json();
            body.innerHTML = renderSkrining(json.data);
        } catch {
            body.innerHTML = '<p class="text-sm text-red-400 italic">Gagal memuat data skrining.</p>';
        }
    }

    function boolLabel(val) {
        const t = val === true || val === 't' || val === '1' || val === 1;
        return t
            ? '<span class="text-red-600 font-semibold">Ya</span>'
            : '<span class="text-gray-500">Tidak</span>';
    }

    function renderSkrining(d) {
        if (!d) return '<p class="text-sm text-gray-400 italic">Belum ada data skrining untuk pasien ini.</p>';

        const keputusan = d.skrining_keputusan ?? '-';
        const isIgd     = keputusan.toLowerCase().includes('igd') || keputusan.toLowerCase().includes('gawat');

        let dateStr = '-';
        if (d.tgl_skrining) {
            const dt = new Date(d.tgl_skrining);
            dateStr = dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            if (d.jam_skrining) dateStr += ', ' + d.jam_skrining.slice(0, 5);
        }

        const bannerBg     = isIgd ? '#fef2f2' : '#f0fdf4';
        const bannerBorder = isIgd ? '#fca5a5' : '#86efac';
        const bannerText   = isIgd ? '#991b1b' : '#166534';
        const bannerIcon   = isIgd ? '⚠' : '✓';

        const rowStyle = 'display:grid; grid-template-columns:110px 1fr; padding:5px 0; border-bottom:1px solid #f3f4f6;';
        const labelStyle = 'font-size:12px; color:#6b7280;';
        const valStyle   = 'font-size:12px; color:#111827; font-weight:500;';

        const klinisRows = [
            ['Kesadaran',   d.kesadaran      ?? '-'],
            ['Pernapasan',  d.pernafasan     ?? '-'],
            ['Skala Nyeri', d.skala_nyeri    ?? '-'],
            ['Nyeri Dada',  d.nyeri_dada     ?? '-'],
            ['Batuk',       d.kategori_batuk ?? '-'],
        ].map(([l, v]) => `<div style="${rowStyle}"><span style="${labelStyle}">${l}</span><span style="${valStyle}">${v}</span></div>`).join('');

        const risikoRows = [
            ['Geriatri',     boolLabel(d.is_geriatri)],
            ['Risiko Jatuh', boolLabel(d.is_risiko_jatuh)],
        ].map(([l, v]) => `<div style="${rowStyle}"><span style="${labelStyle}">${l}</span><span style="font-size:12px;">${v}</span></div>`).join('');

        const unitPart = d.nama_unit ? ` &middot; <span style="color:#111827; font-weight:500;">${d.nama_unit}</span>` : '';

        return `
            <p style="font-size:11px; color:#9ca3af; margin-bottom:10px;">${dateStr}</p>
            <div style="background:${bannerBg}; border:1.5px solid ${bannerBorder}; border-radius:8px; padding:10px 14px; margin-bottom:14px; display:flex; align-items:center; gap:8px;">
                <span style="font-size:16px; color:${bannerText}; line-height:1;">${bannerIcon}</span>
                <span style="font-size:13px; font-weight:700; color:${bannerText}; letter-spacing:0.03em;">${keputusan.toUpperCase()}</span>
            </div>
            <p style="font-size:10px; font-weight:700; letter-spacing:0.08em; color:#9ca3af; margin-bottom:4px;">PEMERIKSAAN KLINIS</p>
            <div style="margin-bottom:14px;">${klinisRows}</div>
            <p style="font-size:10px; font-weight:700; letter-spacing:0.08em; color:#9ca3af; margin-bottom:4px;">FAKTOR RISIKO</p>
            <div style="margin-bottom:14px;">${risikoRows}</div>
            <div style="border-top:1px solid #e5e7eb; padding-top:10px; font-size:12px; color:#6b7280;">
                Petugas: <span style="color:#111827; font-weight:500;">${d.nama_petugas ?? '-'}</span>${unitPart}
            </div>`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('filterTanggalDari').value   = today;
        document.getElementById('filterTanggalSampai').value = today;
        setStatus('');
    });
</script>

<?= $this->endSection(); ?>