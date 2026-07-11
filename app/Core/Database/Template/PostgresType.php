<?php
declare(strict_types=1);

namespace App\Core\Database\Template;

use CodeIgniter\Database\RawSql;

/** @mago-expect lint:too-many-methods */
final readonly class PostgresType
{
    public static function UUID(): ForgeType
    {
        return new ForgeType('UUID', default: new RawSql('uuid7()'));
    }

    public static function ID16(): ForgeType
    {
        return new ForgeType('INT2 GENERATED ALWAYS AS IDENTITY');
    }

    public static function ID32(): ForgeType
    {
        return new ForgeType('INT4 GENERATED ALWAYS AS IDENTITY');
    }

    public static function ID64(): ForgeType
    {
        return new ForgeType('INT8 GENERATED ALWAYS AS IDENTITY');
    }

    public static function ID(int $max): ForgeType
    {
        if ($max <= ((1 << 15) - 1)) {
            return self::ID16();
        }
        if ($max <= ((1 << 31) - 1)) {
            return self::ID32();
        }
        if ($max <= PHP_INT_MAX) {
            return self::ID64();
        }
        return self::UUID();
    }

    public static function INT16(): ForgeType
    {
        return new ForgeType('INT2');
    }

    public static function INT32(): ForgeType
    {
        return new ForgeType('INT4');
    }

    public static function INT64(): ForgeType
    {
        return new ForgeType('INT8');
    }

    public static function INT(int $min, int $max): ForgeType
    {
        if ($min >= -(1 << 15) && $max <= ((1 << 15) - 1)) {
            return self::INT16();
        }
        if ($min >= -(1 << 31) && $max <= ((1 << 31) - 1)) {
            return self::INT32();
        }
        if ($min >= PHP_INT_MIN && $max <= PHP_INT_MAX) {
            return self::INT64();
        }
        die("Integer range {$min}-{$max} is not supported");
    }

    public static function F32(): ForgeType
    {
        return new ForgeType('FLOAT4');
    }

    public static function F64(): ForgeType
    {
        return new ForgeType('FLOAT8');
    }

    public static function NUMERIC(int $len, int $decimal): ForgeType
    {
        return new ForgeType('NUMERIC', constraint: "{$len},{$decimal}");
    }

    public static function CHAR(int $len): ForgeType
    {
        return new ForgeType('CHAR', constraint: (string) $len);
    }

    public static function VARCHAR(int $len): ForgeType
    {
        return new ForgeType('VARCHAR', constraint: (string) $len);
    }

    public static function TEXT(): ForgeType
    {
        return new ForgeType('TEXT');
    }

    public static function BOOL(): ForgeType
    {
        return new ForgeType('BOOLEAN');
    }

    public static function DATE(): ForgeType
    {
        return new ForgeType('DATE');
    }

    public static function TIME(): ForgeType
    {
        return new ForgeType('TIME');
    }

    public static function DTIME(): ForgeType
    {
        return new ForgeType('TIMESTAMPTZ');
    }

    public static function INTERVAL(): ForgeType
    {
        return new ForgeType('INTERVAL');
    }

    public static function IPV4(string $column): ForgeType
    {
        return new ForgeType('INET', "family({$column}) = 4");
    }

    public static function IPV6(string $column): ForgeType
    {
        return new ForgeType('INET', "family({$column}) = 6");
    }

    public static function NETV4(string $column): ForgeType
    {
        return new ForgeType('CIDR', "family({$column}) = 4");
    }

    public static function NETV6(string $column): ForgeType
    {
        return new ForgeType('CIDR', "family({$column}) = 6");
    }

    public static function MAC48(): ForgeType
    {
        return new ForgeType('MACADDR');
    }

    public static function MAC64(): ForgeType
    {
        return new ForgeType('MACADDR8');
    }
}
