<?php

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

// route to fix a bug that redirects to /login when you run migrate:fresh
Route::get('/login', fn () => redirect(route('filament.auth.login')))->name('login');

Route::get('/', fn () => view('welcome'))->name('homepage');
