<?php
use Illuminate\Support\Facades\Route;

Route::get('/ai-tips', [App\Http\Controllers\Admin\AiTipsController::class, 'getTips'])->name('admin.ai-tips');
