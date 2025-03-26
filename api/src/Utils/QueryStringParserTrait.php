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

    protected function getInt(string $key, array $params, int $default = 0): int
    {
        if (!isset($params[$key]) || $params[$key] === '') {
            return $default;
        }

        return (int) $params[$key];
    }
}
