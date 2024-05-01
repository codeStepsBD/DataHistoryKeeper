<?php

use Illuminate\Support\Facades\Route;


Route::prefix('history-keeper')->middleware(config("historyKeeper.middleware"))->group( function (){
    Route::get('/', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryTableDataShowController::class,'index'])->name('history-keeper.index');
    Route::prefix('configuration')->group( function (){
        Route::get('/create', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'index'])->name('history-keeper.configuration.create');
        Route::post('/store', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'store'])->name('history-keeper.configuration.store');
        Route::get('/edit/{id}', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'edit'])->name('history-keeper.configuration.edit');
        Route::post('/update/{id}', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'update'])->name('history-keeper.configuration.update');
    });


    Route::get('/url-command/{value?}', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryTableCommandUrlController::class,'commandUrl']);
    Route::post('/trash', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryTableDataShowController::class,'delete'])->name('history-keeper.trash');
});
