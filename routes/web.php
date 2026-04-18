<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PosDemoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return redirect()->route('administration.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/locale', function (Request $request) {
    $supportedLocales = array_keys(config('app.supported_locales', []));
    $locale = $request->string('locale')->toString();

    abort_unless(in_array($locale, $supportedLocales, true), 404);

    $request->session()->put('locale', $locale);

    return back();
})->name('locale.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/pos-demo', [PosDemoController::class, 'index'])->name('pos.demo');
});

require __DIR__.'/auth.php';
