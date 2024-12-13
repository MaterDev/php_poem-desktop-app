<?php

use App\Http\Controllers\PoemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PoemController::class, 'index']);
Route::post('/poems', [PoemController::class, 'store']);
Route::patch('/poems/{poem}/icon-position', [PoemController::class,'updateIconPosition']);
Route::patch('/poems/{poem}/window-position', [PoemController::class,'updateWindowPosition']);
Route::patch('/poems/{poem}/window-size', [PoemController::class,'updateWindowSize']);
