<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\checkoutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');









// Socialite Routes
Route::get('sign-in-google', [UserController::class, 'google'])->name('user.login.google');
Route::get('auth/google/callback', [UserController::class, 'handleProviderCallback'])->name('user.google.callback');

// 'auth/google/callback' harus sama dengan yang kita daftarin di env-redirect


Route::middleware(['auth'])->group(function () {
    //Checkout Routes
    Route::get('checkout/success', [checkoutController::class, 'success'])->name('checkout.success');
    Route::get('checkout/{camp:slug}', [checkoutController::class, 'create'])->name('checkout.create');
    Route::post('checkout/{camp}', [checkoutController::class, 'store'])->name('checkout.store');

    // user dashboard
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
