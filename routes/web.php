<?php

use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\LayerController;
use App\Http\Controllers\LayerIndexController;
use App\Http\Controllers\LayupController;
use App\Http\Controllers\LayupIndexController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('overview');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Overview
    Route::get('/overview', [OverviewController::class, 'index'])->name('overview');

    // Suppliers
    // Static routes MUST come before resource (which registers suppliers/{supplier})
    Route::get('suppliers/import-template', [ImportExportController::class, 'template'])->name('suppliers.import-template');
    Route::get('suppliers/export-all', [ImportExportController::class, 'exportAll'])->name('suppliers.export-all');
    Route::resource('suppliers', SupplierController::class);

    // Import / Export
    Route::get('suppliers/{supplier}/export', [ImportExportController::class, 'export'])->name('suppliers.export');
    Route::post('suppliers/{supplier}/import', [ImportExportController::class, 'import'])->name('suppliers.import');
    Route::post('suppliers/{supplier}/import-detect', [ImportExportController::class, 'detect'])->name('suppliers.import-detect');
    Route::post('suppliers/{supplier}/resolve-conflicts', [ImportExportController::class, 'resolveConflicts'])->name('suppliers.resolve-conflicts');
    Route::post('suppliers/{supplier}/import-commit', [ImportExportController::class, 'commitDryRun'])->name('suppliers.import-commit');
    Route::post('suppliers/{supplier}/import-discard', [ImportExportController::class, 'discardDryRun'])->name('suppliers.import-discard');

    // Layups (global index)
    Route::get('/layups', [LayupIndexController::class, 'index'])->name('layups.index');

    // Layups (nested under supplier)
    Route::post('suppliers/{supplier}/layups/{layup}/duplicate', [LayupController::class, 'duplicate'])->name('suppliers.layups.duplicate');
    Route::post('suppliers/{supplier}/layups/{layup}/activate', [LayupController::class, 'activate'])->name('suppliers.layups.activate');
    Route::post('suppliers/{supplier}/layups/{layup}/archive', [LayupController::class, 'archive'])->name('suppliers.layups.archive');
    Route::resource('suppliers.layups', LayupController::class)->except(['index']);

    // Layers (global index)
    Route::get('/layers', [LayerIndexController::class, 'index'])->name('layers.index');

    // Layers (nested under supplier + layup)
    Route::patch('suppliers/{supplier}/layups/{layup}/layers/reorder', [LayerController::class, 'reorder'])->name('suppliers.layups.layers.reorder');
    Route::resource('suppliers.layups.layers', LayerController::class)->except(['index', 'show']);

    // Settings (profile)
    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
