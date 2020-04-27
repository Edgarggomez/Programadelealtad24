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

Route::get('/', function () {
   
   return view('layout.main');
   
})->middleware('auth');

Route::any("/cliente/search","web\ClienteController@search") ;
Route::resource("/cliente","web\ClienteController") ;



Route::get("/flotilla/{id}/create","web\FlotillaController@create") ;
Route::resource("/flotilla","web\FlotillaController", ['except' => ['create']]) ;

Route::resource('usuarios', 'UserController');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
