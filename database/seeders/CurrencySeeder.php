<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        $currencies = [
            // Gulf & Middle East
            ['country' => 'United Arab Emirates', 'currency_code' => 'AED', 'currency_name' => 'UAE Dirham', 'dial_code' => '+971', 'flag' => '🇦🇪'],
            ['country' => 'Saudi Arabia', 'currency_code' => 'SAR', 'currency_name' => 'Saudi Riyal', 'dial_code' => '+966', 'flag' => '🇸🇦'],
            ['country' => 'Kuwait', 'currency_code' => 'KWD', 'currency_name' => 'Kuwaiti Dinar', 'dial_code' => '+965', 'flag' => '🇰🇼'],
            ['country' => 'Qatar', 'currency_code' => 'QAR', 'currency_name' => 'Qatari Riyal', 'dial_code' => '+974', 'flag' => '🇶🇦'],
            ['country' => 'Bahrain', 'currency_code' => 'BHD', 'currency_name' => 'Bahraini Dinar', 'dial_code' => '+973', 'flag' => '🇧🇭'],
            ['country' => 'Oman', 'currency_code' => 'OMR', 'currency_name' => 'Omani Rial', 'dial_code' => '+968', 'flag' => '🇴🇲'],
            ['country' => 'Jordan', 'currency_code' => 'JOD', 'currency_name' => 'Jordanian Dinar', 'dial_code' => '+962', 'flag' => '🇯🇴'],
            ['country' => 'Lebanon', 'currency_code' => 'LBP', 'currency_name' => 'Lebanese Pound', 'dial_code' => '+961', 'flag' => '🇱🇧'],
            ['country' => 'Egypt', 'currency_code' => 'EGP', 'currency_name' => 'Egyptian Pound', 'dial_code' => '+20', 'flag' => '🇪🇬'],
            ['country' => 'Turkey', 'currency_code' => 'TRY', 'currency_name' => 'Turkish Lira', 'dial_code' => '+90', 'flag' => '🇹🇷'],

            // Asia
            ['country' => 'India', 'currency_code' => 'INR', 'currency_name' => 'Indian Rupee', 'dial_code' => '+91', 'flag' => '🇮🇳'],
            ['country' => 'Pakistan', 'currency_code' => 'PKR', 'currency_name' => 'Pakistani Rupee', 'dial_code' => '+92', 'flag' => '🇵🇰'],
            ['country' => 'Bangladesh', 'currency_code' => 'BDT', 'currency_name' => 'Bangladeshi Taka', 'dial_code' => '+880', 'flag' => '🇧🇩'],
            ['country' => 'Sri Lanka', 'currency_code' => 'LKR', 'currency_name' => 'Sri Lankan Rupee', 'dial_code' => '+94', 'flag' => '🇱🇰'],
            ['country' => 'Nepal', 'currency_code' => 'NPR', 'currency_name' => 'Nepalese Rupee', 'dial_code' => '+977', 'flag' => '🇳🇵'],
            ['country' => 'China', 'currency_code' => 'CNY', 'currency_name' => 'Chinese Yuan', 'dial_code' => '+86', 'flag' => '🇨🇳'],
            ['country' => 'Japan', 'currency_code' => 'JPY', 'currency_name' => 'Japanese Yen', 'dial_code' => '+81', 'flag' => '🇯🇵'],
            ['country' => 'Singapore', 'currency_code' => 'SGD', 'currency_name' => 'Singapore Dollar', 'dial_code' => '+65', 'flag' => '🇸🇬'],
            ['country' => 'Malaysia', 'currency_code' => 'MYR', 'currency_name' => 'Malaysian Ringgit', 'dial_code' => '+60', 'flag' => '🇲🇾'],
            ['country' => 'Indonesia', 'currency_code' => 'IDR', 'currency_name' => 'Indonesian Rupiah', 'dial_code' => '+62', 'flag' => '🇮🇩'],
            ['country' => 'Philippines', 'currency_code' => 'PHP', 'currency_name' => 'Philippine Peso', 'dial_code' => '+63', 'flag' => '🇵🇭'],
            ['country' => 'Thailand', 'currency_code' => 'THB', 'currency_name' => 'Thai Baht', 'dial_code' => '+66', 'flag' => '🇹🇭'],

            // Europe
            ['country' => 'United Kingdom', 'currency_code' => 'GBP', 'currency_name' => 'British Pound', 'dial_code' => '+44', 'flag' => '🇬🇧'],
            ['country' => 'Germany', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'dial_code' => '+49', 'flag' => '🇩🇪'],
            ['country' => 'France', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'dial_code' => '+33', 'flag' => '🇫🇷'],
            ['country' => 'Italy', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'dial_code' => '+39', 'flag' => '🇮🇹'],
            ['country' => 'Spain', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'dial_code' => '+34', 'flag' => '🇪🇸'],
            ['country' => 'Netherlands', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'dial_code' => '+31', 'flag' => '🇳🇱'],
            ['country' => 'Switzerland', 'currency_code' => 'CHF', 'currency_name' => 'Swiss Franc', 'dial_code' => '+41', 'flag' => '🇨🇭'],
            ['country' => 'Sweden', 'currency_code' => 'SEK', 'currency_name' => 'Swedish Krona', 'dial_code' => '+46', 'flag' => '🇸🇪'],
            ['country' => 'Norway', 'currency_code' => 'NOK', 'currency_name' => 'Norwegian Krone', 'dial_code' => '+47', 'flag' => '🇳🇴'],
            ['country' => 'Denmark', 'currency_code' => 'DKK', 'currency_name' => 'Danish Krone', 'dial_code' => '+45', 'flag' => '🇩🇰'],
            ['country' => 'Russia', 'currency_code' => 'RUB', 'currency_name' => 'Russian Ruble', 'dial_code' => '+7', 'flag' => '🇷🇺'],

            // USA & North America
            ['country' => 'United States', 'currency_code' => 'USD', 'currency_name' => 'US Dollar', 'dial_code' => '+1', 'flag' => '🇺🇸'],
            ['country' => 'Canada', 'currency_code' => 'CAD', 'currency_name' => 'Canadian Dollar', 'dial_code' => '+1', 'flag' => '🇨🇦'],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['country' => $currency['country']],
                $currency
            );
        }
    }
}