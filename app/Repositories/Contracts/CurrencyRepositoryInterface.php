<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Currency;

interface CurrencyRepositoryInterface
{
    /**
     * Finds a currency by its ID or throw ModelNotFoundException.
     */
    public function findOrFail(int $id): Currency; 
}