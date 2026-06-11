<?php
declare(strict_types=1);

namespace App\Features\Donor\Kunjungan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class KunjunganController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KunjunganModel(),
            [
                ['Donor',     'donor'],
                ['Kunjungan', 'kunjungan'],
            ],
            'Kunjungan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_kunjungan',      'ID Kunjungan'],
                [SHOW, REQUIRED, I::TEXT,  'nomor_antrian',     'Nomor Antrian'],
                [SHOW, REQUIRED, I::TEXT,  'nomor_kunjungan',   'Nomor Kunjungan'],
                [SHOW, REQUIRED, I::DTIME, 'tanggal_kunjungan', 'Tanggal Kunjungan'],
                [SHOW, REQUIRED, I::INDEX, 'id_pendonor',       'ID Pendonor'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Kunjungan
     */
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah']
        ];

        $fieldsKunjunganMatang = $this->fields;
        
        $controllerPendonor = new \App\Features\Role\Pendonor\PendonorController();
        $fieldsPendonorMatang = $controllerPendonor->fields;

        $mockBaris = [];
        $konfigGabungan = [];

        $tanggalHariIni = date('Y-m-d H:i:s');
        
        $jumlahKunjunganHariIni = $this->model->db->table('donor.kunjungan')
            ->where('DATE(tanggal_kunjungan)', $tanggalHariIni)
            ->countAllResults();

        $nextAntrian = $jumlahKunjunganHariIni + 1;
        $nomorAntrianOtomatis = str_pad((string)$nextAntrian, 3, '0', STR_PAD_LEFT);

        $formatTanggalPendek = date('ymd');
        $nomorKunjunganOtomatis = 'REG-' . $formatTanggalPendek . '-' . $nomorAntrianOtomatis;

        foreach ($fieldsKunjunganMatang as $fieldKunjungan) {
            $columnKunjungan = $fieldKunjungan[2];

            if ($columnKunjungan === 'id_kunjungan') {
                continue;
            }

            if ($columnKunjungan === 'tanggal_kunjungan') {
                $mockBaris[$columnKunjungan] = $tanggalHariIni;
            } elseif ($columnKunjungan === 'nomor_antrian') {
                $mockBaris[$columnKunjungan] = $nomorAntrianOtomatis;
                $fieldKunjungan[3] = 'indeks';
            } elseif ($columnKunjungan === 'nomor_kunjungan') {
                $mockBaris[$columnKunjungan] = $nomorKunjunganOtomatis;
                $fieldKunjungan[3] = 'indeks';
            } else {
                $mockBaris[$columnKunjungan] = '';
            }

            if ($columnKunjungan === 'id_pendonor') {
                foreach ($fieldsPendonorMatang as $fieldPendonor) {
                    if ($fieldPendonor[2] === 'nomor_pendonor') {
                        $mockBaris['nomor_pendonor'] = '';
                        
                        $konfigGabungan[] = $fieldPendonor;
                        break;
                    }
                }
                continue;
            }

            $konfigGabungan[] = $fieldKunjungan;
        }

        return view('/admin/donor/tambah_kunjungan', [
            'judul'       => 'Registrasi ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigGabungan, 
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Kunjungan
     */
    #[\Override]
    final public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $dataKunjungan = $this->model->find($id);
        if (!$dataKunjungan) {
            $dataKunjungan = [];
        }

        $dataOrang = [];
        $dataPendonor = [];
        if (!empty($dataKunjungan['id_pendonor'])) {
            $pendonorModel = new \App\Features\Role\Pendonor\PendonorModel();
            $dataPendonor = $pendonorModel->find($dataKunjungan['id_pendonor']) ?? [];

            if (!empty($dataPendonor['id_orang'])) {
                $modelOrang = new \App\Features\Person\Orang\OrangModel();
                $dataOrang = $modelOrang->find($dataPendonor['id_orang']) ?? [];
            }
        }

        $baris = array_merge($dataOrang, $dataPendonor, $dataKunjungan);

        if (!empty($baris['tanggal_kunjungan'])) {
            $baris['tanggal_kunjungan'] = date('Y-m-d H:i:s', strtotime($baris['tanggal_kunjungan']));
        }

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $controllerPendonor = new \App\Features\Role\Pendonor\PendonorController();

        $konfigOrang = $controllerOrang->get_fields_with_options(false, true);
        $konfigPendonor = $controllerPendonor->get_fields_with_options(false, true);
        $konfigKunjungan = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];

        foreach ($konfigKunjungan as $fieldKunjungan) {
            $kolomKunjungan = $fieldKunjungan[2];

            if ($kolomKunjungan === 'id_pendonor') {
                foreach ($konfigPendonor as $fieldPendonor) {
                    $kolomPendonor = $fieldPendonor[2];

                    if ($kolomPendonor === 'id_orang') {
                        $konfigGabungan = array_merge($konfigGabungan, $konfigOrang);
                    } else {
                        $konfigGabungan[] = $fieldPendonor;
                    }
                }
            } else {
                $konfigGabungan[] = $fieldKunjungan;
            }
        }

        $card = $baris;
        foreach ($konfigGabungan as $field) {
            $namaKolom = $field[2];
            $tipeField = $field[3];
            $options   = $field[5] ?? [];

            if ($tipeField === 'status' && !empty($options) && isset($card[$namaKolom])) {
                $idMentah = $card[$namaKolom];
                foreach ($options as $opt) {
                    if ((string)$opt[1] === (string)$idMentah) {
                        $card[$namaKolom] = $opt[0];
                        break;
                    }
                }
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

        return view('/admin/donor/tambah_kunjungan', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $konfigKunjungan,
            'baris'       => $baris,
            'card'        => $card,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    /**
     * Menampilkan data modal kunjungan
     */
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model->builder()
            ->select("
                {$tabel}.id_kunjungan,
                {$tabel}.nomor_kunjungan,
                role.pendonor.id_pendonor,
                role.pendonor.nomor_pendonor,
                person.orang.nama
            ")
            ->join('role.pendonor', "role.pendonor.id_pendonor = {$tabel}.id_pendonor", 'inner')
            ->join('person.orang', 'person.orang.id_orang = role.pendonor.id_orang', 'inner')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
