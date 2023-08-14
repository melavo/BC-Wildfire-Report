<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\DatafilterController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('/v1')->namespace('Api\V1')->group(function(){
        Route::post('/filter',[DatafilterController::class, 'index']);
    });
});
