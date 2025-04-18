<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Emails
    Route::get('/emails/folder/{folderId}', [EmailController::class, 'index'])->name('emails.index');
    Route::get('/emails/create', [EmailController::class, 'create'])->name('emails.create');
    Route::post('/emails', [EmailController::class, 'store'])->name('emails.store');
    Route::get('/emails/{id}', [EmailController::class, 'show'])->name('emails.show');
    Route::post('/emails/{id}/move', [EmailController::class, 'move'])->name('emails.move');
    Route::post('/emails/{id}/star', [EmailController::class, 'toggleStar'])->name('emails.star');
    Route::post('/emails/{id}/important', [EmailController::class, 'toggleImportant'])->name('emails.important');
    Route::post('/emails/{id}/trash', [EmailController::class, 'trash'])->name('emails.trash');
    Route::delete('/emails/{id}', [EmailController::class, 'destroy'])->name('emails.destroy');
    
    // Folders
    Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
    Route::get('/folders/create', [FolderController::class, 'create'])->name('folders.create');
    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::get('/folders/{id}/edit', [FolderController::class, 'edit'])->name('folders.edit');
    Route::put('/folders/{id}', [FolderController::class, 'update'])->name('folders.update');
    Route::delete('/folders/{id}', [FolderController::class, 'destroy'])->name('folders.destroy');
    Route::post('/folders/reorder', [FolderController::class, 'reorder'])->name('folders.reorder');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.theme');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/email', [SettingsController::class, 'updateEmailSettings'])->name('settings.email');
});