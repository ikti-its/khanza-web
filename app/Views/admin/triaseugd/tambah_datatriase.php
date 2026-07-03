<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<input type="hidden" id="modalRegistrasiUgdFilterType" value="belum_ditriase">

<?= $this->include('components/modal/modalregistrasiugd') ?>
<?= $this->include('components/modal/modaltriasemacamkasus') ?>
<?= $this->include('components/modal/modalpetugas') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_registrasi" id="id_registrasi" value="<?= $baris['id_registrasi'] ?? '' ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Rawat<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nomor_rawat" name="nomor_rawat" readonly required
                           value="<?= $baris['nomor_rawat'] ?? '' ?>" placeholder="Klik cari..."
                           onclick="open_modalRegistrasiUgd()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    
                    <button type="button" onclick="open_modalRegistrasiUgd()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nomor Rekam Medis
                </label>
                <input type="text" id="nomor_rm" name="nomor_rm" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['nomor_rm'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nama Pasien
                </label>
                <input type="text" id="nama_pasien" name="nama_pasien" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['nama_pasien'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Umur
                </label>
                <input type="text" id="umur_pasien" name="umur_pasien" readonly placeholder="Terisi otomatis..."
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tanggal Kunjungan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="datetime-local" name="tanggal_kunjungan" id="tanggal_kunjungan" readonly
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed" required>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Cara Masuk<span class="text-red-600">*</span>
                </label>
                <select name="id_cara_masuk" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsCaraMasuk = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_cara_masuk') {
                            $optionsCaraMasuk = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsCaraMasuk as $opt) :
                        $selected = ((string)($baris['id_cara_masuk'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Alat Transportasi<span class="text-red-600">*</span>
                </label>
                <select name="id_alat_transportasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsTransport = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_alat_transportasi') {
                            $optionsTransport = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsTransport as $opt) :
                        $selected = ((string)($baris['id_alat_transportasi'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Alasan Kedatangan<span class="text-red-600">*</span>
                </label>
                <select name="id_alasan_kedatangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsAlasan = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_alasan_kedatangan') {
                            $optionsAlasan = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsAlasan as $opt) :
                        $selected = ((string)($baris['id_alasan_kedatangan'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-8 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Keterangan<span class="text-red-600">*</span>
                </label>
                <input type="text" name="keterangan_kedatangan" value="<?= $baris['keterangan_kedatangan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Macam Kasus<span class="text-red-600">*</span>
                </label>
                <input type="hidden" name="id_macam_kasus" id="id_macam_kasus" value="<?= $baris['id_macam_kasus'] ?? '' ?>" required>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nama_macam_kasus" name="nama_macam_kasus" readonly required
                           value="<?= $baris['nama_macam_kasus'] ?? '' ?>" placeholder="Klik cari..."
                           onclick="open_modalTriaseMacamKasus()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    
                    <button type="button" onclick="open_modalTriaseMacamKasus()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mt-8 border border-gray-200 rounded-xl p-4 bg-white dark:bg-slate-900 dark:border-gray-800 shadow-sm">
                
                <div class="flex flex-row justify-between items-center pb-4 mb-6 w-full">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center gap-x-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Pemeriksaan Fisik Klinis Triase
                    </h3>
                                    
                    <div class="flex justify-end">
                        <div class="inline-flex p-1 bg-gray-100 rounded-xl dark:bg-slate-800">
                            <button type="button" id="tabPrimer" onclick="switchTriaseTab('primer')" 
                                class="px-4 py-2 text-sm font-bold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]">
                                Triase Primer
                            </button>
                            <button type="button" id="tabSekunder" onclick="switchTriaseTab('sekunder')"
                                class="px-4 py-2 text-sm font-bold rounded-lg text-gray-900 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition-all outline-none">
                                Triase Sekunder
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    
                    <input type="hidden" name="triase_tab_aktif" id="triase_tab_aktif" value="primer">

                    <div id="rowPrimerSpesifik" class="mb-5 sm:block md:flex items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4 flex-shrink-0">
                            Keluhan Utama<span class="text-red-600">*</span>
                        </label>
                        <div class="w-full md:flex-1">
                            <textarea name="keluhan_utama" id="keluhan_utama" rows="3"
                                      class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:bg-slate-800 dark:text-white resize-y focus:outline-none focus:border-blue-500" required><?= $baris['keluhan_utama'] ?? '' ?></textarea>
                        </div>
                    </div>

                    <div id="rowSekunderSpesifik" class="hidden sm:hidden md:hidden mb-5 items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4 flex-shrink-0">
                            Anamnesa Singkat<span class="text-red-600">*</span>
                        </label>
                        <div class="w-full md:flex-1">
                            <textarea name="anamnesa_singkat" id="anamnesa_singkat" rows="3"
                                      class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:bg-slate-800 dark:text-white resize-y focus:outline-none focus:border-blue-500"><?= $baris['anamnesa_singkat'] ?? '' ?></textarea>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-200 pt-5 mt-5 dark:border-gray-700">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Tanda-Tanda Vital Pasien (Vital Signs)</h4>
                    </div>

                    <div class="mb-5 sm:block md:flex items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                            Tensi (mmHg)<span class="text-red-600">*</span>
                        </label>
                        <div class="w-full lg:w-1/4 flex items-center gap-x-2">
                            <input type="number" name="sistolik" id="sistolik" placeholder="Sistolik" min="70" max="250" value="<?= $baris['sistolik'] ?? '' ?>"
                                   class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full text-center dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                            <span class="text-gray-400 font-medium">/</span>
                            <input type="number" name="diastolik" id="diastolik" placeholder="Diastolik" min="40" max="150" value="<?= $baris['diastolik'] ?? '' ?>"
                                   class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full text-center dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                        </div>

                        <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                            Nadi (x/mnt)<span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="nadi" id="nadi" min="30" max="200" value="<?= $baris['nadi'] ?? '' ?>"
                               class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    </div>

                    <div class="mb-5 sm:block md:flex items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                            Respirasi (x/mnt)<span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="pernapasan" id="pernapasan" min="10" max="60" value="<?= $baris['pernapasan'] ?? '' ?>"
                               class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>

                        <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                            Suhu (°C)<span class="text-red-600">*</span>
                        </label>
                        <input type="number" step="0.1" name="suhu" id="suhu" min="30.0" max="43.0" value="<?= $baris['suhu'] ?? '' ?>"
                               class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    </div>

                    <div class="mb-5 sm:block md:flex items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                            Saturasi O2 (%)<span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="saturasi_o2" id="saturasi_o2" min="50" max="100" value="<?= $baris['saturasi_o2'] ?? '' ?>"
                               class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>

                        <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                            Skala Nyeri<span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="nyeri" id="nyeri" min="0" max="10" placeholder="0-10" value="<?= $baris['nyeri'] ?? '' ?>"
                               class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    </div>

                    <div id="rowKebutuhanKhususContainer" class="mb-5 sm:block md:flex items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                            Kebutuhan Khusus<span class="text-red-600">*</span>
                        </label>
                        <div class="w-full lg:w-1/4">
                            <select name="id_kebutuhan_khusus" id="id_kebutuhan_khusus" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:bg-slate-800 dark:text-white" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                $optionsKebutuhan = [];
                                foreach ($konfig as $field) {
                                    if ($field[2] === 'id_kebutuhan_khusus') { $optionsKebutuhan = $field[5] ?? []; break; }
                                }
                                foreach ($optionsKebutuhan as $opt) :
                                    $selected = ((string)($baris['id_kebutuhan_khusus'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                                ?>
                                    <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="w-1/5 md:ml-10"></div>
                        <div class="w-full lg:w-1/4"></div>
                    </div>

                    <div class="border-t border-dashed border-gray-200 pt-5 mt-5 dark:border-gray-700">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Indikator Penilaian Skor Triase</h4>
                    </div>

                    <div id="hiddenInputsContainer"></div>

                    <div class="flex flex-row gap-x-4 items-start w-full bg-white dark:bg-slate-900">
                        
                        <div class="w-1/2 flex flex-col">
                            <div class="overflow-hidden border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm bg-white dark:bg-slate-900">
                                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700 text-left">
                                    <thead class="bg-gray-100 dark:bg-slate-800">
                                        <tr>
                                            <th scope="col" class="px-3 py-3 text-center text-sm font-bold text-gray-900 dark:text-gray-300 tracking-wider border-b border-gray-200 dark:border-gray-700">
                                                Pemeriksaan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-slate-900" id="tablePemeriksaanBody">
                                        <?php if (!empty($master_pemeriksaan)): ?>
                                            <?php foreach ($master_pemeriksaan as $index => $pem): ?>
                                                <tr onclick="selectPemeriksaanRow('<?= $pem['id_pemeriksaan'] ?>', this)" 
                                                    class="pemeriksaan-row cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-slate-800/50">
                                                    <td class="px-3 py-2 text-sm font-medium text-gray-900 dark:text-gray-300 uppercase">
                                                        <?= esc($pem['nama_pemeriksaan']) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td class="px-3 py-4 text-xs text-gray-400 italic text-center">Data pemeriksaan kosong.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-2 flex items-center gap-x-2 px-1">
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Key Word :</span>
                                <input type="text" id="searchPemeriksaan" onkeyup="filterPemeriksaan()" 
                                       class="border border-gray-300 dark:border-gray-700 text-xs rounded-full px-3 py-1 w-44 focus:outline-none focus:border-blue-500 dark:bg-slate-800 dark:text-white">
                                
                                <button type="button"
                                       onclick="window.location.href='<?= site_url('triase-ugd/triase-pemeriksaan/tambah?redirect_to=') ?>' + encodeURIComponent(window.location.pathname + window.location.search)"
                                       title="Tambah Triase Pemeriksaan"
                                       class="text-lime-600 hover:text-lime-700 p-1">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="w-1/2 flex flex-col">
                            <div class="overflow-hidden border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm bg-white dark:bg-slate-900">
                                <div class="flex w-full bg-gray-100 rounded-t-lg p-1 border-b border-gray-200 dark:bg-slate-800 dark:border-gray-700">
                                    <button type="button" id="tabSkala1" onclick="switchSkalaTab(1)"
                                        class="flex-1 text-center py-2 text-sm font-bold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] outline-none transition-all">
                                        Skala 1
                                    </button>
                                    <button type="button" id="tabSkala2" onclick="switchSkalaTab(2)"
                                        class="flex-1 text-center py-2 text-sm font-bold rounded-lg text-gray-900 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white outline-none transition-all">
                                        Skala 2
                                    </button>

                                    <button type="button" id="tabSkala3" onclick="switchSkalaTab(3)"
                                        class="hidden flex-1 text-center py-2 text-sm font-bold text-teal-950 outline-none transition-all">
                                        Skala 3
                                    </button>
                                    <button type="button" id="tabSkala4" onclick="switchSkalaTab(4)"
                                        class="hidden flex-1 text-center py-2 text-sm font-bold text-teal-950 outline-none transition-all border-l border-gray-300">
                                        Skala 4
                                    </button>
                                    <button type="button" id="tabSkala5" onclick="switchSkalaTab(5)"
                                        class="hidden flex-1 text-center py-2 text-sm font-bold text-teal-950 outline-none transition-all border-l border-gray-300">
                                        Skala 5
                                    </button>
                                </div>

                                <table class="min-w-full text-left">
                                    <thead class="border-b border-gray-200 dark:border-gray-700">
                                        <tr>
                                            <th id="skalaHeaderTitle" class="px-3 py-1.5 text-sm font-semibold text-red-600 dark:text-red-400 tracking-wider text-center bg-red-50/50 dark:bg-red-950/10">
                                                Immediate/Segera
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-100 dark:divide-gray-800" id="triaseContentContainer">
                                        </tbody>
                                </table>
                            </div>

                            <div class="mt-2 flex items-center gap-x-2 px-1">
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Key Word :</span>
                                <input type="text" id="searchSkala" onkeyup="filterSkalaIndikator()" 
                                       class="border border-gray-300 dark:border-gray-700 text-xs rounded-full px-3 py-1 w-44 focus:outline-none focus:border-red-500 dark:bg-slate-800 dark:text-white">
                                <button type="button"
                                       onclick="window.location.href='<?= site_url('triase-ugd/triase-skala/tambah?redirect_to=') ?>' + encodeURIComponent(window.location.pathname + window.location.search)"
                                       title="Tambah Triase Skala"
                                       class="text-lime-600 hover:text-lime-700 p-1">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="border-t border-dashed border-gray-200 pt-5 mt-6 dark:border-gray-700 space-y-5">
                        
                        <div class="mb-5 sm:block md:flex items-center">
                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                                Catatan<span class="text-red-600">*</span>
                            </label>
                            <div class="w-full lg:w-1/4">
                                <input type="text" name="catatan" id="catatan"
                                       value="<?= $baris['catatan'] ?? '' ?>"
                                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:bg-slate-800 dark:text-white focus:outline-none focus:border-blue-500" required>
                            </div>

                            <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                                Plan/Keputusan<span class="text-red-600">*</span>
                            </label>
                            <div class="w-full lg:w-1/4 flex items-center gap-x-6 h-[38px]">
                                
                                <div id="container_plan_primer" class="flex items-center gap-x-6">
                                    <?php
                                    $optionsPlanPrimer = [];
                                    foreach ($konfig as $field) {
                                        if ($field[2] === 'id_plan_primer') { $optionsPlanPrimer = $field[5] ?? []; break; }
                                    }
                                    $currentPlanId = (string)($baris['id_plan_primer'] ?? '');
                                    foreach ($optionsPlanPrimer as $index => $opt): 
                                        $valOption = (string)$opt[1];
                                        $textOption = $opt[0];
                                        $isChecked = ($currentPlanId !== '') ? ($currentPlanId === $valOption ? 'checked' : '') : ($index === 0 ? 'checked' : '');
                                    ?>
                                        <label class="inline-flex items-center gap-x-2 cursor-pointer">
                                            <input type="radio" name="id_plan_primer" value="<?= $valOption ?>" <?= $isChecked ?>
                                                   class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?= esc($textOption) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>

                                <div id="container_plan_sekunder" class="hidden flex items-center gap-x-6">
                                    <?php
                                    $optionsPlanSekunder = [];
                                    foreach ($konfig as $field) {
                                        if ($field[2] === 'id_plan_sekunder') { $optionsPlanSekunder = $field[5] ?? []; break; }
                                    }
                                    $currentPlanSekunderId = (string)($baris['id_plan_sekunder'] ?? '');
                                    foreach ($optionsPlanSekunder as $index => $opt): 
                                        $valOption = (string)$opt[1];
                                        $textOption = $opt[0];
                                        $isChecked = ($currentPlanSekunderId !== '') ? ($currentPlanSekunderId === $valOption ? 'checked' : '') : ($index === 0 ? 'checked' : '');
                                    ?>
                                        <label class="inline-flex items-center gap-x-2 cursor-pointer">
                                            <input type="radio" name="id_plan_sekunder" value="<?= $valOption ?>" <?= $isChecked ?>
                                                   class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?= esc($textOption) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        </div>

                        <div class="mb-5 sm:block md:flex items-center">
                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                                Tanggal Triase<span class="text-red-600">*</span>
                            </label>
                            <div class="w-full lg:w-1/4">
                                <input type="datetime-local" name="tanggal_triase" id="tanggal_triase" 
                                       value="<?= $baris['tanggal_triase'] ?? date('Y-m-d\TH:i:s') ?>"
                                       min="<?= date('Y-m-d\T00:00') ?>"
                                       max="<?= date('Y-m-d\TH:i:s') ?>"
                                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:bg-slate-800 dark:text-white focus:outline-none focus:border-blue-500">
                            </div>

                            <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                                Dokter/Petugas IGD<span class="text-red-600">*</span>
                            </label>
                            <input type="hidden" name="id_petugas" id="id_petugas" value="<?= $baris['id_petugas'] ?? '' ?>" required>
                            <div class="w-full lg:w-1/4 flex gap-x-2">
                                <input type="text" id="nama_petugas" name="nama_petugas" readonly required placeholder="Klik cari..."
                                       value="<?= $baris['nama_petugas'] ?? '' ?>" onclick="open_modalPetugas()"
                                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                                <button type="button" onclick="open_modalPetugas()"
                                        class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    // =========================================================================
    // 1. INITIALIZATION & GLOBAL STATE MANAGEMENT
    // =========================================================================
    document.addEventListener("DOMContentLoaded", function() {
        const registrasiId = "<?= $baris['id_registrasi'] ?? '' ?>";
        if (registrasiId !== '') {
            const savedReg = {
                id_registrasi: registrasiId,
                nomor_rawat: "<?= $baris['nomor_rawat'] ?? '' ?>",
                nomor_rm: "<?= $baris['nomor_rm'] ?? '' ?>",
                nama_pasien: "<?= $baris['nama_pasien'] ?? '' ?>",
                tanggal_lahir: "<?= $baris['tanggal_lahir'] ?? '' ?>",
                tanggal_reg: "<?= $baris['tanggal_kunjungan'] ?? '' ?>"
            };
            autofillRegistrasiUgd(savedReg);
        }

        const kasusId = "<?= $baris['id_macam_kasus'] ?? '' ?>";
        if (kasusId !== '') {
            const savedItem = {
                id_macam_kasus: kasusId,
                nama_macam_kasus: "<?= $baris['nama_macam_kasus'] ?? '' ?>"
            };
            autofillMacamKasus(savedItem);
        }

        const petugasId = "<?= $baris['id_petugas'] ?? '' ?>";
        if (petugasId !== '') {
            const savedItem = {
                id_petugas: petugasId,
                nama: "<?= $baris['nama_petugas'] ?? '' ?>"
            };
            autofillPetugas(savedItem);
        }

        const jenisTabLama = "<?= $tab_aktif_lama ?? 'primer' ?>";
        switchTriaseTab(jenisTabLama);

        const currentEditTriaseId = "<?= $baris['id_triase'] ?? '' ?>";

        const arraySkalaLamaRaw = <?= json_encode($detail_triase_lama ?? []) ?>;
        
        checkedSkalaIds = [];
        if (arraySkalaLamaRaw && arraySkalaLamaRaw.length > 0) {
            arraySkalaLamaRaw.forEach(item => {
                if (String(item.id_triase) !== String(currentEditTriaseId)) {
                    return;
                }
                checkedSkalaIds.push(String(item.id_skala));
            });
            
            syncHiddenInputs();
        }

        const cekJudulHalaman = "<?= $judul ?? '' ?>";
        const isModeEdit = cekJudulHalaman.includes('Ubah') || cekJudulHalaman.includes('Edit');
        if (isModeEdit) {
            const firstRow = document.querySelector('.pemeriksaan-row');
            if (firstRow) {
                firstRow.click(); 
            } else {
                renderIndikatorDetail();
            }
        } else {
            renderIndikatorDetail();
        }
    });

    const masterSkalaRaw = <?= json_encode($master_skala ?? []) ?>;
    let currentCategory = "";
    let currentSkala = 1;
    let currentTriaseTab = 'primer'; 
    let checkedSkalaIds = [];

    // =========================================================================
    // 2. CORE LAYOUT CONTROL & RENDERING LOGIC
    // =========================================================================
    /**
     * Mengatur perpindahan tab panel Triase UGD
     */
    function switchTriaseTab(type) {
        currentTriaseTab = type;
        
        document.getElementById('triase_tab_aktif').value = type;

        const tabPrimer = document.getElementById('tabPrimer');
        const tabSekunder = document.getElementById('tabSekunder');
        const rowPrimer = document.getElementById('rowPrimerSpesifik');
        const rowSekunder = document.getElementById('rowSekunderSpesifik');
        const rowKebutuhan = document.getElementById('rowKebutuhanKhususContainer');
        
        const planPrimer   = document.getElementById('container_plan_primer');
        const planSekunder = document.getElementById('container_plan_sekunder');

        const s1 = document.getElementById('tabSkala1');
        const s2 = document.getElementById('tabSkala2');
        const s3 = document.getElementById('tabSkala3');
        const s4 = document.getElementById('tabSkala4');
        const s5 = document.getElementById('tabSkala5');

        const tabInaktif = "px-4 py-2 text-sm font-bold rounded-lg text-gray-900 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition-all outline-none";
        const tabAktif = "px-4 py-2 text-sm font-bold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]";

        if (type === 'primer') {
            tabPrimer.className = tabAktif;
            tabSekunder.className = tabInaktif;
            rowPrimer.className = "mb-5 sm:block md:flex items-center";
            rowSekunder.className = "hidden";
            rowKebutuhan.className = "mb-5 sm:block md:flex items-center";
            
            planPrimer.classList.remove('hidden');
            planSekunder.classList.add('hidden');
            
            document.getElementById('keluhan_utama').setAttribute('required', 'required');
            document.getElementById('id_kebutuhan_khusus').setAttribute('required', 'required');
            document.getElementById('anamnesa_singkat').removeAttribute('required');

            s1.style.display = "";
            s2.style.display = "";
            s3.style.display = "none";
            s4.style.display = "none";
            s5.style.display = "none";

            switchSkalaTab(1);
        } else {
            tabSekunder.className = tabAktif;
            tabPrimer.className = tabInaktif;
            rowSekunder.className = "mb-5 sm:block md:flex items-center";
            rowPrimer.className = "hidden";
            rowKebutuhan.className = "hidden";
            
            planPrimer.classList.add('hidden');
            planSekunder.classList.remove('hidden');
            
            document.getElementById('anamnesa_singkat').setAttribute('required', 'required');
            document.getElementById('keluhan_utama').removeAttribute('required');
            document.getElementById('id_kebutuhan_khusus').removeAttribute('required');

            s1.style.display = "none";
            s2.style.display = "none";
            s3.style.display = "";
            s4.style.display = "";
            s5.style.display = "";

            switchSkalaTab(3);
        }
    }

    /**
     * Mengatur Perpindahan Tab Skala Kanan
     */
    function switchSkalaTab(skalaNum) {
        currentSkala = skalaNum;
        const headerTitle = document.getElementById('skalaHeaderTitle');

        const semuaTab = {
            1: document.getElementById('tabSkala1'),
            2: document.getElementById('tabSkala2'),
            3: document.getElementById('tabSkala3'),
            4: document.getElementById('tabSkala4'),
            5: document.getElementById('tabSkala5')
        };

        const activeClass = "flex-1 text-center py-2 text-sm font-bold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] outline-none transition-all";
        const inactiveClass = "flex-1 text-center py-2 text-sm font-bold text-gray-900 hover:text-gray-800 outline-none transition-all";

        if (currentTriaseTab === 'primer') {
            for (let num of [1, 2]) {
                if (semuaTab[num]) {
                    semuaTab[num].className = inactiveClass;
                    semuaTab[num].style.backgroundColor = "";
                    if (num === 2) semuaTab[num].classList.add('border-l', 'border-gray-300');
                }
            }
        } else {
            for (let num of [3, 4, 5]) {
                if (semuaTab[num]) {
                    semuaTab[num].className = inactiveClass;
                    semuaTab[num].style.backgroundColor = "";
                    if (num === 4 || num === 5) semuaTab[num].classList.add('border-l', 'border-gray-300');
                }
            }
        }

        if (semuaTab[skalaNum]) {
            semuaTab[skalaNum].className = activeClass;
            semuaTab[skalaNum].style.backgroundColor = "";
        }

        headerTitle.style.color = ""; 

        if (parseInt(skalaNum) === 1) {
            headerTitle.className = "px-3 py-1.5 text-sm font-semibold text-red-600 dark:text-red-400 tracking-wider text-center bg-red-50/50 dark:bg-red-950/10";
            headerTitle.innerHTML = "Immediate/Segera";
        } 
        else if (parseInt(skalaNum) === 2) {
            headerTitle.className = "px-3 py-1.5 text-sm font-semibold tracking-wider text-center bg-orange-50/50 dark:bg-orange-950/10";
            headerTitle.style.color = "#FF9100"; 
            headerTitle.innerHTML = "Emergensi";
        } 
        else if (parseInt(skalaNum) === 3) {
            headerTitle.className = "px-3 py-1.5 text-sm font-semibold tracking-wider text-center bg-amber-50/50 dark:bg-amber-950/10";
            headerTitle.style.color = "#D49100"; 
            headerTitle.innerHTML = "Urgensi";
        } 
        else if (parseInt(skalaNum) === 4) {
            headerTitle.className = "px-3 py-1.5 text-sm font-semibold tracking-wider text-center bg-emerald-50/50 dark:bg-emerald-950/10";
            headerTitle.style.color = "#10B981"; 
            headerTitle.innerHTML = "Semi Urgensi / Urgensi Rendah";
        } 
        else if (parseInt(skalaNum) === 5) {
            headerTitle.className = "px-3 py-1.5 text-sm font-semibold tracking-wider text-center bg-slate-50 dark:bg-slate-900";
            headerTitle.style.color = "#64748B"; 
            headerTitle.innerHTML = "Non Urgensi";
        }

        renderIndikatorDetail();
    }

    /**
     * Merender Baris Checkbox ke dalam Tabel Kanan
     */
    function renderIndikatorDetail() {
        const container = document.getElementById('triaseContentContainer');
        
        if (currentCategory === "") {
            container.innerHTML = `
                <tr>
                    <td class="px-3 py-10 text-sm text-gray-500 dark:text-gray-400 font-medium italic text-center bg-gray-50/50">
                        ⚠️ Silakan pilih jenis pemeriksaan terlebih dahulu pada tabel sebelah kiri.
                    </td>
                </tr>`;
            return;
        }
        
        const filteredItems = masterSkalaRaw.filter(item => {
            const matchCategory = String(item.id_pemeriksaan) === String(currentCategory);
            const matchSkala    = String(item.id_tingkat_skala) === String(currentSkala);
            return matchCategory && matchSkala;
        });

        if (filteredItems.length === 0) {
            container.innerHTML = `
                <tr>
                    <td class="px-3 py-6 text-xs text-gray-400 dark:text-gray-600 italic text-center">
                        Tidak ada kriteria parameter klinis untuk Skala ${currentSkala} pada kategori ini.
                    </td>
                </tr>`;
            return;
        }

        // Tentukan aturan warna Checkbox dan Teks secara dinamis
        let inlineTextColor = "";
        
        let checkColor = "text-red-600 focus:ring-red-500";
        if (parseInt(currentSkala) === 2) {
            checkColor = "text-orange-500 focus:ring-orange-500";
        } else if (parseInt(currentSkala) === 3) {
            checkColor = "text-amber-500 focus:ring-amber-500";
        } else if (parseInt(currentSkala) === 4) {
            checkColor = "text-emerald-500 focus:ring-emerald-500";
        } else if (parseInt(currentSkala) === 5) {
            checkColor = "text-slate-400 focus:ring-slate-400";
        }

        let htmlContent = "";
        filteredItems.forEach(item => {
            const idSkalaKriteria = item.id_skala;
            const teksPengkajian  = item.pengkajian;
            const isChecked = checkedSkalaIds.includes(idSkalaKriteria) ? 'checked' : '';

            htmlContent += `
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="px-3 py-2">
                        <label class="flex items-center gap-x-3 cursor-pointer w-full">
                            <input type="checkbox" value="${idSkalaKriteria}" ${isChecked}
                                onchange="handleCheckboxChange(this)"
                                class="h-3.5 w-3.5 rounded border-gray-300 dark:bg-slate-800 dark:border-gray-600 ${checkColor}">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-300" ${inlineTextColor}>${teksPengkajian}</span>
                        </label>
                    </td>
                </tr>
            `;
        });

        container.innerHTML = htmlContent;
    }

    // =========================================================================
    // 3. INTERACTIVE ELEMENT EVENT HANDLERS
    // =========================================================================
    /**
     * Mengatur Event Klik Baris pada Tabel Kiri (Pemeriksaan)
     */
    function selectPemeriksaanRow(id_pemeriksaan, selectedRow) {
        currentCategory = id_pemeriksaan;

        document.querySelectorAll('.pemeriksaan-row').forEach(row => {
            row.className = "pemeriksaan-row cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-slate-800/50";
            row.cells[0].className = "px-3 py-2 text-sm font-medium text-gray-900 dark:text-gray-300 uppercase";
        });

        selectedRow.className = "pemeriksaan-row cursor-pointer bg-red-50 hover:bg-red-100 dark:bg-slate-800";
        selectedRow.cells[0].className = "px-3 py-2 text-sm font-bold text-red-600 dark:text-red-400 uppercase";

        renderIndikatorDetail();
    }

    function handleCheckboxChange(checkbox) {
        const value = checkbox.value;
        if (checkbox.checked) {
            if (!checkedSkalaIds.includes(value)) {
                checkedSkalaIds.push(value);
            }
        } else {
            checkedSkalaIds = checkedSkalaIds.filter(id => id !== value);
        }

        syncHiddenInputs();
    }

    /**
     * Memproduksi input hidden secara dinamis
     */
    function syncHiddenInputs() {
        const container = document.getElementById('hiddenInputsContainer');
        let html = '';
                                        
        checkedSkalaIds.forEach(id => {
            html += `<input type="hidden" name="id_skala[]" value="${id}">`;
        });
                                        
        container.innerHTML = html;
    }

    function filterPemeriksaan() {
        let input = document.getElementById('searchPemeriksaan').value.toUpperCase();
        let rows = document.querySelectorAll('#tablePemeriksaanBody .pemeriksaan-row');
        rows.forEach(row => {
            let text = row.cells[0].textContent || row.cells[0].innerText;
            row.style.display = text.toUpperCase().indexOf(input) > -1 ? "" : "none";
        });
    }

    function filterSkalaIndikator() {
        let input = document.getElementById('searchSkala').value.toUpperCase();
        let rows = document.querySelectorAll('#triaseContentContainer tr');
        rows.forEach(row => {
            let label = row.querySelector('span');
            if(label) {
                let text = label.textContent || label.innerText;
                row.style.display = text.toUpperCase().indexOf(input) > -1 ? "" : "none";
            }
        });
    }

    // =========================================================================
    // 4. CALL BACK UTILITIES & DATA VALIDATION SENSOR
    // =========================================================================
    function autofillRegistrasiUgd(item) {
        document.getElementById('id_registrasi').value = item.id_registrasi;
        document.getElementById('nomor_rawat').value = item.nomor_rawat;
        document.getElementById('nomor_rm').value = item.nomor_rm;
        document.getElementById('nama_pasien').value = item.nama_pasien;

        if (item.tanggal_reg) {
            try {
                let tanpaZonaWaktu = item.tanggal_reg.split('+')[0].trim();
                let bagian = tanpaZonaWaktu.split(' '); 
                let jamMenit = bagian[1].substring(0, 5);
                let formatBrowser = `${bagian[0]}T${jamMenit}`;
                document.getElementById('tanggal_kunjungan').value = formatBrowser;
            } catch (e) {
                console.error("Gagal memformat tanggal kunjungan:", e);
                document.getElementById('tanggal_kunjungan').value = item.tanggal_reg;
            }
        }

        if (item.tanggal_lahir) {
            const tglLahir = new Date(item.tanggal_lahir);
            const tglSekarang = new Date();
            let tahunUmur = tglSekarang.getFullYear() - tglLahir.getFullYear();
            let bulanUmur = tglSekarang.getMonth() - tglLahir.getMonth();
            if (bulanUmur < 0 || (bulanUmur === 0 && tglSekarang.getDate() < tglLahir.getDate())) {
                tahunUmur--;
                bulanUmur = 12 + bulanUmur;
            }
            document.getElementById('umur_pasien').value = `${tahunUmur} Tahun ${bulanUmur} Bulan`;
        } else {
            document.getElementById('umur_pasien').value = "-";
        }
    }

    function autofillMacamKasus(item) {
        document.getElementById('id_macam_kasus').value = item.id_macam_kasus;
        document.getElementById('nama_macam_kasus').value = item.nama_macam_kasus;
    }

    function autofillPetugas(item) {
        document.getElementById('id_petugas').value = item.id_petugas;
        document.getElementById('nama_petugas').value = item.nama;
    }

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required], textarea[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Mohon lengkapi semua field yang bertanda bintang.");
                return false;
            }
        }
        
        const idReg = document.getElementById('id_registrasi').value;
        if (!idReg) {
            alert("Gagal Menyimpan! Anda wajib menentukan kunjungan nomor rawat pasien terlebih dahulu melalui modal.");
            return false;
        }

        const idKasus = document.getElementById('id_macam_kasus').value;
        if (!idKasus) {
            alert("Gagal Menyimpan! Anda wajib menentukan kategori macam kasus klinis terlebih dahulu.");
            return false;
        }
        
        const tabAktif = document.getElementById('triase_tab_aktif').value;
        if (tabAktif === 'primer') {
            document.getElementById('anamnesa_singkat').value = "";
            document.querySelectorAll('input[name="id_plan_sekunder"]').forEach(radio => radio.checked = false);
        } else if (tabAktif === 'sekunder') {
            document.getElementById('keluhan_utama').value = "";
            document.getElementById('id_kebutuhan_khusus').value = "";
            document.querySelectorAll('input[name="id_plan_primer"]').forEach(radio => radio.checked = false);
        }

        var submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.setAttribute('disabled', true);
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>