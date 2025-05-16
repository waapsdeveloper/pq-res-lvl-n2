<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Use statements for seeders
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserAddressesSeeder::class);
        $this->call(RestaurantSeeder::class);
        $this->call(RestaurantTimingSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductsPropsSeeder::class);
        $this->call(RTableSeeder::class);
        $this->call(VariationSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(ExpenseCategorySeeder::class);
        $this->call(ExpenseSeeder::class);

        
        // $this->call(OrderSeeder::class); //5
        // $this->call(OrderBillingSeeder::class); //6
        // $this->call(OrderProductSeeder::class); //7
        // $this->call(PaymentSeeder::class); //8   
        // $this->call(ProfileSeeder::class); //11
        // $this->call(SessionSeeder::class); //13
        // $this->call(RTableReservingSeeder::class);
    }
}
