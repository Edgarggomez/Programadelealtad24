<?php

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

Route::any("/cliente/search","web\ClienteController@search") ;
Route::resource("/cliente","web\ClienteController") ;



Route::get("/flotilla/{id}/create","web\FlotillaController@create") ;
Route::resource("/flotilla","web\FlotillaController", ['except' => ['create']]) ;

Route::resource('usuarios', 'UserController', ['names' => 'users'])->parameters(['usuarios' => 'user']);
Route::resource('ubicaciones', 'LocationController', ['names' => 'locations'])->parameters(['ubicaciones' => 'location']);
Route::resource('reglas', 'RuleController', ['names' => 'rules'])->parameters(['reglas' => 'rule']);

Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home');
