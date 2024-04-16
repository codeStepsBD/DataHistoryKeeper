<?php

use Illuminate\Support\Facades\Route;


Route::prefix('history-keeper')->group( function (){
    Route::get('/', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'index']);
});
