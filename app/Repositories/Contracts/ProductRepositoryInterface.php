<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Retrieve all products with eager-loaded relationships.
     *
     * @return Collection<int, Product>
     */
    public function getAll(): Collection;

    /**
     * Find a product by its ID or return null.
     */
    public function findById(int $id): ?Product;

    /**
     * Find a product by its ID or throw ModelNotFoundException.
     */
    public function findOrFail(int $id): Product;

    /**
     * Create a new product and load its relationships.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product;

    /**
     * Update an existing product and refresh its relationships.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): Product;

    /**
     * Delete a product instance.
     */
    public function delete(Product $product): bool;
}
