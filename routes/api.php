<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->post('logout', function () {
//     return response()->json([
//         'message' => 'Logged out successfully'
//     ]);
// });
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('infosUser', [AuthController::class, 'infosUser']);

    Route::post('stokage', [WalletController::class, 'stokage']);
    Route::post('retrait', [WalletController::class, 'retrait']);
    Route::post('envoyer', [WalletController::class, 'envoyer']);
    Route::post('allTransaction', [TransactionController::class, 'getMyTransactions']);
});
