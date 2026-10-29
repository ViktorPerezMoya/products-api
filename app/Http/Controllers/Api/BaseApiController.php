<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Product API',
    description: 'RESTful API managing products and multi-currency pricing'
)]
#[OA\Server(
    url: '/',
    description: 'API Server'
)]
#[OA\Schema(
    schema: 'Currency',
    required: ['id', 'name', 'symbol', 'exchange_rate'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'US Dollar'),
        new OA\Property(property: 'symbol', type: 'string', example: '$'),
        new OA\Property(property: 'exchange_rate', type: 'string', example: '1.0000'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Product',
    required: ['id', 'name', 'price', 'tax_cost', 'manufacturing_cost', 'currency', 'created_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Mechanical Keyboard'),
        new OA\Property(property: 'description', type: 'string', example: 'RGB mechanical keyboard', nullable: true),
        new OA\Property(property: 'price', type: 'string', example: '99.99'),
        new OA\Property(property: 'tax_cost', type: 'string', example: '15.00'),
        new OA\Property(property: 'manufacturing_cost', type: 'string', example: '45.00'),
        new OA\Property(property: 'currency', ref: '#/components/schemas/Currency'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-10-28T16:00:00Z'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ProductPrice',
    required: ['id', 'product_id', 'currency', 'price'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'product_id', type: 'integer', example: 1),
        new OA\Property(property: 'currency', ref: '#/components/schemas/Currency'),
        new OA\Property(property: 'price', type: 'string', example: '92.00'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'StoreProductRequest',
    required: ['name', 'price', 'currency_id', 'tax_cost', 'manufacturing_cost'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Mechanical Keyboard'),
        new OA\Property(property: 'description', type: 'string', example: 'RGB mechanical keyboard', nullable: true),
        new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0, example: 99.99),
        new OA\Property(property: 'currency_id', type: 'integer', example: 1),
        new OA\Property(property: 'tax_cost', type: 'number', format: 'float', minimum: 0, example: 15.00),
        new OA\Property(property: 'manufacturing_cost', type: 'number', format: 'float', minimum: 0, example: 45.00),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'UpdateProductRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Mechanical Keyboard Pro'),
        new OA\Property(property: 'description', type: 'string', example: 'Wireless RGB mechanical keyboard', nullable: true),
        new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0, example: 129.99),
        new OA\Property(property: 'currency_id', type: 'integer', example: 1),
        new OA\Property(property: 'tax_cost', type: 'number', format: 'float', minimum: 0, example: 18.00),
        new OA\Property(property: 'manufacturing_cost', type: 'number', format: 'float', minimum: 0, example: 55.00),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'StoreProductPriceRequest',
    required: ['currency_id', 'price'],
    properties: [
        new OA\Property(property: 'currency_id', type: 'integer', example: 2),
        new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0, example: 92.00),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'NotFoundError',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Product] 999'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ValidationError',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The name field is required. (and 1 more error)'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            additionalProperties: new OA\AdditionalProperties(
                type: 'array',
                items: new OA\Items(type: 'string')
            ),
            example: ['name' => ['The name field is required.']]
        ),
    ],
    type: 'object'
)]
abstract class BaseApiController extends Controller
{
    //
}
