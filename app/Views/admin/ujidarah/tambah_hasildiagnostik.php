<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalkasusreaktif') ?>

<?php
    $daftarNilaiDiagnostik = [];

    foreach (($nilai_diagnostik ?? []) as $nilai) {
        $daftarNilaiDiagnostik[] = [
            'id'   => $nilai['id_nilai_diagnostik'] ?? '',
            'nama' => $nilai['nama_nilai_diagnostik'] ?? '',
        ];
    }
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_kasus" id="id_kasus" value="<?= old('id_kasus', $baris['id_kasus'] ?? '') ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Kasus Reaktif<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text"
                           id="nomor_kasus"
                           name="nomor_kasus"
                           readonly
                           required
                           value="<?= old('nomor_kasus', $baris['nomor_kasus'] ?? '') ?>"
                           placeholder="Klik cari..."
                           onclick="open_modalKasusReaktif()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">

                    <button type="button" onclick="open_modalKasusReaktif()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Hasil<span class="text-red-600">*</span>
                </label>
                <input type="date"
                       name="tanggal_hasil"
                       id="tanggal_hasil"
                       value="<?= old('tanggal_hasil', $baris['tanggal_hasil'] ?? date('Y-m-d')) ?>"
                       max="<?= date('Y-m-d') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white"
                       required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Fasyankes Rujukan<span class="text-red-600">*</span>
                </label>
                <input type="text"
                       name="fasyankes_rujukan"
                       id="fasyankes_rujukan"
                       value="<?= old('fasyankes_rujukan', $baris['fasyankes_rujukan'] ?? '') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white"
                       required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Dokter Pemeriksa<span class="text-red-600">*</span>
                </label>
                <input type="text"
                       name="dokter_pemeriksa"
                       id="dokter_pemeriksa"
                       value="<?= old('dokter_pemeriksa', $baris['dokter_pemeriksa'] ?? '') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white"
                       required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Hasil Tes<span class="text-red-600">*</span>
                </label>
            
                <div class="w-full lg:w-1/4">
                    <div class="flex flex-col">
                        <div class="overflow-hidden border border-gray-300 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-800 shadow-sm h-full">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                                <thead class="bg-gray-50 text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                                    <tr class="text-sm text-gray-700 border-b border-gray-200 dark:border-gray-700">
                                        <th class="p-3 w-1/2">Parameter Uji</th>
                                        <th class="p-3 w-1/2">Nilai Diagnostik</th>
                                    </tr>
                                </thead>
            
                                <tbody id="bodyParameterDiagnostik"
                                       class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-800 dark:text-gray-200">
                                    <tr id="parameterDiagnostikKosong">
                                        <td colspan="2" class="text-center py-4 text-gray-400 italic dark:text-gray-500 bg-gray-50/10">
                                            Pilih kasus reaktif terlebih dahulu
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    const nilaiDiagnostikOptions = <?= json_encode($daftarNilaiDiagnostik) ?>;

    function autofillKasusReaktif(item) {
        document.getElementById('id_kasus').value = item.id_kasus || '';
        document.getElementById('nomor_kasus').value = item.nomor_kasus || '';

        renderParameterDiagnostik(item.parameter_detail || []);
    }

    function renderParameterDiagnostik(parameterList) {
        const tbody = document.getElementById('bodyParameterDiagnostik');

        tbody.innerHTML = '';

        if (parameterList.length === 0) {
            const trKosong = document.createElement('tr');

            const tdKosong = document.createElement('td');
            tdKosong.colSpan = 2;
            tdKosong.className = 'text-center py-4 text-gray-400 italic dark:text-gray-500 bg-gray-50/10';
            tdKosong.textContent = 'Parameter reaktif belum tersedia pada kasus yang dipilih';

            trKosong.appendChild(tdKosong);
            tbody.appendChild(trKosong);

            return;
        }

        parameterList.forEach(function(parameter) {
            const idParameter = parameter.id_parameter_uji || '';
            const namaParameter = parameter.nama_parameter || '-';

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition';

            const kolomParameter = document.createElement('td');
            kolomParameter.className = 'p-3 align-top text-gray-900 dark:text-white';
            kolomParameter.textContent = namaParameter;

            const inputParameter = document.createElement('input');
            inputParameter.type = 'hidden';
            inputParameter.name = 'id_parameter_uji[]';
            inputParameter.value = idParameter;

            kolomParameter.appendChild(inputParameter);

            const kolomNilai = document.createElement('td');
            kolomNilai.className = 'p-3';

            const radioWrapper = document.createElement('div');
            radioWrapper.className = 'flex flex-col gap-2';

            nilaiDiagnostikOptions.forEach(function(nilai) {
                const labelRadio = document.createElement('label');
                labelRadio.className = 'inline-flex items-center gap-2 text-sm text-gray-900 dark:text-gray-300 cursor-pointer';

                const radioNilai = document.createElement('input');
                radioNilai.type = 'radio';
                radioNilai.name = 'id_nilai_diagnostik[' + idParameter + ']';
                radioNilai.value = nilai.id;
                radioNilai.required = true;
                radioNilai.className = 'w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600';

                const teksNilai = document.createElement('span');
                teksNilai.textContent = nilai.nama;

                labelRadio.appendChild(radioNilai);
                labelRadio.appendChild(teksNilai);

                radioWrapper.appendChild(labelRadio);
            });

            kolomNilai.appendChild(radioWrapper);

            tr.appendChild(kolomParameter);
            tr.appendChild(kolomNilai);

            tbody.appendChild(tr);
        });
    }

    function validateForm() {
        const idKasus = document.getElementById('id_kasus').value;
        const parameterDipilih = document.querySelectorAll('input[name="id_parameter_uji[]"]');
        const requiredFields = document.querySelectorAll('#myForm input[required], #myForm select[required]');

        if (!idKasus) {
            alert('Silakan pilih kasus reaktif terlebih dahulu.');
            return false;
        }

        if (parameterDipilih.length === 0) {
            alert('Parameter reaktif belum tersedia. Mohon pilih kasus reaktif yang valid.');
            return false;
        }

        for (let i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert('Mohon isi semua field yang bertanda bintang.');
                requiredFields[i].focus();
                return false;
            }
        }

        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.setAttribute('disabled', true);
            submitButton.innerHTML = 'Menyimpan...';
        }

        return true;
    }
</script>

<?= $this->endSection(); ?>