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
                                <?= view('components/notif/icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <div class="px-4">
                                        <?= view('components/notif/header') ?>
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
                            <?= view('components/audit_button', [
                                'link' => '/registrasi/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/header/search_bar');
                        
                        $modul_path = '/registrasi';
                        $tabel    = $registrasi_data;
                        $kolom_id = 'nomor_reg';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => true,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Registrasi' , 'nomor_reg'    , 'indeks'],
                            [1, 'Nomor Rawat'      , 'nomor_rawat'  , 'indeks'],
                            [1, 'Tanggal'          , 'tanggal'      , 'tanggal'],
                            [1, 'Jam'              , 'jam'          , 'jam'],
                            [0, 'Nomor Rekam Medis', 'nomor_rm'     , 'indeks'],
                            [1, 'Nama'             , 'nama_pasien'  , 'nama'],
                            [1, 'Jenis Kelamin'    , 'jenis_kelamin', 'status'],
                            [1, 'Umur'             , 'umur'         , 'jumlah'],
                            [0, 'Poliklinik'       , 'poliklinik'   , 'status'],
                            [0, 'Dokter'           , 'nama_dokter'  , 'nama'],
                            [0, 'Penanggung Jawab'         , 'penanggung_jawab', 'nama'],
                            [0, 'Hubungan Penanggung Jawab', 'hubungan_pj'     , 'teks'],
                            [0, 'Alamat Penanggung Jawab'  , 'alamat_pj'       , 'teks'],
                            [0, 'Nomor Telepon'    , 'no_telepon'       , 'teks'],
                            [0, 'Biaya Registrasi' , 'biaya_registrasi' , 'uang'],
                            [0, 'Status Registrasi', 'status_registrasi', 'status'],
                            [0, 'Status Rawat'     , 'status_rawat'     , 'status'], 
                            [0, 'Status Poliklinik', 'status_poli'      , 'status'],
                            [0, 'Jenis Bayar'      , 'jenis_bayar'      , 'status'],
                            [0, 'Status Bayar'     , 'status_bayar'     , 'status'],
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