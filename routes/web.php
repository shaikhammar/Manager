<?php

use App\Models\Business;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BusinessController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $business_id = session('business_id');
        if (!$business_id) {
            return redirect()->route('business.index');
        }
        $business = Business::find($business_id);
        return Inertia::render('dashboard', [
            'business' => $business,
        ]);
    })->name('dashboard');
    Route::resource('business', BusinessController::class);
    Route::post('business/switch/{business}', [BusinessController::class, 'switch'])->name('business.switch');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
