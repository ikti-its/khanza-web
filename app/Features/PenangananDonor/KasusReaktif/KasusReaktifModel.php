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
                                'id_orang' => ['nama'],
                            ],
                        ],
                    ],
                    'tanggal_uji',
                ],
                'id_status_kasus' => ['nama_status_kasus'],
            ],
        );
    }

    private const STATUS_KASUS_DALAM_PEMANTAUAN = 1;
    private const STATUS_KASUS_SELESAI          = 2;

    /**
     * Membuat nomor kasus reaktif otomatis dengan format KR-YYYY-XXXX
     */
    public function generateNomorKasus(null|string $tanggalDitetapkan = null): string
    {
        $tanggal = $tanggalDitetapkan ?: date('Y-m-d');
        $tahun   = date('Y', strtotime($tanggal));

        $prefix = 'KR-' . $tahun . '-';

        $kasusTerakhir = $this
            ->select('nomor_kasus')
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
     * Mengubah status kasus reaktif menjadi selesai
     */
    public function selesaikanKasus(int|string $idKasus): void
    {
        $this->db
            ->table('penanganan_donor.kasus_reaktif')
            ->where('id_kasus', $idKasus)
            ->update([
                'id_status_kasus' => self::STATUS_KASUS_SELESAI,
            ]);
    }

    /**
     * Mengembalikan status kasus reaktif menjadi dalam penanganan
     */
    public function bukaKembaliKasus(int|string $idKasus): void
    {
        $this->db
            ->table('penanganan_donor.kasus_reaktif')
            ->where('id_kasus', $idKasus)
            ->update([
                'id_status_kasus' => self::STATUS_KASUS_DALAM_PEMANTAUAN,
            ]);
    }

    /**
     * Mengambil data kasus reaktif
     * @param int|null $limit
     * @param int $offset
     * @param array<string, mixed> $opsi
     * @return list<array<string, mixed>>
     */
    public function get_data_tabel(null|int $limit = null, int $offset = 0, array $opsi = []): array
    {
        $denganParameter = $opsi['dengan_parameter'] ?? false;
        $untukModal      = $opsi['untuk_modal'] ?? false;
        $belumDiagnostik = $opsi['belum_diagnostik'] ?? false;

        $select = [
            'kr.id_kasus',
            'kr.nomor_kasus',
            'kr.tanggal_ditetapkan',
            'pd.nomor_pengambilan',
            'p.nomor_pendonor',
            'o.nama AS nama_pendonor',
            'sk.nama_status_kasus',
        ];

        if ($denganParameter) {
            $select = array_merge($select, [
                'hus.hbsag',
                'hus.hcv',
                'hus.hiv',
                'hus.sifilis',
                'hus.malaria',
            ]);
        }

        $builder = $this->db
            ->table('penanganan_donor.kasus_reaktif kr')
            ->select($select)
            ->join('uji_darah.hasil_uji_saring hus', 'hus.id_uji_saring = kr.id_uji_saring', 'inner')
            ->join('donor.pengambilan_darah pd', 'pd.id_pengambilan_darah = hus.id_pengambilan_darah', 'left')
            ->join('donor.kunjungan k', 'k.id_kunjungan = pd.id_kunjungan', 'left')
            ->join('role.pendonor p', 'p.id_pendonor = k.id_pendonor', 'left')
            ->join('person.orang o', 'o.id_orang = p.id_orang', 'left')
            ->join('penanganan_donor.status_kasus sk', 'sk.id_status_kasus = kr.id_status_kasus', 'left')
            ->orderBy('kr.nomor_kasus', 'DESC');

        if ($belumDiagnostik) {
            $builder->join('uji_darah.hasil_diagnostik hd', 'hd.id_kasus = kr.id_kasus', 'left')->where(
                'hd.id_diagnostik IS NULL',
                null,
                false,
            );
        }

        if ($limit !== null && $limit > 0) {
            $builder->limit($limit, $offset);
        }

        $data = $builder->get()->getResultArray();

        if ($denganParameter) {
            foreach ($data as &$row) {
                $row['parameter_reaktif'] = $this->getParameterReaktif($row);

                if ($untukModal) {
                    $row['parameter_detail'] = $this->getParameterReaktifDetail($row);
                }
            }

            unset($row);
        }

        return $data;
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

        return !empty($parameterReaktif) ? implode(', ', $parameterReaktif) : '-';
    }

    /**
     * Mengambil detail parameter reaktif
     * @param array<string, mixed> $hasilUjiSaring
     * @return list<array<string, mixed>>
     */
    public function getParameterReaktifDetail(array $hasilUjiSaring): array
    {
        $daftarUji = [
            'hbsag'   => 'HBsAg',
            'hcv'     => 'HCV',
            'hiv'     => 'HIV',
            'sifilis' => 'Sifilis',
            'malaria' => 'Malaria',
        ];

        $modelParameter  = new \App\Features\UjiDarah\ParameterUji\ParameterUjiModel();
        $masterParameter = $modelParameter->findAll();

        $mapParameter = [];

        foreach ($masterParameter as $parameter) {
            $namaParameter = $parameter['nama_parameter'] ?? '';

            if ($namaParameter === '') {
                continue;
            }

            $mapParameter[$this->normalisasiNamaParameter($namaParameter)] = $parameter;
        }

        $parameterReaktif = [];

        foreach ($daftarUji as $kolom => $label) {
            $nilai = $hasilUjiSaring[$kolom] ?? null;

            if (!($nilai === true || $nilai == 1 || $nilai === 't' || $nilai === '1')) {
                continue;
            }

            $key = $this->normalisasiNamaParameter($label);

            if (!isset($mapParameter[$key])) {
                continue;
            }

            $parameterReaktif[] = [
                'id_parameter_uji' => $mapParameter[$key]['id_parameter_uji'],
                'nama_parameter'   => $mapParameter[$key]['nama_parameter'],
            ];
        }

        return $parameterReaktif;
    }

    /**
     * Menyamakan format nama parameter
     */
    private function normalisasiNamaParameter(string $nama): string
    {
        return strtolower(str_replace([' ', '-', '_'], '', $nama));
    }
}
