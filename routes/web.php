<?php

use App\Http\Controllers\ArquivoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArquivoController::class, 'index'])->name('home');

Route::post('/files/upload', [ArquivoController::class, 'upload'])->name('files.upload');
Route::get('/files/download/{filename}', [ArquivoController::class, 'download'])->name('files.download');
