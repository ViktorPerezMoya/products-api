<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductPrice;
use App\Repositories\Contracts\ProductPriceRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductPriceService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductPriceRepositoryInterface $productPriceRepository,
        private readonly CurrencyRepositoryInterface $currencyRepository
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
     * Assign a new currency to a specific product and calculate the price.
     * Ensures the product exists before creating the price record (throws 404 if not found).
     *
     * @param  array<string, mixed>  $data
     */
    public function createPriceForProduct(int $productId, array $data): ProductPrice
    {
        $product = $this->productRepository->findOrFail($productId);

        $targetCurrency = $this->currencyRepository->findOrFail((int) $data['currency_id']);

        // get factor conversion of product
        $factorConversion = ((float)$targetCurrency->exchange_rate / (float) $product->currency->exchange_rate);

        $data['price'] = round((float) $product->price * $factorConversion, 2);

        return $this->productPriceRepository->createForProduct($productId, $data);
    }
}
