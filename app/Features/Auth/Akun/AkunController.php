<?php
declare(strict_types=1);

namespace App\Features\Auth\Akun;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class AkunController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new AkunModel(),
            [
                ['Auth', 'auth'],
                ['Akun', 'akun'],
            ],
            'Akun',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,      REQUIRED, I::INDEX, 'id',       'ID Akun'],
                [SHOW,      REQUIRED, I::TEXT,  'email',    'Email'],
                [FORM_ONLY, OPTIONAL, I::PASSW,  'password', 'Password'],
                [SHOW,      REQUIRED, I::SELECT, 'role',     'Role'],
            ],
        );
    }

    /**
     * @param array<array-key, mixed> $postData
     */
    #[\Override()]
    protected function before_create(array &$postData): void
    {
        $this->hash_password($postData);
    }

    /**
     * @param array<array-key, mixed> $postData
     */
    #[\Override()]
    protected function before_update(array &$postData, int|string $id): void
    {
        // Password kosong pada form ubah berarti tidak diganti
        /** @var string|null $password */
        $password = $postData['password'] ?? null;
        if ($password === null || $password === '') {
            unset($postData['password']);
            return;
        }
        $this->hash_password($postData);
    }

    /**
     * @param array<array-key, mixed> $postData
     */
    private function hash_password(array &$postData): void
    {
        /** @var string|null $password */
        $password = $postData['password'] ?? null;
        if (is_string($password) && $password !== '') {
            $postData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
    }
}
