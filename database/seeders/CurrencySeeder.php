<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => '1.0000',
            ],
            [
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => '0.9200',
            ],
            [
                'name' => 'British Pound',
                'symbol' => '£',
                'exchange_rate' => '0.7900',
            ],
            [
                'name' => 'Peso Argentino',
                'symbol' => 'ARS',
                'exchange_rate' => '1500.0000',
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::query()->updateOrCreate(
                ['name' => $currency['name']],
                $currency
            );
        }
    }
}
