<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Retrieve all products with eager-loaded relationships.
     *
     * @return Collection<int, Product>
     */
    public function getAll(): Collection
    {
        return Product::with(['currency', 'prices.currency'])->get();
    }

    /**
     * Find a product by its ID or return null.
     */
    public function findById(int $id): ?Product
    {
        return Product::with(['currency', 'prices.currency'])->find($id);
    }

    /**
     * Find a product by its ID or throw ModelNotFoundException.
     */
    public function findOrFail(int $id): Product
    {
        return Product::with(['currency', 'prices.currency'])->findOrFail($id);
    }

    /**
     * Create a new product and load its relationships.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product
    {
        $product = Product::create($data);

        return $product->load(['currency', 'prices.currency']);
    }

    /**
     * Update an existing product and refresh its relationships.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->load(['currency', 'prices.currency']);
    }

    /**
     * Delete a product instance.
     */
    public function delete(Product $product): bool
    {
        return (bool) $product->delete();
    }
}
