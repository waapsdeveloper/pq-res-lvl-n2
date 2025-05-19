<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = json_decode(file_get_contents(database_path('data/coupons.json')), true);

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}