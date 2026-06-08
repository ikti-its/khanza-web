<?php
declare(strict_types=1);

namespace App\Core\Controller;
use App\Core\Model\ModelTemplate;
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;

/** @mago-expect lint:too-many-methods */
class ControllerTemplate extends Controller
{
    public private(set) ModelTemplate $model;
    
    /** @var list<array{
     *    title: string,
     *    icon: string,
     * }> */
    public private(set) array $breadcrumbs;

    /** @var non-empty-string */
    public private(set) string $title;

    /** @var array<string, bool> */
    public private(set) array $actions = [];
    
    
    /** @var array<int, array{
     *  0: 0|1,
     *  1: string,
     *  2: string,
     *  3: string|InputType,
     *  4: 0|1,
     * }> */
    public private(set) array $fields;

    public private(set) array $meta_data;
    public private(set) string $primary_key;

    /** @var array{value: string, threshold: string}|array{} */
    protected array $row_alert = [];

    protected ?string $child_path  = null;
    protected ?string $child_fk    = null;
    protected ?string $parent_fk    = null;
    protected bool    $hide_zero_id = true;
    /** @var array<string, mixed> */
    protected array   $home_params  = [];

    public function __construct(
        ?ModelTemplate $model = null,
        /** @var list<list<string>> */
        array $breadcrumbs = [],
        /** @var non-empty-string */
        string $title = '',
        /** @var  list<ActionType> */
        array $actions = [],
        /** @var array<int, array{
         *  0: 0|1,
         *  1: 0|1,
         *  2: InputType,
         *  3: string,
         *  4: string,
         * }> */
        array $fields = [],
        ?string $child_path = null,
        ?string $child_fk   = null,
        ?string $parent_fk  = null,
    ) {
        $this->child_path  = $child_path;
        $this->child_fk    = $child_fk;
        $this->parent_fk   = $parent_fk;
        $this->model = $model;
        $this->title = $title;
        $this->primary_key = $this->model->primaryKey;
        $this->meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
        for($i = 0; $i < count($breadcrumbs); $i++){
            [$title, $icon] = $breadcrumbs[$i];    
            $this->breadcrumbs[$i] = ['title' => $title, 'icon'  => $icon];
        }
        foreach ($actions as $a){
            if ($a === ActionType::READ) continue;
            $this->actions[$a->value] = true;
        }
        for($i = 0; $i < count($fields); $i++){
            [$show, $required, $type, $column, $name] = $fields[$i];
            $this->fields[$i] = [$show, $name, $column, $type->value, $required];
        }
    }

    protected function get_uri_path(): string {
        $segments = $this->request->getUri()->getSegments();
        while (count($segments) > 2) {
            array_pop($segments);
        }
        return '/' . implode('/', $segments);
    }

    protected function home(): RedirectResponse
    {
        $qs = !empty($this->home_params) ? '?' . http_build_query($this->home_params) : '';
        return redirect()->to($this->get_uri_path() . '/data' . $qs);
    }

    private function fk_from_get(): void
    {
        if ($this->parent_fk === null) return;
        $id = $this->request->getGet($this->parent_fk);
        if ($id !== null) {
            $this->model->set_filter($this->parent_fk, (int) $id);
            $this->home_params = [$this->parent_fk => (int) $id];
        }
    }

    private function fk_from_post(): void
    {
        if ($this->parent_fk === null) return;
        $id = $this->request->getPost($this->parent_fk);
        if ($id !== null) {
            $this->home_params = [$this->parent_fk => (int) $id];
        }
    }

    private function fk_from_row(int|string $id): void
    {
        if ($this->parent_fk === null) return;
        $row = $this->model->find($id);
        if (is_array($row) && isset($row[$this->parent_fk])) {
            $this->home_params = [$this->parent_fk => (int) $row[$this->parent_fk]];
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function get_post_data(): array
    {
        $postData = [];
        foreach ($this->fields as $f) {
            [$_show, $_name, $column, $type, $_required] = $f;

            if ($column === $this->primary_key) continue;
            if ($_show === TABLE_ONLY) continue;

            $raw_data = $this->request->getPost($column);
            if (in_array($type, ['jumlah', 'uang', 'suhu'])) {
                $raw_data = floatval($raw_data);
            }

            if (!$_required && ($raw_data === '' || $raw_data === null)) {
                $raw_data = null;
            }
            $postData[$column] = $raw_data;
        }
        return $postData;
    }

    final public function index(): string
    {
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $size = max(1, (int) ($this->request->getGet('size') ?? $this->meta_data['size']));

        $this->before_read();
        $this->fk_from_get();
        if ($this->hide_zero_id) $this->model->exclude_zero_pk();

        $total_rows  = $this->model->count_filtered();
        $total_pages = ($total_rows > 0) ? (int) ceil($total_rows / $size) : 1;
        $page = min($page, $total_pages);

        $offset    = ($page - 1) * $size;
        $meta_data = [
            'page'  => $page,
            'size'  => $size,
            'total' => $total_pages,
        ];

        $konfig_kolom = $this->build_modular_columns();
        $data_tabel = $this->model->findAll($size, $offset);

        foreach ($data_tabel as $index_baris => $baris) {
            foreach ($konfig_kolom as $kolom) {
                $nama_field_data = $kolom[2];
                if (!array_key_exists($nama_field_data, $baris)) {
                    $data_tabel[$index_baris][$nama_field_data] = '';
                }
            }
        }

        $child_link = ($this->child_path !== null && $this->child_fk !== null)
            ? ['path' => $this->child_path, 'fk' => $this->child_fk]
            : null;

        $extra_params = array_diff_key(
            $this->request->getGet() ?? [],
            ['page' => null, 'size' => null]
        );
        $query_string = !empty($extra_params) ? http_build_query($extra_params) : '';

        return view('/layouts/data', [
            'judul'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs,
            'meta_data'   => $meta_data,
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfig_kolom,
            'aksi'         => $this->actions,
            'tabel'        => $data_tabel,
            'row_alert'    => $this->row_alert,
            'child_link'   => $child_link,
            'query_string' => $query_string,
        ]);
    }

    private function build_modular_columns(): array
    {
        $join_specs   = $this->model->join;
        $fields_lokal = $this->get_fields_with_options(false, false);
        $konfig_kolom = [];
 
        foreach ($fields_lokal as $field) {
            $column = $field[2];
 
            if (isset($join_specs[$column])) {
                $leaf_cols    = $this->extract_leaf_columns($join_specs[$column]);
                $parent_label = $field[1];
                foreach ($leaf_cols as $i => $leaf) {
                    $konfig_kolom[] = $this->make_join_column_config($leaf, $i === 0 ? $parent_label : null);
                }
            } else {
                $konfig_kolom[] = $field;
            }
        }
 
        return $konfig_kolom;
    }

    private function make_join_column_config(string $col_name, ?string $label = null): array
    {
        $label ??= ucwords(str_replace('_', ' ', $col_name));
        $type  = str_contains($col_name, 'tanggal') ? 'tanggal' : 'teks';

        return [1, $label, $col_name, $type, 0];
    }

    /**
    * @param array<int|string, mixed> $spec
    * @return list<string>
    */
    private function extract_leaf_columns(array $spec): array
    {
        $cols = [];
        foreach ($spec as $k => $v) {
            if (is_int($k) && is_string($v)) {
                $cols[] = $v;
            } elseif (is_string($k) && is_array($v)) {
                $cols = array_merge($cols, $this->extract_leaf_columns($v));
            }
        }
        return $cols;
    }

    final public function audit(): string
    {
        $audit_konfig = [
            // [1, 'Nomor Perubahan'  , 'change_id' , 'indeks'],
            [1, 'Nama', 'nama', 'teks'],
            [1, 'Aksi Perubahan', 'action', 'status'],
            [1, 'IP Address', 'user_ip', 'teks'],
            [1, 'MAC Address', 'user_mac', 'teks'],
            // [1, 'Pengubah'         , 'changed_by', 'indeks'],
            [1, 'Tanggal Perubahan', 'changed_at', 'tanggal'],
        ];
        $breadcrumbs = [
            ['title' => 'Audit', 'icon', 'audit']
        ];
        return view('/layouts/audit', [
            'judul'       => 'Audit ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'meta_data'   => $this->meta_data,
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => 'action',
            'konfig'      => array_merge($audit_konfig, $this->fields),
            'tabel'       => $this->model->audit(),
        ]);
    }

    protected function get_fields_with_options(bool $include_pk = false, bool $is_form = false): array
    {
        $all_options = $this->model->get_all_options();
        $result = [];

        foreach ($this->fields as $field) {
            [$visible, $display, $column, $type] = $field;

            if ($visible === HIDE && !($include_pk && $column === $this->primary_key)) {
                continue;
            }
            if ($is_form  && $visible === TABLE_ONLY) continue;
            if (!$is_form && $visible === FORM_ONLY)  continue;

            if ($type === 'status' && isset($all_options[$column])) {
                $field[5] = $all_options[$column];
            }

            $result[] = $field;
        }

        return $result;
    }

    private function create_view(array $baris = []): string
    {
        $breadcrumbs = [['title' => 'Tambah', 'icon', 'tambah']];
        return view('/layouts/tambah_ubah', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $this->get_fields_with_options(false, true),
            'baris'       => $baris,
            'form_action' => '/submittambah/',
        ]);
    }

    public function create_page(): string
    {
        return $this->create_view();
    }

    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon', 'Ubah']
        ];
        $data  = $this->model->find($id);
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

    /** Extract a user-friendly message from a DatabaseException */
    protected function friendly_db_error(DatabaseException $e): string
    {
        $msg = $e->getMessage();
        if (str_contains($msg, 'violates foreign key constraint')) {
            return 'Data tidak dapat dihapus atau diubah karena masih digunakan oleh data lain.';
        }
        if (str_contains($msg, 'duplicate key') || str_contains($msg, 'unique constraint')) {
            return 'Data sudah ada. Gunakan nilai yang berbeda.';
        }
        if (str_contains($msg, 'not-null constraint') || str_contains($msg, 'violates not-null')) {
            return 'Ada kolom wajib yang belum diisi.';
        }
        return $msg;
    }

    protected function before_read(): void {}
    protected function before_create(array &$postData): void {}
    protected function before_update(array &$postData, int|string $id): void {}

    public function create(): string|RedirectResponse
    {
        $postData = $this->get_post_data();
        $this->before_create($postData);
        $this->fk_from_post();
        try {
            $this->model->insert($postData);
        } catch (\ReflectionException | DatabaseException $e) {
            $msg = $e instanceof DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return $this->create_view($postData);
        }

        return $this->home();
    }

    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        /** @var array<string, scalar|null> $postData */
        $postData = $this->get_post_data();
        $this->before_update($postData, $id);
        $this->fk_from_post();
        try {
            $this->model->update($id, $postData);
        } catch(\ReflectionException $e){
            session()->setFlashdata('error', $e->getMessage());
            return $this->home();
        } catch(DatabaseException $e){
            $errMsg = $this->friendly_db_error($e);
            session()->setFlashdata('error', $errMsg);
            $breadcrumbs = [
                ['title' => 'Ubah', 'icon', 'Ubah']
            ];
            $data = $this->model->find($id);
            return view('/layouts/tambah_ubah', [
                'judul'       => 'Ubah ' . $this->title,
                'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
                'modul_path'  => $this->get_uri_path(),
                'kolom_id'    => $this->primary_key,
                'konfig'      => $this->get_fields_with_options(true, true),
                'baris'       => $data,
                'form_action' => '/submitedit/' . $id,
            ]);
        }

        return $this->home();
    }

    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $this->fk_from_row($id);
        try {
            $this->model->delete($id);
        } catch(DatabaseException $e){
            session()->setFlashdata('error', $this->friendly_db_error($e));
        }

        return $this->home();
    }

    public function print(int|string $id): string
    {
        if (in_array(ActionType::PRINT, $this->actions, true)) {
            return view('components/cetak/template', ['id' => $id]);
        }
        return $this->index();
    }
}
