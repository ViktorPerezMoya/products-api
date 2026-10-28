<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Retrieve all products.
     *
     * @return Collection<int, Product>
     */
    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAll();
    }

    /**
     * Retrieve a single product by ID or fail with ModelNotFoundException (404).
     */
    public function getProductById(int $id): Product
    {
        return $this->productRepository->findOrFail($id);
    }

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     */
    public function createProduct(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update an existing product by ID.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->productRepository->findOrFail($id);

        return $this->productRepository->update($product, $data);
    }

    /**
     * Delete a product by ID.
     */
    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->findOrFail($id);

        return $this->productRepository->delete($product);
    }
}
