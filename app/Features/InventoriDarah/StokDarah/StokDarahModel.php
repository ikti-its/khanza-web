<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\StokDarah;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\ModelException;

final class StokDarahModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokDarahDatabase(),
            [
                'id_stok_darah'       => V::DEFAULT(),
                'no_kantong'          => V::DEFAULT(),
                'tanggal_pengambilan' => V::DEFAULT(),
                'tanggal_kadaluarsa'  => V::DEFAULT(),
            ],
            [
                'id_komponen'       => ['nama_komponen'],
                'id_golongan_darah' => ['nama_golongan_darah'],
                'id_rhesus'         => ['kode_rhesus'],
                'id_sumber_darah'   => ['nama_sumber_darah'],
                'id_status_stok'    => ['nama_status_stok'],
            ],
        );
    }

    /**
     * Auto-update status kantong darah yang kadaluwarsa menjadi tidak layak
     * 
     * @throws ModelException
     * @throws DatabaseException
     */
    public function updateStatusKadaluarsa(string $hariIni): void
    {
        $idStatusLayak      = 2;
        $idStatusTidakLayak = 3;

        $this
            ->builder()
            ->where('tanggal_kadaluarsa <', $hariIni)
            ->where('id_status_stok', $idStatusLayak)
            ->update([
                'id_status_stok' => $idStatusTidakLayak,
            ]);
    }

    /**
     * Mengambil data stok darah siap pakai
     * @return list<array<string, mixed>>
     * 
     * @throws ModelException
     */
    public function get_stok_siap_pakai(string $hariIni): array
    {
        $query = $this
            ->builder()
            ->select("
                {$this->table}.id_stok_darah,
                {$this->table}.no_kantong,
                {$this->table}.tanggal_kadaluarsa,
                k.nama_komponen,
                g.nama_golongan_darah AS gol_darah,
                r.kode_rhesus AS rhesus,
                (COALESCE(k.jasa_sarana, 0) + COALESCE(k.paket_bhp, 0) + COALESCE(k.kso, 0) + COALESCE(k.manajemen, 0)) AS total_biaya
            ")
            ->join('inventori_darah.komponen_darah k', 'k.id_komponen = ' . $this->table . '.id_komponen', 'inner')
            ->join('darah.golongan_darah g', 'g.id_golongan_darah = ' . $this->table . '.id_golongan_darah', 'left')
            ->join('darah.rhesus r', 'r.id_rhesus = ' . $this->table . '.id_rhesus', 'left')
            ->where($this->table . '.tanggal_kadaluarsa >=', $hariIni)
            ->where($this->table . '.id_status_stok', 2)
            ->orderBy($this->table . '.tanggal_kadaluarsa', 'ASC')
            ->get();
        
        if ($query === false) {
            return [];
        }

        /** @var list<array<string, mixed>> $data */
        $data = $query->getResultArray();

        return $data;
    }

    /**
     * Memfilter data stok darah berdasarkan status
     */
    public function applyFilter(string $filter): self
    {
        $statusMap = [
            'karantina'     => 1,
            'tersedia'      => 2,
            'tidak_layak'   => 3,
            'terdistribusi' => 4,
        ];

        if (isset($statusMap[$filter])) {
            $this->set_filter('id_status_stok', $statusMap[$filter]);
        }

        return $this;
    }
}
