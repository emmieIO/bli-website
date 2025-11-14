<?php

use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

Route::prefix('certificates')->name('certificates.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('index');
        Route::post('/generate/{course}', [CertificateController::class, 'generate'])->name('generate');
        Route::get('/view/{certificate}', [CertificateController::class, 'view'])->name('view');
    });
    
    // Public certificate verification
    Route::get('/verify/{certificateNumber?}', [CertificateController::class, 'verify'])->name('verify');
});