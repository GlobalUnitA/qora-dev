<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\KakaoController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\LanguageController;

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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/kakao/login', [KakaoController::class, 'redirectToKakao'])->name('kakao.login');
Route::get('/kakao/logout', [KakaoController::class, 'logoutFromKakao'])->name('kakao.logout');
Route::get('/kakao/callback', [KakaoController::class, 'handleKakaoCallback'])->name('kakao.callback');
Route::post('/kakao/get-access-token', [KakaoController::class, 'getAccessToken']);
Route::post('/kakao/send-message', [KakaoController::class, 'handleEvent']);

Route::post('/uploads', [UploadController::class, 'upload']);

Route::get('/crypto-prices', function () {
    if (!Storage::disk('local')->exists('crypto_prices.json')) {
        return response()->json(['error' => 'Price data not available'], 404);
    }

    $prices = json_decode(Storage::disk('local')->get('crypto_prices.json'), true);

    return response()->json($prices);
});

