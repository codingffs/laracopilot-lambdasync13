<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DealController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ActivityLogController;

// Public
Route::get('/', function () { return view('welcome'); });

// Auth
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Users
Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

// Leads
Route::get('/leads', [LeadController::class, 'index'])->name('admin.leads.index');
Route::get('/leads/pipeline', [LeadController::class, 'pipeline'])->name('admin.leads.pipeline');
Route::get('/leads/create', [LeadController::class, 'create'])->name('admin.leads.create');
Route::post('/leads', [LeadController::class, 'store'])->name('admin.leads.store');
Route::get('/leads/{id}', [LeadController::class, 'show'])->name('admin.leads.show');
Route::get('/leads/{id}/edit', [LeadController::class, 'edit'])->name('admin.leads.edit');
Route::put('/leads/{id}', [LeadController::class, 'update'])->name('admin.leads.update');
Route::delete('/leads/{id}', [LeadController::class, 'destroy'])->name('admin.leads.destroy');
Route::post('/leads/{id}/assign', [LeadController::class, 'assign'])->name('admin.leads.assign');
Route::post('/leads/{id}/convert', [LeadController::class, 'convert'])->name('admin.leads.convert');
Route::get('/leads/export/csv', [LeadController::class, 'export'])->name('admin.leads.export');

// Customers
Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
Route::get('/customers/create', [CustomerController::class, 'create'])->name('admin.customers.create');
Route::post('/customers', [CustomerController::class, 'store'])->name('admin.customers.store');
Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('admin.customers.edit');
Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('admin.customers.update');
Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');

// Deals
Route::get('/deals', [DealController::class, 'index'])->name('admin.deals.index');
Route::get('/deals/create', [DealController::class, 'create'])->name('admin.deals.create');
Route::post('/deals', [DealController::class, 'store'])->name('admin.deals.store');
Route::get('/deals/{id}', [DealController::class, 'show'])->name('admin.deals.show');
Route::get('/deals/{id}/edit', [DealController::class, 'edit'])->name('admin.deals.edit');
Route::put('/deals/{id}', [DealController::class, 'update'])->name('admin.deals.update');
Route::delete('/deals/{id}', [DealController::class, 'destroy'])->name('admin.deals.destroy');

// Tasks
Route::get('/tasks', [TaskController::class, 'index'])->name('admin.tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('admin.tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('admin.tasks.store');
Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('admin.tasks.show');
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('admin.tasks.edit');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('admin.tasks.update');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');
Route::post('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('admin.tasks.complete');

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
Route::get('/reports/sales', [ReportController::class, 'sales'])->name('admin.reports.sales');
Route::get('/reports/leads', [ReportController::class, 'leads'])->name('admin.reports.leads');
Route::get('/reports/conversion', [ReportController::class, 'conversion'])->name('admin.reports.conversion');

// Activity Logs
Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity.index');