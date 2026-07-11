<?php
declare(strict_types=1);

namespace App\Core\Database\Template;

/** @mago-expect lint:no-redundant-use */
use App\Core\Database\Template\ForgeType as FT;
/** @mago-expect lint:no-redundant-use */
use App\Core\Database\Template\PostgresType as PT;

/** @mago-expect lint:too-many-methods */
final readonly class SemanticType
{
    public static function FK_AUTO(): FT
    {
        return new FT('FK_AUTO');
    }

    public static function ID(int $max): FT
    {
        return PT::ID($max);
    }

    public static function RECORD(int $len): FT
    {
        return PT::VARCHAR($len);
    }

    public static function BOOL(): FT
    {
        return PT::BOOL();
    }

    public static function INT(int $min, int $max): FT
    {
        return PT::INT($min, $max);
    }

    public static function DURATION(): FT
    {
        return PT::NUMERIC(4, 1);
    }

    public static function QTY(int $min, int $max): FT
    {
        return PT::INT($min, $max);
    }

    public static function MULT(): FT
    {
        return PT::NUMERIC(4, 2);
    }

    public static function TEMP(): FT
    {
        return PT::NUMERIC(4, 1);
    }

    public static function MONEY(): FT
    {
        return PT::NUMERIC(16, 4);
    }

    public static function QUEUE(): FT
    {
        return PT::INT16();
    }

    public static function SCORE(): FT
    {
        return PT::NUMERIC(8, 4);
    }

    public static function MEDIC(): FT
    {
        return PT::NUMERIC(8, 4);
    }

    public static function VITAL(int $min, int $max): FT
    {
        return PT::INT($min, $max);
    }

    public static function BODY(): FT
    {
        return PT::NUMERIC(5, 1);
    }

    public static function LAB(): FT
    {
        return PT::NUMERIC(5, 1);
    }

    public static function RAD(): FT
    {
        return PT::NUMERIC(8, 2);
    }

    public static function PERCENT(): FT
    {
        return PT::NUMERIC(5, 2);
    }

    public static function DATE(): FT
    {
        return PT::DATE();
    }

    public static function TIME(): FT
    {
        return PT::TIME();
    }

    public static function DTIME(): FT
    {
        return PT::DTIME();
    }

    public static function YEAR(string $column): FT
    {
        return new FT('SMALLINT', check: "{$column} >= 1900 AND {$column} <= 2100");
    }

    public static function INTERVAL(): FT
    {
        return PT::INTERVAL();
    }

    public static function EXPIRY(): FT
    {
        return PT::DATE();
    }

    public static function TEXT(): FT
    {
        return PT::TEXT();
    }

    public static function NOTE(): FT
    {
        return PT::TEXT();
    }

    public static function DESCR(): FT
    {
        return PT::TEXT();
    }

    public static function NAME(int $len): FT
    {
        return PT::VARCHAR($len);
    }

    public static function CODE(int $len): FT
    {
        return PT::VARCHAR($len);
    }
}
