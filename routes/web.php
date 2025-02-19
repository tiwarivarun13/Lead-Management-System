<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leads/upload', [LeadController::class, 'showUploadForm'])->name('leads.upload');
    Route::post('/leads/import', [LeadController::class, 'import'])->name('leads.import');
    Route::get('/leads/{id}/edit', [LeadController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{id}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/{id}/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
    Route::get('/leads/export-excel', [LeadController::class, 'exportLeadsToExcel'])->name('leads.export.excel');

});



require __DIR__.'/auth.php';
