<?php

use App\Enums\SystemPermissions;

use App\Http\Controllers\BuyUSDController;
use App\Http\Controllers\BuyUSDTController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealerDashboardController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\SellAdvertController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\TraderController;
use App\Http\Controllers\TradeController;
use App\Models\SellAdvert;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('pages.welcome');
})->name('home');

Route::get('/test-email', function () {
    $user = User::query()->where('email', 'josephajibodu@gmail.com')->first();
    if (! $user) {
        $user = User::query()->first();
    }

    $user->sendEmailVerificationNotification();

    return "Email sent out";
});

Route::get('/download/{file}', function ($file) {
    dd($file);
    return Storage::download($file);
})->name('download.file');

Route::view('/frequently-asked-questions', 'pages.faq')->name('faq');
Route::view('/contact-us', 'pages.contact-us')->name('contact-us');
Route::view('/terms-and-conditions', 'pages.terms')->name('terms-and-conditions');
Route::view('/privacy-policy', 'pages.privacy')->name('privacy-policy');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // HireForex Trader Routes
    Route::get('traders', [TraderController::class, 'index'])->name('traders.index');
    Route::get('traders/{trader}', [TraderController::class, 'show'])->name('traders.show');

    // HireForex Trade Routes
    Route::get('trades/active', [TradeController::class, 'active'])->name('trades.active');
    Route::get('trades/history', [TradeController::class, 'history'])->name('trades.history');
    Route::get('trades/{trade}', [TradeController::class, 'show'])->name('trades.show');

    Route::get('dispute', DisputeController::class)->name('dispute');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/refer-a-friend', 'settings.referrals')->name('settings.referrals');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Top Up Routes
    Route::get('/top-up', [TopUpController::class, 'create'])->name('top-up.create');
    Route::get('/top-up/history', [TopUpController::class, 'history'])->name('top-up.history');

    // Withdrawal Routes
    Route::get('/withdrawal', [WithdrawalController::class, 'create'])->name('withdrawal.create');
    Route::get('/withdrawal/history', [WithdrawalController::class, 'history'])->name('withdrawal.history');
});

require __DIR__.'/auth.php';
