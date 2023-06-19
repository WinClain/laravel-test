<?php

use App\Http\Controllers\api\TargetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/create-target', [TargetController::class, 'createTarget']);
Route::post('/edit-target', [TargetController::class, 'editTarget']);
Route::post('/get-targets', [TargetController::class, 'getTargets']);
Route::post('/close-target', [TargetController::class, 'closeTarget']);

