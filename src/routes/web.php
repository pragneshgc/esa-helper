<?php

use Esa\Helper\Http\Controllers\HomeController;
use Esa\Helper\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'esa::', 'prefix' => 'esa'], function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.list');

    Route::get('/{view?}', [HomeController::class, 'index'])->where('view', '(.*)')->name('index');
});
