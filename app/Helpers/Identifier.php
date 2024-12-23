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
    public static function make(string $modelName, int $id): string
    {
        // Take the first 3 letters of the model name, capitalize them, append '0', and the ID
        $prefix = strtoupper(Str::substr($modelName, 0, 3));
        return "{$prefix}0{$id}";
    }
}
