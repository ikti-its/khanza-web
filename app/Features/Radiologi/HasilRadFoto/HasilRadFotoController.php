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

    private function uploadDir(): string
    {
        $dir = ROOTPATH . 'public/uploads/radiologi/';
        if (!is_dir($dir)) {
            mkdir($dir, 0o755, true);
        }
        return $dir;
    }

    // Simpan satu atau lebih file gambar ke disk dan catat ke DB
    public function upload(int $idHasilRad): ResponseInterface
    {
        $uploaded = [];

        foreach ($this->request->getFiles()['foto'] ?? [] as $file) {
            if (!$file->isValid() || $file->hasMoved() || !str_starts_with($file->getMimeType(), 'image/')) {
                continue;
            }

            $newName = $file->getRandomName();
            $file->move($this->uploadDir(), $newName);

            $this->model->insert([
                'id_hasil_rad' => $idHasilRad,
                'nama_file'    => $newName,
                'tgl_upload'   => date('Y-m-d H:i:s'),
            ]);

            $uploaded[] = [
                'id_rad_foto' => (int) $this->model->getInsertID(),
                'nama_file'   => $newName,
                'url'         => base_url("uploads/radiologi/{$newName}"),
            ];
        }

        return $this->response->setJSON(['success' => true, 'data' => $uploaded]);
    }

    // Hapus file dari disk lalu hapus record dari DB
    public function hapusFoto(int $id): ResponseInterface
    {
        $foto = $this->model->find($id);

        if ($foto) {
            $path = $this->uploadDir() . $foto['nama_file'];
            if (file_exists($path)) {
                unlink($path);
            }
            $this->model->delete($id);
        }

        return $this->response->setJSON(['success' => true]);
    }
}
