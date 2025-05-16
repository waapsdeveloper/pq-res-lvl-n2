<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenses = [
            [
                'name' => 'Downtown Office Rent',
                'description' => 'Monthly rental payment for main location',
                'expense_category_id' => 1,
                'amount' => 4500.00,
                'type' => 'recurring',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(5)->toDateString(),
            ],
            [
                'name' => 'Electricity Bill',
                'description' => 'Monthly electricity consumption',
                'expense_category_id' => 2,
                'amount' => 680.50,
                'type' => 'recurring',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(3)->toDateString(),
            ],
            [
                'name' => 'Kitchen Staff Salaries',
                'description' => 'Monthly salary payment for kitchen team',
                'expense_category_id' => 3,
                'amount' => 12500.00,
                'type' => 'recurring',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(7)->toDateString(),
            ],
            [
                'name' => 'Organic Vegetables Supply',
                'description' => 'Weekly fresh produce delivery',
                'expense_category_id' => 4,
                'amount' => 950.00,
                'type' => 'recurring',
                 'status' => 'unpaid',
                'date' => Carbon::now()->addDays(2)->toDateString(),
            ],
            [
                'name' => 'Social Media Campaign',
                'description' => 'Q3 Instagram marketing campaign',
                'expense_category_id' => 5,
                'amount' => 2200.00,
                'type' => 'one-time',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(10)->toDateString(),
            ],
            [
                'name' => 'Refrigeration Repair',
                'description' => 'Emergency repair of walk-in cooler',
                'expense_category_id' => 6,
                'amount' => 875.00,
                'type' => 'one-time',
                'status' => 'paid',
                'date' => Carbon::now()->subWeek()->toDateString(),
            ],
            [
                'name' => 'Liability Insurance',
                'description' => 'Annual business insurance premium',
                'expense_category_id' => 7,
                'amount' => 3200.00,
                'type' => 'one-time',
                'status' => 'paid',
                'date' => Carbon::now()->subMonth()->toDateString(),
            ],
            [
                'name' => 'Sales Tax Payment',
                'description' => 'Q2 state sales tax filing',
                'expense_category_id' => 8,
                'amount' => 4150.00,
                'type' => 'recurring',
                 'status' => 'unpaid',
                'date' => Carbon::now()->addDays(7)->toDateString(),
            ],
            [
                'name' => 'Office Supplies',
                'description' => 'Monthly cleaning and paper products',
                'expense_category_id' => 9,
                'amount' => 345.75,
                'type' => 'recurring',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(4)->toDateString(),
            ],
            [
                'name' => 'Equipment Upgrade',
                'description' => 'New commercial blender purchase',
                'expense_category_id' => 4,
                'amount' => 1200.00,
                'type' => 'one-time',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(15)->toDateString(),
            ],
            [
                'name' => 'Water Bill',
                'description' => 'Monthly water utility payment',
                'expense_category_id' => 2,
                'amount' => 245.00,
                'type' => 'recurring',
                'status' => 'paid',
                'date' => Carbon::now()->subWeek()->toDateString(),
            ],
            [
                'name' => 'Employee Training',
                'description' => 'Food safety certification course',
                'expense_category_id' => 9,
                'amount' => 650.00,
                'type' => 'one-time',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(20)->toDateString(),
            ],
            [
                'name' => 'Parking Lot Maintenance',
                'description' => 'Monthly landscaping service',
                'expense_category_id' => 6,
                'amount' => 400.00,
                'type' => 'recurring',
                'status' => 'unpaid',
                'date' => Carbon::now()->addDays(3)->toDateString(),
            ],
            [
                'name' => 'Website Hosting',
                'description' => 'Annual domain and hosting fees',
                'expense_category_id' => 5,
                'amount' => 350.00,
                'type' => 'recurring',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(8)->toDateString(),
            ],
            [
                'name' => 'Wine Inventory',
                'description' => 'Monthly restocking of house wines',
                'expense_category_id' => 4,
                'amount' => 1800.00,
                'type' => 'recurring',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(12)->toDateString(),
            ],
            [
                'name' => 'Fire Safety Inspection',
                'description' => 'Annual fire system certification',
                'expense_category_id' => 7,
                'amount' => 450.00,
                'type' => 'recurring',
                'status' => 'paid',
                'date' => Carbon::now()->subMonth()->toDateString(),
            ],
            [
                'name' => 'Charity Donation',
                'description' => 'Local food bank contribution',
                'expense_category_id' => 9,
                'amount' => 500.00,
                'type' => 'one-time',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(25)->toDateString(),
            ],
            [
                'name' => 'POS System Update',
                'description' => 'Software license renewal',
                'expense_category_id' => 6,
                'amount' => 750.00,
                'type' => 'recurring',
                'status' => 'paid',
                'date' => Carbon::now()->subDays(9)->toDateString(),
            ],
            [
                'name' => 'Employee Uniforms',
                'description' => 'New summer uniform set',
                'expense_category_id' => 9,
                'amount' => 1200.00,
                'type' => 'one-time',
                'status' => 'unpaid',
                'date' => Carbon::now()->addDays(10)->toDateString(),
            ],
            [
                'name' => 'Gas Line Installation',
                'description' => 'New outdoor grill connection',
                'expense_category_id' => 6,
                'amount' => 2250.00,
                'type' => 'one-time',
                 'status' => 'unpaid',
                'date' => Carbon::now()->subDays(18)->toDateString(),
            ]
        ];

        foreach ($expenses as $expense) {
            Expense::create(array_merge($expense, [
               'image' => 'images/expense/image.png',
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}