<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductPriceController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function (): void {
    /* Products routes */
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{id}', [ProductController::class, 'show'])->whereNumber('id');
    Route::put('/{id}', [ProductController::class, 'update'])->whereNumber('id');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->whereNumber('id');

    /* Product prices routes */
    Route::get('/{id}/prices', [ProductPriceController::class, 'index'])->whereNumber('id');
    Route::post('/{id}/prices', [ProductPriceController::class, 'store'])->whereNumber('id');
});
