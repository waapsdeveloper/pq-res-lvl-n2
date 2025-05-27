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
                [
                    'currency_code' => $currency['currency_code'],
                    'currency_name' => $currency['currency_name'],
                    'dial_code' => $currency['dial_code'] ?? null,
                    'flag' => $currency['flag'] ?? null,
                    'currency_symbol' => $currency['currency_symbol'] ?? null,
                ]
            );
        }
    }
}
