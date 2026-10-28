<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Price\StoreProductPriceRequest;
use App\Http\Resources\ProductPriceResource;
use App\Services\ProductPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ProductPriceController extends BaseApiController
{
    public function __construct(
        private readonly ProductPriceService $productPriceService
    ) {}

    /**
     * List all prices for a given product.
     */
    public function index(int $id): AnonymousResourceCollection
    {
        $prices = $this->productPriceService->getPricesByProductId($id);

        return ProductPriceResource::collection($prices);
    }

    /**
     * Add a new price in a specific currency for a product.
     */
    public function store(StoreProductPriceRequest $request, int $id): JsonResponse
    {
        $price = $this->productPriceService->createPriceForProduct($id, $request->validated());

        return (new ProductPriceResource($price))
            ->response()
            ->setStatusCode(SymfonyResponse::HTTP_CREATED);
    }
}
