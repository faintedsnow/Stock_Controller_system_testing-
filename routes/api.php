<?php

use App\Http\Controllers\Api\AssignmentApiController;
use App\Http\Middleware\AssignmentApiToken;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/register', [AssignmentApiController::class, 'register']);
    Route::post('/login', [AssignmentApiController::class, 'login']);

    Route::middleware(AssignmentApiToken::class)->group(function (): void {
        Route::get('/suppliers', [AssignmentApiController::class, 'suppliers']);
        Route::post('/suppliers', [AssignmentApiController::class, 'storeSupplier']);
        Route::get('/suppliers/{supplier}', [AssignmentApiController::class, 'showSupplier']);
        Route::put('/suppliers/{supplier}', [AssignmentApiController::class, 'updateSupplier']);
        Route::delete('/suppliers/{supplier}', [AssignmentApiController::class, 'deleteSupplier']);

        Route::get('/inventory', [AssignmentApiController::class, 'inventory']);
        Route::post('/inventory', [AssignmentApiController::class, 'storeInventory']);
        Route::get('/inventory/{inventory}', [AssignmentApiController::class, 'showInventory']);
        Route::put('/inventory/{inventory}', [AssignmentApiController::class, 'updateInventory']);
        Route::delete('/inventory/{inventory}', [AssignmentApiController::class, 'deleteInventory']);
        Route::post('/inventory/{inventory}/stock', [AssignmentApiController::class, 'updateStock']);

        Route::get('/restock-orders', [AssignmentApiController::class, 'restockOrders']);
        Route::post('/restock-orders', [AssignmentApiController::class, 'storeRestockOrder']);
        Route::post('/restock-orders/{order}/receive', [AssignmentApiController::class, 'receiveRestockOrder']);
    });
});
