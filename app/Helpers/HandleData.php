<?php

namespace App\Helpers;

class HandleData
{
    public static function onlyNumber(?string $value): ?string
    {
        if (empty($value)) return null;

        return preg_replace('/[^0-9]/', '', $value);
    }
}
