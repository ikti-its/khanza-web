<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRad;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class HasilRadController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadModel(),
            [
                ['Radiologi',       'radiologi'],
                ['Hasil Radiologi', 'hasil_rad'],
            ],
            'Hasil Radiologi',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::PRINT,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_hasil_rad',      'ID Hasil Radiologi'],
                [SHOW,       REQUIRED, I::INDEX,  'id_permintaan_rad', 'No. Permintaan'],
                [SHOW,       REQUIRED, I::TEXT,   'id_dokter_pj',      'Dokter PJ'],
                [SHOW,       REQUIRED, I::TEXT,   'id_petugas_rad',    'Petugas Rad'],
                [HIDE,       REQUIRED, I::TEXT,   'id_dokter_perujuk', 'Dokter Perujuk'],
                [SHOW,       REQUIRED, I::DTIME,  'tgl_jam_hasil',     'Tanggal dan Jam Hasil'],
                [TABLE_ONLY, OPTIONAL, I::SELECT, 'nama_status',       'Status'],
            ],
        );
    }

    #[\Override]
    protected function after_read(array &$data_tabel): void
    {
        if (empty($data_tabel)) {
            return;
        }

        $ids      = array_column($data_tabel, 'id_permintaan_rad');
        $statuses = $this->model
            ->db
            ->table('radiologi.permintaan_rad pr')
            ->select(['pr.id_permintaan', 's.nama_status'])
            ->join('radiologi.ref_status_permintaan_rad s', 's.id_status = pr.id_status_permintaan', 'left')
            ->whereIn('pr.id_permintaan', $ids)
            ->get()
            ->getResultArray();

        $map = array_column($statuses, 'nama_status', 'id_permintaan');

        foreach ($data_tabel as &$row) {
            $row['nama_status'] = $map[$row['id_permintaan_rad']] ?? null;
        }
    }

    // ──────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────

    private function getKonfig(): array
    {
        return array_values(array_filter(
            $this->get_fields_with_options(false, true),
            static fn($f) => !in_array(
                $f[2],
                [
                    'id_hasil_rad',
                    'id_permintaan_rad',
                    'id_dokter_pj',
                    'id_petugas_rad',
                    'id_dokter_perujuk',
                ],
                true,
            ),
        ));
    }

    private function fetchTemplateRad(): array
    {
        $templates = $this->model
            ->db
            ->table('radiologi.ref_template_rad')
            ->select(['id_template', 'nama_template', 'isi_teks_ekspertise'])
            ->orderBy('nama_template', 'ASC')
            ->get()
            ->getResultArray();

        return array_map(static function ($t) {
            $t['isi_teks_ekspertise'] = str_replace('\n', "\n", $t['isi_teks_ekspertise']);
            return $t;
        }, $templates);
    }

    private function fetchBarangNonMedis(): array
    {
        return $this->model
            ->db
            ->table('inventori_non_medis.barang b')
            ->select(['b.id_barang', 'b.kode_barang', 'b.nama_barang', 'b.stok', 's.nama_satuan'])
            ->join('inventori_non_medis.satuan s', 's.id_satuan = b.id_satuan', 'left')
            ->orderBy('b.nama_barang', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function fetchDetailPermintaan(int $idPermintaanRad): array
    {
        return $this->model
            ->db
            ->table('radiologi.permintaan_rad pr')
            ->select([
                'pr.id_permintaan',
                'pr.no_permintaan',
                'pr.nomor_reg',
                'r.id_dokter AS id_dokter_perujuk',
                'o.nama AS nama_pasien',
                'od.nama AS nama_dokter_perujuk',
            ])
            ->join('registrasi.registrasi r', 'r.nomor_reg   = pr.nomor_reg')
            ->join('role.pasien p', 'p.id_pasien   = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang    = p.id_orang', 'left')
            ->join('role.dokter d', 'd.id_dokter   = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang   = d.id_orang', 'left')
            ->where('pr.id_permintaan', $idPermintaanRad)
            ->get()
            ->getRowArray() ?? [];
    }

    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue, string $aliasKolom): null|string
    {
        $row = $this->model
            ->db
            ->table("role.{$tabel} t")
            ->select(["o.nama AS {$aliasKolom}"])
            ->join('person.orang o', 'o.id_orang = t.id_orang')
            ->where("t.{$idKolom}", $idValue)
            ->get()
            ->getRowArray();

        return $row[$aliasKolom] ?? null;
    }

    private function adjustStok(int $idBarang, int $jumlah, string $operator = '-'): void
    {
        $this->model
            ->db
            ->table('inventori_non_medis.barang')
            ->where('id_barang', $idBarang)
            ->set('stok', "stok {$operator} {$jumlah}", false)
            ->update();
    }

    private function fetchStokBarang(int $idBarang): array
    {
        return $this->model
            ->db
            ->table('inventori_non_medis.barang')
            ->select(['nama_barang', 'stok'])
            ->where('id_barang', $idBarang)
            ->get()
            ->getRowArray() ?? [];
    }

    private function processFotoUpload(int $idHasilRad): void
    {
        $fotoModel = new \App\Features\Radiologi\HasilRadFoto\HasilRadFotoModel();
        foreach ($this->request->getFiles()['foto'] ?? [] as $file) {
            if (!$this->upload_valid($file, ['jpg', 'jpeg', 'png', 'webp'], 5 * 1024 * 1024)) {
                continue;
            }

            $newName = $file->getRandomName();
            $fotoModel->insert([
                'id_hasil_rad' => $idHasilRad,
                'nama_file'    => $newName,
                'konten_file'  => '\x' . bin2hex(file_get_contents($file->getTempName())),
                'tgl_upload'   => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function tindakanKosong(array $tindakan): bool
    {
        $fieldTeks = [
            'proyeksi',
            'inaktivasi',
            'dosis_radiasi',
            'hasil_ekspertise',
            'kilovoltage_kv',
            'milliampere_second_mas',
            'focus_film_distance_ffd',
            'back_scatter_factor_bsf',
            'jumlah_penyinaran',
        ];
        foreach ($fieldTeks as $f) {
            if (trim((string) ($tindakan[$f] ?? '')) !== '') {
                return false;
            }
        }
        return empty($tindakan['id_template_rad']);
    }

    /** Simpan hasil tindakan jika ada field terisi; hapus baris lama jika semua field dikosongkan kembali. */
    private function syncTindakan(
        \App\Features\Radiologi\HasilRadTindakan\HasilRadTindakanModel $modelTindakan,
        array $existingByItem,
        array $tindakan,
        int $idHasilRad,
    ): void {
        $idItem = (int) ($tindakan['id_permintaan_item'] ?? 0);
        if ($idItem <= 0) {
            return;
        }

        $existing = $existingByItem[$idItem] ?? null;

        if ($this->tindakanKosong($tindakan)) {
            if ($existing !== null) {
                $modelTindakan->delete((int) $existing['id_hasil_tindakan']);
            }
            return;
        }

        $data = [
            'id_permintaan_item'      => $idItem,
            'proyeksi'                => $tindakan['proyeksi'] ?? '' ? $tindakan['proyeksi'] ?? '' : null,
            'kilovoltage_kv'          => ($tindakan['kilovoltage_kv'] ?? '') !== ''
                ? (float) $tindakan['kilovoltage_kv']
                : null,
            'milliampere_second_mas'  => ($tindakan['milliampere_second_mas'] ?? '') !== ''
                ? (float) $tindakan['milliampere_second_mas']
                : null,
            'focus_film_distance_ffd' => ($tindakan['focus_film_distance_ffd'] ?? '') !== ''
                ? (float) $tindakan['focus_film_distance_ffd']
                : null,
            'back_scatter_factor_bsf' => ($tindakan['back_scatter_factor_bsf'] ?? '') !== ''
                ? (float) $tindakan['back_scatter_factor_bsf']
                : null,
            'inaktivasi'              => $tindakan['inaktivasi'] ?? '' ? $tindakan['inaktivasi'] ?? '' : null,
            'jumlah_penyinaran'       => ($tindakan['jumlah_penyinaran'] ?? '') !== ''
                ? (int) $tindakan['jumlah_penyinaran']
                : null,
            'dosis_radiasi'           => $tindakan['dosis_radiasi'] ?? '' ? $tindakan['dosis_radiasi'] ?? '' : null,
            'hasil_ekspertise'        => $tindakan['hasil_ekspertise'] ?? ''
                ? $tindakan['hasil_ekspertise'] ?? ''
                : null,
            'id_template_rad'         => !empty($tindakan['id_template_rad'])
                ? (int) $tindakan['id_template_rad']
                : null,
        ];

        if ($existing !== null) {
            $modelTindakan->update((int) $existing['id_hasil_tindakan'], $data);
            return;
        }
        $modelTindakan->insert($data + ['id_hasil_rad' => $idHasilRad]);
    }

    /**
     * Simpan/hapus satu baris BHP dan sesuaikan stok hanya sebesar selisihnya
     * (bukan kembalikan-semua-lalu-kurangi-semua), termasuk saat baris dihapus
     * user dari form (jumlah_pakai kosong) atau baris lama sudah tidak dikirim lagi.
     */
    private function syncBhp(
        \App\Features\Radiologi\HasilRadBhp\HasilRadBhpModel $modelBhp,
        array $existingByBarang,
        int $idBarang,
        array $bhp,
        int $idHasilRad,
    ): void {
        if ($idBarang <= 0) {
            return;
        }

        $rawJumlah = $bhp['jumlah_pakai'] ?? 0;
        if (!is_numeric($rawJumlah)) {
            throw new \RuntimeException('Jumlah pakai BHP harus berupa angka.');
        }

        $jumlahBaru = (int) $rawJumlah;
        if ($jumlahBaru < 0) {
            throw new \RuntimeException('Jumlah pakai BHP tidak boleh negatif.');
        }

        $existing = $existingByBarang[$idBarang] ?? null;

        if ($jumlahBaru === 0) {
            if ($existing !== null) {
                $this->adjustStok($idBarang, (int) $existing['jumlah_pakai'], '+');
                $modelBhp->delete((int) $existing['id_rad_bhp']);
            }
            return;
        }

        $barang       = $this->fetchStokBarang($idBarang);
        $stokTersedia = (int) ($barang['stok'] ?? 0) + ($existing !== null ? (int) $existing['jumlah_pakai'] : 0);
        if ($jumlahBaru > $stokTersedia) {
            throw new \RuntimeException(sprintf(
                'Jumlah pakai BHP "%s" (%d) melebihi stok tersedia (%d).',
                $barang['nama_barang'] ?? $idBarang,
                $jumlahBaru,
                $stokTersedia,
            ));
        }

        if ($existing !== null) {
            $selisih = $jumlahBaru - (int) $existing['jumlah_pakai'];
            if ($selisih !== 0) {
                $this->adjustStok($idBarang, abs($selisih), $selisih > 0 ? '-' : '+');
            }
            $modelBhp->update((int) $existing['id_rad_bhp'], ['jumlah_pakai' => $jumlahBaru]);
            return;
        }
        $modelBhp->insert([
            'id_hasil_rad' => $idHasilRad,
            'id_barang'    => $idBarang,
            'jumlah_pakai' => $jumlahBaru,
        ]);
        $this->adjustStok($idBarang, $jumlahBaru, '-');
    }

    private function upsertTindakanAndBhp(int $idHasilRad, array $tindakanList, array $bhpList): void
    {
        $modelTindakan          = new \App\Features\Radiologi\HasilRadTindakan\HasilRadTindakanModel();
        $existingTindakanByItem = array_column(
            $modelTindakan->set_filter('id_hasil_rad', $idHasilRad)->findAll(),
            null,
            'id_permintaan_item',
        );

        foreach ($tindakanList as $tindakan) {
            $this->syncTindakan($modelTindakan, $existingTindakanByItem, $tindakan, $idHasilRad);
        }

        $modelBhp            = new \App\Features\Radiologi\HasilRadBhp\HasilRadBhpModel();
        $existingBhpByBarang = array_column(
            $modelBhp->set_filter('id_hasil_rad', $idHasilRad)->findAll(),
            null,
            'id_barang',
        );

        $submittedBarangIds = [];
        foreach ($bhpList as $idBarang => $bhp) {
            $idBarang                      = (int) $idBarang;
            $submittedBarangIds[$idBarang] = true;
            $this->syncBhp($modelBhp, $existingBhpByBarang, $idBarang, $bhp, $idHasilRad);
        }

        // BHP yang dihapus user dari form (barisnya tidak dikirim lagi) tetap harus dikembalikan stoknya
        foreach ($existingBhpByBarang as $idBarang => $lama) {
            if (array_key_exists((int) $idBarang, $submittedBarangIds)) {
                continue;
            }
            $this->adjustStok((int) $lama['id_barang'], (int) $lama['jumlah_pakai'], '+');
            $modelBhp->delete((int) $lama['id_rad_bhp']);
        }
    }

    private function tentukanStatusPermintaan(array $tindakanList): int
    {
        // Selesai (3) hanya jika semua tindakan sudah punya hasil ekspertise;
        // selain itu tetap Sedang Diproses (2) menunggu bacaan dokter.
        if (empty($tindakanList)) {
            return 2;
        }

        foreach ($tindakanList as $tindakan) {
            if (trim($tindakan['hasil_ekspertise'] ?? '') === '') {
                return 2;
            }
        }

        return 3;
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function create_page(): string
    {
        return view('admin/radiologi/tambah_hasil_rad', [
            'judul'            => 'Tambah ' . $this->title,
            'breadcrumbs'      => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'       => $this->get_uri_path(),
            'kolom_id'         => $this->model->primaryKey,
            'konfig'           => $this->getKonfig(),
            'baris'            => [],
            'form_action'      => '/submittambah',
            'template_rad'     => $this->fetchTemplateRad(),
            'barang_non_medis' => $this->fetchBarangNonMedis(),
            'item_terpilih'    => [],
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // PROSES SIMPAN
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $dataHeader = [
            'id_permintaan_rad' => (int) ($rawPost['id_permintaan_rad'] ?? 0)
                ? (int) ($rawPost['id_permintaan_rad'] ?? 0)
                : null,
            'id_dokter_pj'      => (int) ($rawPost['id_dokter_pj'] ?? 0) ? (int) ($rawPost['id_dokter_pj'] ?? 0) : null,
            'id_petugas_rad'    => (int) ($rawPost['id_petugas_rad'] ?? 0)
                ? (int) ($rawPost['id_petugas_rad'] ?? 0)
                : null,
            'tgl_jam_hasil'     => $rawPost['tgl_jam_hasil'] ?? date('Y-m-d H:i:s'),
            'catatan'           => $rawPost['catatan'] ?? '',
        ];

        $tindakanList = $rawPost['tindakan'] ?? [];
        $bhpList      = $rawPost['bhp'] ?? [];

        $this->model->db->transStart();

        try {
            // 1. Insert header
            $this->model->insert($dataHeader);
            $idHasilRad = $this->model->getInsertID();

            // 2. Insert tindakan dan BHP
            $this->upsertTindakanAndBhp($idHasilRad, $tindakanList, $bhpList);

            // 3. Update status permintaan sesuai kelengkapan ekspertise
            if (!empty($dataHeader['id_permintaan_rad'])) {
                $this->model
                    ->db
                    ->table('radiologi.permintaan_rad')
                    ->where('id_permintaan', $dataHeader['id_permintaan_rad'])
                    ->set('id_status_permintaan', $this->tentukanStatusPermintaan($tindakanList))
                    ->update();
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan hasil radiologi.');
            }

            $this->processFotoUpload((int) $idHasilRad);

            session()->setFlashdata('success', 'Hasil radiologi berhasil disimpan.');
            return redirect()->to($this->get_uri_path() . '/data');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN UBAH
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function update_page(int|string $id): string
    {
        $baris = $this->model->find($id);

        if (empty($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        if (!empty($baris['id_permintaan_rad'])) {
            $baris = array_merge($baris, $this->fetchDetailPermintaan((int) $baris['id_permintaan_rad']));
        }

        if (!empty($baris['id_dokter_pj'])) {
            $baris['nama_dokter_pj'] =
                $this->fetchNamaRole('dokter', 'id_dokter', (int) $baris['id_dokter_pj'], 'nama_dokter_pj') ?? '';
        }

        if (!empty($baris['id_petugas_rad'])) {
            $baris['nama_petugas'] =
                $this->fetchNamaRole('petugas', 'id_petugas', (int) $baris['id_petugas_rad'], 'nama_petugas') ?? '';
        }

        $itemTerpilih = $this->model
            ->db
            ->table('radiologi.hasil_rad_tindakan hrt')
            ->select([
                'hrt.id_hasil_tindakan',
                'hrt.id_permintaan_item',
                'r.kode_periksa',
                'r.nama_pemeriksaan',
                'pri.is_baca_saja',
                'hrt.proyeksi',
                'hrt.kilovoltage_kv',
                'hrt.milliampere_second_mas',
                'hrt.focus_film_distance_ffd',
                'hrt.back_scatter_factor_bsf',
                'hrt.inaktivasi',
                'hrt.jumlah_penyinaran',
                'hrt.dosis_radiasi',
                'hrt.hasil_ekspertise',
                'hrt.id_template_rad',
            ])
            ->join('radiologi.permintaan_rad_item pri', 'pri.id_permintaan_item = hrt.id_permintaan_item')
            ->join('radiologi.ref_item_rad r', 'r.id_item = pri.id_item')
            ->where('hrt.id_hasil_rad', $id)
            ->get()
            ->getResultArray();

        $bhpTerpilih = $this->model
            ->db
            ->table('radiologi.hasil_rad_bhp hrb')
            ->select([
                'hrb.id_barang',
                'b.kode_barang',
                'b.nama_barang',
                's.nama_satuan',
                'hrb.jumlah_pakai',
                'b.stok',
            ])
            ->join('inventori_non_medis.barang b', 'b.id_barang  = hrb.id_barang')
            ->join('inventori_non_medis.satuan s', 's.id_satuan  = b.id_satuan', 'left')
            ->where('hrb.id_hasil_rad', $id)
            ->get()
            ->getResultArray();

        $fotoTerpilih = $this->model
            ->db
            ->table('radiologi.hasil_rad_foto')
            ->select(['id_rad_foto', 'nama_file'])
            ->where('id_hasil_rad', $id)
            ->orderBy('tgl_upload', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/radiologi/tambah_hasil_rad', [
            'judul'            => 'Ubah ' . $this->title,
            'breadcrumbs'      => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'       => $this->get_uri_path(),
            'kolom_id'         => $this->model->primaryKey,
            'konfig'           => $this->getKonfig(),
            'baris'            => $baris,
            'form_action'      => '/submitedit/' . $id,
            'template_rad'     => $this->fetchTemplateRad(),
            'barang_non_medis' => $this->fetchBarangNonMedis(),
            'item_terpilih'    => $itemTerpilih,
            'bhp_terpilih'     => $bhpTerpilih,
            'foto_terpilih'    => $fotoTerpilih,
            'id_hasil_rad'     => $id,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // PROSES UPDATE
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        $rawPost = $this->request->getPost();

        $dataHeader = [
            'id_permintaan_rad' => (int) ($rawPost['id_permintaan_rad'] ?? 0)
                ? (int) ($rawPost['id_permintaan_rad'] ?? 0)
                : null,
            'id_dokter_pj'      => (int) ($rawPost['id_dokter_pj'] ?? 0) ? (int) ($rawPost['id_dokter_pj'] ?? 0) : null,
            'id_petugas_rad'    => (int) ($rawPost['id_petugas_rad'] ?? 0)
                ? (int) ($rawPost['id_petugas_rad'] ?? 0)
                : null,
            'tgl_jam_hasil'     => $rawPost['tgl_jam_hasil'] ?? date('Y-m-d H:i:s'),
            'catatan'           => $rawPost['catatan'] ?? '',
        ];

        $tindakanList = $rawPost['tindakan'] ?? [];
        $bhpList      = $rawPost['bhp'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->update($id, $dataHeader);

            $this->upsertTindakanAndBhp((int) $id, $tindakanList, $bhpList);

            if (!empty($dataHeader['id_permintaan_rad'])) {
                $this->model
                    ->db
                    ->table('radiologi.permintaan_rad')
                    ->where('id_permintaan', $dataHeader['id_permintaan_rad'])
                    ->set('id_status_permintaan', $this->tentukanStatusPermintaan($tindakanList))
                    ->update();
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui hasil radiologi.');
            }

            session()->setFlashdata('success', 'Hasil radiologi berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    // ──────────────────────────────────────────────────────────
    // DELETE — cascade hapus tindakan & BHP
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        // Fetch BHP lama sebelum transaksi agar tidak terblokir oleh abort
        $modelBhp = new \App\Features\Radiologi\HasilRadBhp\HasilRadBhpModel();
        $bhpLama  = $modelBhp->set_filter('id_hasil_rad', $id)->findAll();

        $this->model->db->transStart();

        try {
            (new \App\Features\Radiologi\HasilRadTindakan\HasilRadTindakanModel())
                ->where('id_hasil_rad', $id)
                ->delete();

            foreach ($bhpLama as $lama) {
                $this->adjustStok((int) $lama['id_barang'], (int) $lama['jumlah_pakai'], '+');
            }
            $modelBhp->where('id_hasil_rad', $id)->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus hasil radiologi.');
            }

            session()->setFlashdata('success', 'Hasil radiologi berhasil dihapus.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return $this->home();
    }

    // ──────────────────────────────────────────────────────────
    // CETAK HASIL EKSPERTISE
    // ──────────────────────────────────────────────────────────

    #[\Override]
    public function print(int|string $id): string
    {
        $hasilRad = $this->model->find($id);

        if (empty($hasilRad)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $detailPermintaan = !empty($hasilRad['id_permintaan_rad'])
            ? $this->fetchDetailPermintaan((int) $hasilRad['id_permintaan_rad'])
            : [];

        $namaDokterPj = !empty($hasilRad['id_dokter_pj'])
            ? $this->fetchNamaRole('dokter', 'id_dokter', (int) $hasilRad['id_dokter_pj'], 'nama_dokter_pj')
            : null;

        $tindakanList = $this->model
            ->db
            ->table('radiologi.hasil_rad_tindakan hrt')
            ->select(['r.kode_periksa', 'r.nama_pemeriksaan', 'hrt.hasil_ekspertise'])
            ->join('radiologi.permintaan_rad_item pri', 'pri.id_permintaan_item = hrt.id_permintaan_item')
            ->join('radiologi.ref_item_rad r', 'r.id_item = pri.id_item')
            ->where('hrt.id_hasil_rad', $id)
            ->get()
            ->getResultArray();

        // $organisasi = $this->model->db->table('ref.organisasi')->get()->getRowArray() ?? [];

        return view('Views/components/cetak/cetak_hasil_rad', [
            'hasilRad'         => $hasilRad,
            'detailPermintaan' => $detailPermintaan,
            'namaDokterPj'     => $namaDokterPj,
            'tindakanList'     => $tindakanList,
            // 'organisasi'    => $organisasi,
        ]);
    }
}
