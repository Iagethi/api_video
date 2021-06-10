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


Route::post('/user', [AuthController::class, 'register']);
Route::post('/auth', [AuthController::class, 'login']);
Route::get('/users', [UserController::class, 'showAll']);
Route::get('/videos', [VideoController::class, 'showAllVideo']);
Route::get('/user/{id}/videos', [VideoController::class, 'showVideoOfUser']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::put('/user/{id}', [UserController::class, 'updateUser']);
    Route::delete('/user/{id}', [UserController::class, 'deleteUser']);
    Route::post('/user/{id}/video', [VideoController::class, 'videoUpload'])->name('fileUpload');
    Route::delete('/video/{id}', [VideoController::class, 'deleteVideo']);
    Route::post('/video/{id}/comment', [CommentController::class, 'createComment']);
    Route::put('/video/{id}', [VideoController::class, 'updateVideo']);
    Route::get('/video/{id}/comments', [CommentController::class, 'showVideoComments']);
});


Route::fallback(function(){
    return response()->json(['message' => 'Not Found!'], 404);
});

