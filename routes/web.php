<?php

use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/checked', [DomainController::class, 'store']);


Route::get(
    '{view}',
    MainController::class
)->where('view', '(.*)');
