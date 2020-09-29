<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContentController;

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

Auth::routes();


// ->middleware('auth');
Route::get('/', [HomeController::class, 'index']);
Route::post('wiki/search', [HomeController::class, 'search']);
Route::get('/wiki/content/{content}', [ContentController::class, 'show']);

//Load add new Content form
Route::get('wiki/category', [ContentController::class, 'create']);

//Store new category
Route::post('wiki/category', [CategoryController::class, 'store']);

//Add / Edit content
Route::post('/wiki/content', [ContentController::class, 'store']);
Route::put('/wiki/content/{id}', [ContentController::class, 'update']);


//Remove content
Route::delete('/wiki/content/{id}', [ContentController::class, 'destroy']);


//Sandbox
//Route::get('sb', 'SandboxController@index');
//Route::get('sb/db/{content}', 'SandboxController@db');
