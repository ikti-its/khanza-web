<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\PemisahanKomponen;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use CodeIgniter\Database\Exceptions\DatabaseException;

final class PemisahanKomponenModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PemisahanKomponenDatabase(),
            [
                'id_pemisahan'      => V::DEFAULT(),
                'tanggal_pemisahan' => V::DEFAULT(),
            ],
            [
                'id_pengambilan_darah' => ['nomor_pengambilan'],
                'id_shift'             => ['nama_shift'],
                'id_petugas'           => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }

    /**
     * Batas jumlah komponen yang boleh dipisah berdasarkan kode jenis bag
     * @var array<string, int>
     */
    private const array BATAS_KOMPONEN = [
        'SB' => 1,
        'DB' => 2,
        'TB' => 3,
        'QB' => 4,
    ];

    /**
     * Mengambil data pemisahan komponen
     * @param int $limit
     * @param int $offset
     * @return list<array<string, mixed>>
     * 
     * @throws DatabaseException
     */
    public function get_data_tabel(int $limit, int $offset): array
    {
        $query = $this->db
            ->table('inventori_darah.pemisahan_komponen pk')
            ->select([
                'pk.id_pemisahan',
                'pk.tanggal_pemisahan',
                'pd.nomor_pengambilan',
                'pd.no_bag',
                's.nama_shift',
                'o_petugas.nama AS nama_petugas',
            ])
            ->join('donor.pengambilan_darah pd', 'pd.id_pengambilan_darah = pk.id_pengambilan_darah', 'inner')
            ->join('operasional.shift s', 's.id_shift = pk.id_shift', 'left')
            ->join('role.petugas pt', 'pt.id_petugas = pk.id_petugas', 'inner')
            ->join('person.orang o_petugas', 'o_petugas.id_orang = pt.id_orang', 'inner')
            ->orderBy('pk.tanggal_pemisahan', 'DESC')
            ->limit($limit, $offset)
            ->get();
        
        if ($query === false) {
            return [];
        }

        /** @var list<array<string, mixed>> $data */
        $data = $query->getResultArray();

        return $data;
    }

    /**
     * Memastikan tanggal input pemisahan valid
     * @param int|string $idPengambilan
     * @param string $tanggalPemisahanInput
     * @return void
     * 
     * @throws \InvalidArgumentException
     * @throws DatabaseException
     */
    public function validasiTanggalPemisahan(int|string $idPengambilan, string $tanggalPemisahanInput): void
    {
        if (trim((string) $idPengambilan) === '' || (int) $idPengambilan <= 0) {
            throw new \InvalidArgumentException('Gagal menyimpan! Data pengambilan darah belum dipilih.');
        }

        if (trim($tanggalPemisahanInput) === '') {
            throw new \InvalidArgumentException('Gagal menyimpan! Tanggal pemisahan komponen wajib diisi.');
        }

        $query = $this->db
            ->table('donor.pengambilan_darah')
            ->select('tanggal_pengambilan')
            ->where('id_pengambilan_darah', $idPengambilan)
            ->get();
        
        $pengambilan = $query !== false ? $query->getRowArray() : null;

        if (!$pengambilan || empty($pengambilan['tanggal_pengambilan'])) {
            throw new \InvalidArgumentException(
                'Gagal menyimpan! Tanggal pengambilan darah tidak ditemukan.',
            );
        }

        try {
            $tglPengambilan = (new \DateTimeImmutable((string) $pengambilan['tanggal_pengambilan']))
                ->setTime(0, 0, 0);

            $tglPemisahan = \DateTimeImmutable::createFromFormat(
                '!Y-m-d',
                substr(trim($tanggalPemisahanInput), 0, 10),
            );

            $parseErrors = \DateTimeImmutable::getLastErrors();
            if (
                $tglPemisahan === false
                || ($parseErrors !== false && ($parseErrors['warning_count'] > 0 || $parseErrors['error_count'] > 0))
            ) {
                throw new \InvalidArgumentException('Format tanggal pemisahan komponen tidak valid.');
            }
        } catch (\InvalidArgumentException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException(
                'Gagal menyimpan! Format tanggal pengambilan darah tidak valid.',
                0,
                $e,
            );
        }

        if ($tglPemisahan < $tglPengambilan) {
            throw new \InvalidArgumentException(
                'Gagal menyimpan! Tanggal pemisahan komponen tidak boleh mendahului tanggal pengambilan darah.',
            );
        }

        $hariIni = new \DateTimeImmutable('today');
        if ($tglPemisahan > $hariIni) {
            throw new \InvalidArgumentException(
                'Gagal menyimpan! Tanggal pemisahan komponen tidak boleh melebihi tanggal hari ini.',
            );
        }
    }

    /**
     * Mengambil batas jumlah komponen berdasarkan jenis bag pengambilan darah
     * @param string|int $idPengambilan
     * @return array{nama_jenis_bag: string, batas: int|null}
     * 
     * @throws DatabaseException
     */
    public function getBatasKomponen(string|int $idPengambilan): array
    {
        $query = $this->db
            ->table('donor.pengambilan_darah pd')
            ->select('jb.kode_jenis_bag, jb.nama_jenis_bag')
            ->join('donor.jenis_bag jb', 'jb.id_jenis_bag = pd.id_jenis_bag', 'left')
            ->where('pd.id_pengambilan_darah', $idPengambilan)
            ->get();
        
        $row = $query !== false ? $query->getRowArray() : null;

        $kode = is_array($row) && isset($row['kode_jenis_bag']) ? (string) $row['kode_jenis_bag'] : null;
        $nama = is_array($row) && isset($row['nama_jenis_bag']) ? (string) $row['nama_jenis_bag'] : '-';

        return [
            'nama_jenis_bag' => $nama,
            'batas'          => $kode !== null ? (self::BATAS_KOMPONEN[$kode] ?? null) : null,
        ];
    }

    /**
     * Memastikan jumlah komponen yang dipilih tidak melebihi batas maksimal jenis bag pengambilan darah
     * @param string|int $idPengambilan
     * @param array $komponenTerpilih
     * @return void
     * 
     * @throws \InvalidArgumentException
     * @throws DatabaseException
     */
    public function validasiJumlahKomponen(string|int $idPengambilan, array $komponenTerpilih): void
    {
        $batasKomponen = $this->getBatasKomponen($idPengambilan);
        $batas         = $batasKomponen['batas'];

        if ($batas === null) {
            return;
        }

        if (count($komponenTerpilih) > $batas) {
            throw new \InvalidArgumentException(
                "Gagal Menyimpan! Jenis bag {$batasKomponen['nama_jenis_bag']} maksimal {$batas} komponen darah.",
            );
        }
    }

    /**
     * Mengambil data hasil pemisahan
     * @return list<array<string, mixed>>
     * 
     * @throws DatabaseException
     */
    public function getHasilPemisahan(int|string $idPemisahan): array
    {
        $query = $this->db
            ->table('inventori_darah.pemisahan_komponen_detail as pkd')
            ->select('pkd.*, k.kode_komponen, k.nama_komponen, k.masa_berlaku_hari')
            ->join('inventori_darah.komponen_darah k', 'k.id_komponen = pkd.id_komponen')
            ->where('pkd.id_pemisahan', $idPemisahan)
            ->get();
        
        if ($query === false) {
            return [];
        }

        /** @var list<array<string, mixed>> $data */
        $data = $query->getResultArray();

        return $data;
    }

    /**
     * Mengambil data penggunaan BHP medis pemisahan
     * @return list<array<string, mixed>>
     * 
     * @throws DatabaseException
     */
    public function getBhpMedisDetail(int|string $idPemisahan): array
    {
        $query = $this->db
            ->table('logistik_utd.medis_pemisahan')
            ->where('id_pemisahan', $idPemisahan)
            ->get();
        
        if ($query === false) {
            return [];
        }

        /** @var list<array<string, mixed>> $bhpMedis */
        $bhpMedis = $query->getResultArray();

        $modelMasterMedis = new \App\Features\InventoriMedis\DataBarang\DataBarangModel();
        foreach ($bhpMedis as $k => $v) {
            $idBarang = isset($v['id_barang']) ? (int) $v['id_barang'] : null;
            if (!is_int($idBarang)) {
                continue;
            }

            $masterItem = $modelMasterMedis->find($idBarang);
            if (!is_array($masterItem)) {
                continue;
            }

            $bhpMedis[$k]['kode_barang'] = $masterItem['kode_barang'] ?? '-';
            $bhpMedis[$k]['nama_barang'] = $masterItem['nama'] ?? '-';
        }

        return $bhpMedis;
    }

    /**
     * Mengambil data penggunaan BHP non medis pemisahan
     * @return list<array<string, mixed>>
     * 
     * @throws DatabaseException
     */
    public function getBhpPenunjangDetail(int|string $idPemisahan): array
    {
        $query = $this->db
            ->table('logistik_utd.penunjang_pemisahan')
            ->where('id_pemisahan', $idPemisahan)
            ->get();
        
        if ($query === false) {
            return [];
        }

        /** @var list<array<string, mixed>> $bhpPenunjang */
        $bhpPenunjang = $query->getResultArray();

        $modelMasterPenunjang = new \App\Features\InventoriNonMedis\Barang\BarangModel();
        foreach ($bhpPenunjang as $k => $v) {
            $idBarang = isset($v['id_barang']) ? (int) $v['id_barang'] : null;
            if (!is_int($idBarang)) {
                continue;
            }

            $masterItem = $modelMasterPenunjang->find($idBarang);
            if (!is_array($masterItem)) {
                continue;
            }

            $bhpPenunjang[$k]['kode_barang'] = $masterItem['kode_barang'] ?? '-';
            $bhpPenunjang[$k]['nama_barang'] = $masterItem['nama_barang'] ?? '-';
        }

        return $bhpPenunjang;
    }
}
