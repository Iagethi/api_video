<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;

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

// Route::get('/upload-file', [VideoUpload::class, 'createForm']);



Route::post('/user', [AuthController::class, 'register']);
Route::post('/auth', [AuthController::class, 'login']);
Route::get('/users', [UserController::class, 'showAll']);
Route::get('/videos', [VideoController::class, 'showAllVideo']);
Route::get('/user/{id}/videos', [VideoController::class, 'showVideoOfUser']);

Route::delete('/video/{id}', [VideoController::class, 'deleteVideo']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::put('/user/{id}', [UserController::class, 'updateUser']);
    Route::delete('/user/{id}', [UserController::class, 'deleteUser']);
    Route::post('/user/{id}/video', [VideoController::class, 'videoUpload'])->name('fileUpload');
    // Route::delete('/video/{id}', [VideoController::class, 'deleteVideo']);
    Route::post('/video/{id}/comment', [CommentController::class, 'createComment']);
});

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'

// ], function ($router) {
//     // Route::post('/login', [AuthController::class, 'login']);
//     // Route::post('/register', [AuthController::class, 'register']);
//     // Route::post('/logout', [AuthController::class, 'logout']);
//     // Route::post('/refresh', [AuthController::class, 'refresh']);
//     Route::get('/user-profile', [AuthController::class, 'userProfile']);
// });
