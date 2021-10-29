<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('app');
});

Route::get('/challan/pdf/{challan_id}', [PDFController::class,"generateChallanPDF"]);
Route::get('/invoice/pdf/{invoice_id}', [PDFController::class,"generateInvoicePDF"]);
Route::get('/directinvoice/pdf/{invoice_id}', [PDFController::class,"generateDirectInvoicePDF"]);
