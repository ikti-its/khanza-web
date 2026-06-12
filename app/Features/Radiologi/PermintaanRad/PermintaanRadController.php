<?php
declare(strict_types=1);

namespace App\Features\Radiologi\PermintaanRad;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanRadController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanRadModel(),
            [
                ['Radiologi',            'radiologi'],
                ['Permintaan Radiologi', 'permintaan_rad'],
            ],
            'Permintaan Radiologi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'no_permintaan',        'No. Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'nomor_reg',            'Nomor Registrasi'],
                [SHOW, REQUIRED, I::DTIME,  'tgl_jam_permintaan',   'Tanggal Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'informasi_tambahan',   'Informasi Tambahan'],
                [SHOW, REQUIRED, I::TEXT,   'indikasi_klinis',      'Indikasi Klinis'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_permintaan', 'Status Permintaan'],
            ],
        );
    }

    #[\Override]
    final public function create_page(): string
    {
        helper('autonomor');

        $lastNo = $this->model->db
            ->table('radiologi.permintaan_rad')
            ->select('no_permintaan')
            ->like('no_permintaan', 'RAD' . date('Ymd'), 'after')
            ->orderBy('no_permintaan', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        $noPermintaan = generateNextNoPermintaanRad($lastNo['no_permintaan'] ?? null);

        $konfig = array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn($f) => !in_array($f[2], [
                'id_permintaan',
                'no_permintaan',
                'nomor_reg',
            ], true)
        ));

        $breadcrumbs = [['title' => 'Tambah', 'icon' => 'tambah']];

        return view('admin/radiologi/tambah_permintaan_rad', [
            'judul'         => 'Tambah ' . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->model->primaryKey,
            'konfig'        => $konfig,
            'baris'         => [],
            'form_action'   => '/submittambah',
            'no_permintaan' => $noPermintaan,
        ]);
    }

    public function list()
    {
        $rows = $this->model->db
            ->table('radiologi.permintaan_rad pr')
            ->select([
                'pr.id_permintaan',
                'pr.no_permintaan',
                'pr.nomor_reg',
                'pr.tgl_jam_permintaan',
                'p.nomor_rm',
                'o.nama',
                's.nama_status',
                'r.id_dokter AS id_dokter_perujuk',
                'od.nama AS nama_dokter_perujuk',
            ])
            ->join('rekam_medis.registrasi r',  'r.nomor_reg = pr.nomor_reg')
            ->join('role.pasien p',             'p.id_pasien = r.id_pasien')
            ->join('person.orang o',            'o.id_orang  = p.id_orang')
            ->join('radiologi.ref_status_permintaan_rad s', 's.id_status = pr.id_status_permintaan', 'left')
            ->join('role.dokter d',             'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od',           'od.id_orang = d.id_orang', 'left')
            ->orderBy('pr.tgl_jam_permintaan', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }

    #[\Override]
    protected function before_create(array &$postData): void
    {
        helper('autonomor');

        if (empty($postData['no_permintaan'])) {
            $lastNo = $this->model->db
                ->table('radiologi.permintaan_rad')
                ->select('no_permintaan')
                ->like('no_permintaan', 'RAD' . date('Ymd'), 'after')
                ->orderBy('no_permintaan', 'DESC')
                ->limit(1)
                ->get()
                ->getRowArray();

            $postData['no_permintaan'] = generateNextNoPermintaanRad($lastNo['no_permintaan'] ?? null);
        }

        if (empty($postData['tgl_jam_permintaan'])) {
            $postData['tgl_jam_permintaan'] = date('Y-m-d H:i:s');
        }
    }

    #[\Override]
    public function create(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $rawPost = $this->request->getPost();

        $data = [
            'no_permintaan'        => $rawPost['no_permintaan']        ?? '',
            'nomor_reg'            => $rawPost['nomor_reg']            ?? '',
            'tgl_jam_permintaan'   => $rawPost['tgl_jam_permintaan']   ?? '',
            'informasi_tambahan'   => $rawPost['informasi_tambahan']   ?? '',
            'indikasi_klinis'      => $rawPost['indikasi_klinis']      ?? '',
            'id_status_permintaan' => $rawPost['id_status_permintaan'] ?? '',
        ];

        $this->before_create($data);

        $idItems = $this->request->getPost('id_item');
        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item radiologi.');
            return redirect()->back()->withInput();
        }

        $this->model->db->transStart();

        try {
            $this->model->insert($data);
            $idPermintaan = $this->model->getInsertID();

            $modelItem = new \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemModel();
            foreach ($idItems as $idItem) {
                $modelItem->insert([
                    'id_permintaan' => $idPermintaan,
                    'id_item'       => (int) $idItem,
                ]);
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan permintaan radiologi.');
            }

            session()->setFlashdata('success', 'Permintaan radiologi berhasil disimpan.');
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

    #[\Override]
    final public function update_page(int|string $id): string
    {
        $breadcrumbs = [['title' => 'Ubah', 'icon' => 'ubah']];

        $baris = $this->model->find($id);

        if (!empty($baris['nomor_reg'])) {
            $dataJoin = $this->model->db
                ->table('rekam_medis.registrasi r')
                ->select([
                    'r.nomor_reg',
                    'p.nomor_rm',
                    'o.nama',
                ])
                ->join('role.pasien p',  'p.id_pasien = r.id_pasien')
                ->join('person.orang o', 'o.id_orang  = p.id_orang')
                ->where('r.nomor_reg', $baris['nomor_reg'])
                ->get()
                ->getRowArray();

            if ($dataJoin) {
                $baris = array_merge($baris, $dataJoin);
            }
        }

        $itemTerpilih = $this->model->db
            ->table('radiologi.permintaan_rad_item pri')
            ->select([
                'pri.id_item',
                'r.kode_periksa',
                'r.nama_pemeriksaan',
                'r.tarif_dasar',
            ])
            ->join('radiologi.ref_item_rad r', 'r.id_item = pri.id_item')
            ->where('pri.id_permintaan', $id)
            ->get()
            ->getResultArray();

        $konfig = array_values(array_filter(
            $this->get_fields_with_options(false, true),
            fn($f) => !in_array($f[2], [
                'id_permintaan',
                'no_permintaan',
                'nomor_reg',
            ], true)
        ));

        return view('admin/radiologi/tambah_permintaan_rad', [
            'judul'         => 'Ubah ' . $this->title,
            'breadcrumbs'   => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->model->primaryKey,
            'konfig'        => $konfig,
            'baris'         => $baris,
            'form_action'   => '/submitedit/' . $id,
            'no_permintaan' => $baris['no_permintaan'] ?? '',
            'item_terpilih' => $itemTerpilih,
        ]);
    }

    #[\Override]
    public function update(int|string $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($id == 0) return $this->home();

        $rawPost = $this->request->getPost();

        $data = [
            'no_permintaan'        => $rawPost['no_permintaan']        ?? '',
            'nomor_reg'            => $rawPost['nomor_reg']            ?? '',
            'tgl_jam_permintaan'   => $rawPost['tgl_jam_permintaan']   ?? '',
            'informasi_tambahan'   => $rawPost['informasi_tambahan']   ?? '',
            'indikasi_klinis'      => $rawPost['indikasi_klinis']      ?? '',
            'id_status_permintaan' => $rawPost['id_status_permintaan'] ?? '',
        ];

        $idItems = $this->request->getPost('id_item');
        if (empty($idItems)) {
            session()->setFlashdata('error', 'Pilih minimal satu item radiologi.');
            return redirect()->back()->withInput();
        }

        $this->model->db->transStart();

        try {
            $this->model->update($id, $data);

            $modelItem = new \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemModel();
            $modelItem->where('id_permintaan', $id)->delete();

            foreach ($idItems as $idItem) {
                $modelItem->insert([
                    'id_permintaan' => $id,
                    'id_item'       => (int) $idItem,
                ]);
            }

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui permintaan radiologi.');
            }

            session()->setFlashdata('success', 'Permintaan radiologi berhasil diperbarui.');
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

    #[\Override]
    final public function delete(int|string $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($id == 0) return $this->home();

        $this->model->db->transStart();

        try {
            $modelItem = new \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemModel();
            $modelItem->where('id_permintaan', $id)->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menghapus permintaan radiologi.');
            }

            session()->setFlashdata('success', 'Permintaan radiologi berhasil dihapus.');

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
