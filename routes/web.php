<?php

use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\RowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', RowController::class)->name('rows');;
Route::post('/import',ExcelImportController::class)->name('import');
