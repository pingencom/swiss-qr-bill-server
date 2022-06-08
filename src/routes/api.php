<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::post(
    '/generate',
    [Controller::class, 'generate']
)->name('generate');
