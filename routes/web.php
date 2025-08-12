<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\PermissionManagementController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Define your application routes here.
|
*/

// =====================================================================
// 🏠 PUBLIC & AUTHENTICATION ROUTES
// =====================================================================

// Redirect root URL to the login page
Route::get('/', fn () => redirect()->route('login'));

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login-submit', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =====================================================================
// 🔐 PROTECTED ROUTES (Require SessionAuth Middleware)
// =====================================================================

Route::middleware(['web', 'auth.session'])->group(function () {

    // --------------------
    // 📊 Dashboard
    // --------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');

    // --------------------
    // 🧑‍💼 User & Access Management
    // --------------------
    Route::resource('users', UserManagementController::class);
    Route::resource('roles', RoleManagementController::class);
    Route::resource('permissions', PermissionManagementController::class);

    Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleManagementController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleManagementController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleManagementController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleManagementController::class, 'destroy'])->name('roles.destroy');
    Route::get('/roles/{id}/disable', [RoleManagementController::class, 'disable'])->name('roles.disable');
    Route::get('/roles/{id}/permissions', [RoleManagementController::class, 'permissions'])->name('roles.permissions');
    Route::post('/roles/update-permissions', [RoleManagementController::class, 'updatePermissions'])->name('roles.update_permissions');

   // --------------------
// 🧾 Candidate Management
// --------------------

Route::prefix('candidates')->name('candidates.')->group(function () {

    // Standard CRUD
    Route::get('/', [CandidateController::class, 'index'])->name('index');
    Route::get('/add_candidate', [CandidateController::class, 'create'])->name('create');
    Route::post('/', [CandidateController::class, 'store'])->name('store');
    Route::get('/{candidate}', [CandidateController::class, 'show'])->where('candidate', '[0-9]+')->name('show');
    Route::get('/{candidate}/edit', [CandidateController::class, 'edit'])->where('candidate', '[0-9]+')->name('edit');
    Route::put('/{candidate}', [CandidateController::class, 'update'])->where('candidate', '[0-9]+')->name('update');
    Route::delete('/{candidate}', [CandidateController::class, 'destroy'])->where('candidate', '[0-9]+')->name('destroy');

    // Status toggle
    Route::patch('/{candidate}/toggle-status', [CandidateController::class, 'toggleStatus'])
        ->where('candidate', '[0-9]+')->name('toggle-status');

    // Bulk operations
    Route::get('/bulk_upload', [CandidateController::class, 'showBulkUploadForm'])->name('bulk-upload');
    Route::post('/bulk_upload', [CandidateController::class, 'processBulkUpload'])->name('process-bulk-upload');
    Route::post('/bulk_upload_preview', [CandidateController::class, 'saveBulkPreview'])->name('save-bulk-preview');

    // -------------------------
    // Candidate Profile Routes
    // -------------------------
    Route::get('{candidate}/profile', [CandidateProfileController::class, 'show'])->name('profile');
    Route::get('{candidate}/json', [CandidateProfileController::class, 'showJson'])->name('profile.json');

    Route::prefix('{candidate}/profile')->name('profile.')->group(function () {
        // Load sections
        Route::get('load-passport', [CandidateProfileController::class, 'loadPassportUploadSection'])->name('load.passport');
        Route::get('load-medical-schools', [CandidateProfileController::class, 'loadMedicalSchoolSection'])->name('load.medical');
        Route::get('load-institutions', [CandidateProfileController::class, 'loadInstitutionSection'])->name('load.institution');
        Route::get('load-postgrads', [CandidateProfileController::class, 'loadPostgradSection'])->name('load.postgrad');

        // Medical schools
        Route::post('save-medical-school', [CandidateProfileController::class, 'saveMedicalSchool'])->name('save.medical');
        Route::put('update-medical-school/{medicalSchool}', [CandidateProfileController::class, 'updateMedicalSchool'])->name('update.medical');
        Route::delete('delete-medical-school/{medicalSchool}', [CandidateProfileController::class, 'deleteMedicalSchool'])->name('delete.medical');

        
        // Institutions
        Route::post('save-institution', [CandidateProfileController::class, 'saveInstitution'])->name('save.institution');
        Route::put('update-institution/{institution}', [CandidateProfileController::class, 'updateInstitution'])->name('update.institution');
        Route::delete('delete-institution/{institution}', [CandidateProfileController::class, 'deleteInstitution'])->name('delete.institution');

        // Postgraduates
        Route::post('save-postgrad', [CandidateProfileController::class, 'savePostgrad'])->name('save.postgrad');
        Route::put('update-postgrad/{postgrad}', [CandidateProfileController::class, 'updatePostgrad'])->name('update.postgrad');
        Route::delete('delete-postgrad/{postgrad}', [CandidateProfileController::class, 'deletePostgrad'])->name('delete.postgrad');



        // Postgraduate training
        Route::post('save-postgraduate-training', [CandidateProfileController::class, 'savePostgraduateTraining'])->name('save.postgrad.training');
    });
});

    // --------------------
    // 🏫 Faculty
    // --------------------
    Route::get('/faculties', [FacultyController::class, 'index'])->name('faculties.index');
    Route::post('/faculties', [FacultyController::class, 'store'])->name('faculties.store');
    Route::get('/faculties/{id}/edit', [FacultyController::class, 'edit'])->name('faculties.edit');
    Route::put('/faculties/{id}', [FacultyController::class, 'update'])->name('faculties.update');
    Route::delete('/faculties/{id}', [FacultyController::class, 'destroy'])->name('faculties.destroy');
    Route::patch('/faculties/{id}/toggle', [FacultyController::class, 'toggle'])->name('faculties.toggle');

    // --------------------
    // 🧭 Menus
    // --------------------
    Route::get('menus/sync', [MenuController::class, 'syncRoutes'])->name('menus.sync');
    Route::resource('menus', MenuController::class);


    //-----------------------
    // Documents 
    //----------------------
    
    Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
});

