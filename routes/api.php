<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Erp\CourseController;

Route::middleware('auth')->group(function () {
    Route::get('/universities/{university}/courses', [CourseController::class, 'getCoursesByUniversity']);
});
