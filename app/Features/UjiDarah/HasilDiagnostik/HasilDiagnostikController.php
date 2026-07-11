<?php
declare(strict_types=1);

namespace App\Features\UjiDarah\HasilDiagnostik;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class HasilDiagnostikController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilDiagnostikModel(),
            [
                ['Uji Darah',            'uji_darah'],
                ['Hasil Tes Diagnostik', 'hasil_tes_diagnostik'],
            ],
            'Hasil Tes Diagnostik',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::DETAIL,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_diagnostik',     'ID Diagnostik'],
                [SHOW, REQUIRED, I::INDEX, 'id_kasus',          'Nomor Kasus Reaktif'],
                [SHOW, REQUIRED, I::DATE,  'tanggal_hasil',     'Tanggal Hasil'],
                [SHOW, REQUIRED, I::NAME,  'fasyankes_rujukan', 'Fasyankes Rujukan'],
                [SHOW, REQUIRED, I::NAME,  'dokter_pemeriksa',  'Dokter Pemeriksa'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Hasil Diagnostik
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $mockBaris = [
            'id_kasus'          => '',
            'nomor_kasus'       => '',
            'tanggal_hasil'     => date('Y-m-d'),
            'fasyankes_rujukan' => '',
            'dokter_pemeriksa'  => '',
        ];

        $modelNilaiDiagnostik = new \App\Features\UjiDarah\NilaiDiagnostik\NilaiDiagnostikModel();
        $nilaiDiagnostik      = $modelNilaiDiagnostik->findAll();

        return view('admin/ujidarah/tambah_hasildiagnostik', [
            'judul'            => 'Tambah ' . $this->title,
            'breadcrumbs'      => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'       => $this->get_uri_path(),
            'kolom_id'         => $this->model->primaryKey,
            'baris'            => $mockBaris,
            'nilai_diagnostik' => $nilaiDiagnostik,
            'form_action'      => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Memproses simpan data hasil diagnostik
     */
    #[\Override]
    final public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $idKasus          = $rawPost['id_kasus'];
        $idParameterUji   = $rawPost['id_parameter_uji'] ?? [];
        $nilaiDiagnostik  = $rawPost['id_nilai_diagnostik'] ?? [];

        $modelKasus = new \App\Features\PenangananDonor\KasusReaktif\KasusReaktifModel();
        $dataKasus  = $modelKasus->find($idKasus);

        if (!$dataKasus) {
            session()->setFlashdata('error', 'Gagal menyimpan! Data kasus reaktif tidak ditemukan.');
            return redirect()->back()->withInput();
        }

        $modelUjiSaring = new \App\Features\UjiDarah\HasilUjiSaring\HasilUjiSaringModel();
        $dataUjiSaring  = $modelUjiSaring->find($dataKasus['id_uji_saring']);

        if (!$dataUjiSaring) {
            session()->setFlashdata('error', 'Gagal menyimpan! Data hasil uji saring sumber kasus tidak ditemukan.');
            return redirect()->back()->withInput();
        }

        $dataDiagnostik = [
            'id_kasus'          => $idKasus,
            'tanggal_hasil'     => $rawPost['tanggal_hasil'],
            'fasyankes_rujukan' => $rawPost['fasyankes_rujukan'],
            'dokter_pemeriksa'  => $rawPost['dokter_pemeriksa'],
        ];

        $this->model->db->transStart();

        try {
            $this->model->insert($dataDiagnostik);
            $idDiagnostik = $this->model->getInsertID();

            $modelDetail = new \App\Features\UjiDarah\HasilDiagnostikDetail\HasilDiagnostikDetailModel();
            $nilaiDiagnostikDipilih = [];

            foreach ($idParameterUji as $idParameter) {
                $idNilai = $nilaiDiagnostik[$idParameter];

                $modelDetail->insert([
                    'id_diagnostik'       => $idDiagnostik,
                    'id_parameter_uji'    => $idParameter,
                    'id_nilai_diagnostik' => $idNilai,
                ]);

                $nilaiDiagnostikDipilih[] = $idNilai;
            }

            $modelPencekalan = new \App\Features\PenangananDonor\Pencekalan\PencekalanModel();
            $modelPencekalan->updateDariHasilDiagnostik(
                $dataUjiSaring,
                $rawPost['tanggal_hasil'],
                $nilaiDiagnostikDipilih
            );

            $modelKasus->selesaikanKasus($idKasus);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan data hasil diagnostik.');
            }

            session()->setFlashdata('success', 'Data hasil diagnostik berhasil disimpan.');

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
     * OVERRIDE: Menampilkan Halaman Ubah Data Hasil Diagnostik
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();
    
        $dataDiagnostik = $this->model->find($id);
    
        if (!$dataDiagnostik) {
            session()->setFlashdata('error', 'Data hasil diagnostik tidak ditemukan.');
            return $this->index();
        }
    
        $modelKasus = new \App\Features\PenangananDonor\KasusReaktif\KasusReaktifModel();
        $dataKasus  = $modelKasus->find($dataDiagnostik['id_kasus']) ?? [];
    
        $modelUjiSaring = new \App\Features\UjiDarah\HasilUjiSaring\HasilUjiSaringModel();
        $dataUjiSaring  = $modelUjiSaring->find($dataKasus['id_uji_saring'] ?? 0) ?? [];
    
        $parameterDiagnostik = !empty($dataUjiSaring)
            ? $modelKasus->getParameterReaktifDetail($dataUjiSaring)
            : [];
    
        $detailDiagnostik = $this->model->db
            ->table('uji_darah.hasil_diagnostik_detail')
            ->where('id_diagnostik', $id)
            ->get()
            ->getResultArray();
    
        $nilaiDiagnostikTerpilih = array_column(
            $detailDiagnostik,
            'id_nilai_diagnostik',
            'id_parameter_uji'
        );
    
        foreach ($parameterDiagnostik as $index => $parameter) {
            $idParameter = $parameter['id_parameter_uji'];
    
            $parameterDiagnostik[$index]['id_nilai_diagnostik'] =
                $nilaiDiagnostikTerpilih[$idParameter] ?? '';
        }
    
        $modelNilaiDiagnostik = new \App\Features\UjiDarah\NilaiDiagnostik\NilaiDiagnostikModel();
        $nilaiDiagnostik      = $modelNilaiDiagnostik->findAll();
    
        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'Ubah']
        ];
    
        return view('admin/ujidarah/tambah_hasildiagnostik', [
            'judul'                => 'Ubah ' . $this->title,
            'breadcrumbs'          => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'           => $this->get_uri_path(),
            'kolom_id'             => $this->model->primaryKey,
            'baris'                => array_merge($dataKasus, $dataDiagnostik),
            'nilai_diagnostik'     => $nilaiDiagnostik,
            'parameter_diagnostik' => $parameterDiagnostik,
            'form_action'          => '/submitedit/' . $id,
        ]);
    }

    /**
     * OVERRIDE: Mengeksekusi Simpan Perubahan Data Hasil Diagnostik
     */
    #[\Override]
    final public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->index();

        $rawPost = $this->request->getPost();
        $dataDiagnostikLama = $this->model->find($id);

        if (!$dataDiagnostikLama) {
            session()->setFlashdata('error', 'Data hasil diagnostik tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idKasus = $dataDiagnostikLama['id_kasus'];
        $idParameterUji  = $rawPost['id_parameter_uji'] ?? [];
        $nilaiDiagnostik = $rawPost['id_nilai_diagnostik'] ?? [];

        $modelKasus = new \App\Features\PenangananDonor\KasusReaktif\KasusReaktifModel();
        $dataKasus  = $modelKasus->find($idKasus);

        if (!$dataKasus) {
            session()->setFlashdata('error', 'Gagal memperbarui! Data kasus reaktif tidak ditemukan.');
            return redirect()->back()->withInput();
        }

        $modelUjiSaring = new \App\Features\UjiDarah\HasilUjiSaring\HasilUjiSaringModel();
        $dataUjiSaring  = $modelUjiSaring->find($dataKasus['id_uji_saring']);

        if (!$dataUjiSaring) {
            session()->setFlashdata('error', 'Gagal memperbarui! Data hasil uji saring sumber kasus tidak ditemukan.');
            return redirect()->back()->withInput();
        }

        $dataDiagnostik = [
            'tanggal_hasil'     => $rawPost['tanggal_hasil'],
            'fasyankes_rujukan' => $rawPost['fasyankes_rujukan'],
            'dokter_pemeriksa'  => $rawPost['dokter_pemeriksa'],
        ];

        $this->model->db->transStart();

        try {
            $this->model->update($id, $dataDiagnostik);

            $this->model->db
                ->table('uji_darah.hasil_diagnostik_detail')
                ->where('id_diagnostik', $id)
                ->delete();

            $modelDetail = new \App\Features\UjiDarah\HasilDiagnostikDetail\HasilDiagnostikDetailModel();
            $nilaiDiagnostikDipilih = [];

            foreach ($idParameterUji as $idParameter) {
                $idNilai = $nilaiDiagnostik[$idParameter] ?? null;

                if (empty($idNilai)) {
                    throw new \RuntimeException('Nilai diagnostik belum lengkap.');
                }

                $modelDetail->insert([
                    'id_diagnostik'       => $id,
                    'id_parameter_uji'    => $idParameter,
                    'id_nilai_diagnostik' => $idNilai,
                ]);

                $nilaiDiagnostikDipilih[] = $idNilai;
            }

            $modelPencekalan = new \App\Features\PenangananDonor\Pencekalan\PencekalanModel();
            $modelPencekalan->updateDariHasilDiagnostik(
                $dataUjiSaring,
                $rawPost['tanggal_hasil'],
                $nilaiDiagnostikDipilih
            );

            $modelKasus->selesaikanKasus($idKasus);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui data hasil diagnostik.');
            }

            session()->setFlashdata('success', 'Data hasil diagnostik berhasil diperbarui.');

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
     * OVERRIDE: Menghapus data hasil diagnostik
     */
    #[\Override]
    final public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->index();
    
        $dataDiagnostik = $this->model->find($id);
    
        if (!$dataDiagnostik) {
            session()->setFlashdata('error', 'Data hasil diagnostik tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }
    
        $idKasus = $dataDiagnostik['id_kasus'];
    
        $modelKasus = new \App\Features\PenangananDonor\KasusReaktif\KasusReaktifModel();
        $dataKasus  = $modelKasus->find($idKasus);
    
        if (!$dataKasus) {
            session()->setFlashdata('error', 'Gagal menghapus! Data kasus reaktif tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }
    
        $modelUjiSaring = new \App\Features\UjiDarah\HasilUjiSaring\HasilUjiSaringModel();
        $dataUjiSaring  = $modelUjiSaring->find($dataKasus['id_uji_saring']);
    
        if (!$dataUjiSaring) {
            session()->setFlashdata('error', 'Gagal menghapus! Data hasil uji saring sumber kasus tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }
    
        $this->model->db->transStart();
    
        try {
            $this->model->db
                ->table('uji_darah.hasil_diagnostik_detail')
                ->where('id_diagnostik', $id)
                ->delete();
    
            $this->model->delete($id);
    
            $modelPencekalan = new \App\Features\PenangananDonor\Pencekalan\PencekalanModel();
            $modelPencekalan->resetPencekalanDiagnostik($dataUjiSaring);

            $modelKasus->bukaKembaliKasus($idKasus);
    
            $this->model->db->transComplete();
    
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus data hasil diagnostik.');
            }
    
            session()->setFlashdata('success', 'Data hasil diagnostik berhasil dihapus.');
    
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
     * Menampilkan Halaman Detail Hasil Tes Diagnostik
     */
    public function detail(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataDiagnostik = $this->model->find($id);
        if (!$dataDiagnostik) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data Hasil Tes Diagnostik tidak ditemukan.');
        }

        $modelKasus = new \App\Features\PenangananDonor\KasusReaktif\KasusReaktifModel();
        $dataKasus  = $modelKasus->find($dataDiagnostik['id_kasus']) ?? [];

        $baris = array_merge($dataKasus, $dataDiagnostik);

        $konfigFields = $this->get_fields_with_options(false, true);
        foreach ($konfigFields as $field) {
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

        $detailDiagnostikRaw = $this->model->db
            ->table('uji_darah.hasil_diagnostik_detail hdd')
            ->select('pu.nama_parameter, nd.nama_nilai_diagnostik')
            ->join('uji_darah.parameter_uji pu', 'pu.id_parameter_uji = hdd.id_parameter_uji', 'inner')
            ->join('uji_darah.nilai_diagnostik nd', 'nd.id_nilai_diagnostik = hdd.id_nilai_diagnostik', 'inner')
            ->where('hdd.id_diagnostik', $id)
            ->get()
            ->getResultArray();

        foreach ($baris as $key => $value) {
            if ($value === null) {
                $baris[$key] = '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Detail', 'icon' => 'detail']
        ];

        return view('admin/ujidarah/detail_hasildiagnostik', [
            'judul'             => 'Detail ' . $this->title,
            'breadcrumbs'       => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'        => $this->get_uri_path(),
            'baris'             => $baris,
            'detail_diagnostik' => $detailDiagnostikRaw,
        ]);
    }
}
