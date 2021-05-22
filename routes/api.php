<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CorreiosController;
use App\Http\Controllers\Admin\Api\PageApiController as AdminPageApiController;
use App\Http\Controllers\Admin\Api\UserApiController as AdminUserApiController;
use App\Http\Controllers\Admin\Api\BrandApiController as AdminBrandApiController;
use App\Http\Controllers\Admin\Api\CategoryApiController as AdminCategoryApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('painel')->group(function() {
    Route::get('users/get', [AdminUserApiController::class, 'get_users'])->name('getUsers');
    Route::put('users/update/{id}', [AdminUserApiController::class, 'update'])->name('users.update');
    Route::post('users/create/user', [AdminUserApiController::class, 'create_user'])->name('users.add');
    Route::delete('users/destroy/{id}', [AdminUserApiController::class, 'destroy'])->name('users.destroy');

    Route::get('pages/get', [AdminPageApiController::class, 'get_pages'])->name('getPages');
    Route::put('pages/update/{id}', [AdminPageApiController::class, 'update'])->name('pages.update');
    Route::post('pages/create/user', [AdminPageApiController::class, 'create_page'])->name('pages.add');
    Route::post('pages/imageupload', [AdminPageApiController::class, 'imageupload'])->name('imageupload');
    Route::delete('pages/destroy/{id}', [AdminPageApiController::class, 'destroy'])->name('pages.destroy');

    Route::get('brands/get', [AdminBrandApiController::class, 'get_brands'])->name('getBrands');
    Route::post('brands/update/{id}', [AdminBrandApiController::class, 'update'])->name('brands.update');
    Route::post('brands/create/user', [AdminBrandApiController::class, 'store'])->name('brands.add');
    Route::delete('brands/destroy/{id}', [AdminBrandApiController::class, 'destroy'])->name('brands.destroy');

    Route::get('categories/get', [AdminCategoryApiController::class, 'get_categories'])->name('getCategories');
    Route::post('categories/update/{id}', [AdminCategoryApiController::class, 'update'])->name('categories.update');
    Route::post('categories/create/user', [AdminCategoryApiController::class, 'store'])->name('categories.add');
    Route::delete('categories/destroy/{id}', [AdminCategoryApiController::class, 'destroy'])->name('categories.destroy');
});

Route::get('cep', [CorreiosController::class, 'cep'])->name('cep');
