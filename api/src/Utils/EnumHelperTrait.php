<?php

namespace App\Utils;

trait EnumHelperTrait
{
    public static function values(): array
    {
        return array_map(fn(\BackedEnum $item) => $item->value, self::cases());
    }

    public static function names(): array
    {
        return array_map(fn(\BackedEnum $item) => $item->name, self::cases());
    }
}
