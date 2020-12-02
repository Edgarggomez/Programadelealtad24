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
Auth::routes(['register' => false]);

Route::resource('usuarios', 'UserController', ['names' => 'users'])->parameters(['usuarios' => 'user'])->except(['show']);
Route::resource('ubicaciones', 'LocationController', ['names' => 'locations'])->parameters(['ubicaciones' => 'location'])->except(['show']);
Route::resource('reglas', 'RuleController', ['names' => 'rules'])->parameters(['reglas' => 'rule'])->except(['create', 'show', 'edit', 'update', 'index']);
Route::get('ubicaciones/{location}/reglas/crear', 'RuleController@create')->name('rules.create');
Route::resource('clientes', 'ClientController', ['names' => 'clients'])->parameters(['clientes' => 'client'])->except(['show']);
Route::get('clientes/{client}/saldo-adicional', 'ClientController@editBalance')->name('client.editBalance');
Route::post('clientes/{client}/saldo-adicional', 'ClientController@updateBalance')->name('client.updateBalance');

Route::get('clientes/{client}/saldo-historial', 'ClientController@balanceHistory')->name('client.balanceHistory');

Route::get('movimientos/export', 'MovimientoSaldoController@export')->name('movimientosSaldo.export');
Route::resource('movimientos', 'MovimientoSaldoController', ['names' => 'movimientosSaldo'])->parameters(['movimientos' => 'movimientoSaldo']);


Route::resource('tarjetas', 'CardController', ['names' => 'cards'])->parameters(['tarjetas' => 'card'])->except(['create', 'edit', 'show', 'index']);
Route::get('clientes/{client}/tarjetas/crear', 'CardController@create')->name('cards.create');

Route::resource('configuraciones', 'SettingController', ['names' => 'settings'])->parameters(['configuraciones' => 'setting']);

Route::get('/', 'HomeController@index')->name('home');

