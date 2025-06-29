<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTiming extends Model
{
    protected $table = 'restaurant_timings_meta';
    
    protected $fillable = [
        'restaurant_id',
        'meta_key',
        'meta_value'
    ];

    public function restaurantDetail()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }

    /**
     * Get timing configuration for a restaurant
     */
    public static function getTimingConfig($restaurantId)
    {
        $timings = self::where('restaurant_id', $restaurantId)->get();
        $config = [];
        
        foreach ($timings as $timing) {
            $config[$timing->meta_key] = $timing->meta_value;
        }
        
        return $config;
    }

    /**
     * Set timing configuration for a restaurant
     */
    public static function setTimingConfig($restaurantId, $config)
    {
        foreach ($config as $key => $value) {
            self::updateOrCreate(
                ['restaurant_id' => $restaurantId, 'meta_key' => $key],
                ['meta_value' => is_array($value) ? json_encode($value) : $value]
            );
        }
    }

    /**
     * Get specific timing value
     */
    public static function getTimingValue($restaurantId, $key, $default = null)
    {
        $timing = self::where('restaurant_id', $restaurantId)
                     ->where('meta_key', $key)
                     ->first();
        
        if (!$timing) {
            return $default;
        }
        
        $value = $timing->meta_value;
        
        // Try to decode JSON if it's a JSON string
        if (is_string($value) && json_decode($value) !== null) {
            return json_decode($value, true);
        }
        
        return $value;
    }

    /**
     * Set specific timing value
     */
    public static function setTimingValue($restaurantId, $key, $value)
    {
        return self::updateOrCreate(
            ['restaurant_id' => $restaurantId, 'meta_key' => $key],
            ['meta_value' => is_array($value) ? json_encode($value) : $value]
        );
    }

    /**
     * Get all timing keys
     */
    public static function getTimingKeys()
    {
        return [
            'monday_start_time',
            'monday_end_time',
            'monday_break_start',
            'monday_break_end',
            'monday_is_24_hours',
            'monday_is_off',
            
            'tuesday_start_time',
            'tuesday_end_time',
            'tuesday_break_start',
            'tuesday_break_end',
            'tuesday_is_24_hours',
            'tuesday_is_off',
            
            'wednesday_start_time',
            'wednesday_end_time',
            'wednesday_break_start',
            'wednesday_break_end',
            'wednesday_is_24_hours',
            'wednesday_is_off',
            
            'thursday_start_time',
            'thursday_end_time',
            'thursday_break_start',
            'thursday_break_end',
            'thursday_is_24_hours',
            'thursday_is_off',
            
            'friday_start_time',
            'friday_end_time',
            'friday_break_start',
            'friday_break_end',
            'friday_is_24_hours',
            'friday_is_off',
            
            'saturday_start_time',
            'saturday_end_time',
            'saturday_break_start',
            'saturday_break_end',
            'saturday_is_24_hours',
            'saturday_is_off',
            
            'sunday_start_time',
            'sunday_end_time',
            'sunday_break_start',
            'sunday_break_end',
            'sunday_is_24_hours',
            'sunday_is_off',
            
            'same_time_all_days',
            'off_days'
        ];
    }

    /**
     * Get day options
     */
    public static function getDayOptions()
    {
        return [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];
    }

    /**
     * Check if restaurant is open on a specific day and time
     */
    public static function isOpenAt($restaurantId, $day, $time = null)
    {
        $isOff = self::getTimingValue($restaurantId, $day . '_is_off', false);
        if ($isOff) {
            return false;
        }

        $is24Hours = self::getTimingValue($restaurantId, $day . '_is_24_hours', false);
        if ($is24Hours) {
            return true;
        }

        if (!$time) {
            return true; // If no specific time provided, just check if day is not off
        }

        $startTime = self::getTimingValue($restaurantId, $day . '_start_time');
        $endTime = self::getTimingValue($restaurantId, $day . '_end_time');
        $breakStart = self::getTimingValue($restaurantId, $day . '_break_start');
        $breakEnd = self::getTimingValue($restaurantId, $day . '_break_end');

        if (!$startTime || !$endTime) {
            return false;
        }

        $time = \Carbon\Carbon::parse($time);
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);

        // If no break time, simple start-end check
        if (!$breakStart || !$breakEnd) {
            return $time->between($start, $end);
        }

        // With break time
        $breakStartTime = \Carbon\Carbon::parse($breakStart);
        $breakEndTime = \Carbon\Carbon::parse($breakEnd);

        return $time->between($start, $breakStartTime) || $time->between($breakEndTime, $end);
    }

    /**
     * Get formatted timing for a day
     */
    public static function getFormattedTiming($restaurantId, $day)
    {
        $isOff = self::getTimingValue($restaurantId, $day . '_is_off', false);
        if ($isOff) {
            return 'Closed';
        }

        $is24Hours = self::getTimingValue($restaurantId, $day . '_is_24_hours', false);
        if ($is24Hours) {
            return '24 Hours Open';
        }

        $startTime = self::getTimingValue($restaurantId, $day . '_start_time');
        $endTime = self::getTimingValue($restaurantId, $day . '_end_time');
        $breakStart = self::getTimingValue($restaurantId, $day . '_break_start');
        $breakEnd = self::getTimingValue($restaurantId, $day . '_break_end');

        if (!$startTime || !$endTime) {
            return 'Not set';
        }

        if (!$breakStart || !$breakEnd) {
            return $startTime . ' - ' . $endTime;
        }

        return $startTime . ' - ' . $breakStart . ' (Break) ' . $breakEnd . ' - ' . $endTime;
    }
}

