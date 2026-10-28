<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\ProductPrice;
use App\Repositories\Contracts\ProductPriceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductPriceRepository implements ProductPriceRepositoryInterface
{
    /**
     * Retrieve all prices for a given product ID with eager-loaded currency.
     *
     * @return Collection<int, ProductPrice>
     */
    public function getByProductId(int $productId): Collection
    {
        return ProductPrice::with(['currency'])
            ->where('product_id', $productId)
            ->get();
    }

    /**
     * Create a new price entry for a given product and load its currency relationship.
     *
     * @param  array<string, mixed>  $data
     */
    public function createForProduct(int $productId, array $data): ProductPrice
    {
        $productPrice = ProductPrice::create([
            ...$data,
            'product_id' => $productId,
        ]);

        return $productPrice->load(['currency']);
    }
}
