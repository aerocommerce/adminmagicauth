<?php

use Illuminate\Support\Facades\Route;
use Aerocargo\Adminmagicauth\AdminMagicAuthController;

Route::get('/adminmagicauth/index', [AdminMagicAuthController::class, 'index'])->name('adminmagicauth.index');
Route::get('/adminmagicauth/status', [AdminMagicAuthController::class, 'status'])->name('adminmagicauth.status');
Route::post('/adminmagicauth', [AdminMagicAuthController::class, 'send'])->name('adminmagicauth');
Route::get('/adminmagicauth/verify', [AdminMagicAuthController::class, 'verify'])->name('adminmagicauth.verify');
Route::post('/adminmagicauth/login', [AdminMagicAuthController::class, 'verifyAndLogin'])->name('adminmagicauth.login');
