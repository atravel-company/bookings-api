<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProductsController;

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

Route::get('/reports', [ReportsController::class, 'index']);

Route::get('/users', [UsersController::class, 'index']);

Route::get('/products', [ProductsController::class, 'index']);