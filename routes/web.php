<?php

use App\Http\Controllers\DividaController;

Route::get('/', [DividaController::class, 'index'])->name('dividas.index');
Route::get('/dividas/create', [DividaController::class, 'create'])->name('dividas.create');
Route::post('/dividas', [DividaController::class, 'store'])->name('dividas.store');
Route::get('/dividas/{id}/edit', [DividaController::class, 'edit'])->name('dividas.edit');
Route::put('/dividas/{id}', [DividaController::class, 'update'])->name('dividas.update');
Route::post('/dividas/{id_divida}/pagar', [DividaController::class, 'pagar'])->name('dividas.pagar');
Route::delete('/dividas/{id_divida}', [DividaController::class, 'excluir'])->name('dividas.destroy');
