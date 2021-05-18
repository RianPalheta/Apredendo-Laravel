<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CorreiosController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\UserApiController as AdminUserApiController;

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

    Route::get('pages/get', [AdminPageController::class, 'get_pages'])->name('getPages');
    Route::put('pages/update/{id}', [AdminPageController::class, 'update'])->name('pages.update');
    Route::post('pages/create/user', [AdminPageController::class, 'create_user'])->name('pages.add');
    Route::delete('pages/destroy/{id}', [AdminPageController::class, 'destroy'])->name('pages.destroy');
});

Route::get('cep', [CorreiosController::class, 'cep'])->name('cep');
