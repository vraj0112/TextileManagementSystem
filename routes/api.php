<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InwardQualityController;
use App\Http\Controllers\InwardController;
use App\Http\Controllers\SellQualityController;
use App\Http\Controllers\BrokerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\BankDetailsController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\ExpenseCategoryControler;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ChallanController;
use App\Http\Controllers\InvoiceController;

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
Route::get('/productqualitycategories',[InwardQualityController::class,'getProductQualityCategories']);
Route::get('/inwardqualityofcategories/{inward_quality_category_id}',[InwardQualityController::class,'getSelectedProductQualities']);
Route::prefix('/inwardquality')->group(function(){
    Route::post('/insert',[InwardQualityController::class,'insertInwardQuality']);
    Route::put('/update/{inward_quality_id}',[InwardQualityController::class,'updateInwardQuality']);
    Route::delete('/delete/{inward_quality_id}',[InwardQualityController::class,'deleteInwardQuality']);
});

Route::post("/inward", [InwardController::class, "addNewInward"]);
Route::get("/inwards", [InwardController::class, "getAllInwards"]);
Route::get("/inward/view/{inward_mst_id}", [InwardController::class, "viewInwardDetails"]);
Route::put("/inward/update/{inward_details_id}", [InwardController::class, "updateInward"]);
Route::delete("/inward/delete/{inward_details_id}", [InwardController::class, "deleteInward"]);
Route::delete('/inward/delete/{inward_mst_id}', [InwardController::class, 'deleteInward']);


Route::get('/sellqualitycategories',[SellQualityController::class,'getQualityCategories']);
Route::get('/sellqualities',[SellQualityController::class,'getAllSellQualities']);
Route::prefix('/sellquality')->group(function(){
    Route::post('/insert',[SellQualityController::class,'insertSellQuality']);
    Route::put('/update/{sell_quality_id}',[SellQualityController::class,'updateSellQuality']);
    Route::delete('/delete/{sell_quality_id}',[SellQualityController::class,'deleteSellQuality']);
});
Route::get("/sellqualityofcategory/{sell_quality_category_id}", [SellQualityController::class, "getSellQualityOfGivenCategory"]);

Route::get('/brokers',[BrokerController::class,'getAllBrokers']);
Route::get('/getBrokers',[BrokerController::class,'getBrokers']);
Route::prefix('/broker')->group(function(){
    Route::post('/insert',[BrokerController::class,'insertBroker']);
    Route::put('/update/{broker_id}',[BrokerController::class,'updateBroker']);
    Route::delete('/delete/{broker_id}',[BrokerController::class,'deleteBroker']);
});
Route::get('/brokerslist', [BrokerController::class, "getBrokersList"]);

Route::post("/customer", [CustomerController::class, "addNewCustomer"]);
Route::get("/customers", [CustomerController::class, "getAllCustomers"]);
Route::put("/customer/update/{customer_id}", [CustomerController::class, "updateCustomer"]);
Route::delete("/customer/delete/{customer_id}", [CustomerController::class, "deleteCustomer"]);
Route::get("/customerlist", [CustomerController::class, "getCustomersList"]);
Route::get("/selectedcustomerdata/{customer_id}", [CustomerController::class, "getSelectedCustomerData"]);

Route::post("/vendor", [VendorController::class, "addNewVendor"]);
Route::get("/vendors", [VendorController::class, "getAllVendors"]);
Route::get('/vendorcompanies',[VendorController::class,'getCompanyNames']);
Route::get('/selectedvendordata/{vendor_id}',[VendorController::class,'getSelectedVendorData']);
Route::put("/vendor/update/{vendor_id}", [VendorController::class, "updateVendor"]);
Route::delete("/vendor/delete/{vendor_id}", [VendorController::class, "deleteVendor"]);

Route::prefix('/bankdetails')->group(function(){
    Route::post('/insert',[BankDetailsController::class,'insertBankDetails']);
});
Route::get('/bankinfo', [BankDetailsController::class, 'getBankInfo']);
Route::get('/bankbranch/{bankId}', [BankDetailsController::class, 'getBankBranch']);

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


Route::get("/getfinancialyear/{challandate}",[ChallanController::class, "getFinancialYearOfChallanDate"]);
Route::get("/verifychallan/{challanno}/{fromdate}/{todate}",[ChallanController::class, "verifyChallanNumber"]);
Route::post("/challan/insert", [ChallanController::class, "addNewChallan"]);
Route::get('/challans', [ChallanController::class, "getChallans"]);
Route::get('/challan/{challanid}', [ChallanController::class, "getChallanDataOfChallanId"]);
Route::put('/challan', [ChallanController::class, "updateChallan"]);
Route::delete('/challan/{challanId}', [ChallanController::class, "deleteChallan"]);


Route::post('/directinvoice', [InvoiceController::class, 'addNewDirectInvoice']);
Route::get('/directinvoices', [InvoiceController::class, 'getAllDirectInvoices']);
Route::get('/directinvoice/{invoiceid}', [InvoiceController::class, "getDirectInvoiceOfInvoiceId"]);
Route::put('/directinvoice', [InvoiceController::class, "updateDirectInvoice"]);
Route::delete('/directinvoice/{invoiceMstId}', [InvoiceController::class, "deleteDirectInvoice"]);

Route::get("/invoice/getfinancialyear/{invoiceno}",[InvoiceController::class, "getFromInvoiceNo"]);
Route::get("/invoice/challandata/{invoiceno}/{fromdate}/{todate}", [InvoiceController::class, "getFromInvoiceNoAndFinancialYear"]);
Route::get("/getstate/{code}", [InvoiceController::class, "getStateFromCode"]);
Route::post("/invoice/insert", [InvoiceController::class, "addNewInvoiceFromChallan"]);
Route::get("/verifyinvoicedate/{invoicedate}/{fromdate}/{todate}", [InvoiceController::class, "verifyInvoiceDate"]);
Route::get("/invoices", [InvoiceController::class, "getAllChallanInvoices"]);
Route::put("/invoice", [InvoiceController::class, "updateChallanInvoice"]);
Route::get("/invoice/{invoiceid}", [InvoiceController::class, "getChallanInvoiceOfInvoiceid"]);
Route::put('/invoice', [InvoiceController::class, "updateInvoiceForChallan"]);
