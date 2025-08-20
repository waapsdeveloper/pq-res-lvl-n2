<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\InvoiceSetting;
use Illuminate\Database\Seeder;

class InvoiceSettingSeeder extends Seeder
{
    public function run()
    {
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            InvoiceSetting::updateOrCreate(
                ['restaurant_id' => $restaurant->id],
                [
                    'invoice_logo' => $restaurant->logo ?? null,
                    'size' => '80mm',
                    'left_margin' => '1mm',
                    'right_margin' => '1mm',
                    'google_review_barcode' => null,
                    'footer_text' => 'Thank you for dining with us! Please visit again.',
                    'restaurant_address' => $restaurant->address ?? null,
                    'font_size' => 10,
                ]
            );
        }

        $this->command->info('Invoice settings seeded/updated for all restaurants.');
    }
}
