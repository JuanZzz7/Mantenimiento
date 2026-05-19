<?php

use App\Http\Controllers\LocaleController;

Route::get('/switch-locale/{locale}', [LocaleController::class, 'switchLocale'])->name('switch.locale');

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\MaintenancePlanController;
use App\Http\Controllers\SpareController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated ─────────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activos (todos los autenticados pueden ver, solo admin puede eliminar)
    Route::resource('plant-assets', AssetController::class)->parameters([
        'plant-assets' => 'asset'
    ])->names('assets');

    // Órdenes de Trabajo
    Route::resource('work-orders', WorkOrderController::class)->parameters([
        'work-orders' => 'workOrder'
    ]);
    Route::post('work-orders/{workOrder}/start', [WorkOrderController::class, 'start'])->name('work-orders.start');
    Route::post('work-orders/{workOrder}/complete', [WorkOrderController::class, 'complete'])->name('work-orders.complete');
    Route::post('work-orders/{workOrder}/spares',        [WorkOrderController::class, 'addSpare'])->name('work-orders.spares.add');
    Route::delete('work-orders/{workOrder}/spares/{spare}', [WorkOrderController::class, 'removeSpare'])->name('work-orders.spares.remove');

    // Planes de mantenimiento
    Route::resource('maintenance-plans', MaintenancePlanController::class)->parameters([
        'maintenance-plans' => 'maintenancePlan'
    ]);
    Route::post('maintenance-plans/{maintenancePlan}/generate', [MaintenancePlanController::class, 'generate'])
         ->name('maintenance-plans.generate');

    // Repuestos
    Route::resource('spares', SpareController::class)->parameters([
        'spares' => 'spare'
    ]);
    Route::post('spares/{spare}/adjust-stock', [SpareController::class, 'adjustStock'])->name('spares.adjust-stock');

    // Reportes
    Route::get('/reports',         [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf',     [ReportController::class, 'exportPdf'])->name('reports.pdf');

    // Usuarios (solo Admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class)->parameters([
            'users' => 'user'
        ])->except(['show']);
    });
});
