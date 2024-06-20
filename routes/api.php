<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/add-user', [UserController::class, 'addUser']);

Route::prefix('transactions')->group(function (){
    Route::get('/', [TransactionController::class, "getTransactions"]);
    Route::post('/create', [TransactionController::class, "createTransaction"]);
    Route::post('/execute', [TransactionController::class, "executeTransactions"]);
});
