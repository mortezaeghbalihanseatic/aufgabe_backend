<?php

use App\Http\Controllers\CheckDatabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\Quote2Controller;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});


Route::get('/CheckDatabase', [CheckDatabaseController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);




Route::middleware('auth:sanctum')->group(function () {
  Route::get('/quotes', [QuoteController::class, 'getAndSaveQuotes']);

  Route::get('/quote', [Quote2Controller::class, 'getAndSave1Quote']);
  Route::get('/otherquotes', [Quote2Controller::class, 'get4Quote']);
});
