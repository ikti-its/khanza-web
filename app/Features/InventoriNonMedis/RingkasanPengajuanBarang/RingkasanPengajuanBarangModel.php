<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPengajuanBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use App\Features\InventoriNonMedis\PengajuanBarang\PengajuanBarangDatabase;

final class RingkasanPengajuanBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengajuanBarangDatabase(),
            [
                'id_pengajuan'     => V::DEFAULT(),
                'no_pengajuan'     => V::DEFAULT(),
                'tanggal'          => V::DEFAULT(),
                'tanggal_diproses' => V::DEFAULT(),
                'total_harga'      => V::DEFAULT(),
            ],
            [
                'petugas_gudang'             => ['id_orang' => ['nama']],
                'atasan_logistik'            => ['id_orang' => ['nama']],
                'id_status_pengajuan_barang' => ['nama_status_pengajuan_barang'],
            ],
        );
    }

    // Batasi pilihan status: Proses Pengajuan (4), Disetujui (2), Ditolak (3) — tanpa Draf
    #[\Override]
    public function get_all_options(): array
    {
        $options = parent::get_all_options();

        if (isset($options['id_status_pengajuan_barang'])) {
            $options['id_status_pengajuan_barang'] = array_values(array_filter($options['id_status_pengajuan_barang'], fn(array $opt) => in_array(
                $opt[1],
                ['2', '3', '4'],
                true,
            )));
        }

        return $options;
    }
}
