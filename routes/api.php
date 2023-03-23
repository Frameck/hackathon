<?php

use App\Helpers\RouteHelper;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Services\KarmaUserService;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')
    ->group(function () {
        RouteHelper::registerApiRoutes();

        Route::get('decay', fn () => (
            response()->json(
                KarmaUserService::calculateDecay(request('user_id'))
            )
        ));
    });
