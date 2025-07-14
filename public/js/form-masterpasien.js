
document.addEventListener('DOMContentLoaded', function () {
    // 📅 Auto Hitung Umur
    const tglLahirInput = document.getElementById('tgl_lahir');
    const umurInput = document.getElementById('umur');

    function hitungUmur(tanggal) {
        const tgl = new Date(tanggal);
        const now = new Date();
        if (isNaN(tgl.getTime())) return '';
        let tahun = now.getFullYear() - tgl.getFullYear();
        let bulan = now.getMonth() - tgl.getMonth();
        let hari = now.getDate() - tgl.getDate();
        if (hari < 0) {
            bulan--;
            hari += new Date(now.getFullYear(), now.getMonth(), 0).getDate();
        }
        if (bulan < 0) {
            tahun--;
            bulan += 12;
        }
        return `${tahun} Th ${bulan} Bl ${hari} Hr`;
    }

    if (tglLahirInput && umurInput) {
        tglLahirInput.addEventListener('change', () => {
            umurInput.value = hitungUmur(tglLahirInput.value);
        });
        umurInput.addEventListener('keydown', e => e.preventDefault());
        umurInput.addEventListener('paste', e => e.preventDefault());
    }

    // 🔢 Validasi Angka
    function onlyNumber(selector) {
        const input = document.querySelector(selector);
        if (input) {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    }
    ['#no_ktp', '#no_tlp', '#nip'].forEach(sel => onlyNumber(sel));

    // 👤 Autofill Nama, Alamat, dan Pekerjaan PJ jika DIRI SENDIRI
    const pjSelect = document.getElementById('keluarga');
    const namaPasien = document.getElementById('nm_pasien');
    const namaPJ = document.getElementById('namakeluarga');
    const pekerjaanPasien = document.getElementById('pekerjaan');
    const pekerjaanPJ = document.getElementById('pekerjaanpj');

    const alamatPasien = document.getElementById('alamat');
    const kelurahanPasien = document.getElementById('kd_kel');
    const kecamatanPasien = document.getElementById('kd_kec');
    const kabupatenPasien = document.getElementById('kd_kab');
    const provinsiPasien = document.getElementById('kd_prop');

    const alamatPJ = document.getElementById('alamatpj');
    const kelurahanPJ = document.getElementById('kelurahanpj');
    const kecamatanPJ = document.getElementById('kecamatanpj');
    const kabupatenPJ = document.getElementById('kabupatenpj');
    const provinsiPJ = document.getElementById('propinsipj');

    function autofillPJ() {
        const isDiriSendiri = pjSelect.value === 'DIRI SENDIRI';

        if (isDiriSendiri) {
            namaPJ.value = namaPasien.value;
            pekerjaanPJ.value = pekerjaanPasien.value;
        }

        [alamatPJ, kelurahanPJ, kecamatanPJ, kabupatenPJ, provinsiPJ].forEach((el, i) => {
            const val = [alamatPasien, kelurahanPasien, kecamatanPasien, kabupatenPasien, provinsiPasien][i].value;
            el.value = isDiriSendiri ? val : '';
            el.readOnly = isDiriSendiri;
        });

        namaPJ.readOnly = isDiriSendiri;
        pekerjaanPJ.readOnly = isDiriSendiri;

        if (!isDiriSendiri) {
            namaPJ.value = '';
            pekerjaanPJ.value = '';
        }
    }

    const fieldsToSync = [pekerjaanPasien, alamatPasien, kelurahanPasien, kecamatanPasien, kabupatenPasien, provinsiPasien];
    fieldsToSync.forEach(field => {
        if (field) {
            field.addEventListener('input', function () {
                if (pjSelect.value === 'DIRI SENDIRI') {
                    autofillPJ();
                }
            });
        }
    });

    if (pjSelect && namaPasien && namaPJ) {
        pjSelect.addEventListener('change', () => setTimeout(() => autofillPJ(), 50));
        namaPasien.addEventListener('input', () => {
            if (pjSelect.value === 'DIRI SENDIRI') {
                namaPJ.value = namaPasien.value;
            }
        });
        pekerjaanPasien.addEventListener('input', () => {
            if (pjSelect.value === 'DIRI SENDIRI') {
                pekerjaanPJ.value = pekerjaanPasien.value;
            }
        });
    }
});
