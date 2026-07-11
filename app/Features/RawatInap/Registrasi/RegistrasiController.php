<?php
declare(strict_types=1);

namespace App\Features\RawatInap\Registrasi;

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
                ['Rawat Inap', 'rawat_inap'],
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
                [HIDE, REQUIRED, I::INDEX,  'id_rawat_inap',  'ID Rawat Inap'],
                [SHOW, REQUIRED, I::INDEX,  'id_registrasi',  'ID Registrasi'],
                [HIDE, REQUIRED, I::SELECT, 'jenis_bayar',    'Jenis Bayar'],
                [SHOW, REQUIRED, I::TEXT,   'kamar',          'Kamar'],
                [HIDE, REQUIRED, I::MONEY,  'tarif_kamar',    'Tarif Kamar'],
                [SHOW, REQUIRED, I::TEXT,   'diagnosa_awal',  'Diagnosa Awal'],
                [HIDE, OPTIONAL, I::TEXT,   'diagnosa_akhir', 'Diagnosa Akhir'],
                [HIDE, REQUIRED, I::DATE,   'tanggal_masuk',  'Tanggal Masuk'],
                [HIDE, OPTIONAL, I::DATE,   'tanggal_keluar', 'Tanggal Keluar'],
                [HIDE, OPTIONAL, I::SELECT, 'status_pulang',  'Status Pulang'],
                [HIDE, OPTIONAL, I::TIME,   'jam_keluar',     'Jam Keluar'],
                [HIDE, REQUIRED, I::NUMBER, 'lama_ranap',     'Lama Rawat Inap'],
                [SHOW, REQUIRED, I::INDEX,  'dokter_pj',      'Dokter Penanggung Jawab'],
                [HIDE, REQUIRED, I::MONEY,  'total_biaya',    'Total Biaya'],
                [HIDE, OPTIONAL, I::SELECT, 'status_bayar',   'Status Bayar'],
            ],
        );
    }

    /**
     * Menampilkan data modal registrasi rawat inap
     */
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model
            ->builder()
            ->select("
                {$tabel}.id_rawat_inap,
                {$tabel}.kamar,
                {$tabel}.dokter_pj AS id_dokter,
                registrasi.registrasi.nomor_rawat,
                role.pasien.nomor_rm,
                pasien_orang.nama AS nama,
                dokter_orang.nama AS nama_dokter
            ")
            ->join('registrasi.registrasi', "registrasi.registrasi.id_registrasi = {$tabel}.id_registrasi", 'inner')
            ->join('role.pasien', 'role.pasien.id_pasien = registrasi.registrasi.id_pasien', 'inner')
            ->join('person.orang AS pasien_orang', 'pasien_orang.id_orang = role.pasien.id_orang', 'inner')
            ->join('role.dokter', "role.dokter.id_dokter = {$tabel}.dokter_pj", 'inner')
            ->join('person.orang AS dokter_orang', 'dokter_orang.id_orang = role.dokter.id_orang', 'inner')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data,
        ]);
    }
}
