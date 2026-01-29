<?php

use App\Http\Controllers\Admin\FileTypeSettingController;
use Illuminate\Support\Facades\Route;

// File Type Settings Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('file-type-settings', [FileTypeSettingController::class, 'index'])->name('file-type-settings.index');
    Route::get('file-type-settings/create', [FileTypeSettingController::class, 'create'])->name('file-type-settings.create');
    Route::post('file-type-settings', [FileTypeSettingController::class, 'store'])->name('file-type-settings.store');
    Route::get('file-type-settings/{fileTypeSetting}/edit', [FileTypeSettingController::class, 'edit'])->name('file-type-settings.edit');
    Route::put('file-type-settings/{fileTypeSetting}', [FileTypeSettingController::class, 'update'])->name('file-type-settings.update');
    Route::delete('file-type-settings/{fileTypeSetting}', [FileTypeSettingController::class, 'destroy'])->name('file-type-settings.destroy');
    Route::post('file-type-settings/{fileTypeSetting}/toggle-status', [FileTypeSettingController::class, 'toggleStatus'])->name('file-type-settings.toggle-status');
});
