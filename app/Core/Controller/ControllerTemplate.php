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

    public function __construct(
        ModelTemplate $model,
        /** @var list<list<string>> */
        array $breadcrumbs,
        /** @var non-empty-string */
        string $title,
        /** @var  list<ActionType> */
        array $actions,
        /** @var array<int, array{
         *  0: 0|1,
         *  1: 0|1,
         *  2: InputType,
         *  3: string,
         *  4: string,
         * }> */
        array $fields,
        
    ) {
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

    /**
     * @return array<string, mixed>
     */
    private function get_post_data(): array
    {
        $postData = [];
        foreach ($this->fields as $f) {
            [$_show, $_name, $column, $type, $_required] = $f;

            if ($column === $this->primary_key) continue;

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

        $total_rows  = $this->model->countAll();
        $total_pages = ($total_rows > 0) ? (int) ceil($total_rows / $size) : 1;
        $page = min($page, $total_pages);

        $offset    = ($page - 1) * $size;
        $meta_data = [
            'page'  => $page,
            'size'  => $size,
            'total' => $total_pages,
        ];

        $konfig_kolom = $this->get_fields_with_options(true);
        $data_tabel = $this->model->findAll($size, $offset);

        foreach ($data_tabel as $index_baris => $baris) {
            foreach ($konfig_kolom as $kolom) {
                $nama_field_data = $kolom[2];
                if (!array_key_exists($nama_field_data, $baris)) {
                    $data_tabel[$index_baris][$nama_field_data] = "";
                }
            }
        }

        return view('/layouts/data', [
            'judul'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs,
            'meta_data'   => $meta_data,
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfig_kolom,
            'aksi'        => $this->actions,
            'tabel'       => $data_tabel,  
        ]);
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

        $fk_lookup = [];
        if (isset($this->model->database) && isset($this->model->database->foreign_keys)) {
            foreach ($this->model->database->foreign_keys as $fk) {
                $fields = $fk[0];
                $ref_class = $fk[1];
                $locals = is_string($fields) ? [$fields] : $fields;
                foreach ($locals as $local) {
                    $fk_lookup[$local] = $ref_class;
                }
            }
        }

        $parse_join_fields_recursive = function(
            array $specs, 
            string $current_fk, 
            ?string $current_db_class,
            string $fallback_label,
            bool $is_single_column_join
        ) use (&$parse_join_fields_recursive, &$result): void {

            $ref_controller_class = null;
            if ($current_db_class !== null) {
                $ref_controller_class = str_replace(['Model', 'Database'], 'Controller', $current_db_class);
                if (!str_contains($ref_controller_class, 'Controller')) {
                    $ref_controller_class .= 'Controller';
                }
            }

            $source_fields = [];
            if ($ref_controller_class !== null && class_exists($ref_controller_class)) {
                try {
                    $controller_instance = \CodeIgniter\Config\Factories::controllers($ref_controller_class);
                    if ($controller_instance !== null) {
                        if (property_exists($controller_instance, 'field')) {
                            $source_fields = $controller_instance->field;
                        } elseif (property_exists($controller_instance, 'fields')) {
                            $source_fields = $controller_instance->fields;
                        }
                    }
                } catch (\Exception $e) {
                    $source_fields = [];
                }
            }

            foreach ($specs as $k => $v) {
                if (is_string($k)) {
                    $next_fk = $k;
                    $next_specs = is_array($v) ? $v : [$v];
                    $next_db_class = null;

                    if (count($specs) === 1) {
                        $next_fallback_label = $fallback_label;
                    } else {
                        $next_fallback_label = ucwords(str_replace('_', ' ', $next_fk));

                        foreach ($source_fields as $f) {
                            if (isset($f[2]) && $f[2] === $next_fk && isset($f[1])) {
                                $next_fallback_label = $f[1];
                                break;
                            }
                        }
                    }

                    if ($current_db_class !== null && class_exists($current_db_class)) {
                        try {
                            $db_instance = new $current_db_class();
                            if (isset($db_instance->foreign_keys) && is_array($db_instance->foreign_keys)) {
                                foreach ($db_instance->foreign_keys as $sub_fk) {
                                    $sub_fields = $sub_fk[0];
                                    $sub_locals = is_string($sub_fields) ? [$sub_fields] : $sub_fields;
                                    if (in_array($next_fk, $sub_locals)) {
                                        $next_db_class = $sub_fk[1]; 
                                        break;
                                    }
                                }
                            }
                        } catch (\Exception $ex) {}
                    }

                    $next_is_single = (count($next_specs) === 1);
                    $parse_join_fields_recursive($next_specs, $next_fk, $next_db_class, $next_fallback_label, $next_is_single);

                } else {
                    $col_name = $v;
                    $found = false;

                    foreach ($source_fields as $f) {
                        $target_col = $f[3] ?? null;

                        if ($target_col === $col_name) {
                            if ((int)$f[0] === 0) {
                                $found = true;
                                break;
                            }

                            $input_type = isset($f[2]) ? (is_object($f[2]) ? $f[2]->value : $f[2]) : 'teks';
                            $required_status = $f[1] ?? 1;

                            if ($is_single_column_join) {
                                $display_name = $fallback_label;
                            } else {
                                $display_name = (isset($f[4]) && is_string($f[4])) ? $f[4] : ucwords(str_replace('_', ' ', $col_name));
                            }

                            $result[] = [
                                (int)$f[0],
                                $display_name,
                                $col_name,
                                $input_type,
                                $required_status
                            ];
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $display_name = $is_single_column_join ? $fallback_label : ucwords(str_replace('_', ' ', $col_name));
                        $default_type = 'teks';
                        if (str_contains($col_name, 'tanggal') || str_contains($col_name, 'tgl')) {
                            $default_type = 'tanggal';
                        }

                        $result[] = [
                            1,
                            $display_name,
                            $col_name,
                            $default_type,
                            1
                        ];
                    }
                }
            }
        };

        foreach ($this->fields as $field) {
            [$visible, $display, $column, $type] = $field;

            if (isset($this->model->join) && array_key_exists($column, $this->model->join)) {
                $display_cols = $this->model->join[$column];
                $specs = is_array($display_cols) ? $display_cols : [$display_cols];

                $is_single_column_join = (count($specs) === 1 && !is_string(array_key_first($specs)));
                $fallback_label = !empty($display) ? $display : ucwords(str_replace('_', ' ', $column));

                $initial_db = $fk_lookup[$column] ?? null;

                if ($is_form) {
                    $options = $all_options[$column] ?? [];
                    $result[] = [
                        (int)$visible,
                        $display,
                        $column,
                        'status',
                        $field[4] ?? 1,
                        $options
                    ];
                } else {
                    $parse_join_fields_recursive(
                        $specs, $column, $initial_db,
                        $fallback_label, $is_single_column_join
                    );
                }

                continue;
            }

            if ((int)$visible === 0 && !($include_pk && $column === $this->primary_key)) {
                continue;
            }

            if (is_object($type) && isset($type->value)) {
                $type = $type->value;
            }
            $field[3] = $type;

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

    public function create(): string|RedirectResponse
    {
        $postData = $this->get_post_data();
        try {
            $this->model->insert($postData);
        } catch (\ReflectionException | DatabaseException $e) {
            $msg = $e instanceof DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return $this->create_view($postData);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return redirect()->to($this->get_uri_path() . '/data');

        /** @var array<string, scalar|null> $postData */
        $postData = $this->get_post_data();
        try {
            $this->model->update($id, $postData);
        } catch(\ReflectionException $e){
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to($this->get_uri_path() . '/data');
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

        return redirect()->to($this->get_uri_path() . '/data');
    }

    final public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return redirect()->to($this->get_uri_path() . '/data');

        try {
            $this->model->delete($id);
        } catch(DatabaseException $e){
            session()->setFlashdata('error', $this->friendly_db_error($e));
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    public function print(int|string $id): string
    {
        if (in_array(ActionType::PRINT, $this->actions, true)) {
            return view('components/cetak/template', ['id' => $id]);
        }
        return $this->index();
    }
}
