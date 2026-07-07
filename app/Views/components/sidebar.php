<!-- Sidebar -->
<?php
    $persetujuanrole   = [1337, 1, 2, 4001, 5001];
    $petugasrole       = [1337, 1, 2, 4001, 5001];
    $petugasdokterrole = [1337, 1, 2, 3, 4001, 5001];
    $dokterrole        = [1337, 1, 3, 4001, 5001];
    $loginadmin        = [1337, 1];
    $loginpetugas      = 2;
    $logindokter       = 3;
?>

<div id="application-sidebar" class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 start-0 bottom-0 z-[60] w-64 bg-white border-e border-gray-200 pt-7 pb-10 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-6">
        <a class="flex-none text-xl font-semibold dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/dashboard">
            <img src="/img/logo-omnia.png" class=" h-12">
        </a>
    </div>

    <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
        <ul class="space-y-1.5">
            <li>
                <?= $this->include('components/search_feature') ?>
            </li>
            
            <li>
                <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/dashboard">
                    <img src="<?= base_url('svg/old_icons/beranda.svg') ?>">
                    Beranda
                </a>
            </li>
            <li>
                <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/profile">
                    <img src="<?= base_url('svg/old_icons/akun.svg') ?>">
                    Akun
                </a>
            </li>

            <li class="hs-accordion" id="account-accordion">
                <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    <img src="<?= base_url('svg/old_icons/kehadiran.svg') ?>">
                    Kehadiran

                    <?= $this->include('components/menu/dropdown_icon') ?>
                </button>

                <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                    <ul class="pt-2 ps-2">

                        <li class="hs-accordion" id="account-accordion">
                            <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                                <img src="<?= base_url('svg/old_icons/presensi.svg') ?>">
                                Presensi
                                <?= $this->include('components/menu/dropdown_icon') ?>
                            </button>

                            <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                <ul class="pt-2 ps-2">
                                    <?php
                                    $userData = session('user_specific_data') ?? [];
                                    $status = $userData['status'] ?? null;
                                    ?>
                                    <?php if ($status === false) : ?>
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/menukehadiran">
                                                Masuk
                                            </a>
                                        </li>
                                    <?php elseif ($status === true) : ?>
                                        <li>
                                            <div class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg" href="/swafoto">
                                                Pulang
                                            </div>
                                        </li>
                                    <?php else: ?>
                                        <li>
                                            <div class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/menukehadiran">
                                                Masuk
                                            </div>
                                        </li>
                                        <?php
                                        $userData = session('user_specific_data') ?? [];
                                        $pegawai = $userData['pegawai'] ?? 'unknown';  // Default value if 'pegawai' is not set
                                        ?>
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                href="/absenpulang/<?php echo $pegawai; ?>">
                                                Pulang
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </li>

                        <li class="hs-accordion" id="account-accordion">
                            <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                                <img src="<?= base_url('svg/old_icons/pengajuan.svg') ?>">
                                Pengajuan

                                <?= $this->include('components/menu/dropdown_icon') ?>
                            </button>

                            <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                <ul class="pt-2 ps-2">
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/izincuti">
                                            Izin Cuti
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="hs-accordion" id="account-accordion">
                            <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                                <img src="<?= base_url('svg/old_icons/peninjauan.svg') ?>">
                                Peninjauan

                                <?= $this->include('components/menu/dropdown_icon') ?>
                            </button>

                            <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                <ul class="pt-2 ps-2">
                                    <?php
                                    $userData = session('user_specific_data') ?? [];
                                    $pegawai = $userData['pegawai'] ?? 'unknown';  // Provide a default value if 'pegawai' is missing
                                    ?>
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                            href="/catatankehadiran/<?php echo $pegawai; ?>">
                                            Catatan Kehadiran
                                        </a>
                                    </li>
                                    <li>
                                        <?php if (isset(session('user_details')['role']) && session('user_details')['role'] === 2) : ?>
                                            <?php
                                            $user_specific_data = session('user_specific_data');
                                            $pegawai = isset($user_specific_data['pegawai']) ? $user_specific_data['pegawai'] : '';
                                            ?>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/lihatjadwal/<?php echo $pegawai; ?>">
                                                Jadwal Kerja
                                            </a>
                                        <?php else : ?>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/lihatjadwal">
                                                Jadwal Kerja
                                            </a>
                                        <?php endif; ?>
                                    </li>
                                    <li>
                                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                            href="/lihatizincuti/<?php echo $pegawai; ?>">
                                            Daftar Pengajuan Cuti
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <?php if (isset(session('user_details')['role']) && session('user_details')['role'] === 1) : ?>
                            <li class="hs-accordion" id="account-accordion">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M4.61666 16.2666C4.10832 16.2666 3.63332 16.0916 3.29166 15.7666C2.85832 15.3583 2.64999 14.7416 2.72499 14.075L3.03332 11.375C3.09166 10.8666 3.39999 10.1916 3.75832 9.82496L10.6 2.58329C12.3083 0.774959 14.0917 0.72496 15.9 2.43329C17.7083 4.14163 17.7583 5.92496 16.05 7.73329L9.20832 14.975C8.85832 15.35 8.20832 15.7 7.69999 15.7833L5.01666 16.2416C4.87499 16.25 4.74999 16.2666 4.61666 16.2666ZM13.275 2.42496C12.6333 2.42496 12.075 2.82496 11.5083 3.42496L4.66666 10.675C4.49999 10.85 4.30832 11.2666 4.27499 11.5083L3.96666 14.2083C3.93332 14.4833 3.99999 14.7083 4.14999 14.85C4.29999 14.9916 4.52499 15.0416 4.79999 15L7.48332 14.5416C7.72499 14.5 8.12499 14.2833 8.29166 14.1083L15.1333 6.86663C16.1667 5.76663 16.5417 4.74996 15.0333 3.33329C14.3667 2.69163 13.7917 2.42496 13.275 2.42496Z" fill="#272727" />
                                        <path d="M14.45 9.12504C14.4333 9.12504 14.4083 9.12504 14.3916 9.12504C11.7916 8.8667 9.69996 6.8917 9.29996 4.30837C9.24996 3.9667 9.48329 3.65004 9.82496 3.5917C10.1666 3.5417 10.4833 3.77504 10.5416 4.1167C10.8583 6.13337 12.4916 7.68337 14.525 7.88337C14.8666 7.9167 15.1166 8.22504 15.0833 8.5667C15.0416 8.88337 14.7666 9.12504 14.45 9.12504Z" fill="#272727" />
                                    </svg>
                                    Ubah


                                    <?= $this->include('components/menu/dropdown_icon') ?>
                                    </svg>
                                </button>


                                <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                    <ul class="pt-2 ps-2">
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/lihatstatuscuti">
                                                Status Pengajuan Cuti
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            </li>
                        <?php endif; ?>
                        <!-- <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/presensi">
                                Face Recognition
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/swafoto">
                                Swafoto
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/tesmenukehadiran">
                                Tes Menu Kehadiran
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/kehadiranmanual">
                                Tes Kehadiran Manual
                            </a>
                        </li> -->
                    </ul>
                </div>
            </li>


            <li>
                <?php if (isset(session('user_details')['role']) && (session('user_details')['role'] === 2)) : ?>
                    <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/detailberkaspegawai/<?php //echo session('user_specific_data')['pegawai']                                                                                                                                                                                                                                                                                  ?>">
                    <img src="<?= base_url('svg/old_icons/pegawai.svg') ?>">
                        Pegawai
                    </a>
                <?php else : ?>

            <li class="hs-accordion" id="account-accordion">
                <button type="button" class="hs-accordion-toggle hs-accordion-active:text-slate-700 hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-gray-100 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                    <img src="<?= base_url('svg/old_icons/pegawai.svg') ?>">
                    Pegawai

                    <?= $this->include('components/menu/dropdown_icon') ?>
                </button>

                <div id="account-accordion-content" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                    <ul class="pt-2 ps-2">
                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/detailberkaspegawai/<?php echo session('user_specific_data') //['pegawai']                                                                                                                                                                                                   ?>">
                                Data Pegawai
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/datauserpegawai"">
                                Ketersediaan Pegawai
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

        <?php endif; ?>

            <?php
            $menu_list = [
                /*
                    **** Format  *******
                    ['Nama menu', 'Link menu', 'Icon menu', 'Prefiks', $rolelist, [
                        ['Nama submenu 1' , 'Link submenu 1', 'Icon submenu 1'],
                        ['Nama submenu 2' , 'Link submenu 2', 'Icon submenu 2'],
                        ['Nama submenu 3' , 'Link submenu 3', 'Icon submenu 3'],
                        dst
                    ]]

                    **** Keterangan *****
                    Nama menu => Nama yang akan muncul di tampilan user di sidebar sebelah kiri
                    Link menu => Link sesuai routes yang telah dibuat di app/Config/Routes.php
                                    Jika menu ini memiliki submenu, maka link menu WAJIB diisi '' (empty string)
                    Icon menu => Nama file icon menu dalam bentuk file svg yang disimpan di public/svg
                                    Nama Icon menu pada halaman ini TIDAK BOLEH mencantumkan ekstensi .svg
                                    Apabila icon tidak ada, Icon menu WAJIB diisi '' (empty string)
                                    Tetapi Icon menu DISARANKAN ada supaya tampilan terlihat rapi
                    Prefiks   => Prefiks url yang ditambahkan sebelum Link Menu
                                    Apabila tidak menggunakan prefiks, Prefiks diisi '' (empty string)
                    Rolelist  => List role user yang dapat melihat dan mengakses menu ini

                    Submenu      => Opsi submenu yang dikelompokkan dalam 1 menu untuk memudahkan navigasi
                                    Apabila tidak ada submenu, maka submenu WAJIB diisi [] (empty array)
                                    Submenu dan Link menu bersifat mutually exclusive sehingga
                                    apabila Link Menu diisi, maka Submenu WAJIB kosong dan sebaliknya
                    Nama submenu => Nama yang akan muncul di tampilan user ketika menu diklik
                    Link submenu => Link sesuai routes yang telah dibuat di app/Config/Routes.php
                    Icon submenu => Nama file icon menu dalam bentuk file svg yang disimpan di public/svg
                                    Nama Icon menu pada halaman ini TIDAK BOLEH mencantumkan ekstensi .svg
                                    Apabila icon tidak ada, Icon submenu WAJIB diisi '' (empty string)
                                    Pada 1 menu, DISARANKAN submenu memiliki icon seluruhnya atau 
                                    tidak memiliki icon seluruhnya supaya tampilan terlihat rapi
                    */
                // ['Data Penggajian', '', 'data_penggajian', '/data-penggajian', $petugasrole, [
                // ]],
                ['Inventaris Medis', '', 'inventaris_medis.svg', '',  $petugasrole, [
                    ['Data', '/datamedis', ''],
                    ['Stok Opname', '/stokopnamemedis', ''],
                    ['Mutasi Antar Gudang', '/mutasimedis', ''],
                    ['Penerimaan Obat & BHP', '/penerimaanmedis', ''],
                    ['Stok Keluar', '/stokkeluarmedis', ''],
                    ['Sisa Stok',  '/sisastokmedis', ''],
                    ['Data Batch', '/batchmedis', '']
                ]],
                ['Rujukan', '', 'rujukan.svg', '', $petugasdokterrole, [
                    ['Rujukan Masuk', '/rujukanmasuk', ''],
                    ['Rujukan Keluar', '/rujukankeluar', ''],
                ]], #allrole
                ['Persetujuan', '/persetujuanpengajuan', 'persetujuan.svg', '', $persetujuanrole, []],
                ['Registrasi', '/registrasi', 'registrasi.svg', '', $petugasrole, []],
                ['Data Pasien', '', 'olah_data_pasien.svg', '', $petugasrole, [
                    ['Daftar Pasien', '/masterpasien', ''],
                    ['Kelahiran Bayi', '/kelahiranbayi', ''],
                    ['Pasien Meninggal', '/pasienmeninggal', ''],
                    ['Asuransi Pasien', '/asuransi', ''],
                    ['Instansi Pasien', '/instansi', ''],
                ]],
                ['Dokter', '', 'dokter_jaga.svg', '', $petugasrole, [
                    ['Daftar Dokter', '/dokter', ''],
                    ['Dokter Jaga', '/dokterjaga', ''],
                ]],
                ['Rawat Inap', '/rawatinap', 'rawat_inap.svg', '', $petugasdokterrole, []],
                ['Ruangan', '/kamar', 'kamar.svg', '', $petugasrole, []],
                // ['Unit Gawat Darurat', '/ugd', 'ugd.svg', '', $petugasdokterrole, []],
                ['Ambulans', '/ambulans', 'ambulans.svg', '', $petugasrole, []],
                ['Tindakan', '/tindakan', 'tindakan.svg', '', $petugasrole, []],
                ['Pemeriksaan', '/pemeriksaanranap', 'pemeriksaan.svg', '', $petugasrole, []],
                ['Resep Obat', '/resepobat', 'resep_obat.svg', '', $petugasdokterrole, []],
                ['Pemberian Obat', '/pemberianobat', 'pemberian_obat.svg', '', $petugasrole, []],
                ['Resep Pulang', '', 'resep_pulang.svg', '', $petugasrole, [
                    ['Permintaan Resep Pulang', '/permintaanreseppulang', ''],
                    ['Resep Pulang', '/reseppulang', ''],
                ]],
                ['Rekam Medis', '', 'rekam_medis.svg', '', $dokterrole, [
                    ['Daftar Rekam Medis', '/rekam-medis', ''],
                    ['Observasi Rawat Inap', '/catatanobservasiranap', ''],
                    ['Observasi Rawat Inap Kebidanan', '/catatanobservasikebidanan', ''],
                    ['Observasi Rawat Inap Post Partum', '/catatanobservasipostpartum', ''],
                ]],
            ];
            echo view('components/menu/menu', ['menu_list' => $menu_list]);

            if (is_file(APPPATH . 'Config/GeneratedSidebar.php')) {
                $new_menu_list = require_once APPPATH . 'Config/GeneratedSidebar.php';
            }
            echo view('components/menu/menu', ['menu_list' => $new_menu_list]);
            ?>
        </ul>
    </nav>
</div>
<!-- End Sidebar -->