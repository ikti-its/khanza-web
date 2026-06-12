<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PermintaanDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanDarahModel(),
            [
                ['Pelayanan Darah',  'pelayanan_darah'],
                ['Permintaan Darah', 'permintaan_darah'],
            ],
            'Permintaan Darah',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'no_permintaan',        'Nomor Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'id_rawat_inap',        'ID Rawat Inap'],
                [SHOW, REQUIRED, I::TEXT,   'id_dokter_pengirim',   'ID Dokter Pengirim'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_permintaan',   'Tanggal Permintaan'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_permintaan', 'Status Permintaan'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Permintaan Darah
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $konfigPermintaan = $this->get_fields_with_options(false, true);

        $controllerRawatInap  = new \App\Features\RawatInap\Registrasi\RegistrasiController();
        $controllerRegistrasi = new \App\Features\RekamMedis\Registrasi\RegistrasiController();
        $controllerPasien     = new \App\Features\Role\Pasien\PasienController();
        $controllerOrang      = new \App\Features\Person\Orang\OrangController();

        $konfigRawatInap  = $controllerRawatInap->fields;
        $konfigRegistrasi = $controllerRegistrasi->fields;
        $konfigPasien     = $controllerPasien->fields;
        $konfigOrang      = $controllerOrang->fields;

        $modelKomponen = new \App\Features\Darah\KomponenDarah\KomponenDarahModel();
        $modelGolDarah = new \App\Features\Darah\GolonganDarah\GolonganDarahModel();
        $modelRhesus   = new \App\Features\Darah\Rhesus\RhesusModel();

        $masterKomponen = $modelKomponen->findAll();
        $masterGolDarah = $modelGolDarah->findAll();
        $masterRhesus   = $modelRhesus->findAll();

        $mockBaris      = [];
        $konfigGabungan = [];

        $tahunSekarang = date('Y');
        $bulanSekarang = date('m');

        $jumlahPermintaanBulanIni = $this->model->db->table('pelayanan_darah.permintaan_darah')
            ->where('EXTRACT(YEAR FROM tanggal_permintaan)', $tahunSekarang)
            ->where('EXTRACT(MONTH FROM tanggal_permintaan)', $bulanSekarang)
            ->countAllResults();

        $nextUrutan = $jumlahPermintaanBulanIni + 1;
        
        $stringUrutan = str_pad((string)$nextUrutan, 5, '0', STR_PAD_LEFT);
        
        $nomorPermintaanOtomatis = "{$tahunSekarang}/{$bulanSekarang}/REQ{$stringUrutan}";

        foreach ($konfigPermintaan as $fieldPermintaan) {
            $columnPermintaan = $fieldPermintaan[2];

            if ($columnPermintaan === 'id_permintaan') {
                continue;
            }

            $isTanggal = ($fieldPermintaan[3] === 'tanggal' || str_contains($columnPermintaan, 'tanggal') || $fieldPermintaan[3] === 'dtime');
            $mockBaris[$columnPermintaan] = $isTanggal ? date('Y-m-d\TH:i') : '';

            if ($columnPermintaan === 'no_permintaan') {
                $mockBaris[$columnPermintaan] = $nomorPermintaanOtomatis;
                $fieldPermintaan[3] = 'indeks';
            }

            if ($columnPermintaan === 'id_rawat_inap') {
                foreach ($konfigRegistrasi as $fieldRegistrasi) {
                    if ($fieldRegistrasi[2] === 'nomor_rawat') {
                        $mockBaris['nomor_rawat'] = '';
                        $konfigGabungan[] = $fieldRegistrasi;
                        break;
                    }
                }

                foreach ($konfigPasien as $fieldPasien) {
                    if ($fieldPasien[2] === 'nomor_rm') {
                        $mockBaris['nomor_rm'] = '';
                        $konfigGabungan[] = $fieldPasien;
                        break;
                    }
                }

                foreach ($konfigOrang as $fieldOrang) {
                    if ($fieldOrang[2] === 'nama') {
                        $mockBaris['nama'] = '';
                        $konfigGabungan[] = $fieldOrang;
                        break;
                    }
                }

                foreach ($konfigRawatInap as $fieldRanap) {
                    if ($fieldRanap[2] === 'kamar') {
                        $mockBaris['kamar'] = '';
                        $konfigGabungan[] = $fieldRanap;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $fieldPermintaan;
        }

        return view('admin/pelayanandarah/tambah_permintaandarah', [
            'judul'           => 'Tambah ' . $this->title,
            'breadcrumbs'     => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'      => $this->get_uri_path(),
            'kolom_id'        => $this->model->primaryKey,
            'konfig'          => $konfigGabungan,
            'baris'           => $mockBaris,
            'master_komponen' => $masterKomponen,
            'master_gol_darah'=> $masterGolDarah,
            'master_rhesus'   => $masterRhesus,
            'form_action'     => '/submittambah',
        ]);
    }
}
