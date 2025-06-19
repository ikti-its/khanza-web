<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <!-- <div class="max-w-[85rem] w-full py-6 lg:py-3"> -->
    <!-- Card -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-hidden">
            <div class="sm:px-20 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">

                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-black text-gray-800 dark:text-gray-200">
                                Pengelolaan Ruangan
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
                                            <div class="flex justify-center items-center w-3/4">
                                                <button id="stok-tab" class="flex items-center justify-center text-center w-full py-2 border-b-2 border-[#272727]">
                                                    Butuh Kamar
                                                    <span class="ml-1"> <!-- Add margin-left for spacing -->
                                                        <span id="notifCount" class="text-red-500">0</span>
                                                    </span>


                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        
                                    <span id="notifCount">0</span>

                                    <!-- Notification popup content -->
                                    <div id="notifList">
                                        <p class="text-gray-500">Memuat data kamar...</p>
                                    </div>
                                    <?php if (!empty($pending_requests)): ?>
                                        <?php foreach ($pending_requests as $req): ?>
                                            <div class="flex items-center justify-between p-4 hover:bg-gray-100 border-l-4 border-red-500">
                                                <div>
                                                    <strong><?= esc($req['nama_pasien']) ?></strong> is waiting for a room 
                                                    <span class="text-red-600 font-semibold">(class: <?= esc($req['kelas']) ?>)</span>.
                                                </div>
                                                <button 
                                                    type="button"
                                                    class="bg-green-600 text-white text-sm px-3 py-1 rounded hover:bg-green-700 assign-room-btn"
                                                    data-nomor-reg="<?= esc($req['nomor_reg']) ?>">
                                                    🚪 Assign Room
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-4 text-gray-500">No pending room requests.</div>
                                    <?php endif; ?>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/tambah_button', [
                                'link' => '/kamar/tambah'
                            ]) ?>

                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
                        $api_url  = '/kamar';
                        $tabel    = $kamar_data;
                        $kolom_id = 'nomor_bed';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true
                        ];
                        $data = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Bed'   , 'nomor_bed'   , 'indeks'],
                            [1, 'Kode Kamar'  , 'kode_kamar'  , 'indeks'],
                            [1, 'Nama Kamar'  , 'nama_kamar'  , 'teks'],
                            [1, 'Kelas'       , 'kelas'       , 'status'],
                            [1, 'Tarif Kamar' , 'tarif_kamar' , 'uang'],
                            [1, 'Status Kamar', 'status_kamar', 'status'],
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
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Card -->

<!-- Modal for Assigning Room -->
<div id="assignRoomModal" class="fixed inset-0 bg-gray-800 bg-opacity-40 flex items-center justify-center hidden z-50">
    
    <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">Pilih Kamar untuk <span id="modal-pasien-nama" class="font-bold"></span></h2>
        <input type="hidden" id="modal-nomor-reg">

        <label for="roomSelect" class="block mb-2">Pilih Kamar</label>
        <select id="roomSelect" class="w-full border rounded-lg p-2 mb-4">
            <option value="">-- Pilih Kamar --</option>
            <!-- Filled dynamically -->
        </select>

        <div class="flex justify-center flex-wrap gap-3">
            <button onclick="closeRoomModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button onclick="markRoomFull()" class="py-2.5 px-5 min-w-[120px] inline-flex items-center justify-center gap-x-2 text-base rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">Kamar Penuh</button>
            <button onclick="submitAssignRoom()"
                class="py-2.5 px-5 min-w-[120px] inline-flex items-center justify-center gap-x-2 text-base rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                Assign
</button>
        </div>
    </div>
</div>
<!-- End Table Section -->

<script>
window.addEventListener('load', function () {
    window.HSOverlay?.autoInit();
  });

function markRoomFull() {
    const nomorReg = document.getElementById("modal-nomor-reg").value;
    const namaPasien = document.getElementById("modal-pasien-nama").textContent;

    const newNotif = {
        nama_pasien: namaPasien,
        nomor_reg: nomorReg,
        waktu: new Date().toISOString()
    };

    // Retrieve existing list or create new one
    let stored = JSON.parse(localStorage.getItem('kamarPenuhList')) || [];
    stored.push(newNotif);
    localStorage.setItem('kamarPenuhList', JSON.stringify(stored));

    closeRoomModal();

    // Optional: redirect to Registrasi page
    // window.location.href = "/registrasi";
}


function openRoomModal(nomorReg, namaPasien) {
    // Set values into modal
    document.getElementById("modal-nomor-reg").value = nomorReg;
    document.getElementById("modal-pasien-nama").textContent = namaPasien;
    document.getElementById("assignRoomModal").classList.remove("hidden");

    // Clear and fetch kamar
    const select = document.getElementById("roomSelect");
    select.innerHTML = '<option value="">-- Pilih Kamar --</option>';

    fetch("http://127.0.0.1:8080/v1/kamar/available")
        .then(res => res.json())
        .then(data => {
            if (data.status === "success" && Array.isArray(data.data)) {
                data.data.forEach(kamar => {
                    select.innerHTML += `
                        <option value="${kamar.nomor_bed}">
                            ${kamar.nama_kamar} (Bed ${kamar.nomor_bed}) - Kelas ${kamar.kelas}
                        </option>`;
                });
            } else {
                select.innerHTML = '<option value="">Tidak ada kamar tersedia</option>';
            }
        })
        .catch(err => {
            console.error("❌ Failed to fetch kamar:", err);
            select.innerHTML = '<option value="">Gagal memuat kamar</option>';
        });
}

function closeRoomModal() {
    document.getElementById("assignRoomModal").classList.add("hidden");
}


function closeRoomModal() {
    document.getElementById("assignRoomModal").classList.add("hidden");
}

function submitAssignRoom() {
    const nomorReg = document.getElementById("modal-nomor-reg").value;
    const kamarId = document.getElementById("roomSelect").value;

    console.log("Nomor Reg:", nomorReg);
    console.log("Kamar ID:", kamarId);

    if (!kamarId) {
        alert("Pilih kamar terlebih dahulu");
        return;
    }

    // Assign room to registrasi
    fetch(`http://127.0.0.1:8080/v1/registrasi/${nomorReg}/assign-kamar`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ kamar_id: kamarId })
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === "success") {
            // 🔄 Update status_kamar to 'penuh'
            return fetch(`http://127.0.0.1:8080/v1/kamar/${kamarId}/status`, {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ status_kamar: "penuh" })
            });
        } else {
            throw new Error("Gagal assign kamar");
        }
    })
    .then(() => {
        alert("Kamar berhasil diassign dan diupdate jadi penuh.");
        closeRoomModal();
        fetchPendingRooms(); // optional refresh
    })
    .catch(err => {
        console.error("❌", err);
        alert("Gagal assign kamar.");
    });
}


    

function butuhKamar(nomorReg) {
    fetch(`http://127.0.0.1:8080/v1/registrasi/${nomorReg}/assign-room/menunggu`, {
        method: 'PUT'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Refresh notifications after successful status update
            fetchPendingRooms();

            // Show the notification popup
            document.getElementById("notif-popup").classList.remove("hidden");

            // Optional: scroll to top of popup
            document.getElementById("notif-popup").scrollTop = 0;

        } else {
            alert('Gagal mengirim permintaan kamar.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghubungi server.');
    });
}

// Fetch and update the notification popup
function fetchPendingRooms() {
    fetch("http://127.0.0.1:8080/v1/registrasi/pending-room")
        .then(res => res.json())
        .then(data => {
            console.log("📦 Response from pending-room:", data);

            const notifList = document.getElementById("notifList");
            const notifCount = document.querySelectorAll("#notifCount");

            let notifHtml = "";

            if (!data.data || data.data.length === 0) {
                notifHtml = `<div class="p-4 text-sm text-gray-500">Tidak ada permintaan kamar saat ini.</div>`;
            } else {
                data.data.forEach(item => {
                    notifHtml += `
                        <div class="flex items-center justify-between p-4 hover:bg-gray-100 border-l-4 border-red-500 cursor-pointer"
                            onclick="openRoomModal('${item.nomor_reg}', '${item.nama_pasien}')">
                            <div>
                                <strong>${item.nama_pasien || item.nomor_reg}</strong> menunggu kamar 
                                <span class="text-black-600 font-semibold">kelas ${item.kelas || '-'}</span>.
                            </div>
                            <span>🚪</span>
                        </div>`;
                });
            }

            notifList.innerHTML = notifHtml;

            notifCount.forEach(el => {
                el.textContent = data.data.length;
            });
        })
        .catch(err => {
            console.error("❌ Failed to fetch pending-room:", err);
            document.getElementById("notifList").innerHTML =
                `<div class="p-4 text-red-500">Gagal memuat notifikasi.</div>`;
        });
}

// Load once on page load
fetchPendingRooms();
setInterval(fetchPendingRooms, 10000); // Optional: refresh every 10s

// Close popup on X click
document.getElementById("close-popup").addEventListener("click", () => {
    document.getElementById("notif-popup").classList.add("hidden");
});
    
    document.addEventListener('DOMContentLoaded', function() {
        var count_notif_stok = <?= isset($count_notif_stok) ? $count_notif_stok : 0 ?>;
        document.querySelector('#stok-tab svg text').textContent = count_notif_stok;
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Menghitung jumlah div elemen di dalam kadaluwarsa-content
        var kadaluwarsaContent = document.getElementById('kadaluwarsa-content');
        var divCount = kadaluwarsaContent.querySelectorAll('div.kadaluwarsabaris, a.kadaluwarsabaris').length;

        // Memperbarui teks dalam SVG
        var svgText = document.querySelector('#kadaluwarsa-tab svg text');
        svgText.textContent = divCount.toString();
    });
    // JavaScript to toggle between tabs
    document.getElementById('stok-tab').addEventListener('click', function() {
        document.getElementById('stok-content').classList.remove('hidden');
        document.getElementById('kadaluwarsa-content').classList.add('hidden');
        this.classList.add('border-[#272727]');
        document.getElementById('kadaluwarsa-tab').classList.remove('border-[#272727]');
    });

    document.getElementById('kadaluwarsa-tab').addEventListener('click', function() {
        document.getElementById('stok-content').classList.add('hidden');
        document.getElementById('kadaluwarsa-content').classList.remove('hidden');
        this.classList.add('border-[#272727]');
        document.getElementById('stok-tab').classList.remove('border-[#272727]');
    });

    

    document.addEventListener('DOMContentLoaded', function () {
        const notifText = document.querySelector('#stok-tab svg text');
        const count = <?= $count_notif_kamar ?? 0 ?>;

        if (notifText) {
            notifText.textContent = count > 0 ? count : '';
        }

        if (count > 0) {
            document.getElementById('notif-popup').classList.remove('hidden');
        }
    });
    document.querySelectorAll('.assign-room-btn').forEach(button => {
    button.addEventListener('click', async () => {
        const nomorReg = button.getAttribute('data-nomor-reg');
        
        try {
            const response = await fetch(`http://127.0.0.1:8080/v1/registrasi/${nomorReg}/assign-room`, {
                method: 'PUT',
            });

            const result = await response.json();

            if (response.ok) {
                alert('✅ ' + result.message);
                button.closest('div').remove(); // remove the item from list
            } else {
                alert('❌ Failed: ' + result.message);
            }
        } catch (err) {
            alert('❌ Error: ' + err.message);
        }
    });
});

    $(document).ready(function () {
        $.ajax({
        url: "http://127.0.0.1:8080/v1/registrasi/pending-room",
        method: "GET",
        success: function (res) {
            if (res.code === 200 && res.data.length > 0) {
            $("#notifCount").text(res.data.length);

            let notifHtml = "";
            res.data.forEach(function (item) {
                notifHtml += `
                <a href="/datamedis/edit/${item.nomor_reg}" class="block p-4 hover:bg-gray-100 border-l-4 border-red-500">
                    <strong>${item.nama_pasien || "Pasien Tanpa Nama"}</strong> menunggu kamar.
                </a>
                `;
            });

            $("#notifList").html(notifHtml);
            } else {
            $("#notifCount").text("0");
            $("#notifList").html(`<p class="text-gray-500 px-4">Tidak ada permintaan kamar saat ini.</p>`);
            }
        },
        error: function (xhr, status, error) {
            console.error("❌ Error fetching room notifications:", error);
            $("#notifList").html(`<p class="text-red-500 px-4">Gagal memuat notifikasi.</p>`);
        }
        });
    });
</script>
<?= $this->endSection(); ?>