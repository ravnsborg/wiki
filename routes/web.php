<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\EntitiesController;


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

//---------------------------------
// Wiki
//---------------------------------

// ->middleware('auth');
Route::get('/', [HomeController::class, 'index']);
Route::post('/wiki/search', [HomeController::class, 'search']);
Route::get('/wiki/content/{content}', [ArticlesController::class, 'show']);

//Load add new Article form
Route::get('/wiki/category', [ArticlesController::class, 'create']);

//Store new category
Route::post('/wiki/category', [CategoriesController::class, 'store']);

//View / Add / Edit articles
Route::get('wiki/article/{id}', [ArticlesController::class, 'index']);
Route::post('/wiki/content', [ArticlesController::class, 'store']);
Route::put('/wiki/content/{id}', [ArticlesController::class, 'update']);
//Route::put('/wiki/content/{id}/favorite', [ArticlesController::class, 'toggleFavorite']);
Route::put('/wiki/article/{id}/favorite/toggle', [ArticlesController::class, 'toggleFavorite']);

//Remove content
Route::delete('/wiki/content/{id}', [ArticlesController::class, 'destroy']);

//---------------------------------
// Links
//---------------------------------

//Get all links
Route::get('/link/management', [LinksController::class, 'index']);

//Add new link
Route::post('/link', [LinksController::class, 'store']);

//Update link
Route::put('/link/{id}', [LinksController::class, 'update']);

//Remove content
Route::delete('/link/{id}', [LinksController::class, 'destroy']);


//---------------------------------
// Entities
//---------------------------------

//Assign user preferred entity
Route::get('/entity/assign/{id}', [HomeController::class, 'assignEntitySelection']);

//Get all entites for this user
Route::get('/entity/management', [EntitiesController::class, 'index']);

//Add new entity
Route::post('/entity', [EntitiesController::class, 'store']);

//Update entity
Route::put('/entity/{id}', [EntitiesController::class, 'update']);

//Remove entity
Route::delete('/entity/{id}', [EntitiesController::class, 'destroy']);

//Sandbox
//Route::get('sb', 'SandboxController@index');
//Route::get('sb/db/{content}', 'SandboxController@db');

//------------------------------------------
// Backup DB
//------------------------------------------
Route::get('/db/dump', [HomeController::class, 'db_dump']);