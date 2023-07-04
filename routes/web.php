<?php

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

use Illuminate\Support\Facades\Route;
use SoipoServices\Cms\Http\Controllers\PageController;
use SoipoServices\Cms\Http\Controllers\BlogController;

Route::get('/', [PageController::class, 'home'])->name('pages.home');
Route::get('/search', [PageController::class, 'search'])->name('pages.search');
Route::get('/about-us', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact-us', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}',  [BlogController::class, 'single'])->name('blog.single');
Route::get('/blog/{year}/{month}/{day}/{slug}',  [BlogController::class, 'date'])->name('blog.date');
Route::get('/category/{slug}',  [BlogController::class, 'category'])->name('blog.category');
Route::get('/preview/{slug}', [PageController::class, 'preview'] )->name('pages.preview');
Route::get('{slug}',  [PageController::class, 'show'])->where(['slug' => '[a-z0-9-]+', 'slug' => '^(?!'.substr(config('nova.path'),1).'|nova).*$'])->name('pages.show');
