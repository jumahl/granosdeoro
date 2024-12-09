<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

// Redirigir a la página de catálogo
Route::get('/', function () {
    return redirect()->route('catalog.index');
});

// Ruta del catálogo
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');


