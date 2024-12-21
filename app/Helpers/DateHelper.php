<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Parse and validate date with multiple formats.
     *
     * @param string $date
     * @param array $formats
     * @return Carbon|null
     */
    public static function parseDate($date, $formats = ['d-m-Y H:i:s', 'Y-m-d H:i:s', 'm/d/Y H:i', 'd-m-y H:i'])
    {
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date);
            } catch (\Exception $e) {
                continue; // Try next format
            }
        }

        return null; // Return null if no format matches
    }

    /**
     * Validate if a date is at least N minutes ahead of now.
     *
     * @param Carbon $date
     * @param int $minutes
     * @return bool
     */
    public static function isAtLeastMinutesAhead(Carbon $date, $minutes = 30)
    {
        return $date->diffInMinutes(Carbon::now(), false) >= $minutes;
    }
}
