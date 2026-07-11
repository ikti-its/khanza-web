<?php
declare(strict_types=1);

namespace App\Core\Model;

/** @mago-expect lint:too-many-methods */
final class ValidationType
{
    private function __construct(
        private string $rules,
        private string $error,
    ) {}

    public function definition(): array
    {
        return [
            'rules' => $this->rules,
            'error' => $this->error,
        ];
    }

    public static function DEFAULT(): self
    {
        return new self('DEFAULT', 'DEFAULT');
    }

    public static function INDEX(): self
    {
        return new self('SELF', 'SELF');
    }

    public static function FLOAT(): self
    {
        return new self('FLOAT', 'desimal');
    }

    public static function MONEY(): self
    {
        return new self('MONEY', 'uang');
    }

    public static function DTIME(): self
    {
        return new self('DTIME', 'tanggal_jam');
    }

    public static function DATE(): self
    {
        return new self('DATE', 'tanggal');
    }

    public static function TIME(): self
    {
        return new self('TIME', 'jam');
    }

    public static function NAME(): self
    {
        return new self('NAME', 'nama');
    }

    public static function TEXT(): self
    {
        return new self('TEXT', 'teks');
    }

    public static function BOOL(): self
    {
        return new self('BOOL', 'bool');
    }

    public static function TEMP(): self
    {
        return new self('TEMP', 'suhu');
    }

    public static function NUMBER(): self
    {
        return new self('NUMBER', 'jumlah');
    }

    public static function SELECT(): self
    {
        return new self('SELECT', 'status');
    }
}
