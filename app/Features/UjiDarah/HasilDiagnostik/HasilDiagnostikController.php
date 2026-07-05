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
                ['Uji Darah',        'uji_darah'],
                ['Hasil Diagnostik', 'hasil_diagnostik'],
            ],
            'Hasil Diagnostik',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_diagnostik',     'ID Diagnostik'],
                [SHOW, REQUIRED, I::INDEX, 'id_kasus',          'Kasus Reaktif'],
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
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }
}
