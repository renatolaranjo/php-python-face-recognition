<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('list', [UserController::class, 'list']);
Route::post('register', [UserController::class, 'register']);
Route::post('recog', [UserController::class, 'recog']);
Route::get('face/{index}', [UserController::class, 'face']);