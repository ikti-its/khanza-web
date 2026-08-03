<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\StokDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class StokDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokDarahModel(),
            [
                ['Inventori Darah', 'inventori_darah'],
                ['Stok Darah',      'stok_darah'],
            ],
            'Stok Darah',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::DETAIL,
                A::FILTER,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX,  'id_stok_darah',       'ID Stok Darah'],
                [SHOW,      REQUIRED, I::TEXT,   'no_kantong',          'Nomor Kantong'],
                [SHOW,      REQUIRED, I::SELECT, 'id_komponen',         'Komponen'],
                [SHOW,      REQUIRED, I::SELECT, 'id_golongan_darah',   'Golongan Darah'],
                [SHOW,      REQUIRED, I::SELECT, 'id_rhesus',           'Rhesus'],
                [FORM_ONLY, REQUIRED, I::DATE,   'tanggal_pengambilan', 'Tanggal Pengambilan'],
                [SHOW,      REQUIRED, I::DATE,   'tanggal_kadaluarsa',  'Tanggal Kadaluarsa'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'id_sumber_darah',     'Sumber Darah'],
                [SHOW,      REQUIRED, I::SELECT, 'id_status_stok',      'Status Stok'],
            ],
        );
    }

    /**
     * OVERRIDE: Memperbarui status kadaluarsa & mengurutkan stok dari yang paling cepat kadaluarsa (FEFO)
     */
    #[\Override]
    protected function before_read(): void
    {
        $this->model->updateStatusKadaluarsa(date('Y-m-d'));
        $this->model->set_order('tanggal_kadaluarsa', 'ASC');
    }

    /**
     * OVERRIDE: Menampilkan Halaman Utama Data Stok Darah
     */
    #[\Override]
    public function index(): string
    {
        $this->filters = [
            'karantina'     => 'Karantina',
            'tersedia'      => 'Tersedia',
            'tidak_layak'   => 'Tidak Layak',
            'terdistribusi' => 'Terdistribusi',
        ];

        $this->active_filter = $this->request->getGet('filter') ?: null;

        if ($this->active_filter !== null && array_key_exists($this->active_filter, $this->filters)) {
            $this->model->applyFilter($this->active_filter);
        }

        return parent::index();
    }

    /**
     * OVERRIDE: Menampilkan Form Stok Darah
     */
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah'],
        ];

        $konfigFields = $this->get_fields_with_options(false, true);

        $mockBaris = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if ($namaKolom === 'id_stok_darah')
                continue;

            $mockBaris[$namaKolom] = '';
        }

        return view('/admin/inventoridarah/tambah_stokdarah', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfigFields,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Memproses simpan data stok darah
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $dataStok                   = $this->request->getPost();
        $dataStok['id_status_stok'] = 2;

        $this->model->db->transStart();
        try {
            $this->model->insert($dataStok);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menyimpan data stok darah.');
            }

            session()->setFlashdata('success', 'Data stok darah berhasil disimpan.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
            return redirect()->back()->withInput();
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Stok Darah
     */
    #[\Override]
    final public function update_page(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $baris = $this->model->find($id);
        if (!$baris) {
            $baris = [];
        }

        $konfigFields = $this->get_fields_with_options(false, true);

        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (($baris[$namaKolom] ?? null) === null) {
                $baris[$namaKolom] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'Ubah'],
        ];

        return view('/admin/inventoridarah/tambah_stokdarah', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfigFields,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    /**
     * OVERRIDE: Mengeksekusi Simpan Perubahan Data Stok Darah
     */
    #[\Override]
    final public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $dataLama = $this->model->find($id);
        if (!$dataLama) {
            session()->setFlashdata('error', 'Gagal memperbarui. Data stok darah tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $dataStok                   = $this->request->getPost();
        $dataStok['id_status_stok'] = $dataLama['id_status_stok'];

        $this->model->db->transStart();
        try {
            $this->model->update($id, $dataStok);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal memperbarui data stok darah.');
            }

            session()->setFlashdata('success', 'Data stok darah berhasil diperbarui.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
            return redirect()->back()->withInput();
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * Menampilkan Halaman Detail Stok Darah
     */
    public function detail(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $dataStok = $this->model->find($id);

        $baris        = $dataStok;
        $konfigFields = $this->get_fields_with_options(false, true);

        foreach ($konfigFields as $field) {
            $colName = $field[2];
            $options = $field[5] ?? [];

            if (!empty($options) && isset($baris[$colName])) {
                $idMentah = $baris[$colName];
                foreach ($options as $opt) {
                    if ((string) $opt[1] === (string) $idMentah) {
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
            ['title' => 'Detail', 'icon' => 'detail'],
        ];

        return view('/admin/inventoridarah/detail_stokdarah', [
            'judul'       => 'Detail ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'baris'       => $baris,
        ]);
    }

    /**
     * Menampilkan data modal stok darah
     */
    public function list(): ResponseInterface
    {
        $hariIni = date('Y-m-d');
        $data    = $this->model->get_stok_siap_pakai($hariIni);

        foreach ($data as &$row) {
            $row['tanggal_kadaluarsa'] = date('d-m-Y', strtotime($row['tanggal_kadaluarsa']));
        }

        return $this->response->setJSON([
            'data' => $data,
        ]);
    }
}
