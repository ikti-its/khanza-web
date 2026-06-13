<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRad;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

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
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_hasil_rad',        'ID Hasil Radiologi'],
                [SHOW, REQUIRED, I::INDEX, 'id_permintaan_rad',   'No. Permintaan'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_pj',        'Dokter PJ'],
                [SHOW, REQUIRED, I::TEXT,  'id_petugas_rad',      'Petugas Rad'],
                [SHOW, REQUIRED, I::TEXT,  'id_dokter_perujuk',   'Dokter Perujuk'],
                [SHOW, REQUIRED, I::DTIME, 'tgl_jam_hasil',       'Tanggal dan Jam Hasil'],
                [SHOW, OPTIONAL, I::TEXT,  'catatan',             'Catatan'],
            ],
        );
    }

    // ──────────────────────────────────────────────────────────
    // HALAMAN TAMBAH
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    final public function create_page(): string
    {
        $breadcrumbs = [['title' => 'Tambah', 'icon' => 'tambah']];
 
        $templateRad = $this->model->db
            ->table('radiologi.ref_template_rad')
            ->select(['id_template', 'nama_template', 'isi_teks_ekspertise'])
            ->orderBy('nama_template', 'ASC')
            ->get()
            ->getResultArray();

        $templateRad = array_map(function($t) {
            $t['isi_teks_ekspertise'] = str_replace('\n', "\n", $t['isi_teks_ekspertise']);
            return $t;
        }, $templateRad);
        
        $barangNonMedis = $this->model->db
            ->table('inventori_non_medis.barang b')
            ->select(['b.id_barang', 'b.kode_barang', 'b.nama_barang', 'b.stok', 's.nama_satuan'])
            ->join('inventori_non_medis.satuan s', 's.id_satuan = b.id_satuan', 'left')
            ->orderBy('b.nama_barang', 'ASC')
            ->get()
            ->getResultArray();
 
        $konfig = array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn($f) => !in_array($f[2], [
                'id_hasil_rad',
                'id_permintaan_rad',
                'id_dokter_pj',
                'id_petugas_rad',
                'id_dokter_perujuk',
            ], true)
        ));
 
        return view('admin/radiologi/tambah_hasil_rad', [
            'judul'           => 'Tambah ' . $this->title,
            'breadcrumbs'     => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'      => $this->get_uri_path(),
            'kolom_id'        => $this->model->primaryKey,
            'konfig'          => $konfig,
            'baris'           => [],
            'form_action'     => '/submittambah',
            'template_rad'    => $templateRad,
            'barang_non_medis'=> $barangNonMedis,
            'item_terpilih'   => [],
        ]);
    }
 
    // ──────────────────────────────────────────────────────────
    // PROSES SIMPAN
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    public function create(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $rawPost = $this->request->getPost();
 
        $dataHeader = [
            'id_permintaan_rad' => (int) ($rawPost['id_permintaan_rad'] ?? 0) ?: null,
            'id_dokter_pj'      => (int) ($rawPost['id_dokter_pj']      ?? 0) ?: null,
            'id_petugas_rad'    => (int) ($rawPost['id_petugas_rad']    ?? 0) ?: null,
            'tgl_jam_hasil'     => $rawPost['tgl_jam_hasil']     ?? date('Y-m-d H:i:s'),
            'catatan'           => $rawPost['catatan']           ?? '',
        ];

        $tindakanList = $rawPost['tindakan'] ?? [];
        $bhpList      = $rawPost['bhp']      ?? [];

        $this->model->db->transStart();

        try {
            // 1. Insert header
            $this->model->insert($dataHeader);
            $idHasilRad = $this->model->getInsertID();
 
            // 2. Insert tindakan
            $modelTindakan = new \App\Features\Radiologi\HasilRadTindakan\HasilRadTindakanModel();
            foreach ($tindakanList as $tindakan) {
                $modelTindakan->insert([
                    'id_hasil_rad'            => $idHasilRad,
                    'id_permintaan_item'      => (int) ($tindakan['id_permintaan_item']      ?? 0),
                    'proyeksi'                => $tindakan['proyeksi']                       ?? '',
                    'kilovoltage_kv'          => (float) ($tindakan['kilovoltage_kv']        ?? 0),
                    'milliampere_second_mas'  => (float) ($tindakan['milliampere_second_mas'] ?? 0),
                    'focus_film_distance_ffd' => (float) ($tindakan['focus_film_distance_ffd'] ?? 0),
                    'back_scatter_factor_bsf' => (float) ($tindakan['back_scatter_factor_bsf'] ?? 0),
                    'inaktivasi'              => $tindakan['inaktivasi']                     ?? '',
                    'jumlah_penyinaran'       => (int) ($tindakan['jumlah_penyinaran']       ?? 0),
                    'dosis_radiasi'           => $tindakan['dosis_radiasi']                  ?? '',
                    'hasil_ekspertise'        => $tindakan['hasil_ekspertise']               ?? '',
                    'id_template_rad'         => !empty($tindakan['id_template_rad'])
                                                    ? (int) $tindakan['id_template_rad']
                                                    : null,
                ]);
            }
 
            // 3. Insert BHP
            $modelBhp = new \App\Features\Radiologi\HasilRadBhp\HasilRadBhpModel();
            foreach ($bhpList as $idBarang => $bhp) {
                if (empty($bhp['jumlah_pakai']) || (int) $bhp['jumlah_pakai'] <= 0) continue;
                $modelBhp->insert([
                    'id_hasil_rad' => $idHasilRad,
                    'id_barang'    => (int) $idBarang,
                    'jumlah_pakai' => (int) $bhp['jumlah_pakai'],
                ]);
                
                // Kurangi stok
                $this->model->db
                    ->table('inventori_non_medis.barang')
                    ->where('id_barang', (int) $idBarang)
                    ->set('stok', 'stok - ' . (int) $bhp['jumlah_pakai'], false)
                    ->update();
            }
            
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan hasil radiologi.');
            }
 
            session()->setFlashdata('success', 'Hasil radiologi berhasil disimpan.');
            return redirect()->to($this->get_uri_path() . '/data');
 
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
            return redirect()->back()->withInput();
        } catch (\RuntimeException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
 
    // ──────────────────────────────────────────────────────────
    // HALAMAN UBAH
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    final public function update_page(int|string $id): string
    {
        $breadcrumbs = [['title' => 'Ubah', 'icon' => 'ubah']];
 
        $baris = $this->model->find($id);
 
        // Fetch data join manual
        if (!empty($baris['id_permintaan_rad'])) {
            $permintaan = $this->model->db
                ->table('radiologi.permintaan_rad pr')
                ->select([
                    'pr.id_permintaan',
                    'pr.no_permintaan',
                    'pr.nomor_reg',
                    'pr.kode_dokter_perujuk',
                    'o.nama AS nama_pasien',
                    'od.nama AS nama_dokter_perujuk',
                ])
                ->join('rekam_medis.registrasi r',  'r.nomor_reg   = pr.nomor_reg')
                ->join('role.pasien p',             'p.id_pasien   = r.id_pasien')
                ->join('person.orang o',            'o.id_orang    = p.id_orang')
                ->join('role.dokter d',             'd.kode_dokter = pr.kode_dokter_perujuk', 'left')
                ->join('person.orang od',           'od.id_orang   = d.id_orang', 'left')
                ->where('pr.id_permintaan', $baris['id_permintaan_rad'])
                ->get()
                ->getRowArray();
 
            if ($permintaan) {
                $baris = array_merge($baris, $permintaan);
            }
        }
 
        // Fetch nama dokter PJ
        if (!empty($baris['id_dokter_pj'])) {
            $dokterPJ = $this->model->db
                ->table('role.dokter d')
                ->select(['o.nama AS nama_dokter_pj'])
                ->join('person.orang o', 'o.id_orang = d.id_orang')
                ->where('d.id_dokter', $baris['id_dokter_pj'])
                ->get()
                ->getRowArray();
 
            if ($dokterPJ) {
                $baris['nama_dokter_pj'] = $dokterPJ['nama_dokter_pj'];
            }
        }
 
        // Fetch nama petugas
        if (!empty($baris['id_petugas_rad'])) {
            $petugas = $this->model->db
                ->table('role.petugas p')
                ->select(['o.nama AS nama_petugas'])
                ->join('person.orang o', 'o.id_orang = p.id_orang')
                ->where('p.id_petugas', $baris['id_petugas_rad'])
                ->get()
                ->getRowArray();
 
            if ($petugas) {
                $baris['nama_petugas'] = $petugas['nama_petugas'];
            }
        }
 
        // Fetch item tindakan yang sudah ada
        $itemTerpilih = $this->model->db
            ->table('radiologi.hasil_rad_tindakan hrt')
            ->select([
                'hrt.id_hasil_tindakan',
                'hrt.id_permintaan_item',
                'r.kode_periksa',
                'r.nama_pemeriksaan',
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
            ->join('radiologi.ref_item_rad r',          'r.id_item = pri.id_item')
            ->where('hrt.id_hasil_rad', $id)
            ->get()
            ->getResultArray();
 
        // Fetch BHP yang sudah ada
        $bhpTerpilih = $this->model->db
            ->table('radiologi.hasil_rad_bhp hrb')
            ->select([
                'hrb.id_barang',
                'b.kode_barang',
                'b.nama_barang',
                's.nama_satuan',
                'hrb.jumlah_pakai',
                'b.stok',
            ])
            ->join('inventori_non_medis.barang b',  'b.id_barang  = hrb.id_barang')
            ->join('inventori_non_medis.satuan s',  's.id_satuan  = b.id_satuan', 'left')
            ->where('hrb.id_hasil_rad', $id)
            ->get()
            ->getResultArray();
 
        $templateRad = $this->model->db
            ->table('radiologi.ref_template_rad')
            ->select(['id_template', 'nama_template', 'isi_teks_ekspertise'])
            ->orderBy('nama_template', 'ASC')
            ->get()
            ->getResultArray();
        
        $templateRad = array_map(function($t) {
            $t['isi_teks_ekspertise'] = str_replace('\n', "\n", $t['isi_teks_ekspertise']);
            return $t;
        }, $templateRad);
 
        $barangNonMedis = $this->model->db
            ->table('inventori_non_medis.barang b')
            ->select(['b.id_barang', 'b.kode_barang', 'b.nama_barang', 'b.stok', 's.nama_satuan'])
            ->join('inventori_non_medis.satuan s', 's.id_satuan = b.id_satuan', 'left')
            ->orderBy('b.nama_barang', 'ASC')
            ->get()
            ->getResultArray();
 
        $konfig = array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn($f) => !in_array($f[2], [
                'id_hasil_rad',
                'id_permintaan_rad',
                'id_dokter_pj',
                'id_petugas_rad',
                'id_dokter_perujuk',
            ], true)
        ));
 
        return view('admin/radiologi/tambah_hasil_rad', [
            'judul'           => 'Ubah ' . $this->title,
            'breadcrumbs'     => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'      => $this->get_uri_path(),
            'kolom_id'        => $this->model->primaryKey,
            'konfig'          => $konfig,
            'baris'           => $baris,
            'form_action'     => '/submitedit/' . $id,
            'template_rad'    => $templateRad,
            'barang_non_medis'=> $barangNonMedis,
            'item_terpilih'   => $itemTerpilih,
            'bhp_terpilih'    => $bhpTerpilih,
        ]);
    }
 
    // ──────────────────────────────────────────────────────────
    // PROSES UPDATE
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    public function update(int|string $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($id == 0) return $this->home();
 
        $rawPost = $this->request->getPost();
 
        $dataHeader = [
            'id_permintaan_rad' => (int) ($rawPost['id_permintaan_rad'] ?? 0) ?: null,
            'id_dokter_pj'      => (int) ($rawPost['id_dokter_pj']      ?? 0) ?: null,
            'id_petugas_rad'    => (int) ($rawPost['id_petugas_rad']    ?? 0) ?: null,
            'tgl_jam_hasil'     => $rawPost['tgl_jam_hasil']     ?? date('Y-m-d H:i:s'),
            'catatan'           => $rawPost['catatan']           ?? '',
        ];

        $tindakanList = $rawPost['tindakan'] ?? [];
        $bhpList      = $rawPost['bhp']      ?? [];

        $this->model->db->transStart();
 
        try {
            // 1. Update header
            $this->model->update($id, $dataHeader);
 
            // 2. Hapus tindakan lama lalu insert ulang
            $modelTindakan = new \App\Features\Radiologi\HasilRadTindakan\HasilRadTindakanModel();
            
            $modelTindakan->where('id_hasil_rad', $id)->delete();
            foreach ($tindakanList as $tindakan) {
                $modelTindakan->insert([
                    'id_hasil_rad'            => $id,
                    'id_item_rad'             => (int) ($tindakan['id_item_rad']               ?? 0),
                    'proyeksi'                => $tindakan['proyeksi']                         ?? '',
                    'kilovoltage_kv'          => (float) ($tindakan['kilovoltage_kv']          ?? 0),
                    'milliampere_second_mas'  => (float) ($tindakan['milliampere_second_mas']  ?? 0),
                    'focus_film_distance_ffd' => (float) ($tindakan['focus_film_distance_ffd'] ?? 0),
                    'back_scatter_factor_bsf' => (float) ($tindakan['back_scatter_factor_bsf'] ?? 0),
                    'inaktivasi'              => $tindakan['inaktivasi']                       ?? '',
                    'jumlah_penyinaran'       => (int) ($tindakan['jumlah_penyinaran']         ?? 0),
                    'dosis_radiasi'           => $tindakan['dosis_radiasi']                    ?? '',
                    'hasil_ekspertise'        => $tindakan['hasil_ekspertise']                 ?? '',
                    'id_template_rad'         => !empty($tindakan['id_template_rad'])
                                                    ? (int) $tindakan['id_template_rad']
                                                    : null,
                ]);
            }
 
            // 3. Hapus BHP lama lalu insert kembalikan stock
            $modelBhp = new \App\Features\Radiologi\HasilRadBhp\HasilRadBhpModel();
 
            $bhpLama = $modelBhp->where('id_hasil_rad', $id)->findAll();
            foreach ($bhpLama as $lama) {
                $this->model->db
                    ->table('inventori_non_medis.barang')
                    ->where('id_barang', $lama['id_barang'])
                    ->set('stok', 'stok + ' . (int) $lama['jumlah_pakai'], false)
                    ->update();
            }
            $modelBhp->where('id_hasil_rad', $id)->delete();

            // 4. Insert BHP baru → kurangi stok
            foreach ($bhpList as $idBarang => $bhp) {
                if (empty($bhp['jumlah_pakai']) || (int) $bhp['jumlah_pakai'] <= 0) continue;
                
                $modelBhp->insert([
                    'id_hasil_rad' => $id,
                    'id_barang'    => (int) $idBarang,
                    'jumlah_pakai' => (int) $bhp['jumlah_pakai'],
                ]);

                $this->model->db
                    ->table('inventori_non_medis.barang')
                    ->where('id_barang', (int) $idBarang)
                    ->set('stok', 'stok - ' . (int) $bhp['jumlah_pakai'], false)
                    ->update();
            }

            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui hasil radiologi.');
            }
 
            session()->setFlashdata('success', 'Hasil radiologi berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');
 
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
            return redirect()->back()->withInput();
        } catch (\RuntimeException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
 
    // ──────────────────────────────────────────────────────────
    // DELETE — cascade hapus tindakan & BHP
    // ──────────────────────────────────────────────────────────
 
    #[\Override]
    public function delete(int|string $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($id == 0) return $this->home();
 
        $this->model->db->transStart();
 
        try {
            $modelTindakan = new \App\Features\Radiologi\HasilRadTindakan\HasilRadTindakanModel();
            $modelTindakan->where('id_hasil_rad', $id)->delete();
 
            $modelBhp = new \App\Features\Radiologi\HasilRadBhp\HasilRadBhpModel();
            
            $bhpLama = $modelBhp->where('id_hasil_rad', $id)->findAll();
            foreach ($bhpLama as $lama) {
                $this->model->db
                    ->table('inventori_non_medis.barang')
                    ->where('id_barang', $lama['id_barang'])
                    ->set('stok', 'stok + ' . (int) $lama['jumlah_pakai'], false)
                    ->update();
            }
            $modelBhp->where('id_hasil_rad', $id)->delete();
 
            $this->model->delete($id);
 
            $this->model->db->transComplete();
 
            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus hasil radiologi.');
            }
 
            session()->setFlashdata('success', 'Hasil radiologi berhasil dihapus.');
 
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $this->friendly_db_error($e));
        } catch (\RuntimeException $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e->getMessage());
        }
 
        return $this->home();
    }
}
