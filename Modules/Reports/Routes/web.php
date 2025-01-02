<?php

use Illuminate\Support\Facades\Route;
use Modules\Reports\Http\Controllers\ReportController;

// Dynamic Reports
Route::get('/get-dynamic-reports', [ReportController::class, 'index']);
Route::get('/generate-report', [ReportController::class, 'generate']);