<?php
declare(strict_types=1);

namespace App\Features\RekamMedis\Registrasi;

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
                ['Rekam Medis', 'rekam_medis'],
                ['Registrasi',  'registrasi'],
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
                [SHOW, REQUIRED, I::TEXT,   'nomor_reg',         'Nomor Registrasi'],
                [SHOW, REQUIRED, I::TEXT,   'nomor_rawat',       'Nomor Rawat'],
                [SHOW, REQUIRED, I::DTIME,  'datetime',          'Tanggal & Jam'],
                [SHOW, REQUIRED, I::SELECT, 'id_dokter',         'Dokter'],
                [SHOW, REQUIRED, I::SELECT, 'id_pasien',         'Pasien'],
                [SHOW, REQUIRED, I::SELECT, 'id_poliklinik',     'Poliklinik'],
                [SHOW, REQUIRED, I::SELECT, 'id_pj_pasien',      'Penanggung Jawab'],
                [SHOW, REQUIRED, I::SELECT, 'id_alamat_pj',      'Alamat PJ'],
                [SHOW, REQUIRED, I::TEXT,   'hubungan_pj',       'Hubungan PJ'],
                [SHOW, OPTIONAL, I::TEXT,   'no_telepon',        'No. Telepon Pasien'],
                [SHOW, OPTIONAL, I::TEXT,   'biaya_registrasi',  'Biaya Registrasi'],
                [SHOW, REQUIRED, I::TEXT,   'jenis_bayar',       'Jenis Bayar'],
                [SHOW, REQUIRED, I::TEXT,   'status_registrasi', 'Status Registrasi'],
                [SHOW, REQUIRED, I::TEXT,   'status_rawat',      'Status Rawat'],
                [SHOW, REQUIRED, I::TEXT,   'status_poli',       'Status Poliklinik'],
                [SHOW, REQUIRED, I::TEXT,   'status_bayar',      'Status Bayar'],
            ],
        );
    }
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model->builder($tabel . ' r')
            ->select([
                'r.id_registrasi',
                'r.nomor_reg',
                'r.nomor_rawat',
                'r.datetime',
                'p.nomor_rm',
                'o.nama',
                'd.kode_dokter',
                'od.nama AS nama_dokter',
                'r.id_dokter',
                "COALESCE(ri.kamar, '-') AS kamar"
            ])
            ->join('role.pasien p',   'p.id_pasien = r.id_pasien', 'inner')
            ->join('person.orang o',  'o.id_orang  = p.id_orang', 'inner')
            ->join('role.dokter d',   'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang',  'left')
            ->join('rawat_inap.registrasi ri', 'ri.id_registrasi = r.id_registrasi', 'left')
            ->orderBy('r.datetime', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}