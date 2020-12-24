<?php


use Illuminate\Support\Facades\Route;
use Aerocargo\Aeroauth\AeroAuthController;

Route::get('/aeroauth/index', [AeroAuthController::class, 'index'])->name('aeroauth.index');
Route::post('/aeroauth', [AeroAuthController::class, 'send'])->name('aeroauth');
Route::post('/aeroauth/login', [AeroAuthController::class, 'send'])->name('aeroauth.login');
