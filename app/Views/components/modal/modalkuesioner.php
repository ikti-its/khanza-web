<?php
$kelompokPertanyaanKuesioner = [
    [
        'judul' => 'Saat ini',
        'items' => [
            1 => 'Apakah anda merasa sehat pada hari ini?',
            2 => 'Apakah anda sedang minum antibiotik?',
            3 => 'Apakah anda sedang minum obat lain untuk infeksi?',
        ],
    ],
    [
        'judul' => 'Dalam waktu 48 jam terakhir',
        'items' => [
            4 => 'Apakah anda sedang minum Aspirin atau obat yang mengandung asam asetil salisilat?',
        ],
    ],
    [
        'judul' => 'Dalam waktu 1 minggu terakhir',
        'items' => [
            5 => 'Apakah anda mengalami sakit kepala dan demam?',
        ],
    ],
    [
        'judul' => 'Dalam waktu 6 minggu terakhir',
        'items' => [
            6 => 'Untuk donor wanita: apakah anda saat ini sedang hamil?',
        ],
    ],
    [
        'judul' => 'Dalam waktu 8 minggu terakhir',
        'items' => [
            7 => 'Apakah anda mendonorkan darah, trombosit, atau plasma?',
            8 => 'Apakah anda menerima vaksinasi atau suntikan lainnya?',
            9 => 'Apakah anda pernah kontak dengan orang yang menerima vaksinasi smallpox?',
        ],
    ],
    [
        'judul' => 'Dalam waktu 16 minggu terakhir',
        'items' => [
            10 => 'Apakah anda mendonorkan 2 kantong sel darah merah melalui proses aferesis?',
        ],
    ],
    [
        'judul' => 'Dalam waktu 12 bulan terakhir',
        'items' => [
            11 => 'Apakah anda pernah menerima transfusi darah?',
            12 => 'Apakah anda pernah mendapat transplantasi organ, jaringan, atau sumsum tulang?',
            13 => 'Apakah anda pernah cangkok tulang atau kulit?',
            14 => 'Apakah anda pernah tertusuk jarum medis?',
            15 => 'Apakah anda pernah berhubungan seksual dengan ODHA?',
            16 => 'Apakah anda pernah berhubungan seksual dengan WPS?',
            17 => 'Apakah anda pernah berhubungan seksual dengan pengguna narkoba jarum suntik?',
            18 => 'Apakah anda pernah berhubungan seksual dengan pengguna konsentrat faktor pembekuan?',
            19 => 'Untuk donor wanita: apakah anda pernah berhubungan seksual dengan laki-laki yang biseksual?',
            20 => 'Apakah anda pernah berhubungan seksual dengan penderita hepatitis?',
            21 => 'Apakah anda tinggal bersama penderita hepatitis?',
            22 => 'Apakah anda memiliki tatto?',
            23 => 'Apakah anda memiliki/melakukan tindik telinga atau bagian tubuh lainnya?',
            24 => 'Apakah anda sedang atau pernah mendapat pengobatan sifilis atau GO/kencing nanah?',
            25 => 'Apakah anda pernah ditahan di penjara untuk waktu lebih dari 72 jam?',
        ],
    ],
    [
        'judul' => 'Dalam waktu 3 tahun terakhir',
        'items' => [
            26 => 'Apakah anda pernah berada di luar wilayah Indonesia?',
        ],
    ],
    [
        'judul' => 'Tahun 1980 hingga 1996',
        'items' => [
            27 => 'Apakah anda tinggal selama 3 bulan atau lebih di Inggris?',
        ],
    ],
    [
        'judul' => 'Tahun 1980 hingga sekarang',
        'items' => [
            28 => 'Apakah anda tinggal selama 5 tahun atau lebih di Eropa?',
            29 => 'Apakah anda menerima transfusi darah di Inggris?',
        ],
    ],
    [
        'judul' => 'Tahun 1977 hingga sekarang',
        'items' => [
            30 => 'Apakah anda menerima uang, obat, atau pembayaran lainnya untuk seks?',
            31 => 'Untuk laki-laki: apakah anda pernah berhubungan seksual dengan laki-laki, walaupun sekali?',
        ],
    ],
    [
        'judul' => 'Riwayat seumur hidup / pernah',
        'items' => [
            32 => 'Apakah anda pernah mendapatkan hasil positif untuk tes HIV/AIDS?',
            33 => 'Apakah anda pernah menggunakan jarum suntik untuk obat-obatan atau steroid yang tidak diresepkan dokter?',
            34 => 'Apakah anda pernah menggunakan konsentrat faktor pembekuan?',
            35 => 'Apakah anda pernah menderita Hepatitis?',
            36 => 'Apakah anda pernah menderita Malaria?',
            37 => 'Apakah anda pernah menderita kanker termasuk leukimia?',
            38 => 'Apakah anda pernah bermasalah dengan jantung dan paru-paru?',
            39 => 'Apakah anda pernah menderita perdarahan atau penyakit yang berhubungan dengan darah?',
            40 => 'Apakah anda pernah berhubungan seksual dengan orang yang tinggal di Afrika?',
            41 => 'Apakah anda pernah tinggal di Afrika?',
        ],
    ],
];

$jawabanKuesioner = [];

if (!empty($baris['jawaban_kuesioner'])) {
    $jawabanKuesioner = json_decode($baris['jawaban_kuesioner'], true) ?? [];
}
?>

<div id="modalKuesionerSkrining"
     style="
        display: none;
        position: fixed;
        inset: 0;
        z-index: 999999;
        background: rgba(0, 0, 0, 0.50);
        padding: 24px;
        align-items: center;
        justify-content: center;
     ">

    <div style="
        width: 100%;
        max-width: 860px;
        max-height: 82vh;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        display: flex;
        flex-direction: column;
        overflow: hidden;
     ">

        <!-- Header Modal -->
        <div style="
            flex: 0 0 auto;
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <div>
                <h2 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0;">
                    Formulir Kuesioner dan Informed Consent Donor
                </h2>
                <p style="font-size: 12px; color: #6b7280; margin: 4px 0 0;">
                    Lengkapi kuesioner sebagai dasar pertimbangan hasil anamnesis donor.
                </p>
            </div>

            <button type="button"
                    onclick="close_modalKuesionerSkrining()"
                    style="
                        border: none;
                        background: transparent;
                        font-size: 26px;
                        font-weight: 700;
                        color: #9ca3af;
                        cursor: pointer;
                        line-height: 1;
                    ">
                &times;
            </button>
        </div>

        <!-- Isi Modal -->
        <div style="
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            padding: 14px 20px;
            background: #ffffff;
        ">

            <?php foreach ($kelompokPertanyaanKuesioner as $kelompok) : ?>

                <div style="
                    margin: 14px 0 10px;
                    padding: 8px 12px;
                    border-left: 4px solid #0A2D27;
                    background: #ecfdf5;
                    border-radius: 8px;
                    color: #0A2D27;
                    font-size: 13px;
                    font-weight: 700;
                ">
                    <?= esc($kelompok['judul']) ?>
                    <span style="color: #dc2626; font-weight: 700;">*</span>
                </div>

                <?php foreach ($kelompok['items'] as $nomor => $pertanyaan) : ?>
                    <div style="
                        margin-bottom: 10px;
                        padding: 12px 14px;
                        border: 1px solid #e5e7eb;
                        border-radius: 10px;
                        background: #f9fafb;
                    ">
                        <p style="
                            font-size: 13px;
                            line-height: 1.45;
                            color: #1f2937;
                            margin: 0 0 9px;
                        ">
                            <strong><?= $nomor ?>.</strong>
                            <?= esc($pertanyaan) ?>
                        </p>
                
                        <div style="
                            display: flex;
                            align-items: center;
                            gap: 28px;
                            font-size: 13px;
                            color: #374151;
                        ">
                            <label style="display: inline-flex; align-items: center; gap: 7px; cursor: pointer;">
                                <input type="radio"
                                       name="q[q<?= $nomor ?>]"
                                       value="ya"
                                       <?= (($jawabanKuesioner['q' . $nomor] ?? '') === 'ya') ? 'checked' : '' ?>>
                                <span>Ya</span>
                            </label>
                
                            <label style="display: inline-flex; align-items: center; gap: 7px; cursor: pointer;">
                                <input type="radio"
                                       name="q[q<?= $nomor ?>]"
                                       value="tidak"
                                       <?= (($jawabanKuesioner['q' . $nomor] ?? '') === 'tidak') ? 'checked' : '' ?>>
                                <span>Tidak</span>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            <?php endforeach; ?>

        </div>

        <!-- Footer Modal -->
        <div style="
            flex: 0 0 auto;
            padding: 12px 20px;
            border-top: 1px solid #e5e7eb;
            background: #ffffff;
            display: flex;
            justify-content: flex-end;
        ">
            <button type="button"
                    onclick="isiDropdownHasilAnamnesis(); close_modalKuesionerSkrining();"
                    style="
                        padding: 8px 18px;
                        font-size: 13px;
                        font-weight: 600;
                        border-radius: 8px;
                        border: none;
                        background: #0A2D27;
                        color: #ACF2E7;
                        cursor: pointer;
                    ">
                Selesai
            </button>
        </div>

    </div>
</div>

<script>
    function open_modalKuesionerSkrining() {
        const modal = document.getElementById('modalKuesionerSkrining');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function isiDropdownHasilAnamnesis() {
        const dropdownAnamnesis = document.getElementById('id_hasil_anamnesis');

        if (!dropdownAnamnesis) {
            return;
        }

        const wajibYa = [1];
        const wajibTidak = [];

        for (let i = 2; i <= 41; i++) {
            wajibTidak.push(i);
        }

        let lengkap = true;
        let memenuhi = true;

        for (let i = 1; i <= 41; i++) {
            const jawaban = document.querySelector(`input[name="q[q${i}]"]:checked`);

            if (!jawaban) {
                lengkap = false;
                memenuhi = false;
                break;
            }

            if (wajibYa.includes(i) && jawaban.value !== 'ya') {
                memenuhi = false;
            }

            if (wajibTidak.includes(i) && jawaban.value !== 'tidak') {
                memenuhi = false;
            }
        }

        if (!lengkap) {
            alert('Mohon lengkapi seluruh 41 pertanyaan informed consent terlebih dahulu.');
            return;
        }

        const ID_MEMENUHI_SYARAT = '1';
        const ID_TIDAK_MEMENUHI_SYARAT = '2';

        if (memenuhi) {
            dropdownAnamnesis.value = ID_MEMENUHI_SYARAT;
        } else {
            dropdownAnamnesis.value = ID_TIDAK_MEMENUHI_SYARAT;
        }

        dropdownAnamnesis.dispatchEvent(new Event('change'));

        const statusKuesioner = document.getElementById('statusKuesioner');

        if (statusKuesioner) {
            statusKuesioner.innerText = 'Sudah diisi';
            statusKuesioner.style.backgroundColor = '#dcfce7';
            statusKuesioner.style.color = '#15803d';
        }
    }

    function close_modalKuesionerSkrining() {
        const modal = document.getElementById('modalKuesionerSkrining');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
</script>