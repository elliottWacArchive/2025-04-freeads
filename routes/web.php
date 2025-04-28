<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'showIndex']);
// Route::get('/exemple', function () {
//     return view('exemple');
// });