<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::prefix('user')->group(function (): void {
    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified', 'user'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth', 'user'])
        ->name('profile');

    Route::middleware(['auth', 'verified', 'user'])->group(function (): void {
        Volt::route('check-in', 'pages.check-in')->name('check-in');
        Volt::route('assessments/{assessment}', 'pages.assessments.show')->name('assessments.show');
        Volt::route('history', 'pages.history')->name('history');
        Volt::route('assessments', 'pages.history')->name('assessments.index');
        Volt::route('goals', 'pages.goals')->name('goals');
        Volt::route('wearables', 'pages.wearables')->name('wearables');
        Volt::route('reports/{assessment}', 'pages.reports.show')->name('reports.show');
        Volt::route('assessments/{assessment}/report', 'pages.reports.show')->name('assessments.report');
        Volt::route('feedback', 'pages.feedback')->name('feedback');
    });
});

Volt::route('shared/{token}', 'pages.shared-report')->name('shared.show');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::view('', 'admin.overview')->name('overview');
    Volt::route('model', 'pages.admin.model-performance')->name('model');
    Volt::route('model-performance', 'pages.admin.model-performance')->name('model-performance');
    Volt::route('templates', 'pages.admin.templates')->name('templates');
    Volt::route('rules', 'pages.admin.rules')->name('rules');
    Route::view('feedback', 'admin.feedback')->name('feedback');
    Volt::route('users', 'pages.admin.users')->name('users');
});

require __DIR__.'/auth.php';
