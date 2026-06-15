<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisRusak;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class MedisRusakController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new MedisRusakModel(),
            [
                ['Logistik UTD',    'logistik_utd'],
                ['BHP Medis Rusak', 'bhp_medis_rusak'],
            ],
            'BHP Medis Rusak',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_medis_rusak', 'ID Medis Rusak'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',     'Petugas'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_rusak',  'Tanggal Rusak'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',     'Keterangan'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form BHP Medis Rusak
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigMedisRusak = $this->get_fields_with_options(false, true);

        $mockBaris = [];
        $konfigGabungan = [];

        foreach ($konfigMedisRusak as $fieldRusak) {
            $columnRusak = $fieldRusak[2];

            if ($columnRusak === 'id_medis_rusak') {
                continue;
            }

            $isTanggal = ($fieldRusak[3] === 'tanggal' || str_contains($columnRusak, 'tanggal'));
            $mockBaris[$columnRusak] = $isTanggal ? date('Y-m-d\TH:i') : '';

            $konfigGabungan[] = $fieldRusak;
        }

        return view('admin/logistikutd/tambah_medisrusak', [
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
     * OVERRIDE: Memproses simpan data BHP medis rusak
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $listBarang = $this->request->getPost('id_barang');
        $listJumlah = $this->request->getPost('jumlah');
        $listHarga  = $this->request->getPost('harga_beli');

        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if (array_key_exists($namaKolom, $rawPost)) {
                $dataMedisRusak[$namaKolom] = $rawPost[$namaKolom];
            }
        }

        $this->model->db->transStart();

        try {
            $this->model->insert($dataMedisRusak);
            $idMedisRusak = $this->model->getInsertID();

            if (!empty($listBarang) && is_array($listBarang)) {
                $modelDetail   = new \App\Features\LogistikUTD\MedisRusakDetail\MedisRusakDetailModel();

                foreach ($listBarang as $index => $idBarang) {
                    if (empty($idBarang)) continue;

                    $modelDetail->insert([
                        'id_medis_rusak'    => $idMedisRusak,
                        'id_barang'         => $idBarang,
                        'jumlah'            => $listJumlah[$index],
                        'harga_beli'        => (float)($listHarga[$index] ?? 0),
                    ]);
                }
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException("Gagal menyimpan data kerusakan BHP medis.");
            }

            session()->setFlashdata('success', 'Data kerusakan BHP medis berhasil disimpan.');

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
     * OVERRIDE: Menghapus data BHP medis rusak
     */
    #[\Override]
    final public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $dataMedisRusak = $this->model->find($id);
        if (!$dataMedisRusak) {
            session()->setFlashdata('error', 'Gagal menghapus. Data kerusakan BHP medis tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $this->model->db->transStart();

        try {
            $modelDetail = new \App\Features\LogistikUTD\MedisRusakDetail\MedisRusakDetailModel();

            $modelDetail->where('id_medis_rusak', $id)->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus data kerusakan BHP medis.');
            }

            session()->setFlashdata('success', 'Data kerusakan BHP medis berhasil dihapus.');

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
        }

        return $this->home();
    }
}
