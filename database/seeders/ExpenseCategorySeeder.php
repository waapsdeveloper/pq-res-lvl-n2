<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use Faker\Factory as Faker;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $restaurantId = 1; // Assuming a default restaurant ID

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Utilities',
            'daily_estimate' => 100,
            'weekly_estimate' => 700,
            'monthly_estimate' => 3000,
            'description' => 'Electricity, water, gas, etc.',
            'image' => null,
            'status' => 'active',
        ]);

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Rent',
            'daily_estimate' => 500,
            'weekly_estimate' => 3500,
            'monthly_estimate' => 15000,
            'description' => 'Monthly rent for the premises.',
            'image' => null,
            'status' => 'active',
        ]);

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Salaries',
            'daily_estimate' => 1200,
            'weekly_estimate' => 8400,
            'monthly_estimate' => 36000,
            'description' => 'Salaries for all employees.',
            'image' => null,
            'status' => 'active',
        ]);

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Food Supplies',
            'daily_estimate' => 800,
            'weekly_estimate' => 5600,
            'monthly_estimate' => 24000,
            'description' => 'Cost of raw ingredients and food items.',
            'image' => null,
            'status' => 'active',
        ]);

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Marketing',
            'daily_estimate' => 50,
            'weekly_estimate' => 350,
            'monthly_estimate' => 1500,
            'description' => 'Advertising and promotional expenses.',
            'image' => null,
            'status' => 'active',
        ]);

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Maintenance',
            'daily_estimate' => 30,
            'weekly_estimate' => 210,
            'monthly_estimate' => 900,
            'description' => 'Repairs and upkeep of the restaurant.',
            'image' => null,
            'status' => 'active',
        ]);

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Cleaning Supplies',
            'daily_estimate' => 20,
            'weekly_estimate' => 140,
            'monthly_estimate' => 600,
            'description' => 'Cost of cleaning materials.',
            'image' => null,
            'status' => 'active',
        ]);

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Insurance',
            'daily_estimate' => 15,
            'weekly_estimate' => 105,
            'monthly_estimate' => 450,
            'description' => 'Insurance premiums.',
            'image' => null,
            'status' => 'active',
        ]);

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Licenses & Permits',
            'daily_estimate' => 10,
            'weekly_estimate' => 70,
            'monthly_estimate' => 300,
            'description' => 'Fees for required licenses and permits.',
            'image' => null,
            'status' => 'active',
        ]);

        ExpenseCategory::create([
            'restaurant_id' => $restaurantId,
            'category_name' => 'Technology',
            'daily_estimate' => 40,
            'weekly_estimate' => 280,
            'monthly_estimate' => 1200,
            'description' => 'Software subscriptions and IT support.',
            'image' => null,
            'status' => 'active',
        ]);

        for ($i = 11; $i <= 30; $i++) {
            ExpenseCategory::create([
                'restaurant_id' => $restaurantId,
                'category_name' => $faker->word(),
                'daily_estimate' => $faker->numberBetween(10, 500),
                'weekly_estimate' => $faker->numberBetween(70, 3500),
                'monthly_estimate' => $faker->numberBetween(300, 15000),
                'description' => $faker->sentence(),
                'image' => null,
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
        }
    }
}