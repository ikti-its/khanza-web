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
                [SHOW,       OPTIONAL, I::SELECT, 'id_kota',        'Kota'],
                [SHOW,       OPTIONAL, I::TEXT,   'alamat',         'Alamat'],
                [SHOW,       OPTIONAL, I::TEXT,   'no_telp',        'No. Telepon'],
                [TABLE_ONLY, OPTIONAL, I::SELECT, 'id_rekening',    'No. Rekening'],
                [FORM_ONLY,  OPTIONAL, I::SELECT, 'id_bank',        'Bank'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,   'nomor_rekening', 'No. Rekening'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,   'nama_akun',      'Nama Akun'],
            ],
        );
    }

    // inject pilihan bank ke field id_bank pas render form
    protected function get_fields_with_options(bool $include_pk = false, bool $is_form = false): array
    {
        $fields = parent::get_fields_with_options($include_pk, $is_form);
        if (!$is_form) return $fields;

        $banks = $this->get_db()
            ->query("SELECT id_bank, nama_bank FROM finansial.bank WHERE id_bank > 0 ORDER BY nama_bank")
            ->getResultArray();

        $bank_options = array_map(
            fn(array $b): array => [$b['nama_bank'] ?? '-', (string) $b['id_bank']],
            $banks,
        );

        foreach ($fields as &$field) {
            if ($field[2] === 'id_bank') {
                $field[5] = $bank_options;
            }
        }
        unset($field);

        return $fields;
    }

    // load data rekening terkait suplier ke form ubah
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $data = $this->model->find($id);

        if (is_array($data)) {
            $data += ['id_bank' => null, 'nomor_rekening' => null, 'nama_akun' => null];

            if (!empty($data['id_rekening'])) {
                $rekening = $this->get_db()
                    ->query(
                        "SELECT bank AS id_bank, nomor_rekening, nama_akun
                         FROM finansial.rekening WHERE id_rekening = ?",
                        [(int) $data['id_rekening']],
                    )
                    ->getRowArray();

                if (is_array($rekening)) {
                    $data = array_merge($data, $rekening);
                }
            }
        }

        $breadcrumbs = [['title' => 'Ubah', 'icon', 'Ubah']];
        return view('/layouts/tambah_ubah', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $this->get_fields_with_options(false, true),
            'baris'       => $data,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    // generate kode suplier format S0001, S0002, dst
    private function generate_kode(): string
    {
        $row = $this->get_db()
            ->query(
                "SELECT MAX(CAST(SUBSTRING(TRIM(kode_suplier) FROM 2) AS INTEGER)) AS max_num
                 FROM inventori_non_medis.suplier
                 WHERE TRIM(kode_suplier) ~ '^S[0-9]+$'"
            )
            ->getRowArray();

        $next = (int) ($row['max_num'] ?? 0) + 1;
        return 'S' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    // pre-fill kode_suplier dengan kode otomatis
    public function create_page(): string
    {
        $konfig = $this->get_fields_with_options(false, true);
        $baris  = array_fill_keys(array_column($konfig, 2), null);
        $baris['kode_suplier'] = $this->generate_kode();

        $breadcrumbs = [['title' => 'Tambah', 'icon', 'tambah']];
        return view('/layouts/tambah_ubah', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfig,
            'baris'       => $baris,
            'form_action' => '/submittambah/',
        ]);
    }

    // simpan rekening baru ke finansial.rekening, link id_rekening ke suplier
    protected function before_create(array &$postData): void
    {
        $id_bank        = $postData['id_bank']        ?? null;
        $nomor_rekening = $postData['nomor_rekening'] ?? null;
        $nama_akun      = $postData['nama_akun']      ?? null;

        unset($postData['id_bank'], $postData['nomor_rekening'], $postData['nama_akun']);

        if (!$id_bank || !$nomor_rekening || !$nama_akun) return;

        $db = $this->get_db();
        $db->table('finansial.rekening')->insert([
            'bank'           => $id_bank,
            'nomor_rekening' => $nomor_rekening,
            'nama_akun'      => $nama_akun,
        ]);
        $postData['id_rekening'] = $db->insertID();
    }

    // update rekening yang sudah ada, atau insert baru kalau belum punya rekening
    protected function before_update(array &$postData, int|string $id): void
    {
        $id_bank        = $postData['id_bank']        ?? null;
        $nomor_rekening = $postData['nomor_rekening'] ?? null;
        $nama_akun      = $postData['nama_akun']      ?? null;

        unset($postData['id_bank'], $postData['nomor_rekening'], $postData['nama_akun']);

        if (!$id_bank && !$nomor_rekening && !$nama_akun) return;

        $suplier     = $this->model->find($id);
        $id_rekening = $suplier['id_rekening'] ?? null;

        $db = $this->get_db();

        if ($id_rekening) {
            $db->table('finansial.rekening')
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
