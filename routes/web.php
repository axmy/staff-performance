<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\AttendanceImportController;
use App\Http\Controllers\StaffActionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.attempt')
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

    // Staff Management
    Route::resource('staff', StaffController::class);
    Route::get('/staff/{staff}/history', [StaffController::class, 'history'])->name('staff.history');
    Route::post('/staff/import', [StaffController::class, 'import'])->name('staff.import');

    // Training Sessions
    Route::resource('training', TrainingController::class);
    Route::get('/training/{training}/attendance', [TrainingController::class, 'attendance'])
        ->name('training.attendance');
    Route::post('/training/{training}/attendance', [TrainingController::class, 'saveAttendance'])
        ->name('training.attendance.save');
    Route::post('/training/{training}/notify', [TrainingController::class, 'sendNotifications'])
        ->name('training.notify');
    Route::patch('/training/{training}/status', [TrainingController::class, 'updateStatus'])
        ->name('training.status');

    // Attendance Import
    Route::get('/attendance', [AttendanceImportController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/import', [AttendanceImportController::class, 'showImport'])->name('attendance.import');
    Route::post('/attendance/import', [AttendanceImportController::class, 'import'])->name('attendance.import.store');
    Route::get('/attendance/template', [AttendanceImportController::class, 'downloadTemplate'])
        ->name('attendance.template');
    Route::get('/attendance/history', [AttendanceImportController::class, 'history'])->name('attendance.history');
    Route::get('/attendance/{import}', [AttendanceImportController::class, 'show'])->name('attendance.show');

    // Staff Actions
    Route::resource('actions', StaffActionController::class);
    Route::patch('/actions/{action}/status', [StaffActionController::class, 'updateStatus'])
        ->name('actions.status');
    Route::post('/actions/{action}/documents', [StaffActionController::class, 'uploadDocument'])
        ->name('actions.documents.upload');
    Route::delete('/actions/documents/{document}', [StaffActionController::class, 'deleteDocument'])
        ->name('actions.documents.delete');
    Route::post('/actions/{action}/feedback', [StaffActionController::class, 'addFeedback'])
        ->name('actions.feedback.add');
    Route::post('/actions/process-triggers', [StaffActionController::class, 'processTriggers'])
        ->name('actions.process-triggers');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/staff-performance', [ReportController::class, 'staffPerformance'])
        ->name('reports.staff-performance');
    Route::get('/reports/attendance-summary', [ReportController::class, 'attendanceSummary'])
        ->name('reports.attendance-summary');
    Route::get('/reports/training-summary', [ReportController::class, 'trainingSummary'])
        ->name('reports.training-summary');
    Route::get('/reports/action-report', [ReportController::class, 'actionReport'])
        ->name('reports.action-report');

    // Report Exports
    Route::get('/reports/export/staff-performance', [ReportController::class, 'exportStaffPerformance'])
        ->name('reports.export.staff-performance');
    Route::get('/reports/export/attendance-summary', [ReportController::class, 'exportAttendanceSummary'])
        ->name('reports.export.attendance-summary');
    Route::get('/reports/export/training-summary', [ReportController::class, 'exportTrainingSummary'])
        ->name('reports.export.training-summary');
    Route::get('/reports/export/action-report', [ReportController::class, 'exportActionReport'])
        ->name('reports.export.action-report');

    // PDF Exports
    Route::get('/reports/pdf/staff/{staff}', [ReportController::class, 'staffPdf'])
        ->name('reports.pdf.staff');
    Route::get('/reports/pdf/attendance', [ReportController::class, 'attendancePdf'])
        ->name('reports.pdf.attendance');

    // Settings (Admin only)
    Route::middleware(['role:admin'])->prefix('settings')->name('settings.')->group(function () {
        // Action Types
        Route::get('/action-types', [SettingsController::class, 'actionTypes'])->name('action-types');
        Route::post('/action-types', [SettingsController::class, 'storeActionType'])->name('action-types.store');
        Route::put('/action-types/{actionType}', [SettingsController::class, 'updateActionType'])
            ->name('action-types.update');
        Route::delete('/action-types/{actionType}', [SettingsController::class, 'destroyActionType'])
            ->name('action-types.destroy');

        // Action Triggers
        Route::get('/triggers', [SettingsController::class, 'triggers'])->name('triggers');
        Route::post('/triggers', [SettingsController::class, 'storeTrigger'])->name('triggers.store');
        Route::put('/triggers/{trigger}', [SettingsController::class, 'updateTrigger'])->name('triggers.update');
        Route::delete('/triggers/{trigger}', [SettingsController::class, 'destroyTrigger'])->name('triggers.destroy');
        Route::patch('/triggers/{trigger}/toggle', [SettingsController::class, 'toggleTrigger'])
            ->name('triggers.toggle');

        // Users Management
        Route::get('/users', [SettingsController::class, 'users'])->name('users');
        Route::put('/users/{user}/role', [SettingsController::class, 'updateUserRole'])->name('users.role');
        Route::patch('/users/{user}/toggle', [SettingsController::class, 'toggleUser'])->name('users.toggle');

        // Departments & Designations
        Route::get('/departments', [SettingsController::class, 'departments'])->name('departments');
        Route::post('/departments', [SettingsController::class, 'storeDepartment'])->name('departments.store');
        Route::put('/departments/{department}', [SettingsController::class, 'updateDepartment'])
            ->name('departments.update');
        Route::delete('/departments/{department}', [SettingsController::class, 'destroyDepartment'])
            ->name('departments.destroy');

        Route::get('/designations', [SettingsController::class, 'designations'])->name('designations');
        Route::post('/designations', [SettingsController::class, 'storeDesignation'])->name('designations.store');
        Route::put('/designations/{designation}', [SettingsController::class, 'updateDesignation'])
            ->name('designations.update');
        Route::delete('/designations/{designation}', [SettingsController::class, 'destroyDesignation'])
            ->name('designations.destroy');
    });
});
