<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'symbol',
        'exchange_rate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'exchange_rate' => 'decimal:4',
    ];

    /**
     * Get the products that use this currency as their base currency.
     *
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'currency_id');
    }

    /**
     * Get the product prices registered in this currency.
     *
     * @return HasMany<ProductPrice, $this>
     */
    public function productPrices(): HasMany
    {
        return $this->hasMany(ProductPrice::class, 'currency_id');
    }
}
