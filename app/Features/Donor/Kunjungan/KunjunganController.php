<?php
declare(strict_types=1);

namespace App\Features\Donor\Kunjungan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class KunjunganController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KunjunganModel(),
            [
                ['Donor',     'donor'],
                ['Registrasi Kunjungan', 'registrasi_kunjungan'],
            ],
            'Registrasi Kunjungan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::DETAIL,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_kunjungan',      'ID Kunjungan'],
                [SHOW, REQUIRED, I::TEXT,  'nomor_kunjungan',   'Nomor Kunjungan'],
                [SHOW, REQUIRED, I::DTIME, 'tanggal_kunjungan', 'Tanggal Kunjungan'],
                [SHOW, REQUIRED, I::INDEX, 'id_pendonor',       'ID Pendonor'],
            ],
        );
    }

    /**
     * OVERRIDE: Halaman Utama Registrasi Kunjungan
     */
    #[\Override]
    final public function index(): string
    {
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage     = 10;
        $offset      = ($currentPage - 1) * $perPage;

        $totalRows  = $this->model->count_filtered();
        $data_tabel = $this->model->get_data_tabel($perPage, $offset);

        $konfig = [
            [1, 'Nomor Kunjungan',   'nomor_kunjungan',   'teks',        0],
            [1, 'Tanggal Kunjungan', 'tanggal_kunjungan', 'tanggal_jam', 0],
            [1, 'Nomor Pendonor',    'nomor_pendonor',    'teks',        0],
            [1, 'Nama Lengkap',      'nama',              'teks',        0],
        ];

        return view('/layouts/data', [
            'judul'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs,
            'meta_data'    => ['page' => $currentPage, 'size' => count($data_tabel), 'total' => ceil($totalRows / $perPage)],
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
     * OVERRIDE: Menampilkan Form Registrasi Kunjungan
     */
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $fieldsKunjunganMatang = $this->fields;
        
        $controllerPendonor = new \App\Features\Role\Pendonor\PendonorController();
        $fieldsPendonorMatang = $controllerPendonor->fields;

        $mockBaris = [];
        $konfigGabungan = [];

        $tanggalHariIni = date('Y-m-d H:i:s');
        
        $jumlahKunjunganHariIni = $this->model->db->table('donor.kunjungan')
            ->where('DATE(tanggal_kunjungan)', $tanggalHariIni)
            ->countAllResults();

        $nextUrutan = $jumlahKunjunganHariIni + 1;
        $suffixUrutan = str_pad((string)$nextUrutan, 3, '0', STR_PAD_LEFT);

        $formatTanggalPendek = date('Ymd');
        $nomorKunjunganOtomatis = 'REG-' . $formatTanggalPendek . '-' . $suffixUrutan;

        foreach ($fieldsKunjunganMatang as $fieldKunjungan) {
            $columnKunjungan = $fieldKunjungan[2];

            if ($columnKunjungan === 'id_kunjungan') {
                continue;
            }

            if ($columnKunjungan === 'tanggal_kunjungan') {
                $mockBaris[$columnKunjungan] = $tanggalHariIni;
            } elseif ($columnKunjungan === 'nomor_kunjungan') {
                $mockBaris[$columnKunjungan] = $nomorKunjunganOtomatis;
                $fieldKunjungan[3] = 'indeks';
            } else {
                $mockBaris[$columnKunjungan] = '';
            }

            if ($columnKunjungan === 'id_pendonor') {
                foreach ($fieldsPendonorMatang as $fieldPendonor) {
                    if ($fieldPendonor[2] === 'nomor_pendonor') {
                        $mockBaris['nomor_pendonor'] = '';
                        
                        $konfigGabungan[] = $fieldPendonor;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $fieldKunjungan;
        }

        return view('/admin/donor/tambah_kunjungan', [
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
     * OVERRIDE: Memproses simpan data registrasi kunjungan
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $rawPost     = $this->request->getPost();
        $idPendonor  = $rawPost['id_pendonor'] ?? null;
        $tglKunjunganInput = $rawPost['tanggal_kunjungan'] ?? date('Y-m-d H:i:s');

        $validasiMedis = $this->model->cekIntervalMedis($idPendonor, $tglKunjunganInput);
        
        if ($validasiMedis['status'] === false) {
            session()->setFlashdata('error', $validasiMedis['message']);
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $dataKunjungan = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataKunjungan[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $this->model->db->transStart();

        try {
            $this->model->insert($dataKunjungan);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan data registrasi kunjungan.');
            }

            session()->setFlashdata('success', 'Data registrasi kunjungan berhasil disimpan.');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = ($e instanceof \CodeIgniter\Database\Exceptions\DatabaseException) 
                ? $this->friendly_db_error($e) 
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Kunjungan
     */
    #[\Override]
    final public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataKunjungan = $this->model->find($id);
        if (!$dataKunjungan) {
            $dataKunjungan = [];
        }

        $dataOrang = [];
        $dataPendonor = [];
        if (!empty($dataKunjungan['id_pendonor'])) {
            $pendonorModel = new \App\Features\Role\Pendonor\PendonorModel();
            $dataPendonor = $pendonorModel->find($dataKunjungan['id_pendonor']) ?? [];

            if (!empty($dataPendonor['id_orang'])) {
                $modelOrang = new \App\Features\Person\Orang\OrangModel();
                $dataOrang = $modelOrang->find($dataPendonor['id_orang']) ?? [];
            }
        }

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan);

        if (!empty($baris['tanggal_kunjungan'])) {
            $baris['tanggal_kunjungan'] = date('Y-m-d H:i:s', strtotime($baris['tanggal_kunjungan']));
        }

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $controllerPendonor = new \App\Features\Role\Pendonor\PendonorController();

        $konfigOrang = $controllerOrang->get_fields_with_options(false, true);
        $konfigPendonor = $controllerPendonor->get_fields_with_options(false, true);
        $konfigKunjungan = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];

        foreach ($konfigKunjungan as $fieldKunjungan) {
            $kolomKunjungan = $fieldKunjungan[2];

            if ($kolomKunjungan === 'id_pendonor') {
                foreach ($konfigPendonor as $fieldPendonor) {
                    $kolomPendonor = $fieldPendonor[2];

                    if ($kolomPendonor === 'id_orang') {
                        $konfigGabungan = array_merge($konfigGabungan, $konfigOrang);
                    } else {
                        $konfigGabungan[] = $fieldPendonor;
                    }
                }
            } else {
                $konfigGabungan[] = $fieldKunjungan;
            }
        }

        $card = $baris;
        foreach ($konfigGabungan as $field) {
            $namaKolom = $field[2];
            $tipeField = $field[3];
            $options   = $field[5] ?? [];

            if ($tipeField === 'status' && !empty($options) && isset($card[$namaKolom])) {
                $idMentah = $card[$namaKolom];
                foreach ($options as $opt) {
                    if ((string)$opt[1] === (string)$idMentah) {
                        $card[$namaKolom] = $opt[0];
                        break;
                    }
                }
            }
        }

        foreach ($konfigGabungan as $field) {
            $namaKolom = $field[2];
            if (($baris[$namaKolom] ?? null) === null) {
                $baris[$namaKolom] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon', 'Ubah']
        ];

        return view('/admin/donor/tambah_kunjungan', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigKunjungan,
            'baris'       => $baris,
            'card'        => $card,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    /**
     * Menampilkan Halaman Detail Registrasi Kunjungan
     */
    public function detail(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataKunjungan = $this->model->find($id);

        $dataPendonor = [];
        $dataOrang    = [];

        if (!empty($dataKunjungan['id_pendonor'])) {
            $pendonorModel = new \App\Features\Role\Pendonor\PendonorModel();
            $dataPendonor  = $pendonorModel->find($dataKunjungan['id_pendonor']) ?? [];

            if (!empty($dataPendonor['id_orang'])) {
                $modelOrang = new \App\Features\Person\Orang\OrangModel();
                $dataOrang  = $modelOrang->find($dataPendonor['id_orang']) ?? [];
            }
        }

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan);

        $controllerOrang    = new \App\Features\Person\Orang\OrangController();
        $controllerPendonor = new \App\Features\Role\Pendonor\PendonorController();

        $konfigOrang     = $controllerOrang->get_fields_with_options(false, true);
        $konfigPendonor  = $controllerPendonor->get_fields_with_options(false, true);
        $konfigKunjungan = $this->get_fields_with_options(false, true);

        $konfigGabungan = array_merge($konfigOrang, $konfigPendonor, $konfigKunjungan);

        foreach ($konfigGabungan as $field) {
            $colName = $field[2];
            $options = $field[5] ?? [];

            if (!empty($options) && isset($baris[$colName])) {
                $idMentah = $baris[$colName];
                foreach ($options as $opt) {
                    if ((string)$opt[1] === (string)$idMentah) {
                        $baris[$colName] = $opt[0];
                        break;
                    }
                }
            }
        }

        foreach ($baris as $key => $value) {
            if ($value === null) {
                $baris[$key] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Detail', 'icon' => 'detail']
        ];

        return view('/admin/donor/detail_kunjungan', [
            'judul'       => 'Detail ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'baris'       => $baris,
        ]);
    }

    /**
     * Menampilkan data modal kunjungan
     */
    public function list()
    {
        $filter = $this->request->getGet('filter');

        $data = $this->model->get_data_tabel(null, 0, $filter);

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
