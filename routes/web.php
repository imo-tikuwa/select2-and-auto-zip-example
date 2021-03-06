<?php

use App\Http\Controllers\ExampleController;
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
$example_view_regex = '(object-define-property|mutation-observer)';
Route::get('/{view?}', [ExampleController::class, 'index'])->where('view', $example_view_regex)->name('example');
Route::post('/{view?}', [ExampleController::class, 'index'])->where('view', $example_view_regex)->name('example');

Route::get('/add-row', [ExampleController::class, 'addRow']);
