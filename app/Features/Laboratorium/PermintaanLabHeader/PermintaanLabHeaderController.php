<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabHeader;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanLabHeaderController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabHeaderModel(),
            [
                ['Laboratorium',   'laboratorium'],
                ['Permintaan Lab', 'permintaan_lab'],
            ],
            'Permintaan Lab',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::SAMPEL,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'no_permintaan',        'No. Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'nomor_reg',            'Nomor Registrasi'],
                [HIDE, REQUIRED, I::INDEX,  'id_kategori_lab',      'Kategori Lab'],
                [SHOW, REQUIRED, I::TEXT,   'id_dokter_perujuk',    'Kode Dokter Perujuk'],
                [SHOW, REQUIRED, I::DTIME,  'tgl_permintaan',       'Tanggal Permintaan'],
                [SHOW, REQUIRED, I::TEXT,   'indikasi_klinis',      'Indikasi Klinis'],
                [SHOW, REQUIRED, I::TEXT,   'informasi_tambahan',   'Informasi Tambahan'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_permintaan', 'Status Permintaan'],
                [SHOW, OPTIONAL, I::DTIME,  'tgl_jam_sampel',       'Waktu Sampel'],
            ],
        );
    }

    // ──────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────

    private function baseListBuilder(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->model
            ->db
            ->table('laboratorium.permintaan_lab_header plh')
            ->select([
                'plh.id_permintaan',
                'plh.no_permintaan',
                'plh.nomor_reg',
                'plh.tgl_permintaan AS tgl_jam_permintaan',
                'plh.id_status_permintaan',
                'p.nomor_rm',
                'o.nama',
                'o.tanggal_lahir',
                's.nama_status',
                'r.id_dokter AS id_dokter_perujuk',
                'od.nama AS nama_dokter_perujuk',
            ])
            ->join('registrasi.registrasi r', 'r.nomor_reg = plh.nomor_reg')
            ->join('role.pasien p', 'p.id_pasien = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang  = p.id_orang', 'left')
            ->join('laboratorium.ref_status_permintaan s', 's.id_status = plh.id_status_permintaan', 'left')
            ->join('role.dokter d', 'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang', 'left')
            ->orderBy('plh.tgl_permintaan', 'DESC');
    }

    // ──────────────────────────────────────────────────────────
    // LIST — untuk modal pilih permintaan
    // ──────────────────────────────────────────────────────────

    public function list()
    {
        $builder = $this->baseListBuilder();

        $status = $this->request->getGet('status');
        if ($status !== null) {
            $builder->where('plh.id_status_permintaan', (int) $status);
        }

        $kategori = (int) ($this->request->getGet('kategori') ?? 0);

        $tabelItem = match ($kategori) {
            ID_KATEGORI_MB => 'laboratorium.permintaan_lab_mb_item',
            ID_KATEGORI_PA => 'laboratorium.permintaan_lab_pa_item',
            ID_KATEGORI_PK => 'laboratorium.permintaan_lab_pk_item',
            default        => null,
        };

        if ($tabelItem !== null) {
            $builder->where("EXISTS (
                SELECT 1
                FROM {$tabelItem} mi
                JOIN laboratorium.ref_item_pemeriksaan_lab ri ON ri.id_item_lab = mi.id_item_pemeriksaan
                WHERE mi.id_permintaan_lab = plh.id_permintaan
                AND ri.id_kategori = {$kategori}
            )");
        }

        return $this->response->setJSON(['data' => $builder->get()->getResultArray()]);
    }
}
