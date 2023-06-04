<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/getAll', [AuthController::class, 'getAllUsers']);
    Route::post('/getUser', [AuthController::class, 'getUser']);
    Route::post('/update', [AuthController::class, 'updateUser']);

    Route::get('/apply-job/{userId}', [ApiController::class, 'jobByUserId']);
    Route::post('/apply-job', [ApiController::class, 'applyJob']);
});

Route::prefix('job')->group(function () {
    Route::get('/', [ApiController::class, 'index']);
});