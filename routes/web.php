<?php

use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;

Route::get('/', function () {
    return redirect('admin');
});

// Route::prefix('admin')->middleware(['auth', 'web'])->group(function () {
//     Filament::registerRoutes();
// });

// Route::prefix('admin')->middleware(['auth', 'web'])->group(function () {
//     Filament::routes(); // Use Filament::routes(), not registerRoutes
// });

// Route::get('/login', function () {
//     return response()->json(['message' => 'Please log in'], 401);
// })->name('login');

// Route::get('/register', function () {
//     return response()->json(['message' => 'Please register'], 401);
// })->name('register');