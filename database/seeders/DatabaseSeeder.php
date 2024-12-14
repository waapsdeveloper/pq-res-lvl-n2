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
        $this->call(RolesTableSeeder::class); //1
        $this->call(UsersTableSeeder::class); //2
        $this->call(CategorySeeder::class); //3
        $this->call(RestaurantsTableSeeder::class); //4
        $this->call(OrderSeeder::class); //5
        $this->call(OrderBillingSeeder::class); //6
        $this->call(OrderProductSeeder::class); //7
        $this->call(PaymentSeeder::class); //8
        $this->call(ProductSeeder::class); //9
        $this->call(ProductsPropsSeeder::class); //10
        $this->call(ProfileSeeder::class); //11
        $this->call(RTableSeeder::class); //12
        $this->call(SessionSeeder::class); //13
        $this->call(UserAddressesSeeder::class);

        // You can also seed additional data here if needed
    }
}
