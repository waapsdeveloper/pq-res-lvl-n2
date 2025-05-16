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
        $restaurantId = 1;

        // Core categories matching expense seeder references
        $categories = [
            [
                'category_name' => 'Rent',
                'daily_estimate' => 166.67,  // 5000/month
                'weekly_estimate' => 1166.69,
                'monthly_estimate' => 5000,
                'description' => 'Property rental costs'
            ],
            [
                'category_name' => 'Utilities',
                'daily_estimate' => 33.33,   // 1000/month
                'weekly_estimate' => 233.31,
                'monthly_estimate' => 1000,
                'description' => 'Electricity, water, and gas services'
            ],
            [
                'category_name' => 'Salaries',
                'daily_estimate' => 400,      // 12000/month
                'weekly_estimate' => 2800,
                'monthly_estimate' => 12000,
                'description' => 'Staff wages and benefits'
            ],
            [
                'category_name' => 'Food Supplies',
                'daily_estimate' => 266.67,  // 8000/month
                'weekly_estimate' => 1866.69,
                'monthly_estimate' => 8000,
                'description' => 'Raw ingredients and food items'
            ],
            [
                'category_name' => 'Marketing',
                'daily_estimate' => 16.67,   // 500/month
                'weekly_estimate' => 116.69,
                'monthly_estimate' => 500,
                'description' => 'Advertising and promotions'
            ],
            [
                'category_name' => 'Maintenance',
                'daily_estimate' => 33.33,   // 1000/month
                'weekly_estimate' => 233.31,
                'monthly_estimate' => 1000,
                'description' => 'Equipment and facility repairs'
            ],
            [
                'category_name' => 'Insurance',
                'daily_estimate' => 8.33,    // 250/month
                'weekly_estimate' => 58.31,
                'monthly_estimate' => 250,
                'description' => 'Business insurance policies'
            ],
            [
                'category_name' => 'Taxes',
                'daily_estimate' => 66.67,   // 2000/month
                'weekly_estimate' => 466.69,
                'monthly_estimate' => 2000,
                'description' => 'Government taxes and fees'
            ],
            [
                'category_name' => 'Office Supplies',
                'daily_estimate' => 6.67,   // 200/month
                'weekly_estimate' => 46.69,
                'monthly_estimate' => 200,
                'description' => 'Stationery and office materials'
            ]
        ];

        // Create core categories
        foreach ($categories as $category) {
            ExpenseCategory::create([
                'restaurant_id' => $restaurantId,
                'category_name' => $category['category_name'],
                'daily_estimate' => $category['daily_estimate'],
                'weekly_estimate' => $category['weekly_estimate'],
                'monthly_estimate' => $category['monthly_estimate'],
                'description' => $category['description'],
                'image' => 'images/expense-category/expense.png',
                'status' => 'active',
            ]);
        }

   
       
}}