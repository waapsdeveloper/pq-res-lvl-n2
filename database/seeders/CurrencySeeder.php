<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        $currencies = json_decode(file_get_contents(database_path('data/currencies.json')), true);

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['country' => $currency['country']],
                $currency
            );
        }
    }
}
