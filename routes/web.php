<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['dbsync'])->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('home');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
});
Route::post('/events/import-csv', [EventController::class, 'importCsv'])->name('events.importCsv');
