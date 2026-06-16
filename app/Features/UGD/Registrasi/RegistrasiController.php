<?php
declare(strict_types=1);

namespace App\Features\UGD\Registrasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RegistrasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RegistrasiModel(),
            [
                ['UGD',        'ugd'],
                ['Registrasi', 'registrasi'],
            ],
            'Registrasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_registrasi',     'ID Registrasi'],
                [SHOW, REQUIRED, I::INDEX,  'nomor_reg',         'Nomor Registrasi'],
                [SHOW, REQUIRED, I::INDEX,  'nomor_rawat',       'Nomor Rawat'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_kunjungan', 'Tanggal Kunjungan'],
                [SHOW, REQUIRED, I::INDEX,  'id_pasien',         'ID Pasien'],
                [SHOW, REQUIRED, I::INDEX,  'id_dokter',         'ID Dokter'],
                [HIDE, REQUIRED, I::INDEX,  'id_pj_pasien',      'ID Penanggung Jawab'],
                [HIDE, REQUIRED, I::TEXT,   'hubungan_pj',       'Hubungan Penanggung Jawab'],
                [HIDE, REQUIRED, I::TEXT,   'alamat_pj',         'Alamat Penanggung Jawab'],
                [HIDE, REQUIRED, I::MONEY,  'biaya_registrasi',  'Biaya Registrasi'],
                [HIDE, REQUIRED, I::TEXT,   'status_rawat',      'Status Rawat'],
                [HIDE, REQUIRED, I::TEXT,   'jenis_bayar',       'Jenis Bayar'],
                [SHOW, REQUIRED, I::SELECT, 'status_bayar',      'Status Bayar'],
            ],
        );
    }

    /**
     * Menampilkan data modal registrasi kunjungan UGD
     */
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model->builder($tabel . ' r')
            ->select([
                'r.id_registrasi',
                'r.nomor_reg',
                'r.nomor_rawat',
                'r.tanggal_kunjungan',
                'r.id_pasien',
                'r.id_dokter',
                'p.nomor_rm',
                'o.nama AS nama_pasien',
                'o.tanggal_lahir',
                'od.nama AS nama_dokter'
            ])
            ->join('role.pasien p',   'p.id_pasien = r.id_pasien', 'inner')
            ->join('person.orang o',  'o.id_orang  = p.id_orang', 'inner')
            ->join('role.dokter d',   'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang',  'left')
            ->orderBy('r.tanggal_kunjungan', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}
