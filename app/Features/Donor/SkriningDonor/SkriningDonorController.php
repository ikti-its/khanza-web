<?php
declare(strict_types=1);

namespace App\Features\Donor\SkriningDonor;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;

final class SkriningDonorController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkriningDonorModel(),
            [
                ['Donor',          'donor'],
                ['Skrining Donor', 'skrining_donor'],
            ],
            'Skrining Donor',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::DETAIL,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_skrining',        'ID Skrining'],
                [SHOW, REQUIRED, I::INDEX,  'id_kunjungan',       'ID Kunjungan'],
                [SHOW, REQUIRED, I::FLOAT,  'berat_badan',        'Berat Badan'],
                [SHOW, REQUIRED, I::NUMBER, 'sistolik',           'Tekanan Sistolik'],
                [SHOW, REQUIRED, I::NUMBER, 'diastolik',          'Tekanan Diastolik'],
                [SHOW, REQUIRED, I::NUMBER, 'nadi',               'Denyut Nadi (x/menit)'],
                [SHOW, REQUIRED, I::TEMP,   'suhu_tubuh',         'Suhu'],
                [SHOW, REQUIRED, I::FLOAT,  'kadar_hemoglobin',   'Kadar Hemoglobin'],
                [HIDE, REQUIRED, I::TEXT,   'jawaban_kuesioner',  'Jawaban Kuesioner'],
                [SHOW, REQUIRED, I::SELECT, 'id_hasil_anamnesis', 'Hasil Anamnesis'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_skrining', 'Status Skrining'],
            ],
        );
    }

    /**
     * OVERRIDE: Halaman Utama Skrining Donor
     * 
     * @throws DatabaseException
     */
    #[\Override]
    final public function index(): string
    {
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage     = 10;
        $offset      = ($currentPage - 1) * $perPage;

        $totalRows  = $this->model->count_filtered();

        $skriningModel = new SkriningDonorModel();
        $data_tabel    = $skriningModel->get_data_tabel($perPage, $offset);

        $konfig = [
            [1, 'Nomor Kunjungan', 'nomor_kunjungan',      'teks',   0],
            [1, 'Nomor Pendonor',  'nomor_pendonor',       'teks',   0],
            [1, 'Nama Lengkap',    'nama',                 'teks',   0],
            [1, 'Status Skrining', 'nama_status_skrining', 'status', 0],
        ];

        return view('/layouts/data', [
            'judul'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs,
            'meta_data'    => [
                'page'  => $currentPage,
                'size'  => count($data_tabel),
                'total' => ceil($totalRows / $perPage),
            ],
            'modul_path'   => $this->get_uri_path(),
            'kolom_id'     => $this->primary_key,
            'konfig'       => $konfig,
            'aksi'         => $this->actions,
            'tabel'        => $data_tabel,
            'row_alert'    => [],
            'child_link'   => null,
            'query_string' => '',
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Form Skrining Donor
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah'],
        ];

        $controllerKunjungan = new \App\Features\Donor\Kunjungan\KunjunganController();
        $controllerPendonor  = new \App\Features\Role\Pendonor\PendonorController();
        $controllerOrang     = new \App\Features\Person\Orang\OrangController();

        /** @var list<array<int, mixed>> $konfigSkrining */
        $konfigSkrining  = $this->get_fields_with_options(false, true);
        $konfigKunjungan = $controllerKunjungan->fields;
        $konfigPendonor  = $controllerPendonor->fields;
        $konfigOrang     = $controllerOrang->fields;

        $mockBaris      = [];
        $konfigGabungan = [];

        foreach ($konfigSkrining as $fieldSkrining) {
            if (!isset($fieldSkrining[2])) {
                continue;
            }

            $columnSkrining = (string) $fieldSkrining[2];

            if ($columnSkrining === 'id_skrining') {
                continue;
            }

            $mockBaris[$columnSkrining] = '';

            if ($columnSkrining === 'id_kunjungan') {
                foreach ($konfigKunjungan as $fieldKunjungan) {
                    if ($fieldKunjungan[2] === 'nomor_kunjungan') {
                        $mockBaris['nomor_kunjungan'] = '';
                        $konfigGabungan[]             = $fieldKunjungan;
                        break;
                    }
                }

                foreach ($konfigPendonor as $fieldPendonor) {
                    if ($fieldPendonor[2] === 'nomor_pendonor') {
                        $mockBaris['nomor_pendonor'] = '';
                        $konfigGabungan[]            = $fieldPendonor;
                        break;
                    }
                }

                foreach ($konfigOrang as $fieldOrang) {
                    if ($fieldOrang[2] === 'nama') {
                        $mockBaris['nama'] = '';
                        $konfigGabungan[]  = $fieldOrang;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $fieldSkrining;
        }

        return view('/admin/donor/tambah_skriningdonor', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigGabungan,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Memproses Simpan Data Skrining Donor
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        /** @var array<string, mixed> $rawPost */
        $rawPost  = $this->request->getPost();
        $jawabanQ = isset($rawPost['q']) && is_array($rawPost['q']) ? $rawPost['q'] : [];

        /** @var array{status: int, alasan: list<string>} $hasilSkrining */
        $skriningModel    = new SkriningDonorModel();
        $hasilSkrining    = $skriningModel->hitungOtomatisStatusSkrining($rawPost);
        $idStatusSkrining = $hasilSkrining['status'];
        $daftarAlasan     = $hasilSkrining['alasan'];

        $dataSkrining = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataSkrining[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $dataSkrining['id_status_skrining'] = $idStatusSkrining;
        $dataSkrining['jawaban_kuesioner']  = !empty($jawabanQ) ? json_encode($jawabanQ) : null;

        $anamnesisTidakMemenuhiSyarat = (int) ($rawPost['id_hasil_anamnesis'] ?? 0) !== 1;
        $idKunjungan                  = !empty($dataSkrining['id_kunjungan']) 
            ? (int) $dataSkrining['id_kunjungan'] 
            : null;
        $pencekalanUrl                = null;

        $this->model->db->transStart();
        try {
            $this->model->insert($dataSkrining);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menyimpan data skrining donor.');
            }

            if ($anamnesisTidakMemenuhiSyarat && !empty($idKunjungan) && !$this->hasPencekalan($idKunjungan)) {
                $pencekalanUrl = $this->makePencekalanUrlAnamnesis($idKunjungan);
            }

            if ($pencekalanUrl !== null) {
                session()->set('pencekalan_url', $pencekalanUrl);
                session()->set('pencekalan_title', 'Hasil Anamnesis Tidak Memenuhi Syarat');
                session()->set(
                    'pencekalan_message',
                    'Silakan lengkapi data pencekalan terlebih dahulu sebagai tindak lanjut hasil anamnesis donor yang tidak memenuhi syarat.',
                );
            } elseif ($idStatusSkrining === 1) {
                session()->setFlashdata('success', 'Data skrining berhasil disimpan. Pendonor dinyatakan LOLOS.');
            } else {
                $teksAlasan = implode(', ', $daftarAlasan);
                session()->setFlashdata(
                    'success',
                    "Data skrining berhasil disimpan. Pendonor dinyatakan GAGAL/DITUNDA karena indikator: {$teksAlasan}.",
                );
            }
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = $e instanceof DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Skrining Donor
     * 
     * @throws DatabaseException
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $dataSkrining = $this->model->find($id);
        if (!is_array($dataSkrining)) {
            $dataSkrining = [];
        }

        $dataKunjungan = [];
        $dataPendonor  = [];
        $dataOrang     = [];

        if (!empty($dataSkrining['id_kunjungan'])) {
            $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
            $dataKunjungan  = $modelKunjungan->find((int) $dataSkrining['id_kunjungan']);
            if (!is_array($dataKunjungan)) {
                $dataKunjungan = [];
            }

            if (!empty($dataKunjungan['id_pendonor'])) {
                $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
                $dataPendonor  = $modelPendonor->find((int) $dataKunjungan['id_pendonor']);
                if (!is_array($dataPendonor)) {
                    $dataPendonor = [];
                }

                if (!empty($dataPendonor['id_orang'])) {
                    $modelOrang = new \App\Features\Person\Orang\OrangModel();
                    $dataOrang  = $modelOrang->find((int) $dataPendonor['id_orang']);
                    if (!is_array($dataOrang)) {
                        $dataOrang = [];
                    }
                }
            }
        }

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan, $dataSkrining);

        $controllerKunjungan = new \App\Features\Donor\Kunjungan\KunjunganController();
        $controllerPendonor  = new \App\Features\Role\Pendonor\PendonorController();
        $controllerOrang     = new \App\Features\Person\Orang\OrangController();

        /** @var list<array<int, mixed>> $konfigSkrining */
        $konfigSkrining  = $this->get_fields_with_options(false, true);
        $konfigKunjungan = $controllerKunjungan->fields;
        $konfigPendonor  = $controllerPendonor->fields;
        $konfigOrang     = $controllerOrang->fields;

        $konfigGabungan = [];

        foreach ($konfigSkrining as $fieldSkrining) {
            if (!isset($fieldSkrining[2])) {
                continue;
            }

            $columnSkrining = (string) $fieldSkrining[2];

            if ($columnSkrining === 'id_skrining') {
                continue;
            }

            if ($columnSkrining === 'id_kunjungan') {
                foreach ($konfigKunjungan as $fieldKunjungan) {
                    if ($fieldKunjungan[2] === 'nomor_kunjungan') {
                        $konfigGabungan[] = $fieldKunjungan;
                        break;
                    }
                }

                foreach ($konfigPendonor as $fieldPendonor) {
                    if ($fieldPendonor[2] === 'nomor_pendonor') {
                        $konfigGabungan[] = $fieldPendonor;
                        break;
                    }
                }

                foreach ($konfigOrang as $fieldOrang) {
                    if ($fieldOrang[2] === 'nama') {
                        $konfigGabungan[] = $fieldOrang;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $fieldSkrining;
        }

        foreach ($konfigGabungan as $field) {
            $namaKolom = (string) $field[2];
            if (($baris[$namaKolom] ?? null) === null) {
                $baris[$namaKolom] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'Ubah'],
        ];

        return view('/admin/donor/tambah_skriningdonor', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigGabungan,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    /**
     * OVERRIDE: Mengeksekusi Simpan Perubahan Data Skrining Donor
     * 
     * @throws DatabaseException
     */
    #[\Override]
    final public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) {
            return $this->index();
        }

        /** @var array<string, mixed> $rawPost */
        $rawPost  = $this->request->getPost();
        $jawabanQ = isset($rawPost['q']) && is_array($rawPost['q']) ? $rawPost['q'] : [];

        /** @var array{status: int, alasan: list<string>} $hasilSkrining */
        $skriningModel    = new SkriningDonorModel();
        $hasilSkrining    = $skriningModel->hitungOtomatisStatusSkrining($rawPost);
        $idStatusSkrining = $hasilSkrining['status'];
        $daftarAlasan     = $hasilSkrining['alasan'];

        $dataSkrining = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataSkrining[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $dataSkrining['id_status_skrining'] = $idStatusSkrining;
        $dataSkrining['jawaban_kuesioner']  = !empty($jawabanQ) ? json_encode($jawabanQ) : null;

        $anamnesisTidakMemenuhiSyarat = (int) ($rawPost['id_hasil_anamnesis'] ?? 0) !== 1;
        $idKunjungan                  = !empty($dataSkrining['id_kunjungan']) 
            ? (int) $dataSkrining['id_kunjungan'] 
            : null;
        $pencekalanUrl                = null;

        $this->model->db->transStart();
        try {
            $dataLama         = $this->model->find($id);
            $statusSebelumnya = (int) ($dataLama['id_status_skrining'] ?? 0);

            $this->model->update($id, $dataSkrining);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal memperbarui data skrining donor.');
            }

            if ($anamnesisTidakMemenuhiSyarat && !empty($idKunjungan) && !$this->hasPencekalan($idKunjungan)) {
                $pencekalanUrl = $this->makePencekalanUrlAnamnesis($idKunjungan);
            } elseif (!$anamnesisTidakMemenuhiSyarat && !empty($idKunjungan)) {
                $this->hapusPencekalan($idKunjungan);
            }

            if ($pencekalanUrl !== null) {
                session()->set('pencekalan_url', $pencekalanUrl);
                session()->set('pencekalan_title', 'Hasil Anamnesis Tidak Memenuhi Syarat');
                session()->set(
                    'pencekalan_message',
                    'Silakan lengkapi data pencekalan terlebih dahulu sebagai tindak lanjut hasil anamnesis donor yang tidak memenuhi syarat.',
                );
            } elseif ($statusSebelumnya === 1 && $idStatusSkrining === 1) {
                session()->setFlashdata('success', 'Data skrining berhasil diperbarui.');
            } elseif ($statusSebelumnya === 1 && $idStatusSkrining === 2) {
                $teksAlasan = implode(', ', $daftarAlasan);
                session()->setFlashdata(
                    'success',
                    "Data skrining berhasil diperbarui. Pendonor dinyatakan GAGAL/DITUNDA karena indikator: {$teksAlasan}.",
                );
            } elseif ($statusSebelumnya === 2 && $idStatusSkrining === 1) {
                session()->setFlashdata('success', 'Data skrining berhasil diperbarui. Pendonor dinyatakan LOLOS.');
            } elseif ($statusSebelumnya === 2 && $idStatusSkrining === 2) {
                session()->setFlashdata('success', 'Data skrining berhasil diperbarui.');
            }
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = $e instanceof DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * Menampilkan Halaman Detail Skrining Donor
     * 
     * @throws DatabaseException
     */
    public function detail(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $dataSkrining = $this->model->find($id);

        if (!is_array($dataSkrining)) {
            $dataSkrining = [];
        }

        $dataKunjungan = [];
        $dataPendonor  = [];
        $dataOrang     = [];

        if (!empty($dataSkrining['id_kunjungan'])) {
            $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
            $dataKunjungan  = $modelKunjungan->find((int) $dataSkrining['id_kunjungan']);
            if (!is_array($dataKunjungan)) {
                $dataKunjungan = [];
            }

            if (!empty($dataKunjungan['id_pendonor'])) {
                $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
                $dataPendonor  = $modelPendonor->find((int) $dataKunjungan['id_pendonor']);
                if (!is_array($dataPendonor)) {
                    $dataPendonor = [];
                }

                if (!empty($dataPendonor['id_orang'])) {
                    $modelOrang = new \App\Features\Person\Orang\OrangModel();
                    $dataOrang  = $modelOrang->find((int) $dataPendonor['id_orang']);
                    if (!is_array($dataOrang)) {
                        $dataOrang = [];
                    }
                }
            }
        }

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan, $dataSkrining);

        $controllerKunjungan = new \App\Features\Donor\Kunjungan\KunjunganController();
        $controllerPendonor  = new \App\Features\Role\Pendonor\PendonorController();
        $controllerOrang     = new \App\Features\Person\Orang\OrangController();

        $konfigSkrining  = $this->get_fields_with_options(false, true);
        $konfigKunjungan = $controllerKunjungan->get_fields_with_options(false, true);
        $konfigPendonor  = $controllerPendonor->get_fields_with_options(false, true);
        $konfigOrang     = $controllerOrang->get_fields_with_options(false, true);

        $konfigGabungan = array_merge($konfigOrang, $konfigPendonor, $konfigKunjungan, $konfigSkrining);

        /** @var list<array<int, mixed>> $fieldsList */
        $fieldsList = array_values(array_filter($konfigGabungan, 'is_array'));

        foreach ($fieldsList as $field) {
            if (!isset($field[2])) {
                continue;
            }

            $colName = (string) $field[2];
            $options = is_array($field[5] ?? null) ? $field[5] : [];

            if (!empty($options) && isset($baris[$colName])) {
                $idMentah = (string) $baris[$colName];

                /** @var list<array<int, mixed>> $optionsList */
                $optionsList = array_values(array_filter($options, 'is_array'));

                foreach ($optionsList as $opt) {
                    if ((string) ($opt[1] ?? '') === $idMentah) {
                        $baris[$colName] = $opt[0] ?? '';
                        break;
                    }
                }
            }
        }

        foreach (array_keys($baris) as $key) {
            if ($baris[$key] === null) {
                $baris[$key] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Detail', 'icon' => 'detail'],
        ];

        return view('/admin/donor/detail_skriningdonor', [
            'judul'       => 'Detail ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'baris'       => $baris,
        ]);
    }

    /**
     * Membuat URL form pencekalan dari hasil anamnesis yang tidak memenuhi syarat
     */
    private function makePencekalanUrlAnamnesis(int|string $idKunjungan): string
    {
        $queryPencekalan = [
            'kunjungan' => $idKunjungan,
        ];

        return '/penanganan-donor/pencekalan/tambah?' . http_build_query($queryPencekalan);
    }

    /**
     * Mengecek apakah pencekalan untuk kunjungan tersebut sudah ada
     */
    private function hasPencekalan(int|string $idKunjungan): bool
    {
        if (empty($idKunjungan)) {
            return false;
        }

        $modelPencekalan = new \App\Features\PenangananDonor\Pencekalan\PencekalanModel();

        return $modelPencekalan->where('id_kunjungan', $idKunjungan)->first() !== null;
    }

    /**
     * Menghapus pencekalan karena hasil anamnesis sudah memenuhi syarat
     * 
     * @throws DatabaseException
     */
    private function hapusPencekalan(int|string $idKunjungan): void
    {
        if (empty($idKunjungan)) {
            return;
        }

        $modelPencekalan = new \App\Features\PenangananDonor\Pencekalan\PencekalanModel();

        $modelPencekalan->where('id_kunjungan', $idKunjungan)->delete();
    }
}
