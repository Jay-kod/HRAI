<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Volt::route('check-in', 'pages.check-in')->name('check-in');
    Volt::route('assessments/{assessment}', 'pages.assessments.show')->name('assessments.show');
    Volt::route('history', 'pages.history')->name('history');
});

require __DIR__.'/auth.php';
