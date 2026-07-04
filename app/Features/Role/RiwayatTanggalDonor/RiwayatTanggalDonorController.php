<?php
declare(strict_types=1);

namespace App\Features\Role\RiwayatTanggalDonor;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class RiwayatTanggalDonorController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RiwayatTanggalDonorModel(),
            [
                ['Role', 'role'],
                ['Riwayat Tanggal Donor', 'riwayat_tanggal_donor'],
            ],
            'Riwayat Tanggal Donor',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_riwayat',    'ID Riwayat'],
                [SHOW, REQUIRED, I::INDEX, 'id_pendonor',   'ID Pendonor'],
                [SHOW, REQUIRED, I::DATE,  'tanggal_donor', 'Tanggal Donor'],
                [SHOW, REQUIRED, I::DATE,  'start_valid',   'Start Valid'],
                [SHOW, OPTIONAL, I::DATE,  'end_valid',     'End Valid'],
            ],
        );
    }

    /**
     * OVERRIDE: Halaman utama riwayat tanggal donor
     */
    #[\Override]
    final public function index(): string|RedirectResponse
    {
        $page   = max(1, (int) ($this->request->getGet('page') ?? 1));
        $size   = max(1, (int) ($this->request->getGet('size') ?? 10));
        $offset = ($page - 1) * $size;

        $totalRows  = $this->model->count_filtered();
        $totalPages = ($totalRows > 0) ? (int) ceil($totalRows / $size) : 1;
        $page       = min($page, $totalPages);
        $offset     = ($page - 1) * $size;

        $dataTabel = $this->model->get_data_tabel($size, $offset);

        $konfig = [
            [1, 'Nomor Pendonor', 'nomor_pendonor', 'teks',    0],
            [1, 'Nama Pendonor',  'nama',           'teks',    0],
            [1, 'Tanggal Donor',  'tanggal_donor',  'tanggal', 0],
            [1, 'Start Valid',    'start_valid',    'tanggal', 0],
            [1, 'End Valid',      'end_valid',      'tanggal', 0],
        ];

        return view('/layouts/data', [
            'judul'         => $this->title,
            'breadcrumbs'   => $this->breadcrumbs,
            'meta_data'     => ['page' => $page, 'size' => count($dataTabel), 'total' => $totalPages],
            'modul_path'    => $this->get_uri_path(),
            'kolom_id'      => $this->primary_key,
            'konfig'        => $konfig,
            'aksi'          => $this->actions,
            'tabel'         => $dataTabel,
            'row_alert'     => [],
            'child_link'    => null,
            'query_string'  => '',
            'back_url'      => null,
            'filters'       => [],
            'active_filter' => null,
        ]);
    }
}
