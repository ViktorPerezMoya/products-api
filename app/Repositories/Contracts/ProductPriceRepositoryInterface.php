<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Collection;

interface ProductPriceRepositoryInterface
{
    /**
     * Retrieve all prices for a given product ID with eager-loaded currency.
     *
     * @return Collection<int, ProductPrice>
     */
    public function getByProductId(int $productId): Collection;

    /**
     * Create a new price entry for a given product and load its currency relationship.
     *
     * @param  array<string, mixed>  $data
     */
    public function createForProduct(int $productId, array $data): ProductPrice;
}
