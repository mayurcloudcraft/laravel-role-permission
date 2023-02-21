<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/auth/login', [LoginController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function (){

    Route::prefix('user')->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::get('/roles', [UserController::class, 'getRoles']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'list']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleAndPermissionController::class, 'listRole']);
        Route::post('/', [RoleAndPermissionController::class, 'storeRole']);
        Route::get('/{role}', [RoleAndPermissionController::class, 'showRole']);
        Route::put('/{role}', [RoleAndPermissionController::class, 'updateRole']);
        Route::delete('/{role}', [RoleAndPermissionController::class, 'destroyRole']);

        Route::post('/assign', [RoleAndPermissionController::class, 'assignRoles']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [RoleAndPermissionController::class, 'listPermission']);
        Route::post('/', [RoleAndPermissionController::class, 'storePermission']);
        Route::get('/{permission}', [RoleAndPermissionController::class, 'showPermission']);
        Route::put('/{permission}', [RoleAndPermissionController::class, 'updatePermission']);
        Route::delete('/{permission}', [RoleAndPermissionController::class, 'destroyPermission']);
    });

});
