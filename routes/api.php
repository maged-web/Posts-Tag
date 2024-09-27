<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Auth routes
Route::controller(AuthController::class)->group(function()
{
Route::post('/register','register');
Route::post('/login','login');
Route::post('/verify','codeVerification')/* ->middleware(['auth:sanctum']) */;

});

//Tags routes
Route::middleware(['auth:sanctum'])->prefix('/tags')->group(function()
{
    Route::get('/',[TagController::class,'index']);
    Route::post('/',[TagController::class,'store']);
    Route::put('/{id}',[TagController::class,'update']);
    Route::delete('/{id}',[TagController::class,'destroy']);
});

//Post routes
Route::middleware(['auth:sanctum'])->prefix('/posts')->group(function()
{
    Route::get('/',[PostController::class,'index']);
    Route::post('/',[PostController::class,'store']);
    Route::get('/trashed', [PostController::class, 'trashed']);
    Route::post('/restore/{id}', [PostController::class, 'restore']);
    Route::get('/{id}',[PostController::class,'show']);
    Route::post('/{id}',[PostController::class,'update']);
    Route::delete('/{id}',[PostController::class,'destroy']);
});



//stats routes
Route::get('/stats',[StatsController::class,'stats']);




