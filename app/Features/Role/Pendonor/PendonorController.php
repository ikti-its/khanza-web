<?php
declare(strict_types=1);

namespace App\Features\Role\Pendonor;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
final class PendonorController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PendonorModel(),
            [
                ['Role',     'role'],
                ['Pendonor', 'pendonor'],
            ],
            'Pendonor',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::PRINT,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_pendonor',            'ID Pendonor'],
                [SHOW, REQUIRED, I::TEXT,  'nomor_pendonor',         'Nomor Pendonor'],
                [SHOW, REQUIRED, I::INDEX, 'id_orang',               'ID Orang'],
                [SHOW, REQUIRED, I::SELECT,'id_rhesus',              'Rhesus'],
                [SHOW, REQUIRED, I::TEXT,  'nomor_telepon',          'Nomor Telepon'],
                [SHOW, OPTIONAL, I::DATE,  'tanggal_donor_terakhir', 'Tanggal Donor Terakhir'],
            ],
        );
    }

    /**
     * @throws PageNotFoundException
     */
    #[\Override]
    public function print(int|string $id): string
    {
        $dataPendonor = $this->model->find($id);
        if (!$dataPendonor) {
            throw PageNotFoundException::forPageNotFound('Data Pendonor tidak ditemukan.');
        }

        $modelOrang = new \App\Features\Person\Orang\OrangModel();
        $idOrang = $dataPendonor['id_orang'] ?? null;
        $dataOrang = $idOrang ? $modelOrang->find($idOrang) : [];

        $baris = array_merge($dataOrang, $dataPendonor);

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $konfigOrang     = $controllerOrang->get_fields_with_options(false, true);
        $konfigPendonor  = $this->get_fields_with_options(false, true);
        $konfigGabungan  = array_merge($konfigOrang, $konfigPendonor);

        foreach ($konfigGabungan as $field) {
            $namaKolom = $field[2];
            $tipeField = $field[3];
            $options   = $field[5] ?? [];

            if ($tipeField === 'status' && !empty($options) && isset($baris[$namaKolom])) {
                $idMentah = $baris[$namaKolom];

                foreach ($options as $opt) {
                    if ($opt[1] == $idMentah) {
                        $baris[$namaKolom] = $opt[0];
                        break;
                    }
                }
            }

            if (($baris[$namaKolom] ?? null) === null) {
                $baris[$namaKolom] = '';
            }
        }

        return view('components/cetak/cetak_kartu', [
            'judul' => 'Cetak Kartu Pendonor',
            'baris' => $baris,
        ]);
    }
    
    /**
     * OVERRIDE: Menampilkan Form Gabungan
     */
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon', 'tambah']
        ];

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $fieldsOrang = $controllerOrang->fields;
        $fieldsPendonor = $this->fields;

        $modelOrang = new \App\Features\Person\Orang\OrangModel();
        $opsiOrang = $modelOrang->get_all_options();
        $opsiPendonor = $this->model->get_all_options();

        $terakhir = $this->model->orderBy($this->model->primaryKey, 'DESC')->first();
        $nextNumber = 1;
        if ($terakhir !== null && isset($terakhir['nomor_pendonor'])) {
            $cleanNumber = str_replace('UTD', '', $terakhir['nomor_pendonor']);
            $nextNumber = ((int) $cleanNumber) + 1;
        }
        $nomorPendonorOtomatis = 'UTD' . str_pad((string)$nextNumber, 6, '0', STR_PAD_LEFT);

        $mockBaris = [];
        $konfigGabungan = [];

        foreach ($fieldsPendonor as $fieldPendonor) {
            $columnPendonor = $fieldPendonor[2];

            if ($columnPendonor === 'nomor_pendonor') {
                $mockBaris[$columnPendonor] = $nomorPendonorOtomatis;
                $fieldPendonor[3] = 'indeks';
            } elseif ($columnPendonor !== 'id_pendonor') {
                $mockBaris[$columnPendonor] = '';
            }

            if ($columnPendonor === 'id_orang') {
                foreach ($fieldsOrang as $fieldOrang) {
                    $columnOrang = $fieldOrang[2];

                    if ($columnOrang === 'id_orang') {
                        continue;
                    }

                    $mockBaris[$columnOrang] = '';

                    if (isset($opsiOrang[$columnOrang])) {
                        $fieldOrang[5] = $opsiOrang[$columnOrang];
                    }
                    $konfigGabungan[] = $fieldOrang;
                }
                continue;
            }

            if ($columnPendonor === 'id_pendonor') {
                continue;
            }

            if (isset($opsiPendonor[$columnPendonor])) {
                $fieldPendonor[5] = $opsiPendonor[$columnPendonor];
            }

            $konfigGabungan[] = $fieldPendonor;
        }

        return view('/layouts/tambah_ubah', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigGabungan,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah/',
        ]);
    }

    /**
     * OVERRIDE: Memproses Simpan Data Form Gabungan
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $modelOrang = new \App\Features\Person\Orang\OrangModel();

        $dataPendonor = $this->get_post_data_custom();
        $rawPost = $this->request->getPost();

        $dataOrang = [];

        foreach ($modelOrang->allowedFields as $field) {
            $value = $rawPost[$field] ?? '';

            if ($value === '') {
                $value = null;
            } 
            else if (is_numeric($value) && (str_contains($field, 'id_') || $field === 'tempat_lahir_kota')) {
                $value = (int) $value;
            }

            $dataOrang[$field] = $value;
        }

        $this->model->db->transBegin();

        try {
            if (!$modelOrang->insert($dataOrang)) {
                throw new \RuntimeException('Sistem gagal menyimpan identitas Orang.');
            }

            $idOrang = $modelOrang->insertID();
            $dataPendonor['id_orang'] = $idOrang;

            if (!$this->model->insert($dataPendonor)) {
                throw new \RuntimeException('Sistem gagal menyimpan entitas Pendonor.');
            }

            if ($this->model->db->transStatus() === false) {
                $this->model->db->transRollback();
                session()->setFlashdata('error', 'Transaksi database gagal. Seluruh perubahan dibatalkan.');
                return redirect()->back()->withInput();
            }

            $this->model->db->transCommit();

        } catch (\ReflectionException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', 'Kesalahan Refleksi: ' . $e->getMessage());
            return redirect()->back()->withInput();
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
     * CUSTOM HELPER: Menyaring dan mengambil data POST murni untuk kebutuhan tabel Pendonor saja
     */
    private function get_post_data_custom(): array
    {
        $postData = [];
        $rawPost = $this->request->getPost();

        $fieldsPendonor = $this->fields;

        foreach ($fieldsPendonor as $field) {
            $column = $field[2];
            $type   = $field[3];

            if (in_array($column, [$this->model->primaryKey, 'id_orang'])) {
                continue;
            }

            $value = $rawPost[$column] ?? '';

            if ($value === '') {
                $value = null;
            }
            else if (str_contains($column, 'id_') || $type === 'indeks') {
                $value = (int) $value;
            }

            $postData[$column] = $value;
        }
        
        return $postData;
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Form Gabungan
     */
    #[\Override]
    final public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataPendonor = $this->model->find($id);
        if (!$dataPendonor) {
            $dataPendonor = [];
        }

        $modelOrang = new \App\Features\Person\Orang\OrangModel();
        $idOrang = $dataPendonor['id_orang'] ?? null;
        $dataOrang = $idOrang ? $modelOrang->find($idOrang) : [];

        $baris = array_merge($dataOrang, $dataPendonor);

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $konfigOrang = $controllerOrang->get_fields_with_options(false, true);
        $konfigPendonor = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];

        foreach ($konfigPendonor as $field) {
            $namaKolom = $field[2];

            if ($namaKolom === 'id_orang') {
                $konfigGabungan = array_merge($konfigGabungan, $konfigOrang);
            } else {
                if ($namaKolom === 'nomor_pendonor') {
                    $field[3] = 'indeks';
                }
                $konfigGabungan[] = $field;
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

        return view('/layouts/tambah_ubah', [
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
     * OVERRIDE: Mengeksekusi Simpan Perubahan Data Form Gabungan
     */
    #[\Override]
    final public function update(int|string $id): string|RedirectResponse
    {
        $dataPendonorLama = $this->model->find($id);
        if (!$dataPendonorLama) {
            session()->setFlashdata('error', 'Data gagal diubah. Pendonor tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang = $dataPendonorLama['id_orang'];
        $nomorPendonor = $dataPendonorLama['nomor_pendonor'];

        $rawPost = $this->request->getPost();

        $modelOrang = new \App\Features\Person\Orang\OrangModel();

        $this->model->db->transBegin();

        try {
            $dataOrang = [];
            foreach ($modelOrang->allowedFields as $field) {
                if ($field === $modelOrang->primaryKey) {
                    continue;
                }

                $value = $rawPost[$field] ?? '';

                if ($value === '') {
                    $value = null;
                } else if (is_numeric($value) && (str_contains($field, 'id_') || $field === 'tempat_lahir_kota')) {
                    $value = (int) $value;
                }

                $dataOrang[$field] = $value;
            }

            if (!$modelOrang->update($idOrang, $dataOrang)) {
                throw new \Exception('Gagal memperbarui data personal Orang.');
            }

            $dataPendonor = $this->get_post_data_custom();

            $dataPendonor['nomor_pendonor'] = $nomorPendonor;
            $dataPendonor['id_orang'] = $idOrang;

            if (!$this->model->update($id, $dataPendonor)) {
                throw new \Exception('Gagal memperbarui data spesifik Pendonor.');
            }

            $this->model->db->transCommit();
            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil diperbarui.');

        } catch (\Throwable $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * Menampilkan data modal pendonor
     */
    public function list()
    {
        $data = $this->model->builder()
            ->select('
                role.pendonor.id_pendonor,
                role.pendonor.nomor_pendonor,
                role.pendonor.id_rhesus,
                person.orang.nama,
                person.orang.nik,
                person.orang.id_jenis_kelamin,
                person.orang.id_golongan_darah,
                person.orang.tanggal_lahir,
                person.jenis_kelamin.nama_jenis_kelamin,
                darah.golongan_darah.nama_golongan_darah,
                darah.rhesus.kode_rhesus
            ')
            ->join('person.orang', 'person.orang.id_orang = role.pendonor.id_orang', 'inner')
            ->join('person.jenis_kelamin', 'person.jenis_kelamin.id_jenis_kelamin = person.orang.id_jenis_kelamin', 'left')
            ->join('darah.golongan_darah', 'darah.golongan_darah.id_golongan_darah = person.orang.id_golongan_darah', 'left')
            ->join('darah.rhesus', 'darah.rhesus.id_rhesus = role.pendonor.id_rhesus', 'left')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
