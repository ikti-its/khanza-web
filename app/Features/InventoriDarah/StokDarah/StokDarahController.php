<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\StokDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class StokDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokDarahModel(),
            [
                ['Inventori Darah',  'inventori_darah'],
                ['Stok Darah',       'stok_darah'],
            ],
            'Stok Darah',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_stok_darah',       'ID Stok Darah'],
                [SHOW, REQUIRED, I::TEXT,   'no_kantong',          'Nomor Kantong'],
                [SHOW, REQUIRED, I::SELECT, 'id_komponen',         'Komponen'],
                [SHOW, REQUIRED, I::SELECT, 'id_golongan_darah',   'Golongan Darah'],
                [SHOW, REQUIRED, I::SELECT, 'id_rhesus',           'Rhesus'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_pengambilan', 'Tanggal Pengambilan'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_kadaluarsa',  'Tanggal Kadaluarsa'],
                [SHOW, REQUIRED, I::SELECT, 'id_sumber_darah',     'Sumber Darah'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_stok',      'Status Stok'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Halaman Utama Data Stok Darah
     */
    #[\Override]
    public function index(): string
    {
        $hariIni = date('Y-m-d');
        $this->model->updateStatusKadaluarsa($hariIni);

        return parent::index();
    }

    /**
     * OVERRIDE: Menampilkan Form Stok Darah
     */
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigFields = $this->get_fields_with_options(false, true);

        $mockBaris = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if ($namaKolom === 'id_stok_darah') continue;

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
        $dataStok = $this->request->getPost();
        $dataStok['id_status_stok'] = 2;

        $this->model->db->transStart();
        try {
            $this->model->insert($dataStok);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan data stok darah.');
            }

            session()->setFlashdata('success', 'Data stok darah berhasil disimpan.');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = ($e instanceof \CodeIgniter\Database\Exceptions\DatabaseException)
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
        if ($id == 0) return $this->index();

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
            ['title' => 'Ubah', 'icon' => 'Ubah']
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
     * Menampilkan data modal stok darah
     */
    public function list()
    {
        $hariIni = date('Y-m-d');
        $data = $this->model->get_stok_siap_pakai($hariIni);

        foreach ($data as &$row) {
            $row['tanggal_kadaluarsa'] = date('d-m-Y', strtotime($row['tanggal_kadaluarsa']));
        }

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
