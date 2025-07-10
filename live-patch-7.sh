#!/bin/bash

# Run the MigrationsTableSeeder to mark all migrations as run in the migrations table
php artisan db:seed --class=MigrationsTableSeeder
# Run the SuperAdminTableSeeder to seed the super admin user
php artisan db:seed --class=SuperAdminTableSeeder
