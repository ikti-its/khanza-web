<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRadFoto;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class HasilRadFotoController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadFotoModel(),
            [
                ['Radiologi',            'radiologi'],
                ['Foto Hasil Radiologi', 'foto-hasil-radiologi'],
            ],
            'Foto Hasil Radiologi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_rad_foto',  'ID Foto'],
                [HIDE, REQUIRED, I::INDEX, 'id_hasil_rad', 'Hasil Radiologi'],
                [SHOW, REQUIRED, I::TEXT,  'nama_file',    'Nama File'],
                [SHOW, REQUIRED, I::DTIME, 'tgl_upload',   'Waktu Upload'],
            ],
        );
    }

    // Simpan satu atau lebih file gambar ke disk (writable/, di luar public/)
    // dan catat ke DB; penyajiannya lewat route tampil/(:num) yang dijaga auth
    public function upload(int $idHasilRad): ResponseInterface
    {
        $uploaded = [];

        foreach ($this->request->getFiles()['foto'] ?? [] as $file) {
            if (!$this->upload_valid($file, ['jpg', 'jpeg', 'png', 'webp'], 5 * 1024 * 1024)) {
                continue;
            }

            $newName = $file->getRandomName();
            $file->move($this->upload_dir(), $newName);

            $this->model->insert([
                'id_hasil_rad' => $idHasilRad,
                'nama_file'    => $newName,
                'tgl_upload'   => date('Y-m-d H:i:s'),
            ]);

            $idFoto = (int) $this->model->getInsertID();

            $uploaded[] = [
                'id_rad_foto' => $idFoto,
                'nama_file'   => $newName,
                'url'         => site_url("radiologi/foto-hasil-radiologi/tampil/{$idFoto}"),
            ];
        }

        return $this->response->setJSON(['success' => true, 'data' => $uploaded]);
    }

    // Hapus file dari disk lalu hapus record dari DB
    public function hapusFoto(int $id): ResponseInterface
    {
        $foto = $this->model->find($id);

        if ($foto) {
            $path = $this->upload_dir() . $foto['nama_file'];
            if (file_exists($path)) {
                unlink($path);
            }
            $this->model->delete($id);
        }

        return $this->response->setJSON(['success' => true]);
    }
}
