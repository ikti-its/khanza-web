<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <!-- <div class="max-w-[85rem] w-full py-6 lg:py-3"> -->
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-hidden">
            <div class="sm:px-20 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">

                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-black text-gray-800 dark:text-gray-200">
                                Rujukan Keluar
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif/icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <?= view('components/notif/notif') ?>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">
                                            <?php
                                            $count_notif_stok = 0;
                                            $today = new DateTime();
                                            $today->setTime(0, 0, 0);
                                            if ($count_notif_stok !== 0) {
                                                foreach ($rujukankeluar_tanpa_params_data as $rujukankeluar_stok) {
                                                    if ($rujukankeluar_stok['nomor_rujuk'] <= $rujukankeluar_stok['nomor_rujuk']) {
                                                        $count_notif_stok++; ?>
                                                        <a href="/datamedis/edit/<?= $rujukankeluar_stok['nomor_rujuk'] ?>" class="p-4 flex items-center border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141] hover:bg-gray-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                                <path d="M12.5358 6.667C14.0754 4.00033 17.9244 4.00033 19.464 6.66699L27.8356 21.167C29.3752 23.8337 27.4507 27.167 24.3715 27.167H7.62834C4.54914 27.167 2.62464 23.8337 4.16424 21.167L12.5358 6.667Z" fill="#DA4141" />
                                                                <path d="M16 18.333C15.4533 18.333 15 17.8797 15 17.333V10.333C15 9.78634 15.4533 9.33301 16 9.33301C16.5467 9.33301 17 9.78634 17 10.333V17.333C17 17.8797 16.5467 18.333 16 18.333Z" fill="#FEE2E2" />
                                                                <path d="M15.9998 23.0001C15.8265 23.0001 15.6532 22.9601 15.4932 22.8934C15.3198 22.8268 15.1865 22.7335 15.0531 22.6135C14.9331 22.4802 14.8398 22.3335 14.7598 22.1735C14.6932 22.0135 14.6665 21.8401 14.6665 21.6668C14.6665 21.3201 14.7998 20.9734 15.0531 20.7201C15.1865 20.6001 15.3198 20.5068 15.4932 20.4402C15.9865 20.2268 16.5732 20.3468 16.9465 20.7201C17.0665 20.8534 17.1598 20.9868 17.2265 21.1601C17.2931 21.3201 17.3332 21.4935 17.3332 21.6668C17.3332 21.8401 17.2931 22.0135 17.2265 22.1735C17.1598 22.3335 17.0665 22.4802 16.9465 22.6135C16.6932 22.8668 16.3598 23.0001 15.9998 23.0001Z" fill="#FEE2E2" />
                                                            </svg>
                                                            <div class="mx-2">
                                                                <span>Stok <span class="font-semibold"><?= $rujukankeluar_stok['nomor_rujuk'] ?></span> telah mencapai jumlah minimum</span>
                                                                <div class="py-1 font-semibold text-sm text-[#DA4141]">Sisa stok: <?= $rujukankeluar_stok['nomor_rujuk'] ?></div>
                                                            </div>
                                                        </a>

                                                <?php }
                                                }
                                            } else { ?>
                                                <button class="p-4 flex items-center border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141] hover:bg-gray-100">

                                                    <div class="mx-2">
                                                        <span>Belum ada notifikasi stok</span>

                                                    </div>
                                                </button>
                                            <?php
                                            } ?>

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/header/tambah_button', [
                                'link' => '/rujukankeluar/tambah'
                            ]) ?>
                            <?= view('components/header/audit_button', [
                                'link' => '/rujukankeluar/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/header/search_bar');
                        
                        $modul_path = '/rujukankeluar';
                        $tabel    = $rujukankeluar_data;
                        $kolom_id = 'nomor_rujuk';
                        $aksi = [
                            'cetak'    => true,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => false,
                            'ambulans' => true
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'Nomor Rujuk'        , 'nomor_rujuk', 'indeks'],
                            [0, 'Nomor Rawat'        , 'nomor_rawat'],
                            [0, 'Nomor Rekam Medis'  , 'nomor_rm'],
                            [1, 'Nama Pasien'        , 'nama_pasien', 'indeks'],
                            [1, 'Tempat Rujuk'       , 'tempat_rujuk', 'teks'],
                            [0, 'Tanggal Rujuk'      , 'tanggal_rujuk'],
                            [0, 'Jam Rujuk'          , 'jam_rujuk'],
                            [0, 'Keterangan Diagnosa', 'keterangan_diagnosa'],
                            [0, 'Dokter Perujuk'     , 'dokter_perujuk'],
                            [1, 'Kategori Rujuk'     , 'kategori_rujuk', 'status'],
                            [1, 'Pengantaran'        , 'pengantaran', 'teks'],
                            [0, 'Keterangan'         , 'keterangan'],
                        ];
                        echo view('components/tabel/data', [
                            'modul_path' => $modul_path,
                            'tabel'      => $tabel,
                            'kolom_id'   => $kolom_id,
                            'konfig'     => $konfig,
                            'aksi'       => $aksi
                        ]);
                        
                        echo view('components/footer/footer', [
                            'meta_data'  => $meta_data,
                            'modul_path' => $modul_path
                        ]);      
                    ?>

                    <!-- Table -->
                    <!-- <div class="overflow-x-auto w-full">                       
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($rujukankeluar_data as $rujukankeluar) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $rujukankeluar['nomor_rujuk'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="p-4">
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                    <a href="/rujukankeluar/cetak/<?= $rujukankeluar['nomor_rawat'] ?>" 
                                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                                        data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $rujukankeluar['nomor_rujuk'] ?>">
                                                        Cetak Surat
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div> -->

                    <!-- End Table -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->
<!-- 🚑 Modal Ambulans -->
<div id="ambulansModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center px-4 py-3 border-b">
            <h2 class="text-lg font-semibold">Pilih Ambulans</h2>
            <button onclick="closeAmbulansModal()" class="text-gray-600 hover:text-red-500 text-xl">×</button>
        </div>
        <div class="p-4 space-y-3" id="ambulansList">
            <p class="text-sm text-gray-500">Memuat ambulans...</p>
        </div>
    </div>
</div>
<!-- End Table Section -->
<script>
function requestAmbulanceModal(nomorRujuk) {
    document.getElementById('ambulansModal').classList.remove('hidden');

    const token = sessionStorage.getItem("token") || '<?= session('jwt_token') ?>';

    fetch('http://127.0.0.1:8080/v1/ambulans/request/pending', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(res => res.json())
    .then(res => {
        const list = document.getElementById('ambulansList');
        list.innerHTML = '';

        if (!res.data || res.data.length === 0) {
            list.innerHTML = `<p class="text-sm text-gray-500">Tidak ada ambulans tersedia.</p>`;
            return;
        }

        res.data
            .filter(item => item.status.toLowerCase() === 'available')
            .forEach(item => {
                list.innerHTML += `
                    <div class="flex justify-between items-center p-2 border rounded" id="ambulans-${item.no_ambulans}">
                        <div>🚑 <strong>${item.no_ambulans}</strong></div>
                        <button onclick="confirmAmbulance('${item.no_ambulans}', '${nomorRujuk}')" 
                                class="text-white bg-blue-600 hover:bg-blue-700 px-2 py-1 text-sm rounded">
                            Pilih
                        </button>
                    </div>`;
            });

        if (list.innerHTML === '') {
            list.innerHTML = `<p class="text-sm text-gray-500">Tidak ada ambulans yang tersedia.</p>`;
        }
    })
    .catch(() => {
        document.getElementById('ambulansList').innerHTML = `<p class="text-sm text-red-500">Gagal memuat data ambulans.</p>`;
    });
}


    function closeAmbulansModal() {
        document.getElementById('ambulansModal').classList.add('hidden');
    }

    function confirmAmbulance(noAmbulans, nomorRujuk) {
    fetch('http://127.0.0.1:8080/v1/ambulans/request', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer <?= session('jwt_token') ?>'
        },
        body: JSON.stringify({ no_ambulans: noAmbulans })
    })
    .then(res => res.json())
    .then(res => {
        const el = document.getElementById(`ambulans-${noAmbulans}`);
        if (el) {
            el.querySelector('strong').classList.replace('text-red-600', 'text-green-600');
            el.querySelector('span')?.classList.replace('text-red-600', 'text-green-600');
        }

        const panggilBtn = document.getElementById(`btn-panggil-${nomorRujuk}`);
        if (panggilBtn) {
            panggilBtn.innerText = "Sudah Dipanggil";
            panggilBtn.classList.replace("text-blue-600", "text-green-600");
            panggilBtn.style.pointerEvents = "none";
        }

        closeAmbulansModal();
        alert("✅ Permintaan ambulans dikirim.");
    })
    .catch(() => {
        alert("❌ Gagal mengirim permintaan.");
    });
}

    function requestAmbulance(noAmbulans) {
            $.ajax({
                url: "http://127.0.0.1:8080/v1/ambulans/request",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    no_ambulans: noAmbulans,
                    message: "Permintaan ambulans untuk rujukan"
                }),
                success: function (res) {
                    $("#ambulanceResponse")
                        .text("🚨 Permintaan ambulans berhasil dikirim!")
                        .removeClass("hidden text-red-600")
                        .addClass("text-green-600");
                },
                error: function (xhr) {
                    const errMsg = xhr.responseJSON?.message || "Gagal mengirim permintaan.";
                    $("#ambulanceResponse")
                        .text("❌ " + errMsg)
                        .removeClass("hidden text-green-600")
                        .addClass("text-red-600");
                }
            });
        }

    document.addEventListener('DOMContentLoaded', function() {
        var count_notif_stok = <?= $count_notif_stok ?>;
        document.querySelector('#stok-tab svg text').textContent = count_notif_stok;
    });
</script>
<?= $this->endSection(); ?>