<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ProductController extends BaseApiController
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    /**
     * List all products.
     */
    public function index(): AnonymousResourceCollection
    {
        $products = $this->productService->getAllProducts();

        return ProductResource::collection($products);
    }

    /**
     * Create a new product.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(SymfonyResponse::HTTP_CREATED);
    }

    /**
     * Retrieve a single product by ID.
     */
    public function show(int $id): ProductResource
    {
        $product = $this->productService->getProductById($id);

        return new ProductResource($product);
    }

    /**
     * Update an existing product.
     */
    public function update(UpdateProductRequest $request, int $id): ProductResource
    {
        $product = $this->productService->updateProduct($id, $request->validated());

        return new ProductResource($product);
    }

    /**
     * Delete a product.
     */
    public function destroy(int $id): Response
    {
        $this->productService->deleteProduct($id);

        return response()->noContent();
    }
}
