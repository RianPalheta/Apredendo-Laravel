<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\PageController as SitePageController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OptionsController as AdminOptionsController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\Auth\RegisterController as AdminRegisterController;

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
});

Route::get('/auth/user', [ApiTokenController::class, 'update'])->name('auth.update');

Route::prefix('painel')->group(function() {
    Route::get('/', [AdminHomeController::class, 'index'])->name('painel');

    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::get('login', [AdminLoginController::class, 'index'])->name('login');
    Route::post('login', [AdminLoginController::class, 'authenticate'])->name('auth.login');

    Route::get('register', [AdminRegisterController::class, 'index'])->name('register');
    Route::post('register', [AdminRegisterController::class, 'register'])->name('auth.register');

    Route::get('products', [AdminProductController::class, 'index'])->name('products.list');
    Route::get('products/add', [AdminProductController::class, 'create'])->name('products.create');
    Route::get('products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');

    Route::get('options', [AdminOptionsController::class, 'index'])->name('options.list');

    Route::get('users', [AdminUserController::class, 'index'])->name('users.list');
    Route::get('users/add', [AdminUserController::class, 'create'])->name('users.create');
    Route::get('users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');

    Route::get('pages', [AdminPageController::class, 'index'])->name('pages.list');
    Route::get('pages/add', [AdminPageController::class, 'create'])->name('pages.create');
    Route::get('pages/{id}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');

    Route::get('brands', [AdminBrandController::class, 'index'])->name('brands.list');
    Route::get('brands/add', [AdminBrandController::class, 'create'])->name('brands.create');
    Route::get('brands/{id}/edit', [AdminBrandController::class, 'edit'])->name('brands.edit');

    Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.list');
    Route::get('categories/add', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::get('categories/{id}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');

    Route::get('gallery', [AdminGalleryController::class, 'index'])->name('gallery.list');
    Route::get('gallery/add', [AdminGalleryController::class, 'create'])->name('gallery.create');
    Route::get('gallery/{id}/edit', [AdminGalleryController::class, 'edit'])->name('gallery.edit');

    Route::get('settings', [AdminSettingsController::class, 'index']);
    Route::post('settings/update', [AdminSettingsController::class, 'update'])->name('settings.edit');
});

Route::fallback([SitePageController::class, 'index']);
