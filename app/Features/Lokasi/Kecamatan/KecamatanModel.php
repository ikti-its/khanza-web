<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Kecamatan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class KecamatanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KecamatanDatabase(),
            [
                'id_kecamatan'   => V::DEFAULT(),
                'id_kec_lokal'   => V::DEFAULT(),
                'nama_kecamatan' => V::DEFAULT(),
            ],
            [
                'id_provinsi'   => ['nama_provinsi'],
                'id_kota_lokal' => ['nama_kota'],
            ],
        );
    }

    /**
     * Mengambil data kecamatan
     */
    public function get_data_tabel(null|int $limit = null, int $offset = 0): array
    {
        $tabel = $this->table;

        $builder = $this
            ->builder()
            ->select("
                {$tabel}.id_kecamatan,
                {$tabel}.id_provinsi,
                {$tabel}.id_kota_lokal,
                {$tabel}.id_kec_lokal,
                {$tabel}.nama_kecamatan AS nama_kecamatan,
                kt.nama_kota AS nama_kota,
                pr.nama_provinsi AS nama_provinsi
            ")
            ->join(
                'lokasi.kota kt',
                "kt.id_provinsi = {$tabel}.id_provinsi AND kt.id_kota_lokal = {$tabel}.id_kota_lokal",
                'inner',
            )
            ->join('lokasi.provinsi pr', "pr.id_provinsi = {$tabel}.id_provinsi", 'inner')
            ->where("{$tabel}.id_kecamatan >", 0);

        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Mengambil satu baris data detail kecamatan
     */
    public function find_data(int|string $id): array|null
    {
        $tabel = $this->table;

        $row = $this
            ->builder()
            ->select("
                {$tabel}.id_kecamatan,
                {$tabel}.id_provinsi,
                {$tabel}.id_kota_lokal,
                {$tabel}.id_kec_lokal,
                {$tabel}.nama_kecamatan AS nama_kecamatan,
                kt.nama_kota AS nama_kota,
                pr.nama_provinsi AS nama_provinsi
            ")
            ->join(
                'lokasi.kota kt',
                "kt.id_provinsi = {$tabel}.id_provinsi AND kt.id_kota_lokal = {$tabel}.id_kota_lokal",
                'inner',
            )
            ->join('lokasi.provinsi pr', "pr.id_provinsi = {$tabel}.id_provinsi", 'inner')
            ->where("{$tabel}.id_kecamatan", $id)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }
}
