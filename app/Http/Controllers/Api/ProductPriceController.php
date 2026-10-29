<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Price\StoreProductPriceRequest;
use App\Http\Resources\ProductPriceResource;
use App\Services\ProductPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ProductPriceController extends BaseApiController
{
    public function __construct(
        private readonly ProductPriceService $productPriceService
    ) {}

    /**
     * List all prices for a given product.
     *
     * @OA\Get(path="/api/products/{id}/prices")
     */
    #[OA\Get(
        path: '/api/products/{id}/prices',
        operationId: 'getProductPrices',
        summary: 'List all prices for a given product',
        tags: ['Product Prices'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Product ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/ProductPrice')
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(ref: '#/components/schemas/NotFoundError')
            ),
        ]
    )]
    public function index(int $id): AnonymousResourceCollection
    {
        $prices = $this->productPriceService->getPricesByProductId($id);

        return ProductPriceResource::collection($prices);
    }

    /**
     * Add a new price in a specific currency for a product.
     *
     * @OA\Post(path="/api/products/{id}/prices")
     */
    #[OA\Post(
        path: '/api/products/{id}/prices',
        operationId: 'storeProductPrice',
        summary: 'Add a new price in a specific currency for a product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreProductPriceRequest')
        ),
        tags: ['Product Prices'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Product ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Product price created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/ProductPrice'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(ref: '#/components/schemas/NotFoundError')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
        ]
    )]
    public function store(StoreProductPriceRequest $request, int $id): JsonResponse
    {
        $price = $this->productPriceService->createPriceForProduct($id, $request->validated());

        return (new ProductPriceResource($price))
            ->response()
            ->setStatusCode(SymfonyResponse::HTTP_CREATED);
    }
}
