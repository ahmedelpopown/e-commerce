<?php

use App\Livewire\Customer\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::middleware('auth:customer')->group(function(){



// logout
Route::post('/logout',function(){
Route::get('my-account',Dashboard::class)->name('customer.dashboard');


auth('customer')->logout();
request()->session()->invalidate();
request()->session()->regenerateToken();
return redirect('/');
})->name('logout');


});
require __DIR__.'/settings.php';
