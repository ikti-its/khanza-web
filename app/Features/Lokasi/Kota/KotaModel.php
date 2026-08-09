<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Kota;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class KotaModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KotaDatabase(),
            [
                'id_kota'       => V::DEFAULT(),
                'id_kota_lokal' => V::DEFAULT(),
                'nama_kota'     => V::DEFAULT(),
            ],
            [
                'id_provinsi' => ['nama_provinsi'],
            ],
        );
    }

    /**
     * Mengambil data kota
     *
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function get_data_tabel(): array
    {
        $tabel = $this->table;

        $builder = $this
            ->builder()
            ->select("
                {$tabel}.id_kota,
                {$tabel}.id_provinsi,
                {$tabel}.id_kota_lokal,
                {$tabel}.nama_kota AS nama_kota,
                pr.nama_provinsi AS nama_provinsi
            ")
            ->join('lokasi.provinsi pr', "pr.id_provinsi = {$tabel}.id_provinsi", 'inner')
            ->where("{$tabel}.id_kota >", 0);

        /** @var list<array<string, mixed>> */
        return $this->guarded_get($builder, 'get_data_tabel')->getResultArray();
    }
}
