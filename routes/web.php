<?php

use App\Http\Controllers\FacturaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/gdo');


});
Route::get('/factura/{pedidoId}', [FacturaController::class, 'generarFactura'])->name('factura.generar');
