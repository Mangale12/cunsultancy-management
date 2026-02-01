<?php

use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Erp\AgentController;
use App\Http\Controllers\Erp\BranchController;
use App\Http\Controllers\Erp\CountryController;
use App\Http\Controllers\Erp\CourseController;
use App\Http\Controllers\Erp\DocumentController;
use App\Http\Controllers\Erp\DocumentTypeController;
use App\Http\Controllers\Erp\EmployeeController;
use App\Http\Controllers\Erp\StateController;
use App\Http\Controllers\Erp\StudentController;
use App\Http\Controllers\Erp\StudentApplicationController;
use App\Http\Controllers\Erp\UniversityController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Erp\IntakeController;
use App\Http\Controllers\Erp\ApplicationYearController;
use App\Http\Controllers\Erp\StudentDocumentController;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/test-branches', function () {
    return response()->json(['message' => 'Test route working']);
});

Route::get('/test-controller', [TestController::class, 'test'])->name('test.controller');

Route::resource('countries', CountryController::class);
Route::resource('states', StateController::class);
Route::resource('branches', BranchController::class);

// Admin routes for user roles and permissions management
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('user-roles', [UserRoleController::class, 'index'])->name('user-roles.index');
    Route::get('user-roles/{user}/edit', [UserRoleController::class, 'edit'])->name('user-roles.edit');
    Route::put('user-roles/{user}', [UserRoleController::class, 'update'])->name('user-roles.update');
    Route::get('user-roles/{user}/details', [UserRoleController::class, 'getUserDetails'])->name('user-roles.details');
    
    Route::get('roles', [UserRoleController::class, 'rolesIndex'])->name('user-roles.roles');
    Route::get('roles/{role}/edit', [UserRoleController::class, 'editRole'])->name('user-roles.edit-role');
    Route::put('roles/{role}', [UserRoleController::class, 'updateRole'])->name('user-roles.update-role');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::resource('application-years', \App\Http\Controllers\Erp\ApplicationYearController::class);
    Route::resource('application-status', \App\Http\Controllers\Erp\ApplicationStatusController::class);
    // Route::resource('intakes', \App\Http\Controllers\Erp\IntakeController::class);
    // Route::get('dashboard', function () {
    //     return Inertia::render('dashboard');
    // })->name('dashboard');

    Route::resource('countries', CountryController::class);
    Route::resource('states', StateController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('agents', AgentController::class);
    Route::resource('universities', UniversityController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('students', StudentController::class);
    Route::resource('intakes', IntakeController::class);
    Route::resource('application-years', ApplicationYearController::class);
    Route::post('students/{student}/complete-application', [StudentController::class, 'completeApplication'])
    ->name('students.complete-application');
    Route::post('student-apply-application/{application}', [StudentApplicationController::class, 'updateStatus'])
    ->name('applications.update-status');
    Route::resource('student-apply-application', StudentApplicationController::class);
    Route::post('students/{student}/upload-document', [StudentController::class, 'uploadDocument'])->name('students.upload-document');
    Route::resource('student-documents', StudentDocumentController::class);
    // Route::resource('student-applications', StudentApplicationController::class);
    // Route::get('/students/{student}/apply', [StudentApplicationController::class, 'create'])->name('students.apply');
    // Route::resource('documents', DocumentController::class);
    // Route::resource('document-types', DocumentTypeController::class);
    
    // // Document specific routes
    // Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    // Route::post('documents/{document}/verify', [DocumentController::class, 'verify'])->name('documents.verify');
    // Route::get('students/{student}/documents', [DocumentController::class, 'studentDocuments'])->name('students.documents');

    Route::get('dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');

    Route::resource('student-applications', StudentApplicationController::class);
});

require __DIR__.'/settings.php';
