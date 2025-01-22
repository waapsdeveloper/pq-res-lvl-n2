<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Identifier
{
    /**
     * Generate a unique identifier.
     *
     * @param string $modelName
     * @param int $id
     * @return string
     */
    public static function make(string $modelName, int $id, int $length = 3): string
    {
        $prefix = strtoupper(Str::substr($modelName, 0, $length));

        $numDigits = max(strlen($id), $length);

        $paddedId = str_pad($id, 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$paddedId}";
    }
}
