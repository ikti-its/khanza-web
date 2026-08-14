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

    /**
     * Simpan satu atau lebih file gambar sebagai bytea di database;
     * penyajiannya lewat route tampil/(:num) yang dijaga auth
     *
     * @throws \ReflectionException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function upload(int $idHasilRad): ResponseInterface
    {
        $uploaded = [];

        $request = $this->request;
        assert($request instanceof \CodeIgniter\HTTP\IncomingRequest, 'Request harus berupa IncomingRequest.');

        /** @mago-expect analysis:mixed-assignment */
        $rawFiles = $request->getFiles()['foto'] ?? [];
        $rawFiles = is_array($rawFiles) ? $rawFiles : [$rawFiles];

        $files = array_values(array_filter(
            $rawFiles,
            static fn($f) => $f instanceof \CodeIgniter\HTTP\Files\UploadedFile,
        ));

        foreach ($files as $file) {
            if (!$this->upload_valid($file, ['jpg', 'jpeg', 'png', 'webp'], 5 * 1024 * 1024)) {
                continue;
            }

            $newName = $file->getRandomName();

            $konten = file_get_contents($file->getTempName());
            if ($konten === false) {
                continue;
            }

            $this->model->insert([
                'id_hasil_rad' => $idHasilRad,
                'nama_file'    => $newName,
                'konten_file'  => '\x' . bin2hex($konten),
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

    /**
     * Hapus record foto; isi filenya ikut terhapus karena tersimpan di DB
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function hapusFoto(int $id): ResponseInterface
    {
        if ($this->model->find($id)) {
            $this->model->delete($id);
        }

        return $this->response->setJSON(['success' => true]);
    }
}
