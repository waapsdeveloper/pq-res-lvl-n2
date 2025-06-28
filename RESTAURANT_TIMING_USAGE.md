# Restaurant Timing System Usage

## Overview
The restaurant timing system now uses a meta-based approach where each timing configuration is stored as key-value pairs. This provides flexibility for different timing patterns including 24-hour operations, break times, and off days.

## Database Structure
- Table: `restaurant_timings_meta`
- Fields: `restaurant_id`, `meta_key`, `meta_value`

## Timing Keys
Each day has the following possible keys:
- `{day}_start_time` - Opening time (HH:MM format)
- `{day}_end_time` - Closing time (HH:MM format)
- `{day}_break_start` - Break start time (HH:MM format)
- `{day}_break_end` - Break end time (HH:MM format)
- `{day}_is_24_hours` - Boolean for 24-hour operation
- `{day}_is_off` - Boolean for off day

Global settings:
- `same_time_all_days` - Boolean for same timing all days
- `off_days` - Array of off days

## Sample Restaurant Configurations

### Restaurant 1: Standard Restaurant with Break Times
- **Monday-Friday**: 9:00 AM - 10:00 PM (with 2:00-3:00 PM break)
- **Saturday-Sunday**: 10:00 AM - 11:00 PM (with 2:00-3:00 PM break)
- **Pattern**: Standard hours with lunch breaks

### Restaurant 2: Extended Hours Restaurant
- **Monday-Thursday**: 8:00 AM - 11:00 PM
- **Friday-Saturday**: 8:00 AM - 12:00 AM (midnight)
- **Sunday**: 8:00 AM - 11:00 PM
- **Pattern**: Extended hours, no breaks

### Restaurant 3: 24-Hour Restaurant
- **All days**: 24 hours open
- **Pattern**: Always open

### Restaurant 4: Lunch/Dinner Focused Restaurant
- **Monday-Thursday**: 11:00 AM - 10:00 PM
- **Friday-Saturday**: 11:00 AM - 11:00 PM
- **Sunday**: Closed
- **Pattern**: Lunch/dinner hours with Sunday off

### Restaurant 5: Early Bird Restaurant
- **Monday-Friday**: 7:00 AM - 9:00 PM (with 12:00-1:00 PM break)
- **Saturday-Sunday**: 8:00 AM - 8:00 PM (with 12:00-1:00 PM break)
- **Pattern**: Early hours with lunch breaks

## API Endpoints

### 1. Get Timing Configuration
```
GET /admin/restaurant-timing/config?restaurant_id=1
```

### 2. Store Timing Configuration
```
POST /admin/restaurant-timing/config
{
    "restaurant_id": 1,
    "timings": [
        {
            "key": "monday_start_time",
            "value": "09:00"
        },
        {
            "key": "monday_end_time",
            "value": "22:00"
        },
        {
            "key": "monday_break_start",
            "value": "14:00"
        },
        {
            "key": "monday_break_end",
            "value": "15:00"
        },
        {
            "key": "monday_is_24_hours",
            "value": false
        },
        {
            "key": "monday_is_off",
            "value": false
        }
    ]
}
```

### 3. Update Timing Configuration
```
PUT /admin/restaurant-timing/config/1
{
    "restaurant_id": 1,
    "timings": [
        {
            "key": "tuesday_start_time",
            "value": "08:00"
        },
        {
            "key": "tuesday_end_time",
            "value": "23:00"
        }
    ]
}
```

### 4. Check Open Status
```
POST /admin/restaurant-timing/check-open-status
{
    "restaurant_id": 1,
    "day": "monday",
    "time": "14:30"
}
```

## Restaurant Create/Update Integration

### Creating Restaurant with Timings
```
POST /admin/restaurant
{
    "name": "My Restaurant",
    "address": "123 Main St",
    "phone": "1234567890",
    "email": "info@myrestaurant.com",
    "timings": [
        {
            "key": "monday_start_time",
            "value": "09:00"
        },
        {
            "key": "monday_end_time",
            "value": "22:00"
        },
        {
            "key": "monday_break_start",
            "value": "14:00"
        },
        {
            "key": "monday_break_end",
            "value": "15:00"
        },
        {
            "key": "tuesday_is_24_hours",
            "value": true
        },
        {
            "key": "sunday_is_off",
            "value": true
        }
    ]
}
```

### Updating Restaurant with Timings
```
PUT /admin/restaurant/1
{
    "name": "Updated Restaurant",
    "address": "456 New St",
    "timings": [
        {
            "key": "monday_start_time",
            "value": "08:00"
        },
        {
            "key": "monday_end_time",
            "value": "23:00"
        }
    ]
}
```

## Timing Patterns Supported

### 1. Simple Start-End Time
```
monday_start_time: "09:00"
monday_end_time: "22:00"
```

### 2. With Break Time
```
monday_start_time: "09:00"
monday_break_start: "14:00"
monday_break_end: "15:00"
monday_end_time: "22:00"
```

### 3. 24 Hours Open
```
monday_is_24_hours: true
```

### 4. Off Day
```
monday_is_off: true
```

### 5. Same Time for All Days
```
same_time_all_days: true
monday_start_time: "09:00"
monday_end_time: "22:00"
```

## Model Methods

### RestaurantTiming Model Methods

1. `getTimingConfig($restaurantId)` - Get all timing configuration for a restaurant
2. `setTimingConfig($restaurantId, $config)` - Set timing configuration for a restaurant
3. `getTimingValue($restaurantId, $key, $default)` - Get specific timing value
4. `setTimingValue($restaurantId, $key, $value)` - Set specific timing value
5. `isOpenAt($restaurantId, $day, $time)` - Check if restaurant is open at specific time
6. `getFormattedTiming($restaurantId, $day)` - Get formatted timing display

## Example Usage in Code

```php
// Get timing configuration
$timingConfig = RestaurantTiming::getTimingConfig($restaurantId);

// Check if restaurant is open
$isOpen = RestaurantTiming::isOpenAt($restaurantId, 'monday', '14:30');

// Get formatted timing
$formattedTiming = RestaurantTiming::getFormattedTiming($restaurantId, 'monday');

// Set timing configuration
RestaurantTiming::setTimingConfig($restaurantId, [
    'monday_start_time' => '09:00',
    'monday_end_time' => '22:00',
    'monday_break_start' => '14:00',
    'monday_break_end' => '15:00'
]);
```

## Migration and Seeding
Run the migration to create the new table:
```bash
php artisan migrate
```

Seed the database with sample restaurant timings:
```bash
php artisan db:seed --class=RestaurantTimingSeeder
```

This will create the `restaurant_timings_meta` table and populate it with timing configurations for 5 sample restaurants demonstrating different timing patterns. 