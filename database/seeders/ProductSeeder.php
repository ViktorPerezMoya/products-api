<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usd = Currency::query()->where('name', 'US Dollar')->first();
        $eur = Currency::query()->where('name', 'Euro')->first();

        if (! $usd || ! $eur) {
            return;
        }

        $product = Product::query()->updateOrCreate(
            ['name' => 'Mechanical Keyboard'],
            [
                'description' => 'RGB mechanical keyboard',
                'price' => '99.99',
                'currency_id' => $usd->id,
                'tax_cost' => '15.00',
                'manufacturing_cost' => '45.00',
            ]
        );

        ProductPrice::query()->updateOrCreate(
            [
                'product_id' => $product->id,
                'currency_id' => $eur->id,
            ],
            [
                'price' => '92.00',
            ]
        );
    }
}
