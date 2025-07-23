<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => ($mode ?? 'tambah') === 'ubah' ? 'Ubah Data Kelahiran Bayi' : 'Form Tambah Kelahiran Bayi']) ?>

        <form action="<?= base_url(
                            ($mode ?? 'tambah') === 'ubah'
                                ? "/kelahiranbayi/simpanUbah/{$no_rkm_medis}"
                                : "/kelahiranbayi/simpanTambah"
                        ) ?>" method="post" id="myForm" onsubmit="return validateForm()">
            <?= csrf_field() ?>

            <!-- No. Rekam Medis dan Nama Bayi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No. Rekam Medis Bayi<span class="text-red-600">*</span></label>
                <input type="text" id="no_rkm_medis" name="no_rkm_medis"
                    value="<?= old('no_rkm_medis', $bayi['no_rkm_medis'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" <?= ($mode ?? 'tambah') === 'ubah' ? 'readonly' : '' ?> required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Bayi<span class="text-red-600">*</span></label>
                <input type="text" id="nm_pasien" name="nm_pasien"
                    value="<?= old('nm_pasien', $bayi['nm_pasien'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Nama Bayi wajib diisi.">
            </div>

            <!-- JK dan Panjang Badan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin<span class="text-red-600">*</span></label>
                <select id="jk" name="jk"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Jenis Kelamin wajib dipilih.">
                    <option value="" disabled <?= old('jk', $bayi['jk'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <option value="L" <?= old('jk', $bayi['jk'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= old('jk', $bayi['jk'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Panjang Badan (cm)<span class="text-red-600">*</span></label>
                <input type="number" step="0.1" id="pb" name="pb"
                    value="<?= old('pb', $bayi['pb'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Panjang Badan wajib diisi.">
            </div>

            <!-- Berat Badan dan Lingkar Dada -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Berat Badan (gram)<span class="text-red-600">*</span></label>
                <input type="number" step="0.1" id="bb" name="bb"
                    value="<?= old('bb', $bayi['bb'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Berat Badan wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Lingkar Dada (cm)<span class="text-red-600">*</span></label>
                <input type="number" step="0.1" id="lk_dada" name="lk_dada"
                    value="<?= old('lk_dada', $bayi['lk_dada'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Lingkar Dada wajib diisi.">
            </div>

            <!-- Lingkar Kepala dan Lingkar Perut -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Lingkar Kepala (cm)<span class="text-red-600">*</span></label>
                <input type="number" step="0.1" id="lk_kepala" name="lk_kepala"
                    value="<?= old('lk_kepala', $bayi['lk_kepala'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Lingkar Kepala wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Lingkar Perut (cm)<span class="text-red-600">*</span></label>
                <input type="number" step="0.1" id="lk_perut" name="lk_perut"
                    value="<?= old('lk_perut', $bayi['lk_perut'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Lingkar Perut wajib diisi.">
            </div>

            <!-- Tanggal dan Jam Lahir -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Lahir<span class="text-red-600">*</span></label>
                <input type="date" id="tgl_lahir" name="tgl_lahir"
                    value="<?= old('tgl_lahir', $bayi['tgl_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Tanggal Lahir wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam Lahir<span class="text-red-600">*</span></label>
                <input type="time" id="jam" name="jam"
                    value="<?= old('jam', $bayi['jam'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Jam Lahir wajib diisi.">
            </div>

            <!-- Tempat Lahir dan Umur -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tempat Lahir<span class="text-red-600">*</span></label>
                <input type="text" id="tmp_lahir" name="tmp_lahir"
                    value="<?= old('tmp_lahir', $bayi['tmp_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Tempat Lahir wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur<span class="text-red-600">*</span></label>
                <input type="text" id="umur" name="umur"
                    value="<?= old('umur', $bayi['umur'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
            </div>


            <!-- Tanggal Daftar dan No. SKL -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Daftar<span class="text-red-600">*</span></label>
                <input type="date" id="tgl_daftar" name="tgl_daftar"
                    value="<?= old('tgl_daftar', $bayi['tgl_daftar'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Tanggal Daftar wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">No. SKL<span class="text-red-600">*</span></label>
                <input type="text" id="no_skl" name="no_skl"
                    value="<?= old('no_skl', $bayi['no_skl'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
            </div>

            <!-- Nama Ibu dan Umur Ibu -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nama Ibu<span class="text-red-600">*</span></label>
                <input type="text" id="nm_ibu" name="nm_ibu"
                    value="<?= old('nm_ibu', $bayi['nm_ibu'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Nama Ibu wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur Ibu<span class="text-red-600">*</span></label>
                <input type="number" id="umur_ibu" name="umur_ibu"
                    value="<?= old('umur_ibu', $bayi['umur_ibu'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Umur Ibu wajib diisi.">
            </div>

            <!-- Nama Ayah dan Umur Ayah -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nama Ayah</label>
                <input type="text" id="nm_ayah" name="nm_ayah"
                    value="<?= old('nm_ayah', $bayi['nm_ayah'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur Ayah</label>
                <input type="number" id="umur_ayah" name="umur_ayah"
                    value="<?= old('umur_ayah', $bayi['umur_ayah'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Alamat Ibu -->
            <div class="mb-5 sm:block md:flex items-center">
                <label for="alamat" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Alamat Ibu<span class="text-red-600">*</span>
                </label>
                <textarea id="alamat" name="alamat"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    rows="2"
                    required data-error="Alamat Ibu wajib diisi."><?= old('alamat', $bayi['alamat'] ?? '') ?></textarea>
            </div>

            <!-- Judul Keterangan -->
            <div class="mb-3 mt-8">
                <h3 class="text-lg font-bold text-gray-700 dark:text-white">Riwayat Persalinan</h3>
            </div>

            <!-- Penolong dan Keterangan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label for="penolong" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Penolong<span class="text-red-600">*</span>
                </label>
                <input type="text" id="penolong" name="penolong"
                    value="<?= old('penolong', $bayi['penolong'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Nama Penolong wajib diisi.">

                <label for="keterangan" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="2"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"><?= old('keterangan', $bayi['keterangan'] ?? '') ?></textarea>
            </div>


            <!-- Diagnosa dan Ketuban -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Diagnosa<span class="text-red-600">*</span></label>
                <input type="text" id="diagnosa" name="diagnosa"
                    value="<?= old('diagnosa', $bayi['diagnosa'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Diagnosa wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Ketuban<span class="text-red-600">*</span></label>
                <input type="text" id="ketuban" name="ketuban"
                    value="<?= old('ketuban', $bayi['ketuban'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Ketuban wajib diisi.">
            </div>

            <!-- Penyulit dan proses kelahiran -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Penyulit Kehamilan</label>
                <input type="text" id="penyulit_kehamilan" name="penyulit_kehamilan"
                    value="<?= old('penyulit_kehamilan', $bayi['penyulit_kehamilan'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Proses Kelahiran<span class="text-red-600">*</span></label>
                <input type="text" id="proses_lahir" name="proses_lahir"
                    value="<?= old('proses_lahir', $bayi['proses_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Proses Kelahiran wajib diisi.">
            </div>

            <!-- Kelahiran ke dan Gravida -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kelahiran Ke<span class="text-red-600">*</span></label>
                <input type="text" id="kelahiran_ke" name="kelahiran_ke"
                    value="<?= old('kelahiran_ke', $bayi['kelahiran_ke'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Kelahiran Ke wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Gravida<span class="text-red-600">*</span></label>
                <input type="text" id="gravida" name="gravida"
                    value="<?= old('gravida', $bayi['gravida'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Gravida wajib diisi.">
            </div>

            <!-- Para dan Abortus -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Para<span class="text-red-600">*</span></label>
                <input type="text" id="para" name="para"
                    value="<?= old('para', $bayi['para'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Para wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Abortus<span class="text-red-600">*</span></label>
                <input type="text" id="abortus" name="abortus"
                    value="<?= old('abortus', $bayi['abortus'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Abortus wajib diisi.">
            </div>

            <!-- Judul Nilai APGAR -->
            <div class="mb-3 mt-8">
                <h3 class="text-lg font-bold text-gray-700 dark:text-white">Nilai APGAR</h3>
            </div>

            <!-- Tabel APGAR -->
            <div class="overflow-x-auto mb-5">
                <table class="min-w-full border border-gray-300 text-sm text-center">
                    <!-- HEADER -->
                    <thead style="background-color: #E6F2EF;" class="text-gray-900 font-semibold">
                        <tr>
                            <th rowspan="2" class="border border-gray-300 px-4 py-2 align-middle">Tanda</th>
                            <th colspan="3" class="border border-gray-300 px-4 py-2">Nilai</th>
                            <th colspan="3" class="border border-gray-300 px-4 py-2">Skor</th>
                        </tr>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">0</th>
                            <th class="border border-gray-300 px-4 py-2">1</th>
                            <th class="border border-gray-300 px-4 py-2">2</th>
                            <th class="border border-gray-300 px-4 py-2">N 1'</th>
                            <th class="border border-gray-300 px-4 py-2">N 5'</th>
                            <th class="border border-gray-300 px-4 py-2">N 10'</th>
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody class="text-gray-800">
                        <?php
                        $data_apgar = [
                            'Frekuensi Jantung' => ['Tidak Ada', '&lt; 100', '&gt; 100'],
                            'Usaha Nafas' => ['Tidak Ada', 'Lambat Tak Teratur', 'Menangis Kuat'],
                            'Tonus Otot' => ['Lumpuh', 'Ext. Fleksi Sedikit', 'Gerakan Aktif'],
                            'Refleks' => ['Tidak Ada Respon', 'Pergerakan Sedikit', 'Menangis'],
                            'Warna' => ['Biru Pucat', 'Tubuh Kemerahan, Tangan &amp; Kaki Biru', 'Kemerahan']
                        ];
                        $prefixes = ['f', 'u', 't', 'r', 'w'];
                        $index = 0;
                        foreach ($data_apgar as $label => $deskripsi):
                            $id = $prefixes[$index++];
                        ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 text-left font-medium"><?= $label ?></td>
                                <?php foreach ($deskripsi as $d): ?>
                                    <td class="border border-gray-300 px-4 py-2 text-left"><?= $d ?></td>
                                <?php endforeach; ?>
                                <td class="border border-gray-300 p-2"><input type="number" name="<?= $id ?>1" id="<?= $id ?>1" min="0" max="2" value="<?= old($id . '1', $bayi[$id . '1'] ?? '') ?>" class="w-full p-1 text-sm border rounded" required data-error="Nilai APGAR N 1' wajib diisi.">
                                    <small id="error-<?= $id ?>1" class="text-red-500 text-xs mt-1 hidden">Angka tidak valid</small>
                                </td>
                                <td class="border border-gray-300 p-2"><input type="number" name="<?= $id ?>5" id="<?= $id ?>5" min="0" max="2" value="<?= old($id . '5', $bayi[$id . '5'] ?? '') ?>" class="w-full p-1 text-sm border rounded">
                                    <small id="error-<?= $id ?>5" class="text-red-500 text-xs mt-1 hidden">Angka tidak valid</small>
                                </td>
                                <td class="border border-gray-300 p-2"><input type="number" name="<?= $id ?>10" id="<?= $id ?>10" min="0" max="2" value="<?= old($id . '10', $bayi[$id . '10'] ?? '') ?>" class="w-full p-1 text-sm border rounded">
                                    <small id="error-<?= $id ?>10" class="text-red-500 text-xs mt-1 hidden">Angka tidak valid</small>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Jumlah Nilai -->
                        <tr style="background-color: #E6F2EF;" class="font-semibold text-gray-800">
                            <td colspan="4" class="border border-gray-300 px-4 py-2 text-left">Jumlah Nilai</td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="n1" id="n1" value="<?= old('n1', $bayi['n1'] ?? '') ?>" class="w-full p-1 text-sm border rounded text-gray-900" readonly>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="n5" id="n5" value="<?= old('n5', $bayi['n5'] ?? '') ?>" class="w-full p-1 text-sm border rounded text-gray-900" readonly>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="n10" id="n10" value="<?= old('n10', $bayi['n10'] ?? '') ?>" class="w-full p-1 text-sm border rounded text-gray-900" readonly>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>


            <!-- Resusitas dan Mikasi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label for="resusitas" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Resusitas<span class="text-red-600">*</span>
                </label>
                <input type="text" id="resusitas" name="resusitas"
                    value="<?= old('resusitas', $bayi['resusitas'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Resusitas wajib diisi.">

                <label for="mikasi" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Mikasi<span class="text-red-600">*</span>
                </label>
                <input type="text" id="mikasi" name="mikasi"
                    value="<?= old('mikasi', $bayi['mikasi'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Mikasi wajib diisi.">
            </div>

            <!-- Obat Diberikan dan Mikonium -->
            <div class="mb-5 sm:block md:flex items-center">
                <label for="obat" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Obat Diberikan<span class="text-red-600">*</span>
                </label>
                <textarea id="obat" name="obat" rows="2"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Obat Diberikan wajib diisi."><?= old('obat', $bayi['obat'] ?? '') ?></textarea>

                <label for="mikonium" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Mikonium<span class="text-red-600">*</span>
                </label>
                <input type="text" id="mikonium" name="mikonium"
                    value="<?= old('mikonium', $bayi['mikonium'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Mikonium wajib diisi.">
            </div>

            <!-- Button -->
            <div class="mt-5 pt-5 border-t flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]">
                    <?= ($mode ?? 'tambah') === 'ubah' ? 'Perbarui' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="toast-apgar" class="hidden opacity-0 fixed top-6 right-6 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50 text-sm transition-all duration-300">
    Nilai APGAR hanya boleh antara 0 sampai 2.
</div>

<script src="<?= base_url('js/form-validation.js') ?>"></script>
<script>
    // Apgar Score Auto Update + Validasi Maksimum Nilai
    function updateApgarTotal(prefix) {
        const fields = ['f', 'u', 't', 'r', 'w'];
        let total = 0;
        let hasInvalid = false;

        fields.forEach(field => {
            const input = document.getElementById(field + prefix);
            if (input) {
                const errorMsg = document.getElementById('error-' + input.id);
                let val = parseInt(input.value);

                if (input.value !== '' && (isNaN(val) || val < 0 || val > 2)) {
                    // Input invalid
                    hasInvalid = true;
                    input.classList.add('border-red-500', 'ring-1', 'ring-red-500', 'focus:ring-red-500');
                    if (errorMsg) errorMsg.classList.remove('hidden');

                    // Tampilkan toast
                    const toast = document.getElementById('toast-apgar');
                    toast.classList.remove('hidden');
                    toast.classList.add('opacity-100');
                    setTimeout(() => {
                        toast.classList.add('opacity-0');
                        setTimeout(() => toast.classList.add('hidden'), 300);
                    }, 3000);

                    input.value = '';
                } else {
                    // Input valid
                    input.classList.remove('border-red-500', 'ring-1', 'ring-red-500', 'focus:ring-red-500');
                    if (errorMsg) errorMsg.classList.add('hidden');

                    if (!isNaN(val)) {
                        total += val;
                    }
                }
            }
        });

        const totalInput = document.getElementById('n' + prefix);
        if (totalInput) {
            totalInput.value = hasInvalid ? '' : total;
        }
    }


    ['1', '5', '10'].forEach(minute => {
        ['f', 'u', 't', 'r', 'w'].forEach(field => {
            const input = document.getElementById(field + minute);
            if (input) {
                input.addEventListener('input', () => updateApgarTotal(minute));
            }
        });
    });


    // Format Float dengan 2 desimal
    ['pb', 'lk_perut', 'lk_dada', 'lk_kepala'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('blur', () => {
                const val = parseFloat(el.value.replace(',', '.'));
                if (!isNaN(val)) {
                    el.value = val.toFixed(2);
                }
            });
        }
    });

    // Auto Hitung Umur Bayi saat input tgl_lahir dan jam_lahir
    document.addEventListener('DOMContentLoaded', function() {
        const tglLahirInput = document.getElementById('tgl_lahir');
        const jamLahirInput = document.getElementById('jam');
        const umurInput = document.getElementById('umur');

        function updateUmur() {
            const tanggal = tglLahirInput.value;
            const jam = jamLahirInput.value;

            if (!tanggal || !jam) {
                umurInput.value = '';
                return;
            }

            const lahir = new Date(`${tanggal}T${jam}`);
            const now = new Date();

            if (isNaN(lahir.getTime())) {
                umurInput.value = '';
                return;
            }

            let tahun = now.getFullYear() - lahir.getFullYear();
            let bulan = now.getMonth() - lahir.getMonth();
            let hari = now.getDate() - lahir.getDate();

            if (hari < 0) {
                bulan--;
                const prevMonth = new Date(now.getFullYear(), now.getMonth(), 0);
                hari += prevMonth.getDate();
            }

            if (bulan < 0) {
                tahun--;
                bulan += 12;
            }

            let result = '';
            if (tahun > 0) result += `${tahun} Th `;
            if (bulan > 0) result += `${bulan} Bl `;
            result += `${hari} Hr`;

            umurInput.value = result.trim();
        }

        if (tglLahirInput && jamLahirInput && umurInput) {
            tglLahirInput.addEventListener('change', updateUmur);
            jamLahirInput.addEventListener('change', updateUmur);
        }


    });
</script>

<?= $this->endSection(); ?>