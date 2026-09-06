<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangRusak;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
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
                // A::AUDIT,
                // A::UPDATE,
                A::DELETE,
                A::DETAIL,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_penunjang_rusak', 'ID Penunjang Rusak'],
                [SHOW, REQUIRED, I::INDEX, 'id_petugas',         'Petugas'],
                [SHOW, REQUIRED, I::DTIME, 'tanggal_rusak',      'Tanggal Rusak'],
                [SHOW, REQUIRED, I::TEXT,  'keterangan',         'Keterangan'],
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
            ['title' => 'Tambah', 'icon' => 'tambah'],
        ];

        $konfigPenunjangRusak = $this->get_fields_with_options(false, true);

        $mockBaris      = [];
        $konfigGabungan = [];

        /** @var list<array<int, mixed>> $fieldsList */
        $fieldsList = array_values(array_filter($konfigPenunjangRusak, 'is_array'));

        foreach ($fieldsList as $fieldRusak) {
            $columnRusak = (string) ($fieldRusak[2] ?? '');

            if ($columnRusak === 'id_penunjang_rusak') {
                continue;
            }

            $isTanggal               = ($fieldRusak[3] ?? '') === 'tanggal' || str_contains($columnRusak, 'tanggal');
            $mockBaris[$columnRusak] = $isTanggal ? date('Y-m-d\TH:i') : '';

            $konfigGabungan[] = $fieldRusak;
        }

        return view('admin/logistikutd/tambah_penunjangrusak', [
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
     * OVERRIDE: Memproses simpan data BHP non medis (penunjang) rusak
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        /** @var array<string, mixed> $rawPost */
        $rawPost = $this->request->getPost();

        /** @var list<int|string> $listBarang */
        $listBarang = is_array($rawPost['id_barang'] ?? null) ? array_values($rawPost['id_barang']) : [];
        $listJumlah = is_array($rawPost['jumlah'] ?? null) ? $rawPost['jumlah'] : [];
        $listHarga  = is_array($rawPost['harga_beli'] ?? null) ? $rawPost['harga_beli'] : [];

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

            if (!empty($listBarang)) {
                $modelDetail = new \App\Features\LogistikUTD\PenunjangRusakDetail\PenunjangRusakDetailModel();

                foreach ($listBarang as $index => $idBarang) {
                    if (empty($idBarang))
                        continue;

                    $modelDetail->insert([
                        'id_penunjang_rusak' => $idPenunjangRusak,
                        'id_barang'          => $idBarang,
                        'jumlah'             => $listJumlah[$index],
                        'harga_beli'         => is_numeric($listHarga[$index] ?? null) ? (float) $listHarga[$index] : 0.0,
                    ]);
                }
            }

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menyimpan data kerusakan BHP non medis.');
            }

            session()->setFlashdata('success', 'Data kerusakan BHP non medis berhasil disimpan.');
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
     * OVERRIDE: Menghapus data BHP non medis rusak
     */
    #[\Override]
    final public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->home();

        $dataPenunjangRusak = $this->model->find($id);
        if (!$dataPenunjangRusak) {
            session()->setFlashdata('error', 'Gagal menghapus. Data kerusakan BHP non medis tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $this->model->db->transStart();

        try {
            $modelDetail = new \App\Features\LogistikUTD\PenunjangRusakDetail\PenunjangRusakDetailModel();

            $modelDetail->where('id_penunjang_rusak', $id)->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus() ) {
                throw new \RuntimeException('Gagal menghapus data kerusakan BHP non medis.');
            }

            session()->setFlashdata('success', 'Data kerusakan BHP non medis berhasil dihapus.');
        } catch (DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
        }

        return $this->home();
    }

    /**
     * Menampilkan Halaman Detail BHP Non Medis Rusak
     * 
     * @throws PageNotFoundException
     * @throws DatabaseException
     */
    public function detail(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $dataRusak = $this->model->find($id);
        if (!is_array($dataRusak)) {
            throw PageNotFoundException::forPageNotFound(
                'Data Kerusakan BHP Non Medis tidak ditemukan.',
            );
        }

        $dataPetugas = [];

        if (!empty($dataRusak['id_petugas'])) {
            $modelPetugas = new \App\Features\Role\Petugas\PetugasModel();
            $petugasRow   = $modelPetugas->find((int) $dataRusak['id_petugas']) ?? [];

            if (!empty($petugasRow['id_orang'])) {
                $modelOrangPetugas = new \App\Features\Person\Orang\OrangModel();
                $orangPetugasRow   = $modelOrangPetugas->find((int) $petugasRow['id_orang']) ?? [];

                if (isset($orangPetugasRow['nama'])) {
                    $dataPetugas['nama_petugas'] = $orangPetugasRow['nama'];
                }
            }
        }

        $baris = array_merge($dataRusak, $dataPetugas);

        $query = $this->model
            ->db
            ->table('logistik_utd.penunjang_rusak_detail prd')
            ->select('prd.id_barang, prd.jumlah, prd.harga_beli')
            ->where('prd.id_penunjang_rusak', $id)
            ->get();
        
        /** @var list<array<string, mixed>> $detailRusakRaw */
        $detailRusakRaw = $query !== false ? $query->getResultArray() : [];

        $modelMasterPenunjang = new \App\Features\InventoriNonMedis\Barang\BarangModel();
        foreach ($detailRusakRaw as $k => $v) {
            $idBarang   = (int) ($v['id_barang'] ?? 0);
            $masterItem = $modelMasterPenunjang->find($idBarang);

            $detailRusakRaw[$k]['kode_barang'] = $masterItem['kode_barang'] ?? '-';
            $detailRusakRaw[$k]['nama_barang'] = $masterItem['nama_barang'] ?? '-';
        }

        foreach (array_keys($baris) as $key) {
            if ($baris[$key] === null) {
                $baris[$key] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Detail', 'icon' => 'detail'],
        ];

        return view('admin/logistikutd/detail_penunjangrusak', [
            'judul'        => 'Detail ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'   => $this->get_uri_path(),
            'baris'        => $baris,
            'detail_rusak' => $detailRusakRaw,
        ]);
    }
}
