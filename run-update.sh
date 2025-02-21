#!/bin/bash

php artisan migrate --path="database/migrations/2025_02_21_114136_add_dial_code_to_users_table.php"
php artisan db:seed --class=GuestRoleSeeder
