<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Symfony\Component\Intl\Currencies;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codes = Currencies::getCurrencyCodes();

        foreach ($codes as $code) {
            Currency::firstOrCreate([
                'code' => $code,
                'name' => Currencies::getName($code),
                'symbol' => Currencies::getSymbol($code),
            ]);
        }
    }
}
