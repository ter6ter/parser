<?php

declare(strict_types=1);


use App\Orchid\Screens\PlatformScreen;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\NewsScreen;
use App\Orchid\Screens\NewsEditScreen;
use App\Orchid\Screens\LogListScreen;
/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

Route::screen('news', NewsScreen::class)->name('platform.news');
Route::screen('edit/{item}', NewsEditScreen::class)->name('platform.news.edit');

Route::screen('logs', LogListScreen::class)->name('platform.logs');
