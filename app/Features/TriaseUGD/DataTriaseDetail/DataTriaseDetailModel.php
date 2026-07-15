<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriaseDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class DataTriaseDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataTriaseDetailDatabase(),
            [
                'id_triase_detail' => V::DEFAULT(),
            ],
            [
                'id_triase' => [
                    'id_registrasi' => ['nomor_rawat'],
                ],
                'id_skala'  => ['pengkajian'],
            ],
        );
    }

    /**
     * Mengambil daftar kriteria skala yang tercentang berdasarkan ID Triase
     */
    public function getKriteriaOlehTriase(int|string $idTriase): array
    {
        return $this
            ->builder()
            ->select('
                triase_ugd.data_triase_detail.id_skala,
                triase_ugd.triase_skala.pengkajian,
                triase_ugd.triase_skala.id_tingkat_skala,
                triase_ugd.triase_pemeriksaan.nama_pemeriksaan
            ')
            ->join(
                'triase_ugd.triase_skala',
                'triase_ugd.triase_skala.id_skala = triase_ugd.data_triase_detail.id_skala',
                'inner',
            )
            ->join(
                'triase_ugd.triase_pemeriksaan',
                'triase_ugd.triase_pemeriksaan.id_pemeriksaan = triase_ugd.triase_skala.id_pemeriksaan',
                'inner',
            )
            ->where('triase_ugd.data_triase_detail.id_triase', $idTriase)
            ->get()
            ->getResultArray();
    }

    /**
     * Mendapatkan tingkat kegawatan skala tertinggi (1-5)
     */
    public function hitungSkalaFinal(int|string $idTriase): null|int
    {
        $listKriteria = $this->getKriteriaOlehTriase($idTriase);

        if (empty($listKriteria)) {
            return null;
        }

        $kumpulanSkalaNum = array_map(fn($item) => (int) $item['id_tingkat_skala'], $listKriteria);

        // Menggunakan min() karena Skala 1 (Immediate) memiliki tingkat kegawatan tertinggi
        return min($kumpulanSkalaNum);
    }
}
