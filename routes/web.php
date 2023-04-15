<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnusController;


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
    return view('welcome.welcome');
});

Route::resource('alumni', AlumnusController::class);
Route::get('/alumni/import/create', [AlumnusController::class, 'import_create'])->name('alumni.import.create');
Route::post('/alumni/import', [AlumnusController::class, 'import_store'])->name('alumni.import.store');


// -----------------------------------------

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
