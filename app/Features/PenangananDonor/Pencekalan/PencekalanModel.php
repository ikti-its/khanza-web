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
                'id_kunjungan'         => ['nomor_kunjungan'],
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
    private const JENIS_PENCEKALAN_SEMENTARA = 1;
    private const JENIS_PENCEKALAN_PERMANEN  = 2;

    /**
     * Menyinkronkan status pencekalan menjadi selesai jika tanggal selesai sudah lewat
     */
    public function sinkronkanStatusPencekalan(): void
    {
        $this
            ->builder()
            ->set('id_status_pencekalan', self::STATUS_SELESAI)
            ->where('tanggal_selesai IS NOT NULL', null, false)
            ->where('tanggal_selesai <', date('Y-m-d'))
            ->where('id_status_pencekalan !=', self::STATUS_SELESAI)
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
     */
    public function tentukanStatusPencekalan(null|string $tanggalSelesai): int
    {
        if ($tanggalSelesai === null || $tanggalSelesai === '') {
            return self::STATUS_AKTIF;
        }

        return $tanggalSelesai < date('Y-m-d') ? self::STATUS_SELESAI : self::STATUS_AKTIF;
    }

    /**
     * Memfilter data pencekalan berdasarkan status
     */
    public function applyFilterStatus(string $filter): self
    {
        $statusMap = [
            'aktif'   => 1,
            'selesai' => 2,
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
