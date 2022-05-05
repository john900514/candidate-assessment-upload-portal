<?php

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

Route::group(['middleware' => ['api', 'auth.basic']], function() {
    Route::post('/user/login', \App\Actions\InboundAPI\Users\Auth\BasicLoginAction::class);
});

Route::group(['middleware' => ['api', 'auth:sanctum']], function() {
    Route::post('/user/verify-token', \App\Actions\InboundAPI\Users\Auth\VerifyAccessToken::class);

    Route::group(['prefix' => 'assets'], function() {
        Route::group(['prefix' => 'files'], function() {
            Route::group(['prefix' => 'source-code'], function() {
                Route::get('/', \App\Actions\InboundAPI\Assets\Files\SourceCode\GetUserScopedAvailableSourceCodes::class);
                Route::post('/download', \App\Actions\InboundAPI\Assets\Files\SourceCode\DownloadSourceCode::class);
            });
        });
    });

    Route::group(['prefix' => 'candidates'], function() {
        Route::group(['prefix' => 'assessments'], function() {
            Route::get('/', \App\Actions\InboundAPI\Candidates\Assessments\GetUserScopedAvailableAssessments::class);
        });
    });
});

