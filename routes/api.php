<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InwardQualityController;
use App\Http\Controllers\SellQualityController;
use App\Http\Controllers\BrokerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\BankDetailsController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\ExpenseCategoryControler;
use App\Http\Controllers\ExpenseController;

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

Route::get('/inwardqualitycategories',[InwardQualityController::class,'getQualityCategories']);
Route::get('/inwardqualities',[InwardQualityController::class,'getAllInwardQualities']);
Route::prefix('/inwardquality')->group(function(){
    Route::post('/insert',[InwardQualityController::class,'insertInwardQuality']);
    Route::put('/update/{inward_quality_id}',[InwardQualityController::class,'updateInwardQuality']);
    Route::delete('/delete/{inward_quality_id}',[InwardQualityController::class,'deleteInwardQuality']);
});

Route::get('/sellqualitycategories',[SellQualityController::class,'getQualityCategories']);
Route::get('/sellqualities',[SellQualityController::class,'getAllSellQualities']);
Route::prefix('/sellquality')->group(function(){
    Route::post('/insert',[SellQualityController::class,'insertSellQuality']);
    Route::put('/update/{sell_quality_id}',[SellQualityController::class,'updateSellQuality']);
    Route::delete('/delete/{sell_quality_id}',[SellQualityController::class,'deleteSellQuality']);
});

Route::get('/brokers',[BrokerController::class,'getAllBrokers']);
Route::prefix('/broker')->group(function(){
    Route::post('/insert',[BrokerController::class,'insertBroker']);
    Route::put('/update/{broker_id}',[BrokerController::class,'updateBroker']);
    Route::delete('/delete/{broker_id}',[BrokerController::class,'deleteBroker']);
});

Route::post("/customer", [CustomerController::class, "addNewCustomer"]);
Route::get("/customers", [CustomerController::class, "getAllCustomers"]);
Route::put("/customer/update/{customer_id}", [CustomerController::class, "updateCustomer"]);
Route::delete("/customer/delete/{customer_id}", [CustomerController::class, "deleteCustomer"]);

Route::post("/vendor", [VendorController::class, "addNewVendor"]);
Route::get("/vendors", [VendorController::class, "getAllVendors"]);
Route::put("/vendor/update/{vendor_id}", [VendorController::class, "updateVendor"]);
Route::delete("/vendor/delete/{vendor_id}", [VendorController::class, "deleteVendor"]);

Route::prefix('/bankdetails')->group(function(){
    Route::post('/insert',[BankDetailsController::class,'insertBankDetails']);
});
Route::prefix('/credit')->group(function(){
    Route::post('/insert',[CreditController::class,'insertCredit']);
});

Route::get("/bankdetails", [BankDetailsController::class, "getAllBankDetails"]);
Route::get("/credits", [CreditController::class, "getAllCreditDetails"]);

Route::put("/bankdetail/update/{bank_details_id}", [BankDetailsController::class, "updateBankDetail"]);
Route::put("/credit/update/{credit_id}", [CreditController::class, "updateCreditDetail"]);

Route::delete("/bankdetail/delete/{bank_details_id}", [BankDetailsController::class, "deleteBankDetail"]);
Route::delete("/credit/delete/{credit_id}", [CreditController::class, "deleteDetail"]);


Route::get("/expensecategories", [ExpenseCategoryControler::class, "getAllExpenseCategories"]);
Route::get("/expensecategorieslist", [ExpenseCategoryControler::class, "getAllExpenseCategoriesList"]);
Route::put("/expensecategory/update/{expense_category_id}", [ExpenseCategoryControler::class, "updateExpenseCategory"]);
Route::post("/expensecategory", [ExpenseCategoryControler::class, "addNewCategory"]);
Route::delete("/expensecategory/{expense_category_id}", [ExpenseCategoryControler::class, "deleteCategory"]);


Route::post("/expense", [ExpenseController::class, "addExpense"]);
Route::get("/expense", [ExpenseController::class, "getExpenses"]);
Route::put("/expense", [ExpenseController::class, "updateExpenses"]);
Route::delete("/expense/{expenseid}", [ExpenseController::class, "deleteExpenses"]);
Route::get("/totalexpenseamount", [ExpenseController::class, "getTotalAmountOfGivenDateRangeAndCategory"]);
