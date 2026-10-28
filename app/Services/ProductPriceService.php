<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductPrice;
use App\Repositories\Contracts\ProductPriceRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductPriceService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductPriceRepositoryInterface $productPriceRepository
    ) {}

    /**
     * Retrieve all prices for a specific product.
     * Ensures the product exists before querying prices (throws 404 if not found).
     *
     * @return Collection<int, ProductPrice>
     */
    public function getPricesByProductId(int $productId): Collection
    {
        $this->productRepository->findOrFail($productId);

        return $this->productPriceRepository->getByProductId($productId);
    }

    /**
     * Assign a new currency price to a specific product.
     * Ensures the product exists before creating the price record (throws 404 if not found).
     *
     * @param  array<string, mixed>  $data
     */
    public function createPriceForProduct(int $productId, array $data): ProductPrice
    {
        $this->productRepository->findOrFail($productId);

        return $this->productPriceRepository->createForProduct($productId, $data);
    }
}
