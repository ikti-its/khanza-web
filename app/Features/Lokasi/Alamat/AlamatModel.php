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
     *
     * @return array<string, mixed>|null
     */
    public function get_detail_wilayah(int|string $idAlamat): null|array
    {
        $alamat = $this->find($idAlamat);
        if (!is_array($alamat)) {
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
        if (is_array($desa)) {
            $alamat['nama_desa'] = $desa['nama_desa'] ?? '';
        }

        $kec = (new \App\Features\Lokasi\Kecamatan\KecamatanModel())->where([
            'id_provinsi'   => $alamat['id_provinsi'],
            'id_kota_lokal' => $alamat['id_kota_lokal'],
            'id_kec_lokal'  => $alamat['id_kec_lokal'],
        ])->first();
        if (is_array($kec)) {
            $alamat['nama_kecamatan'] = $kec['nama_kecamatan'] ?? '';
        }

        $kota = (new \App\Features\Lokasi\Kota\KotaModel())->where([
            'id_provinsi'   => $alamat['id_provinsi'],
            'id_kota_lokal' => $alamat['id_kota_lokal'],
        ])->first();
        if (is_array($kota)) {
            $alamat['nama_kota_wilayah'] = $kota['nama_kota'] ?? '';
        }

        $prov = (new \App\Features\Lokasi\Provinsi\ProvinsiModel())->find((string) $alamat['id_provinsi']);
        if (is_array($prov)) {
            $alamat['nama_provinsi'] = (string) ($prov['nama_provinsi'] ?? '');
        }

        /** @var array<string, mixed> */
        return $alamat;
    }
}
