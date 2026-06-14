<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\StokDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StokDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokDarahModel(),
            [
                ['Inventaris Darah', 'inventaris_darah'],
                ['Stok Darah',       'stok_darah'],
            ],
            'Stok Darah',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
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
        
        $idStatusLayak      = 2;
        $idStatusTidakLayak = 3; 

        $this->model->builder()
            ->where('tanggal_kadaluarsa <', $hariIni)
            ->where('id_status_stok', $idStatusLayak)
            ->update([
                'id_status_stok' => $idStatusTidakLayak
            ]);

        return parent::index();
    }

    /**
     * Menampilkan data modal stok darah
     */
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model->builder()
            ->select("
                {$tabel}.id_stok_darah,
                {$tabel}.no_kantong,
                {$tabel}.tanggal_kadaluarsa,
                k.nama_komponen,
                g.nama_golongan_darah AS gol_darah,
                r.kode_rhesus AS rhesus,
                (COALESCE(k.jasa_sarana, 0) + COALESCE(k.paket_bhp, 0) + COALESCE(k.kso, 0) + COALESCE(k.manajemen, 0)) AS total_biaya
            ")
            ->join('darah.komponen_darah k', 'k.id_komponen = ' . $tabel . '.id_komponen', 'inner')
            ->join('darah.golongan_darah g', 'g.id_golongan_darah = ' . $tabel . '.id_golongan_darah', 'left')
            ->join('darah.rhesus r', 'r.id_rhesus = ' . $tabel . '.id_rhesus', 'left')
            ->where($tabel . '.tanggal_kadaluarsa >=', date('Y-m-d'))
            ->where($tabel . '.id_status_stok', 2) 
            ->get()
            ->getResultArray();

        foreach ($data as &$row) {
            $row['tanggal_kadaluarsa'] = date('d-m-Y', strtotime($row['tanggal_kadaluarsa']));
        }

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
