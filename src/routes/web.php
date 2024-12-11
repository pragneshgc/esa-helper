<?php

use Illuminate\Support\Facades\Route;
use Esa\Helper\Http\Controllers\HomeController;

Route::get('esa-helper/', [HomeController::class, 'index']);
