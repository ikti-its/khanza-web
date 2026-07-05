<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\KasusReaktif;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class KasusReaktifController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KasusReaktifModel(),
            [
                ['Penanganan Donor', 'penanganan_donor'],
                ['Kasus Reaktif',    'kasus_reaktif'],
            ],
            'Kasus Reaktif',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::DETAIL
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_kasus',           'ID Kasus'],
                [SHOW, REQUIRED, I::TEXT,   'nomor_kasus',        'Nomor Kasus'],
                [SHOW, REQUIRED, I::INDEX,  'id_uji_saring',      'Nomor Pengambilan'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_ditetapkan', 'Tanggal Ditetapkan'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_kasus',    'Status Kasus'],
            ],
        );
    }

    /**
     * OVERRIDE: Halaman utama Kasus Reaktif
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
            [1, 'Nomor Kasus',        'nomor_kasus',        'teks',    0],
            [1, 'Tanggal Ditetapkan', 'tanggal_ditetapkan', 'tanggal', 0],
            [1, 'Nomor Pengambilan',  'nomor_pengambilan',  'teks',    0],
            [1, 'Nama Pendonor',      'nama_pendonor',      'teks',    0],
            [1, 'Status Kasus',       'nama_status_kasus',  'status',  0],
        ];

        return view('/layouts/data', [
            'judul'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs,
            'meta_data'    => ['page'  => $currentPage, 'size'  => count($data_tabel), 'total' => ceil($totalRows / $perPage)],
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
     * Menampilkan Halaman Detail Kasus Reaktif
     */
    public function detail(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataKasus = $this->model->find($id) ?? [];

        $dataUjiSaring   = [];
        $dataPengambilan = [];
        $dataKunjungan   = [];
        $dataPendonor    = [];
        $dataOrang       = [];

        if (!empty($dataKasus['id_uji_saring'])) {
            $modelUjiSaring = new \App\Features\UjiDarah\HasilUjiSaring\HasilUjiSaringModel();
            $dataUjiSaring  = $modelUjiSaring->find($dataKasus['id_uji_saring']) ?? [];

            if (!empty($dataUjiSaring['id_pengambilan_darah'])) {
                $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
                $dataPengambilan  = $modelPengambilan->find($dataUjiSaring['id_pengambilan_darah']) ?? [];

                if (!empty($dataPengambilan['id_kunjungan'])) {
                    $modelKunjungan = new \App\Features\Donor\Kunjungan\KunjunganModel();
                    $dataKunjungan  = $modelKunjungan->find($dataPengambilan['id_kunjungan']) ?? [];

                    if (!empty($dataKunjungan['id_pendonor'])) {
                        $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
                        $dataPendonor  = $modelPendonor->find($dataKunjungan['id_pendonor']) ?? [];

                        if (!empty($dataPendonor['id_orang'])) {
                            $modelOrang = new \App\Features\Person\Orang\OrangModel();
                            $dataOrang  = $modelOrang->find($dataPendonor['id_orang']) ?? [];
                        }
                    }
                }
            }
        }

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan, $dataPengambilan, $dataUjiSaring, $dataKasus);

        $baris['parameter_reaktif'] = $this->model->getParameterReaktif($dataUjiSaring);

        $controllerUjiSaring = new \App\Features\UjiDarah\HasilUjiSaring\HasilUjiSaringController();
        $konfigUjiSaring     = $controllerUjiSaring->get_fields_with_options(false, true);
        $konfigKasusReaktif  = $this->get_fields_with_options(false, true);
        $konfigGabungan      = array_merge($konfigUjiSaring, $konfigKasusReaktif);

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

        return view('/admin/penanganandonor/detail_kasusreaktif', [
            'judul'       => 'Detail ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'baris'       => $baris,
        ]);
    }
}
