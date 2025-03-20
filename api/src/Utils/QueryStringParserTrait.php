<?php

namespace App\Utils;

trait QueryStringParserTrait
{
    private function parseArray(string|array $value): array
    {
        if (is_string($value)) {
            return explode(',', $value);
        }

        return $value;
    }
}
