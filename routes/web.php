<?php

use Illuminate\Support\Facades\Route;


Route::prefix('history-keeper')->middleware(config("historyKeeper.middleware"))->group( function (){
    Route::get('/', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'index']);
    Route::post('/store', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'store'])->name('table.store');
    Route::get('/edit/{id}', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'edit'])->name('history.table.edit');
    Route::post('/update/{id}', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryKeeperController::class,'update'])->name('history.table.update');


    Route::get('/url-command/{value?}', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryTableCommandUrlController::class,'commandUrl']);

    Route::get('/history-table-data/', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryTableDataShowController::class,'index'])->name('history-table-data-show');
    Route::post('/history-table-data-delete/', [\CodeStepsBD\HistoryKeeper\Controllers\HistoryTableDataShowController::class,'delete'])->name('history-table-data-delete');
});
