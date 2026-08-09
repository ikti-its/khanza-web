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
     *
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
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

        /** @var list<array<string, mixed>> */
        return $this->guarded_get($builder, 'get_data_tabel')->getResultArray();
    }

    /**
     * Mengambil satu baris data detail kecamatan
     *
     * @return array<string, mixed>|null
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function find_data(int|string $id): array|null
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
            ->where("{$tabel}.id_kecamatan", $id);

        /** @var array<string, mixed>|null $row */
        $row = $this->guarded_get($builder, 'find_data')->getRowArray();
        return $row ?: null;
    }
}
