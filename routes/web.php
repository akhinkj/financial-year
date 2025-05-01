<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinancialYearController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [FinancialYearController::class, 'index']);
Route::post('/get-details', [FinancialYearController::class, 'getDetails']);
