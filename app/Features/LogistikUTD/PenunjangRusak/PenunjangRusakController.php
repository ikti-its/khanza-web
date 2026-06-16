<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangRusak;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PenunjangRusakController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenunjangRusakModel(),
            [
                ['Logistik UTD',        'logistik_utd'],
                ['BHP Non Medis Rusak', 'bhp_non_medis_rusak'],
            ],
            'BHP Non Medis Rusak',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penunjang_rusak', 'ID Penunjang Rusak'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',         'ID Petugas'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_rusak',      'Tanggal Rusak'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',         'Keterangan'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form BHP Non Medis (Penunjang) Rusak
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigPenunjangRusak = $this->get_fields_with_options(false, true);

        $mockBaris = [];
        $konfigGabungan = [];

        foreach ($konfigPenunjangRusak as $fieldRusak) {
            $columnRusak = $fieldRusak[2];

            if ($columnRusak === 'id_penunjang_rusak') {
                continue;
            }

            $isTanggal = ($fieldRusak[3] === 'tanggal' || str_contains($columnRusak, 'tanggal'));
            $mockBaris[$columnRusak] = $isTanggal ? date('Y-m-d\TH:i') : '';

            $konfigGabungan[] = $fieldRusak;
        }

        return view('admin/logistikutd/tambah_penunjangrusak', [
            'judul'             => 'Tambah ' . $this->title,
            'breadcrumbs'       => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'        => $this->get_uri_path(),
            'kolom_id'          => $this->model->primaryKey,
            'konfig'            => $konfigGabungan,
            'baris'             => $mockBaris,
            'form_action'       => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Memproses simpan data BHP non medis (penunjang) rusak
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $listBarang = $this->request->getPost('id_barang');
        $listJumlah = $this->request->getPost('jumlah');
        $listHarga  = $this->request->getPost('harga_beli');

        $dataPenunjangRusak = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataPenunjangRusak[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $this->model->db->transStart();

        try {
            $this->model->insert($dataPenunjangRusak);
            $idPenunjangRusak = $this->model->getInsertID();

            if (!empty($listBarang) && is_array($listBarang)) {
                $modelDetail = new \App\Features\LogistikUTD\PenunjangRusakDetail\PenunjangRusakDetailModel();

                foreach ($listBarang as $index => $idBarang) {
                    if (empty($idBarang)) continue;

                    $modelDetail->insert([
                        'id_penunjang_rusak' => $idPenunjangRusak,
                        'id_barang'          => $idBarang,
                        'jumlah'             => $listJumlah[$index],
                        'harga_beli'         => (float)($listHarga[$index] ?? 0),
                    ]);
                }
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException("Gagal menyimpan data kerusakan BHP non medis.");
            }

            session()->setFlashdata('success', 'Data kerusakan BHP non medis berhasil disimpan.');

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errMsg = ($e instanceof \CodeIgniter\Database\Exceptions\DatabaseException)
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errMsg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }
}
