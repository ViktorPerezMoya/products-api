<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    private Currency $usd;

    private Currency $eur;

    private Currency $gbp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usd = Currency::query()->create([
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => '1.0000',
        ]);

        $this->eur = Currency::query()->create([
            'name' => 'Euro',
            'symbol' => '€',
            'exchange_rate' => '0.9200',
        ]);

        $this->gbp = Currency::query()->create([
            'name' => 'British Pound',
            'symbol' => '£',
            'exchange_rate' => '0.7900',
        ]);
    }

    public function test_it_performs_full_product_crud_lifecycle(): void
    {
        // 1. CREATE (POST /api/products)
        $createPayload = [
            'name' => 'Mechanical Keyboard',
            'description' => 'RGB mechanical keyboard',
            'price' => 99.99,
            'currency_id' => $this->usd->id,
            'tax_cost' => 15.00,
            'manufacturing_cost' => 45.00,
        ];

        $createResponse = $this->postJson('/api/products', $createPayload);

        $createResponse->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'tax_cost',
                    'manufacturing_cost',
                    'currency' => [
                        'id',
                        'name',
                        'symbol',
                        'exchange_rate',
                    ],
                    'created_at',
                ],
            ])
            ->assertJsonPath('data.name', 'Mechanical Keyboard')
            ->assertJsonPath('data.description', 'RGB mechanical keyboard')
            ->assertJsonPath('data.price', '99.99')
            ->assertJsonPath('data.tax_cost', '15.00')
            ->assertJsonPath('data.manufacturing_cost', '45.00')
            ->assertJsonPath('data.currency.id', $this->usd->id)
            ->assertJsonPath('data.currency.name', 'US Dollar')
            ->assertJsonPath('data.currency.symbol', '$')
            ->assertJsonPath('data.currency.exchange_rate', '1.0000');

        $productId = (int) $createResponse->json('data.id');
        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'name' => 'Mechanical Keyboard',
        ]);

        // 2. LIST ALL (GET /api/products)
        $listResponse = $this->getJson('/api/products');

        $listResponse->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $productId)
            ->assertJsonPath('data.0.name', 'Mechanical Keyboard');

        // 3. SHOW SINGLE (GET /api/products/{id})
        $showResponse = $this->getJson("/api/products/{$productId}");

        $showResponse->assertStatus(200)
            ->assertJsonPath('data.id', $productId)
            ->assertJsonPath('data.name', 'Mechanical Keyboard')
            ->assertJsonPath('data.currency.id', $this->usd->id);

        // 4. UPDATE (PUT /api/products/{id})
        $updatePayload = [
            'name' => 'Mechanical Keyboard Pro',
            'description' => 'Wireless RGB mechanical keyboard',
            'price' => 129.99,
            'currency_id' => $this->usd->id,
            'tax_cost' => 18.00,
            'manufacturing_cost' => 55.00,
        ];

        $updateResponse = $this->putJson("/api/products/{$productId}", $updatePayload);

        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.id', $productId)
            ->assertJsonPath('data.name', 'Mechanical Keyboard Pro')
            ->assertJsonPath('data.description', 'Wireless RGB mechanical keyboard')
            ->assertJsonPath('data.price', '129.99')
            ->assertJsonPath('data.tax_cost', '18.00')
            ->assertJsonPath('data.manufacturing_cost', '55.00');

        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'name' => 'Mechanical Keyboard Pro',
        ]);

        // 5. DELETE (DELETE /api/products/{id})
        $deleteResponse = $this->deleteJson("/api/products/{$productId}");

        $deleteResponse->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $productId]);

        // 6. VERIFY 404 AFTER DELETION
        $this->getJson("/api/products/{$productId}")->assertStatus(404);
    }

    public function test_it_registers_and_lists_product_prices_in_multiple_currencies(): void
    {
        $product = Product::query()->create([
            'name' => 'Mechanical Keyboard',
            'description' => 'RGB mechanical keyboard',
            'price' => '99.99',
            'currency_id' => $this->usd->id,
            'tax_cost' => '15.00',
            'manufacturing_cost' => '45.00',
        ]);

        // Add price in EUR (POST /api/products/{id}/prices)
        $eurPriceResponse = $this->postJson("/api/products/{$product->id}/prices", [
            'currency_id' => $this->eur->id,
        ]);

        $eurPriceResponse->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'product_id',
                    'currency' => [
                        'id',
                        'name',
                        'symbol',
                        'exchange_rate',
                    ],
                    'price',
                ],
            ])
            ->assertJsonPath('data.product_id', $product->id)
            ->assertJsonPath('data.currency.id', $this->eur->id)
            ->assertJsonPath('data.currency.name', 'Euro')
            ->assertJsonPath('data.currency.symbol', '€')
            ->assertJsonPath('data.currency.exchange_rate', '0.9200')
            ->assertJsonPath('data.price', '92.00');

        // Add price in GBP (POST /api/products/{id}/prices)
        $gbpPriceResponse = $this->postJson("/api/products/{$product->id}/prices", [
            'currency_id' => $this->gbp->id,
        ]);

        $gbpPriceResponse->assertStatus(201)
            ->assertJsonPath('data.product_id', $product->id)
            ->assertJsonPath('data.currency.id', $this->gbp->id)
            ->assertJsonPath('data.price', '79.00');

        // List all prices for the product (GET /api/products/{id}/prices)
        $listPricesResponse = $this->getJson("/api/products/{$product->id}/prices");

        $listPricesResponse->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.currency.name', 'Euro')
            ->assertJsonPath('data.0.price', '92.00')
            ->assertJsonPath('data.1.currency.name', 'British Pound')
            ->assertJsonPath('data.1.price', '79.00');

        // Deleting the product cascades to product_prices
        $this->deleteJson("/api/products/{$product->id}")->assertStatus(204);
        $this->assertDatabaseCount('product_prices', 0);
    }

    public function test_it_returns_422_on_invalid_product_or_price_payloads_and_404_on_missing_product(): void
    {
        // Invalid product creation
        $this->postJson('/api/products', [
            'name' => '',
            'price' => -10,
            'currency_id' => 9999,
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'currency_id', 'tax_cost', 'manufacturing_cost']);

        // Non-existent product on prices endpoints
        $this->getJson('/api/products/9999/prices')->assertStatus(404);
        $this->postJson('/api/products/9999/prices', [
            'currency_id' => $this->eur->id
        ])->assertStatus(404);
    }
}
