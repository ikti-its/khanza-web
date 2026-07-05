<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\KasusReaktif;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class KasusReaktifModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KasusReaktifDatabase(),
            [
                'id_kasus'           => V::DEFAULT(),
                'nomor_kasus'        => V::DEFAULT(),
                'tanggal_ditetapkan' => V::DEFAULT(),
            ],
            [
                'id_uji_saring'   => [
                    'id_pengambilan_darah' => [
                        'nomor_pengambilan',
                        'id_kunjungan' => [
                            'id_pendonor' => [
                                'nomor_pendonor',
                                'id_orang' => ['nama']
                            ],
                        ],
                    ],
                    'tanggal_uji'
                ],
                'id_status_kasus' => ['nama_status_kasus'],
            ],
        );
    }

    /**
     * Membuat nomor kasus reaktif otomatis dengan format KR-YYYY-XXXX
     */
    public function generateNomorKasus(?string $tanggalDitetapkan = null): string
    {
        $tanggal = $tanggalDitetapkan ?: date('Y-m-d');
        $tahun   = date('Y', strtotime($tanggal));
    
        $prefix = 'KR-' . $tahun . '-';
    
        $kasusTerakhir = $this->select('nomor_kasus')
            ->like('nomor_kasus', $prefix, 'after')
            ->orderBy('nomor_kasus', 'DESC')
            ->first();
    
        $urutanBerikutnya = 1;
    
        if (!empty($kasusTerakhir['nomor_kasus'])) {
            $urutanTerakhir   = (int) substr((string) $kasusTerakhir['nomor_kasus'], -4);
            $urutanBerikutnya = $urutanTerakhir + 1;
        }
    
        return $prefix . str_pad((string) $urutanBerikutnya, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Mengambil data kasus reaktif
     * @param int|null $limit
     * @param int $offset
     * @return list<array<string, mixed>>
     */
    public function get_data_tabel(?int $limit = null, int $offset = 0): array
    {
        $builder = $this->db
            ->table('penanganan_donor.kasus_reaktif kr')
            ->select([
                'kr.id_kasus',
                'kr.nomor_kasus',
                'kr.tanggal_ditetapkan',
                'pd.nomor_pengambilan',
                'p.nomor_pendonor',
                'o.nama AS nama_pendonor',
                'sk.nama_status_kasus',
            ])
            ->join('uji_darah.hasil_uji_saring hus', 'hus.id_uji_saring = kr.id_uji_saring', 'inner')
            ->join('donor.pengambilan_darah pd', 'pd.id_pengambilan_darah = hus.id_pengambilan_darah', 'left')
            ->join('donor.kunjungan k', 'k.id_kunjungan = pd.id_kunjungan', 'left')
            ->join('role.pendonor p', 'p.id_pendonor = k.id_pendonor', 'left')
            ->join('person.orang o', 'o.id_orang = p.id_orang', 'left')
            ->join('penanganan_donor.status_kasus sk', 'sk.id_status_kasus = kr.id_status_kasus', 'left')
            ->orderBy('kr.nomor_kasus', 'DESC');

        if ($limit !== null && $limit > 0) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Mengambil parameter reaktif dari hasil uji saring
     */
    public function getParameterReaktif(array $hasilUjiSaring): string
    {
        $daftarUji = [
            'hbsag'   => 'HBsAg',
            'hcv'     => 'HCV',
            'hiv'     => 'HIV',
            'sifilis' => 'Sifilis',
            'malaria' => 'Malaria',
        ];

        $parameterReaktif = [];

        foreach ($daftarUji as $kolom => $label) {
            $nilai = $hasilUjiSaring[$kolom] ?? null;

            if ($nilai === true || $nilai == 1 || $nilai === 't' || $nilai === '1') {
                $parameterReaktif[] = $label;
            }
        }

        return !empty($parameterReaktif)
            ? implode(', ', $parameterReaktif)
            : '-';
    }
}
