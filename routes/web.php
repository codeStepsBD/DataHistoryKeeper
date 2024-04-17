<?php

use Illuminate\Support\Facades\Route;


Route::prefix('history-keeper')->middleware(config("historyKeeper.middleware"))->group( function (){
    Route::get('/', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'index']);
    Route::post('/store', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'store'])->name('table.store');
});
