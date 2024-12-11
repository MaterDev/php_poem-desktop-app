<?php

use App\Http\Controllers\PoemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PoemController::class, 'index']);
Route::post('/poems', [PoemController::class, 'store']);
