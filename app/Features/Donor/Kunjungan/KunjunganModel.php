<?php
declare(strict_types=1);

namespace App\Features\Donor\Kunjungan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class KunjunganModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KunjunganDatabase(),
            [
                'id_kunjungan'      => V::DEFAULT(),
                'nomor_kunjungan'   => V::DEFAULT(),
                'tanggal_kunjungan' => V::DEFAULT(),
            ],
            [
                'id_pendonor' => [
                    'nomor_pendonor',
                    'id_orang'  => [
                        'nama',
                        'nik',
                        'tanggal_lahir',
                        'id_jenis_kelamin'  => ['nama_jenis_kelamin'],
                        'id_golongan_darah' => ['nama_golongan_darah']
                    ],
                    'id_rhesus' => ['kode_rhesus']
                ],
            ],
        );
    }

    /**
     * Mengambil data registrasi kunjungan
     * @param int|null $limit
     * @param int $offset
     * @param string|null $filter
     * @return list<array<string, mixed>>
     */
    public function get_data_tabel(?int $limit = null, int $offset = 0, ?string $filter = null): array
    {
        $builder = $this->db
            ->table('donor.kunjungan k')
            ->select([
                'k.id_kunjungan',
                'k.nomor_kunjungan',
                'k.tanggal_kunjungan',
                'p.id_pendonor',
                'p.nomor_pendonor',
                'o.nama'
            ])
            ->join('role.pendonor p', 'p.id_pendonor = k.id_pendonor', 'inner')
            ->join('person.orang o', 'o.id_orang = p.id_orang', 'inner')
            ->orderBy('k.tanggal_kunjungan', 'DESC');

        if ($filter === 'lolos_skrining') {
            $builder->join('donor.skrining_donor sd', 'sd.id_kunjungan = k.id_kunjungan', 'inner')
                    ->where('sd.id_status_skrining', 1);
        }

        if ($limit !== null && $limit > 0) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Memeriksa apakah pendonor lolos syarat interval jeda donor UTD
     * @param int|string $idPendonor
     * @param string $tglKunjunganInput
     * @return array ['status' => bool, 'message' => string]
     */
    public function cekIntervalMedis(int|string $idPendonor, string $tglKunjunganInput): array
    {
        $minimalJedaHari = 84;

        $modelPendonor = new \App\Features\Role\Pendonor\PendonorModel();
        $dataPendonor  = $modelPendonor->find($idPendonor);

        if ($dataPendonor && !empty($dataPendonor['tanggal_donor_terakhir'])) {
            $tglDonorTerakhir = new \DateTime($dataPendonor['tanggal_donor_terakhir']);
            $tglKunjunganBaru = new \DateTime($tglKunjunganInput);

            $selisih = $tglDonorTerakhir->diff($tglKunjunganBaru);
            $jumlahHari = (int) $selisih->format('%r%a');

            if ($jumlahHari < $minimalJedaHari) {
                $sisaHari = $minimalJedaHari - $jumlahHari;
                $sisaMinggu = (int) ceil($sisaHari / 7);
                
                return [
                    'status'  => false,
                    'message' => "Gagal Mendaftarkan Kunjungan! Sesuai regulasi Kemenkes, syarat interval jeda donor minimal adalah 3 bulan / 12 minggu. Calon pendonor baru bisa donor kembali dalam {$sisaHari} hari lagi (sekitar {$sisaMinggu} minggu)."
                ];
            }
        }

        return ['status' => true, 'message' => ''];
    }

    /**
     * Memeriksa apakah pendonor memiliki pencekalan aktif
     * @param int|string $idPendonor
     * @return array ['status' => bool, 'message' => string]
     */
    private function cekPencekalanAktif(int|string $idPendonor): array
    {
        $dataPencekalan = $this->db
            ->table('penanganan_donor.pencekalan pc')
            ->select([
                'pc.id_pencekalan',
                'pc.tanggal_mulai',
                'pc.tanggal_selesai',
                'k.nomor_kunjungan',
            ])
            ->join('donor.kunjungan k', 'k.id_kunjungan = pc.id_kunjungan', 'inner')
            ->where('k.id_pendonor', $idPendonor)
            ->where('pc.id_status_pencekalan', 1)
            ->get()
            ->getRowArray();

        if (!empty($dataPencekalan)) {
            $tanggalSelesai = !empty($dataPencekalan['tanggal_selesai'])
                ? date('d-m-Y', strtotime($dataPencekalan['tanggal_selesai']))
                : 'belum ditentukan';

            return [
                'status'  => false,
                'message' => "Gagal Mendaftarkan Kunjungan! Pendonor masih memiliki pencekalan aktif pada kunjungan {$dataPencekalan['nomor_kunjungan']}. Tanggal selesai pencekalan: {$tanggalSelesai}."
            ];
        }

        return ['status' => true, 'message' => ''];
    }

    /**
     * Memeriksa apakah pendonor memenuhi seluruh syarat registrasi kunjungan
     * @param int|string|null $idPendonor
     * @param string $tglKunjunganInput
     * @return array ['status' => bool, 'message' => string]
     */
    public function cekSyaratRegistrasiKunjungan(int|string|null $idPendonor, string $tglKunjunganInput): array
    {
        if (empty($idPendonor)) {
            return [
                'status'  => false,
                'message' => 'Gagal Mendaftarkan Kunjungan! Data pendonor belum dipilih.'
            ];
        }

        $pencekalanModel = new \App\Features\PenangananDonor\Pencekalan\PencekalanModel();
        $pencekalanModel->sinkronkanStatusPencekalan();

        $validasiPencekalan = $this->cekPencekalanAktif($idPendonor);

        if ($validasiPencekalan['status'] === false) {
            return $validasiPencekalan;
        }

        $validasiInterval = $this->cekIntervalMedis($idPendonor, $tglKunjunganInput);

        if ($validasiInterval['status'] === false) {
            return $validasiInterval;
        }

        return ['status' => true, 'message' => ''];
    }

    /**
     * Memeriksa apakah kunjungan sudah digunakan pada proses lanjutan
     */
    public function kunjunganSudahDiproses(int|string $idKunjungan): bool
    {
        $daftarTabel = [
            'donor.skrining_donor',
            'donor.pengambilan_darah',
            'penanganan_donor.pencekalan',
        ];
    
        foreach ($daftarTabel as $tabel) {
            $jumlahData = $this->db
                ->table($tabel)
                ->where('id_kunjungan', $idKunjungan)
                ->countAllResults();
    
            if ($jumlahData > 0) {
                return true;
            }
        }
    
        return false;
    }
}
