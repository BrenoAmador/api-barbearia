<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rotas de Registro, Login e Eu
Route::post('/register', 'LoginController@register');
Route::post('/login', 'LoginController@authenticate');
Route::middleware('auth:sanctum')->get('/me','LoginController@me');

// Middleware de Verificação se é ADM - ADM tem Acesso aos Clientes também
Route::middleware('auth:sanctum', 'check.admin')->group(function () {
    Route::get('/usuario/{search}', 'UsersController@search');
    Route::apiResource('admin', 'AdminController');
    Route::apiResource('usuario', 'UsersController');
    Route::apiResource('horario', 'HorariosController');
});

// Middleware de Verificação se é Cliente/Usuário
Route::middleware('auth:sanctum', 'check.client')->group(function () {
    Route::apiResource('horario', 'HorariosController');
});
