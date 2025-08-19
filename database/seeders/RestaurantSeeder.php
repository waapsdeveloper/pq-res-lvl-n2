<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\BranchConfig;
use App\Models\InvoiceSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('data/restaurants.json');

        // Read and decode JSON
        $restaurants = json_decode(File::get($jsonFilePath), true);

        // Insert restaurants into the database
        foreach ($restaurants as $data) {
            $restaurant = Restaurant::updateOrCreate(
                ['name' => $data['name']],
                [
                    'address' => $data['address'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'email' => $data['email'] ?? null,
                    'description' => $data['description'] ?? null,
                    'image' => $data['image'] ?? null,
                    'favicon' => $data['favicon'] ?? null,
                    'logo' => $data['logo'] ?? null,
                    'status' => $data['status'] ?? 1,
                    'is_active' => $data['is_active'] ?? 1,
                    'updated_at' => now(),
                ]
            );

            // Create branch config for the restaurant
            BranchConfig::updateOrCreate(
                [
                    'branch_id' => $restaurant->id,
                ],
                [
                    'tax' => $data['tax'] ?? 0,
                    'currency' => $data['currency'] ?? 'USD',
                    'dial_code' => $data['dial_code'] ?? '+1',
                    'currency_symbol' => $data['currency_symbol'] ?? '$',
                ]
            );

            // Create invoice settings for the restaurant
            InvoiceSetting::updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                ],
                [
                    'invoice_logo' => $data['invoice_logo'] ?? $restaurant->logo,
                    'size' => $data['size'] ?? '80mm',
                    'left_margin' => $data['left_margin'] ?? '1mm',
                    'right_margin' => $data['right_margin'] ?? '1mm',
                    'google_review_barcode' => $data['google_review_barcode'] ?? null,
                    'footer_text' => $data['footer_text'] ??
                        'Thank you for dining with us! Please visit again.',
                    'restaurant_address' => $data['restaurant_address'] ?? $restaurant->address,
                    'font_size' => $data['font_size'] ?? 10,
                ]
            );
        }

        $this->command->info('Restaurants & Invoice settings imported successfully from JSON file.');
    }
}
