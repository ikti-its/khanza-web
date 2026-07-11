<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Alamat;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class AlamatModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new AlamatDatabase(),
            [
                'id_alamat'      => V::DEFAULT(),
                'rw'             => V::DEFAULT(),
                'rt'             => V::DEFAULT(),
                'alamat_lengkap' => V::DEFAULT(),
            ],
            [
                'id_provinsi'   => ['nama_provinsi'],
                'id_kota_lokal' => ['nama_kota'],
                'id_kec_lokal'  => ['nama_kecamatan'],
                'id_desa_lokal' => ['nama_desa'],
            ],
        );
    }

    /**
     * Mengambil data alamat lengkap beserta nama wilayahnya
     */
    public function get_detail_wilayah(int|string $idAlamat): null|array
    {
        $alamat = $this->find($idAlamat);
        if (!$alamat) {
            return null;
        }

        $alamat['nama_desa']         = '';
        $alamat['nama_kecamatan']    = '';
        $alamat['nama_kota_wilayah'] = '';
        $alamat['nama_provinsi']     = '';

        $desa = (new \App\Features\Lokasi\Desa\DesaModel())->where([
            'id_provinsi'   => $alamat['id_provinsi'],
            'id_kota_lokal' => $alamat['id_kota_lokal'],
            'id_kec_lokal'  => $alamat['id_kec_lokal'],
            'id_desa_lokal' => $alamat['id_desa_lokal'],
        ])->first();
        if ($desa) {
            $alamat['nama_desa'] = $desa['nama_desa'];
        }

        $kec = (new \App\Features\Lokasi\Kecamatan\KecamatanModel())->where([
            'id_provinsi'   => $alamat['id_provinsi'],
            'id_kota_lokal' => $alamat['id_kota_lokal'],
            'id_kec_lokal'  => $alamat['id_kec_lokal'],
        ])->first();
        if ($kec) {
            $alamat['nama_kecamatan'] = $kec['nama_kecamatan'];
        }

        $kota = (new \App\Features\Lokasi\Kota\KotaModel())->where([
            'id_provinsi'   => $alamat['id_provinsi'],
            'id_kota_lokal' => $alamat['id_kota_lokal'],
        ])->first();
        if ($kota) {
            $alamat['nama_kota_wilayah'] = $kota['nama_kota'];
        }

        $prov = (new \App\Features\Lokasi\Provinsi\ProvinsiModel())->find($alamat['id_provinsi']);
        if ($prov) {
            $alamat['nama_provinsi'] = $prov['nama_provinsi'];
        }

        return $alamat;
    }
}
