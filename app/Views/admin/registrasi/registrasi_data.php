<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<script src="https://unpkg.com/preline@latest/dist/preline.js"></script>
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
                                Registrasi Pasien
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif_icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <div class="px-4">
                                        <?= view('components/notif_header') ?>
                                        <div class="flex">                                    
                                        </div>
                                    </div>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">
                                        <div id="kamarpenuh-content" class="max-h-[15rem] overflow-y-auto hidden">
                                            <!-- This will be dynamically filled by JS -->
                                        </div>
                                        </div>
                                        <div class="flex justify-center items-center w-3/4">
                                            <button id="kamarpenuh-tab" class="flex items-center justify-center text-center w-full py-2 border-b-2">Kamar Penuh
                                                <span class="ml-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                                        <circle cx="7.75" cy="7.5" r="7" fill="#EF4444" />
                                                        <text x="50%" y="45%" text-anchor="middle" dominant-baseline="central" fill="#FFF" font-size="10px"></text>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                        <script>
                                        window.addEventListener("DOMContentLoaded", function () {
                                            const container = document.getElementById("kamarpenuh-content");
                                            const notifList = JSON.parse(localStorage.getItem("kamarPenuhList")) || [];

                                            if (!container) return;

                                            container.innerHTML = ""; // Clear previous content

                                            if (notifList.length === 0) {
                                                container.innerHTML = `<div class="p-4 text-gray-500">Tidak ada notifikasi kamar penuh.</div>`;
                                            } else {
                                                notifList.forEach(notif => {
                                                    container.innerHTML += `
                                                        <div class="p-4 mb-2 ml-2 border-b border-gray-200 rounded hover:bg-gray-50">
                                                            <div class="flex items-center gap-2">
                                                                <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                                                                <div>
                                                                    <p class="text-base font-semibold text-gray-800">
                                                                        Kamar penuh untuk ${notif.nama_pasien}
                                                                    </p>
                                                                    <p class="text-sm text-gray-500">
                                                                        Nomor Registrasi: ${notif.nomor_reg}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `;
                                                });
                                            }
                                        });
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/tambah_button', [
                                'link' => '/registrasi/tambah'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
                        $api_url  = '/registrasi';
                        $tabel    = $registrasi_data;
                        $kolom_id = 'nomor_reg';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $data = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Registrasi' , 'nomor_reg'    , 'indeks'],
                            [1, 'Nomor Rawat'      , 'nomor_rawat'  , 'indeks'],
                            [1, 'Tanggal'          , 'tanggal'      , 'tanggal'],
                            [1, 'Jam'              , 'jam'          , 'jam'],
                            [1, 'Nomor Rekam Medis', 'nomor_rm'     , 'indeks'],
                            [1, 'Nama'             , 'nama_pasien'  , 'nama'],
                            [1, 'Jenis Kelamin'    , 'jenis_kelamin', 'status'],
                            [1, 'Umur'             , 'umur'         , 'jumlah'],
                            [1, 'Poliklinik'       , 'poliklinik'   , 'status'],
                            [1, 'Dokter'           , 'nama_dokter'  , 'nama'],
                            [1, 'Penanggung Jawab'         , 'penanggung_jawab', 'nama'],
                            [1, 'Hubungan Penanggung Jawab', 'hubungan_pj'     , 'teks'],
                            [1, 'Alamat Penanggung Jawab'  , 'alamat_pj'       , 'teks'],
                            [1, 'Nomor Telepon'    , 'no_telepon'       , 'teks'],
                            [1, 'Biaya Registrasi' , 'biaya_registrasi' , 'uang'],
                            [1, 'Status Registrasi', 'status_registrasi', 'status'],
                            [1, 'Status Rawat'     , 'status_rawat'     , 'status'], 
                            [1, 'Status Poliklinik', 'status_poli'      , 'status'],
                            [1, 'Jenis Bayar'      , 'jenis_bayar'      , 'status'],
                            [1, 'Status Bayar'     , 'status_bayar'     , 'status'],
                        ];
                        echo view('components/tabel', [
                            'api_url'   => $api_url,
                            'tabel'     => $tabel,
                            'kolom_id'  => $kolom_id,
                            'data'      => $data,
                            'aksi'      => $aksi
                        ]);
                        
                        echo view('components/footer', [
                            'meta_data' => $meta_data,
                            'api_url'   => $api_url
                        ]);      
                    ?>
                    <!-- Table -->
                    <div class="overflow-x-auto w-full">                       
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                       

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($registrasi_data as $registrasi) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $registrasi['nomor_reg'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <div class="px-3 py-1.5">
                                            </div>
                                            <div class="px-3 py-1.5">
                                            <button
                                                type="button"
                                                class="btn btn-info btn-tindakan gap-x-1 text-sm font-semibold"
                                                data-nomor-reg="<?= $registrasi['nomor_reg'] ?>"
                                                data-hs-overlay="#modal-tindakan-<?= $registrasi['nomor_reg'] ?>">
                                                Tindakan
                                            </button>
                                            </div>
                                        </div>
                                    </td>


                                </tr>
                                <!-- Tindakan Modal -->
                                <div id="modal-tindakan-<?= $registrasi['nomor_reg'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                    <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                        <h3 class="font-bold text-gray-800 dark:text-white">
                                        Form Tindakan - <?= $registrasi['nama_pasien'] ?>
                                        </h3>
                                        <button type="button" class="size-7 rounded-full text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-neutral-700" data-hs-overlay="#modal-tindakan-<?= $registrasi['nomor_reg'] ?>">
                                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        </button>
                                    </div>
                                    <div class="p-4 space-y-4">
                                        <div>
                                            <label class="block text-sm text-gray-900 dark:text-white">Nomor Registrasi</label>
                                            <input type="text" value="<?= $registrasi['nomor_reg'] ?>" readonly class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:bg-neutral-700 dark:text-white">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-900 dark:text-white">Nomor Rawat</label>
                                            <input type="text" value="<?= $registrasi['nomor_rawat'] ?>" readonly class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:bg-neutral-700 dark:text-white">
                                        </div>
                                            <div class="hs-accordion" id="aksi-accordion">
                                                <button type="button" class="font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-1000 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 shadow-md">
                                                    <svg class="h-8 w-8 text-slate-950" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                                    </svg>
                                                Tindakan
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>

                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                    <a href="/tindakan/<?= $registrasi['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                        Lihat Tindakan
                                                    </a>
                                                    </li>
                                                    <li>
                                                    <a href="/tindakan/submit-registrasi/<?= $registrasi['nomor_reg'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                        Tambah Tindakan
                                                    </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="hs-accordion" id="aksi-accordion">
                                                <button type="button" class="font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-1000 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 shadow-md">
                                                    <svg class="h-8 w-8 text-slate-950" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                                    </svg>
                                                Pemeriksaan
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>

                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                    <a href="/pemeriksaanranap/by-rawat/<?= $registrasi['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                        Lihat Pemeriksaan
                                                    </a>
                                                    </li>
                                                    <li>
                                                    <a href="/pemeriksaanranap/tambah-dari-registrasi/<?= $registrasi['nomor_reg'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                        Tambah Pemeriksaan
                                                    </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="mt-3">
                                            <form method="POST" action="/rawatinap/tambah/<?= $registrasi['nomor_reg'] ?>">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="font-bold text-gray-800 dark:text-white w-1000 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-800 shadow-md">
                                                <svg class="h-8 w-8 text-slate-950"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M3 7v11m0 -4h18m0 4v-8a2 2 0 0 0 -2 -2h-8v6" />  <circle cx="7" cy="10" r="1" /></svg>
                                                Rawat Inap
                                                </button>
                                            </form>
                                            </div>

                                        </div>

                                        <!-- Add more fields if needed -->
                                    </div>
                                    
                                    <div class="flex justify-end gap-x-2 p-4 border-t dark:border-neutral-700">
                                        <button class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#modal-tindakan-<?= $registrasi['nomor_reg'] ?>">
                                        Tutup
                                        </button>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>

                    <!-- End Table -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-hs-overlay]').forEach(btn => {
    btn.addEventListener('click', function () {
      // Close all open modals
      document.querySelectorAll('.hs-overlay').forEach(modal => {
        modal.classList.add('hidden');
      });

      // Then open the correct one
      const selector = btn.getAttribute('data-hs-overlay');
      const targetModal = document.querySelector(selector);
      if (targetModal) {
        targetModal.classList.remove('hidden');
      }
    });
  });
});

  document.addEventListener('DOMContentLoaded', function () {
    window.HSOverlay?.init();
  });
     
    document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("kamarpenuh-content");
    const notifList = JSON.parse(localStorage.getItem("kamarPenuhList")) || [];

    if (!container) {
        console.error("⚠️ #kamarpenuh-content not found in DOM");
        return;
    }

    container.innerHTML = ""; // clear old

    if (notifList.length === 0) {
        container.innerHTML = `<div class="p-4 text-gray-500">Tidak ada notifikasi kamar penuh.</div>`;
        return;
    }

    notifList.forEach(notif => {
        container.innerHTML += `
            <div class="p-4 mb-2 ml-2 border-b border-gray-200 rounded hover:bg-gray-50">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                    <div>
                        <p class="text-base font-semibold text-gray-800">
                            Kamar penuh untuk ${notif.nama_pasien}
                        </p>
                        <p class="text-sm text-gray-500">
                            Nomor Registrasi: ${notif.nomor_reg}
                        </p>
                    </div>
                </div>
            </div>`;
    });
});

    function markRoomFull() {
    const nomorReg = document.getElementById("modal-nomor-reg").value;
    const namaPasien = document.getElementById("modal-pasien-nama").textContent;

    const notif = {
        nama_pasien: namaPasien,
        nomor_reg: nomorReg,
        waktu: new Date().toISOString()
    };

    const existing = JSON.parse(localStorage.getItem("kamarPenuhList")) || [];
    existing.push(notif);
    localStorage.setItem("kamarPenuhList", JSON.stringify(existing));

    // Optional: Feedback
    alert("🚨 Kamar penuh notification saved for " + namaPasien);

    // Close modal if needed
    closeRoomModal();
}
    document.addEventListener('DOMContentLoaded', () => {
    fetch('http://127.0.0.1:8080/v1/kamar/kelas')
        .then(res => res.json())
        .then(data => {
            document.querySelectorAll("#kelas_kamar_select").forEach(select => {
    fetch("http://127.0.0.1:8080/v1/kamar/kelas")
        .then(res => res.json())
        .then(data => {
            if (!data.data || data.data.length === 0) {
                console.warn("⚠️ No kelas data received from API");
                return;
            }

            data.data.forEach(kelas => {
                const option = document.createElement("option");
                option.value = kelas;
                option.textContent = kelas;
                select.appendChild(option);
            });
        })
        .catch(err => {
            console.error("❌ Failed to fetch kelas list:", err);
        });
});

            if (!data.data || data.data.length === 0) {
                console.warn("⚠️ No kelas data received from API");
                return;
            }

            data.data.forEach(kelas => {
                const option = document.createElement("option");
                option.value = kelas;
                option.textContent = kelas;
                select.appendChild(option);
            });
        })
        .catch(err => {
            console.error("❌ Failed to fetch kelas list:", err);
        });
});

document.addEventListener('DOMContentLoaded', () => {
        fetch('http://127.0.0.1:8080/v1/kamar/kelas')
            .then(res => res.json())
            .then(res => {
                const select = document.getElementById("kelas-select");
                if (!select) {
                    console.error('Select element not found');
                    return;
                }

                res.data.forEach(kelas => {
                    const option = document.createElement("option");
                    option.value = kelas;
                    option.textContent = kelas;
                    select.appendChild(option);
                });
            })
            .catch(err => console.error('Error fetching kelas:', err));
    });

    window.addEventListener("DOMContentLoaded", () => {
    const notifList = JSON.parse(localStorage.getItem("kamarPenuhList")) || [];
    const container = document.getElementById("kamarpenuh-content");

    if (!container) return;

    if (notifList.length === 0) {
        container.innerHTML = `<div class="p-4 text-gray-500">Tidak ada notifikasi kamar penuh.</div>`;
    } else {
        notifList.forEach(notif => {
            container.innerHTML += `
                <div class="p-4 mb-2 ml-2 border-b border-gray-200 rounded hover:bg-gray-50">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                        <div>
                            <p class="text-base font-semibold text-gray-800">
                                Kamar penuh untuk ${notif.nama_pasien}
                            </p>
                            <p class="text-sm text-gray-500">
                                Nomor Registrasi: ${notif.nomor_reg}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    // OPTIONAL: clear after rendering if you only want them to show once
    // localStorage.removeItem("kamarPenuhList");
});

    window.addEventListener('DOMContentLoaded', () => {
        const notifData = localStorage.getItem('kamarPenuhNotif');
        if (notifData) {
            const { nomor_reg, nama } = JSON.parse(notifData);
            showNotification(`Kamar penuh untuk ${nama} (${nomor_reg})`);

            // Clear so it doesn't show again on refresh
            localStorage.removeItem('kamarPenuhNotif');
        }
    });

    function showNotification(message) {
        const notif = document.createElement('div');
        notif.className = 'fixed top-5 right-5 z-[9999] bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg';
        notif.innerText = message;
        document.body.appendChild(notif);
        setTimeout(() => notif.remove(), 4000);
    }

    // Fetch kamar penuh notifications
    function fetchKamarPenuhNotifications() {
        fetch("http://127.0.0.1:8080/v1/notifikasi/kamar-penuh")
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById("kamarpenuh-content");
                container.innerHTML = "";

                if (data.status === "success" && data.data.length > 0) {
                    data.data.forEach(notif => {
                        container.innerHTML += `
                        <div class="p-4 flex items-center border-b-2 border-l-2 border-l-red-500 hover:bg-gray-100">
                            <div class="mx-2 text-red-600">
                                🚫 ${notif.pesan}
                                <div class="text-xs text-gray-500">${new Date(notif.created_at).toLocaleString()}</div>
                            </div>
                        </div>`;
                    });
                } else {
                    container.innerHTML = `
                    <div class="p-4 text-gray-500">
                        Tidak ada notifikasi kamar penuh.
                    </div>`;
                }
            })
            .catch(err => {
                console.error("❌ Error fetching kamar penuh notifications:", err);
            });
    }

    // Call once and refresh every 10s
    fetchKamarPenuhNotifications();
    setInterval(fetchKamarPenuhNotifications, 10000);

document.getElementById("close-popup").addEventListener("click", function () {
    document.getElementById("notif-popup").classList.add("hidden");
});

function fetchPendingRooms() {
    fetch("http://127.0.0.1:8080/v1/registrasi/pending-room")
    .then(res => res.json())
    .then(data => {
        let notifHtml = "";
        const pending = data.data || [];

        document.getElementById("notifCount").textContent = pending.length;

        if (pending.length === 0) {
            notifHtml = `<div class="p-4 text-sm text-gray-500">Tidak ada permintaan kamar saat ini.</div>`;
        } else {
            pending.forEach(item => {
                notifHtml += `
                    <div class="flex items-center justify-between p-4 hover:bg-gray-100 border-l-4 border-red-500">
                        <div>
                            <strong>${item.nama_pasien || item.nomor_reg}</strong> menunggu kamar.
                        </div>
                        <button 
                            type="button"
                            class="bg-green-600 text-white text-sm px-3 py-1 rounded hover:bg-green-700 assign-room-btn"
                            data-nomor-reg="${item.nomor_reg}">
                            🚪 Assign Room
                        </button>
                    </div>`;
            });
        }

        document.getElementById("notifList").innerHTML = notifHtml;
    })
    .catch(error => {
        console.error("Failed to fetch pending room requests:", error);
        document.getElementById("notifList").innerHTML = `<div class="p-4 text-red-500">Gagal memuat notifikasi.</div>`;
    });
}

// Optional: refresh periodically
setInterval(fetchPendingRooms, 10000);

function butuhKamar(nomorReg) {
    fetch(`http://127.0.0.1:8080/v1/registrasi/${nomorReg}/assign-room/menunggu`, {
        method: 'PUT'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Permintaan kamar berhasil dikirim.');

            // Immediately refresh notification list
            fetchPendingRooms();

            // Show the notification popup
            document.getElementById("notif-popup").classList.remove("hidden");
        } else {
            alert('Gagal mengirim permintaan kamar.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghubungi server.');
    });
}

function butuhKamar(nomorReg) {
    fetch(`http://127.0.0.1:8080/v1/registrasi/${nomorReg}/assign-room/menunggu`, {
        method: 'PUT'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Status kamar berhasil diubah ke "menunggu"');
            // Optional: update the UI, change button text, reload, etc.
        } else {
            alert('Gagal memperbarui status kamar');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghubungi server');
    });
}

function updateStatusKamar(nomorReg) {
  $.ajax({
    url: `http://localhost:8080/v1/registrasi/status_kamar/${nomorReg}`,
    method: "PUT",
    contentType: "application/json",
    data: JSON.stringify({ status_kamar: "menunggu" }),
    success: function (res) {
      alert("Status kamar diubah ke 'menunggu'");
      // optionally refresh or update UI here
    },
    error: function () {
      alert("Gagal mengubah status kamar");
    }
  });
}

    function myFunction() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable"); // Pastikan ini mengacu pada ID tabel yang benar

        if (!table) return; // Pastikan tabel ada sebelum melanjutkan

        tr = table.getElementsByTagName("tr");
        var dataFound = false;

        // Iterate over all table rows (including header row)
        for (i = 0; i < tr.length; i++) {
            var found = false;

            // Check if it's a regular row (skip header row)
            if (i > 0) {
                td = tr[i].getElementsByTagName("td");

                // Iterate over all td elements in the row
                for (j = 0; j < td.length; j++) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break; // Break out of inner loop if match found
                    }
                }

                // Show or hide row based on search result
                if (found) {
                    tr[i].style.display = "";
                    dataFound = true;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function closeNotificationPopup() {
        document.getElementById('notif-popup').classList.add('hidden');
    }

    // Event listener untuk menutup pop up saat mengklik di luar pop up
    document.addEventListener('click', function(event) {
        const notifPopup = document.getElementById('notif-popup');
        const notifIcon = document.getElementById('notif-icon');

        // Periksa apakah yang diklik bukan bagian dari pop up notifikasi
        if (!notifPopup.contains(event.target) && event.target !== notifIcon) {
            closeNotificationPopup();
        }
    });

    // Event listener untuk menghindari menutup pop up saat mengklik ikon notifikasi
    document.getElementById('notif-icon').addEventListener('click', function(event) {
        event.stopPropagation(); // Menghentikan penyebaran event ke elemen lain
        document.getElementById('notif-popup').classList.toggle('hidden');
    });

    // Event listener untuk menutup pop up saat mengklik ikon X di dalam pop up
    document.getElementById('close-popup').addEventListener('click', function(event) {
        event.stopPropagation(); // Menghentikan penyebaran event ke elemen lain
        closeNotificationPopup();
    });


    

    document.addEventListener("DOMContentLoaded", function () {
    const tindakanButtons = document.querySelectorAll(".btn-tindakan");

    tindakanButtons.forEach(button => {
        button.addEventListener("click", function () {
            const nomorReg = this.getAttribute("data-nomor-reg");
            document.getElementById("formNomorReg").value = nomorReg;
        });
    });
});
</script>
<?= $this->endSection(); ?>