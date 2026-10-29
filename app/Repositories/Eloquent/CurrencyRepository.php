<?php
declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Currency;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CurrencyRepository implements CurrencyRepositoryInterface
{
    /**
     * Find a currency by its ID or throw ModelNotFoundException.
     */
    public function findOrFail(int $id): Currency
    {
        return Currency::findOrFail($id);
    }
}



