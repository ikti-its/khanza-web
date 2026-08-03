<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\Suplier;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class SuplierController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SuplierModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Suplier',             'suplier'],
            ],
            'Suplier',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_suplier',     'ID Suplier'],
                [SHOW,       REQUIRED, I::TEXT,   'kode_suplier',   'Kode Suplier'],
                [SHOW,       REQUIRED, I::NAME,   'nama_suplier',   'Nama Suplier'],
                [FORM_ONLY,  OPTIONAL, I::SELECT, 'id_kota',        'Kota'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,   'alamat',         'Alamat'],
                [SHOW,       OPTIONAL, I::TEXT,   'no_telp',        'No. Telepon'],
                [TABLE_ONLY, OPTIONAL, I::SELECT, 'id_rekening',    'No. Rekening'],
                [FORM_ONLY,  OPTIONAL, I::SELECT, 'id_bank',        'Bank'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,   'nomor_rekening', 'No. Rekening'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,   'nama_akun',      'Nama Akun'],
            ],
        );
    }

    // inject pilihan bank ke field id_bank pas render form
    #[\Override]
    protected function get_fields_with_options(bool $include_pk = false, bool $is_form = false): array
    {
        $fields = parent::get_fields_with_options($include_pk, $is_form);
        if (!$is_form)
            return $fields;

        $banks = $this
            ->get_db()
            ->query('SELECT id_bank, nama_bank FROM finansial.bank WHERE id_bank > 0 ORDER BY nama_bank')
            ->getResultArray();

        $bank_options = array_map(fn(array $b): array => [$b['nama_bank'] ?? '-', (string) $b['id_bank']], $banks);

        foreach ($fields as &$field) {
            if ($field[2] === 'id_bank') {
                $field[5] = $bank_options;
            }
        }
        unset($field);

        return $fields;
    }

    /** Ambil data suplier + nama kota & data rekening (bank, nomor, nama akun) untuk prefill form custom */
    private function get_baris_with_relasi(int|string $id): array|null
    {
        $data = $this->model->find($id);
        if (!is_array($data))
            return null;

        $data += ['id_bank' => null, 'nomor_rekening' => null, 'nama_akun' => null, 'nama_bank' => null, 'nama_kota' => null];

        if (!empty($data['id_kota'])) {
            $kota = $this->get_db()->query(
                'SELECT nama_kota FROM lokasi.kota WHERE id_kota = ?',
                [(int) $data['id_kota']],
            )->getRowArray();
            if (is_array($kota)) {
                $data['nama_kota'] = $kota['nama_kota'];
            }
        }

        if (!empty($data['id_rekening'])) {
            $rekening = $this
                ->get_db()
                ->query(
                    'SELECT r.bank AS id_bank, r.nomor_rekening, r.nama_akun, b.nama_bank
                     FROM finansial.rekening r
                     LEFT JOIN finansial.bank b ON b.id_bank = r.bank
                     WHERE r.id_rekening = ?',
                    [(int) $data['id_rekening']],
                )
                ->getRowArray();

            if (is_array($rekening)) {
                $data = array_merge($data, $rekening);
            }
        }

        return $data;
    }

    // custom view yang sama dengan tambah, dengan modal kota & bank (bukan dropdown generik)
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        return view('admin/inventorinonmedis/tambah_suplier', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submitedit/' . $id,
            'baris'       => $this->get_baris_with_relasi($id) ?? [],
        ]);
    }

    // form tambah gagal validasi: render ulang view custom yang sama, bukan layout generik
    #[\Override]
    protected function create_view(array $baris = []): string
    {
        return view('admin/inventorinonmedis/tambah_suplier', [
            'judul'        => 'Tambah ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'   => $this->get_uri_path(),
            'form_action'  => '/submittambah/',
            'kode_suplier' => $baris['kode_suplier'] ?? $this->generate_kode(),
            'baris'        => $baris,
        ]);
    }

    // form ubah gagal validasi: render ulang view custom yang sama dengan input yang baru disubmit
    #[\Override]
    protected function update_error_view(int|string $id, string $msg, array $postData = []): string
    {
        session()->setFlashdata('error', $msg);
        $data  = $this->get_baris_with_relasi($id) ?? [];
        $baris = array_merge($data, $postData);

        return view('admin/inventorinonmedis/tambah_suplier', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submitedit/' . $id,
            'baris'       => $baris,
        ]);
    }

    // generate kode suplier format S0001, S0002, dst
    private function generate_kode(): string
    {
        $row = $this->get_db()->query("SELECT MAX(CAST(SUBSTRING(TRIM(kode_suplier) FROM 2) AS INTEGER)) AS max_num
                 FROM inventori_non_medis.suplier
                 WHERE TRIM(kode_suplier) ~ '^S[0-9]+$'")->getRowArray();

        $next = (int) ($row['max_num'] ?? 0) + 1;
        return 'S' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    // urut berdasarkan nama A-Z
    #[\Override]
    protected function before_read(): void
    {
        $this->model->set_order('nama_suplier', 'ASC');
    }

    // pre-fill kode_suplier dengan kode otomatis, custom view dengan modal kota
    #[\Override]
    public function create_page(): string
    {
        $banks = $this
            ->get_db()
            ->table('finansial.bank')
            ->select('id_bank, nama_bank')
            ->where('id_bank >', 0)
            ->orderBy('nama_bank', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/inventorinonmedis/tambah_suplier', [
            'judul'        => 'Tambah ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'   => $this->get_uri_path(),
            'form_action'  => '/submittambah/',
            'kode_suplier' => $this->generate_kode(),
            'options_bank' => $banks,
        ]);
    }

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->model
            ->builder()
            ->select('id_suplier, kode_suplier, nama_suplier')
            ->orderBy('nama_suplier', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }

    // simpan rekening baru ke finansial.rekening, link id_rekening ke suplier
    #[\Override]
    protected function before_create(array &$postData): void
    {
        $id_bank        = $postData['id_bank'] ?? null;
        $nomor_rekening = $postData['nomor_rekening'] ?? null;
        $nama_akun      = $postData['nama_akun'] ?? null;

        unset($postData['id_bank'], $postData['nomor_rekening'], $postData['nama_akun']);

        if (!$id_bank || !$nomor_rekening || !$nama_akun)
            return;

        $db = $this->get_db();
        $db->table('finansial.rekening')->insert([
            'bank'           => $id_bank,
            'nomor_rekening' => $nomor_rekening,
            'nama_akun'      => $nama_akun,
        ]);
        $postData['id_rekening'] = $db->insertID();
    }

    // update rekening yang sudah ada, atau insert baru kalau belum punya rekening
    #[\Override]
    protected function before_update(array &$postData, int|string $id): void
    {
        $id_bank        = $postData['id_bank'] ?? null;
        $nomor_rekening = $postData['nomor_rekening'] ?? null;
        $nama_akun      = $postData['nama_akun'] ?? null;

        unset($postData['id_bank'], $postData['nomor_rekening'], $postData['nama_akun']);

        if (!$id_bank && !$nomor_rekening && !$nama_akun)
            return;

        $suplier     = $this->model->find($id);
        $id_rekening = $suplier['id_rekening'] ?? null;

        $db = $this->get_db();

        if ($id_rekening) {
            $db
                ->table('finansial.rekening')
                ->where('id_rekening', $id_rekening)
                ->update([
                    'bank'           => $id_bank,
                    'nomor_rekening' => $nomor_rekening,
                    'nama_akun'      => $nama_akun,
                ]);
        } else {
            $db->table('finansial.rekening')->insert([
                'bank'           => $id_bank,
                'nomor_rekening' => $nomor_rekening,
                'nama_akun'      => $nama_akun,
            ]);
            $postData['id_rekening'] = $db->insertID();
        }
    }
}
