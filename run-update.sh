#!/bin/bash

php artisan migrate --path="database/migrations/2025_03_07_103629_create_countries_table.php"
php artisan db:seed --class=CountrySeeder

