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
                [SHOW, OPTIONAL, I::TEXT,   'pekerjaanpj',       'Pekerjaan PJ'],
                [SHOW, OPTIONAL, I::TEXT,   'notelp_pj',         'No. Telp PJ'],
                [SHOW, OPTIONAL, I::TEXT,   'no_asuransi',       'No. Asuransi'],
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
        $nomorReg = $this->request->getGet('nomor_reg') ?? '';
        $nama     = $this->request->getGet('nama')      ?? '';

        $builder = $this->model->db
            ->table('rekam_medis.registrasi r')
            ->select([
                'r.nomor_reg',
                'r.nomor_rawat',
                'r.datetime',
                'p.nomor_rm',
                'o.nama',
                'd.kode_dokter',
                'od.nama AS nama_dokter',
                'r.id_dokter',
            ])
            ->join('role.pasien p',   'p.id_pasien = r.id_pasien')
            ->join('person.orang o',  'o.id_orang  = p.id_orang')
            ->join('role.dokter d',   'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang',  'left');

        if ($nomorReg !== '') {
            $builder->like('r.nomor_reg', $nomorReg);
        }
        if ($nama !== '') {
            $builder->like('o.nama', $nama);
        }

        $rows = $builder->orderBy('r.datetime', 'ASC')->get()->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}