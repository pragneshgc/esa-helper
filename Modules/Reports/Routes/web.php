<?php

use Illuminate\Support\Facades\Route;
use Modules\Reports\Http\Controllers\MonthlyReportController;
use Modules\Reports\Http\Controllers\ReportController;

// Dynamic Reports
Route::get('/get-dynamic-reports', [ReportController::class, 'index']);
Route::get('/generate-report', [ReportController::class, 'generate']);
Route::post('/save-report', [ReportController::class, 'store']);
Route::patch('/update-report/{id}', [ReportController::class, 'update']);
Route::delete('/delete-report/{id}', [ReportController::class, 'delete']);
Route::get('/get-saved-reports', [ReportController::class, 'getSavedReports']);
Route::get('/get-current-month-report', [MonthlyReportController::class, 'getCurrentMonthReport']);
Route::get('/get-past-month-report', [MonthlyReportController::class, 'getLastMonthReport']);
