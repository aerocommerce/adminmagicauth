<?php


use Illuminate\Support\Facades\Route;
use Aerocargo\Aeroauth\AeroAuthController;

Route::get('/aeroauth/index', [AeroAuthController::class, 'index'])->name('aeroauth.index');
Route::post('/aeroauth', [AeroAuthController::class, 'send'])->name('aeroauth');
Route::get('/aeroauth/login', [AeroAuthController::class, 'verifyAndLogin'])->name('aeroauth.login');
