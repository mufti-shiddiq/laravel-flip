<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransferController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('transfer')->group(function () {
    Route::get('/', [TransferController::class, 'index'])->name('transfer.index');
    Route::get('/bank', [TransferController::class, 'bank'])->name('transfer.bank');
    Route::get('/inquiry', [TransferController::class, 'inquiry'])->name('transfer.inquiry');
    Route::post('/inquiry', [TransferController::class, 'storeInquiry'])->name('transfer.storeInquiry');
});
