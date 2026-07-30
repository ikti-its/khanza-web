<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\Pencekalan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PencekalanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PencekalanDatabase(),
            [
                'id_pencekalan'   => V::DEFAULT(),
                'tanggal_mulai'   => V::DEFAULT(),
                'tanggal_selesai' => V::DEFAULT(),
                'keterangan'      => V::DEFAULT(),
            ],
            [
                'id_kunjungan'         => [
                    'nomor_kunjungan',
                    'id_pendonor' => [
                        'id_orang' => ['nama'],
                    ],
                ],
                'id_jenis_pencekalan'  => ['nama_jenis_pencekalan'],
                'id_shift'             => ['nama_shift'],
                'id_petugas'           => [
                    'id_orang' => ['nama'],
                ],
                'id_status_pencekalan' => ['nama_status_pencekalan'],
            ],
        );
    }

    private const STATUS_AKTIF               = 1;
    private const STATUS_SELESAI             = 2;
    private const STATUS_MENUNGGU_UJI_ULANG  = 3;
    private const JENIS_PENCEKALAN_SEMENTARA = 1;
    private const JENIS_PENCEKALAN_PERMANEN  = 2;

    /**
     * Mengambil data pencekalan
     * @param int $limit
     * @param int $offset
     * @return list<array<string, mixed>>
     */
    public function get_data_tabel(int $limit, int $offset): array
    {
        $builder = $this->db
            ->table('penanganan_donor.pencekalan pc')
            ->select([
                'pc.id_pencekalan',
                'k.nomor_kunjungan',
                'o.nama',
                'jp.nama_jenis_pencekalan',
                'pc.tanggal_mulai',
                'pc.tanggal_selesai',
                'sp.nama_status_pencekalan',
            ])
            ->join('donor.kunjungan k', 'k.id_kunjungan = pc.id_kunjungan', 'inner')
            ->join('role.pendonor p', 'p.id_pendonor = k.id_pendonor', 'inner')
            ->join('person.orang o', 'o.id_orang = p.id_orang', 'inner')
            ->join('penanganan_donor.jenis_pencekalan jp', 'jp.id_jenis_pencekalan = pc.id_jenis_pencekalan', 'left')
            ->join(
                'penanganan_donor.status_pencekalan sp',
                'sp.id_status_pencekalan = pc.id_status_pencekalan',
                'left',
            );

        foreach ($this->runtime_filters as $col => $val) {
            is_array($val) ? $builder->whereIn("pc.{$col}", $val) : $builder->where("pc.{$col}", $val);
        }

        return $builder->orderBy('pc.id_pencekalan', 'DESC')->limit($limit, $offset)->get()->getResultArray();
    }

    /**
     * Menyinkronkan status pencekalan yang tanggal selesainya sudah lewat
     */
    public function sinkronkanStatusPencekalan(): void
    {
        $this->db->query("
            UPDATE penanganan_donor.pencekalan pc
            SET id_status_pencekalan = " . self::STATUS_MENUNGGU_UJI_ULANG . "
            WHERE pc.tanggal_selesai IS NOT NULL
              AND pc.tanggal_selesai < CURRENT_DATE
              AND pc.id_status_pencekalan = " . self::STATUS_AKTIF . "
              AND pc.id_jenis_pencekalan = " . self::JENIS_PENCEKALAN_SEMENTARA . "
              AND EXISTS (
                  SELECT 1
                  FROM penanganan_donor.kasus_reaktif kr
                  JOIN uji_darah.hasil_uji_saring hus ON hus.id_uji_saring = kr.id_uji_saring
                  JOIN donor.pengambilan_darah pd ON pd.id_pengambilan_darah = hus.id_pengambilan_darah
                  WHERE pd.id_kunjungan = pc.id_kunjungan
              )
        ");

        $this
            ->builder()
            ->set('id_status_pencekalan', self::STATUS_SELESAI)
            ->where('tanggal_selesai IS NOT NULL', null, false)
            ->where('tanggal_selesai <', date('Y-m-d'))
            ->where('id_status_pencekalan', self::STATUS_AKTIF)
            ->update();
    }

    /**
     * Mengatur status pencekalan default menjadi aktif
     */
    public function setStatusAktif(array &$data): void
    {
        $data['id_status_pencekalan'] = self::STATUS_AKTIF;
    }

    /**
     * Menentukan status pencekalan berdasarkan tanggal selesai
     * @param int|string|null $idPencekalan ID baris pencekalan yang sedang diubah (untuk cek asal kasus reaktif)
     */
    public function tentukanStatusPencekalan(null|string $tanggalSelesai, null|int|string $idPencekalan = null): int
    {
        if ($tanggalSelesai === null || $tanggalSelesai === '') {
            return self::STATUS_AKTIF;
        }

        if ($tanggalSelesai >= date('Y-m-d')) {
            return self::STATUS_AKTIF;
        }

        if ($idPencekalan !== null && $this->berasalDariKasusReaktif($idPencekalan)) {
            return self::STATUS_MENUNGGU_UJI_ULANG;
        }

        return self::STATUS_SELESAI;
    }

    /**
     * Mengecek apakah pencekalan berasal dari kasus reaktif (lewat kunjungan yang sama)
     */
    private function berasalDariKasusReaktif(int|string $idPencekalan): bool
    {
        $row = $this->find($idPencekalan);
        if (!$row || empty($row['id_kunjungan'])) {
            return false;
        }

        return $this->db
            ->table('penanganan_donor.kasus_reaktif kr')
            ->join('uji_darah.hasil_uji_saring hus', 'hus.id_uji_saring = kr.id_uji_saring', 'inner')
            ->join('donor.pengambilan_darah pd', 'pd.id_pengambilan_darah = hus.id_pengambilan_darah', 'inner')
            ->where('pd.id_kunjungan', $row['id_kunjungan'])
            ->countAllResults() > 0;
    }

    /**
     * Memfilter data pencekalan berdasarkan status
     */
    public function applyFilterStatus(string $filter): self
    {
        $statusMap = [
            'aktif'           => self::STATUS_AKTIF,
            'menunggu_retest' => self::STATUS_MENUNGGU_UJI_ULANG,
            'selesai'         => self::STATUS_SELESAI,
        ];

        if (isset($statusMap[$filter])) {
            $this->set_filter('id_status_pencekalan', $statusMap[$filter]);
        }

        return $this;
    }

    /**
     * Memperbarui pencekalan berdasarkan hasil diagnostik
     */
    public function updateDariHasilDiagnostik(
        array $dataUjiSaring,
        string $tanggalHasil,
        array $nilaiDiagnostikDipilih,
    ): void {
        $idPengambilanDarah = $dataUjiSaring['id_pengambilan_darah'] ?? null;

        $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
        $dataPengambilan  = $modelPengambilan->find($idPengambilanDarah);

        if (!$dataPengambilan || empty($dataPengambilan['id_kunjungan'])) {
            throw new \RuntimeException('Data kunjungan donor tidak ditemukan.');
        }

        $dataPencekalan = $this
            ->where('id_kunjungan', $dataPengambilan['id_kunjungan'])
            ->orderBy('id_pencekalan', 'DESC')
            ->first();

        if (!$dataPencekalan) {
            throw new \RuntimeException('Data pencekalan donor tidak ditemukan.');
        }

        if ($this->adaNilaiDiagnostikPositif($nilaiDiagnostikDipilih)) {
            $this->update($dataPencekalan['id_pencekalan'], [
                'id_jenis_pencekalan'  => self::JENIS_PENCEKALAN_PERMANEN,
                'tanggal_selesai'      => null,
                'id_status_pencekalan' => self::STATUS_AKTIF,
            ]);

            return;
        }

        $tanggalSelesai = (new \DateTimeImmutable($tanggalHasil))
            ->modify('+6 months')
            ->format('Y-m-d');

        $this->update($dataPencekalan['id_pencekalan'], [
            'id_jenis_pencekalan'  => self::JENIS_PENCEKALAN_SEMENTARA,
            'tanggal_selesai'      => $tanggalSelesai,
            'id_status_pencekalan' => self::STATUS_AKTIF,
        ]);
    }

    /**
     * Mengecek apakah terdapat hasil diagnostik positif
     */
    private function adaNilaiDiagnostikPositif(array $nilaiDiagnostikDipilih): bool
    {
        if (empty($nilaiDiagnostikDipilih)) {
            return false;
        }

        $dataNilai = $this->db
            ->table('uji_darah.nilai_diagnostik')
            ->select('nama_nilai_diagnostik')
            ->whereIn('id_nilai_diagnostik', $nilaiDiagnostikDipilih)
            ->get()
            ->getResultArray();

        foreach ($dataNilai as $nilai) {
            $namaNilai = strtolower(trim((string) ($nilai['nama_nilai_diagnostik'] ?? '')));

            if ($namaNilai === 'positif') {
                return true;
            }
        }

        return false;
    }

    /**
     * Mencari pencekalan pendonor (lintas kunjungan) yang berstatus "Menunggu Uji Ulang"
     * @param int|string $idPendonor
     * @return array<string, mixed>|null
     */
    public function cariPencekalanSementaraByPendonor(int|string $idPendonor): null|array
    {
        return $this->db
            ->table('penanganan_donor.pencekalan pc')
            ->join('donor.kunjungan k', 'k.id_kunjungan = pc.id_kunjungan', 'inner')
            ->where('k.id_pendonor', $idPendonor)
            ->where('pc.id_status_pencekalan', self::STATUS_MENUNGGU_UJI_ULANG)
            ->orderBy('pc.id_pencekalan', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    /**
     * Eskalasi pencekalan sementara menjadi permanen karena hasil uji saring ulang masih reaktif
     */
    public function eskalasiKePermanen(int|string $idPencekalan): void
    {
        $this->update($idPencekalan, [
            'id_jenis_pencekalan'  => self::JENIS_PENCEKALAN_PERMANEN,
            'tanggal_selesai'      => null,
            'id_status_pencekalan' => self::STATUS_AKTIF,
        ]);
    }

    /**
     * Menyelesaikan pencekalan karena hasil uji saring ulang sudah nonreaktif
     */
    public function selesaikanKarenaUjiUlangBersih(int|string $idPencekalan, string $tanggalUji): void
    {
        $this->update($idPencekalan, [
            'id_status_pencekalan' => self::STATUS_SELESAI,
            'keterangan'           => "Pencekalan selesai — dikonfirmasi bersih lewat uji saring ulang pada {$tanggalUji}.",
        ]);
    }

    /**
     * Mengembalikan pencekalan ke kondisi sementara setelah hasil diagnostik dihapus
     */
    public function resetPencekalanDiagnostik(array $dataUjiSaring): void
    {
        $idPengambilanDarah = $dataUjiSaring['id_pengambilan_darah'] ?? null;

        $modelPengambilan = new \App\Features\Donor\PengambilanDarah\PengambilanDarahModel();
        $dataPengambilan  = $modelPengambilan->find($idPengambilanDarah);

        if (!$dataPengambilan || empty($dataPengambilan['id_kunjungan'])) {
            throw new \RuntimeException('Data kunjungan donor tidak ditemukan.');
        }

        $dataPencekalan = $this
            ->where('id_kunjungan', $dataPengambilan['id_kunjungan'])
            ->orderBy('id_pencekalan', 'DESC')
            ->first();

        if (!$dataPencekalan) {
            throw new \RuntimeException('Data pencekalan donor tidak ditemukan.');
        }

        $this->update($dataPencekalan['id_pencekalan'], [
            'id_jenis_pencekalan'  => self::JENIS_PENCEKALAN_SEMENTARA,
            'tanggal_selesai'      => null,
            'id_status_pencekalan' => self::STATUS_AKTIF,
        ]);
    }
}
