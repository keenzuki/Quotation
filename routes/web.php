<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SalesController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SalesRepController as AdminSalesRepController;


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
    return view('welcome');
})->name('home');

Route::get('/dashboard',[PageController::class,'dashboard'])->middleware(['auth'])->name('dashboard');
Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('invoice/{invoice}/pdf', [QuotationController::class, 'invoicePdf'])->name('invoicepdf');
        Route::get('quotation/{quotation}/pdf', [QuotationController::class, 'quotationPdf'])->name('quotationpdf');
        Route::get('view-invoice/{invoice}', [QuotationController::class, 'invoice'])->name('viewinvoice');
        Route::get('client/edit-invoice/{invoice}', [QuotationController::class, 'editInvoice'])->name('editinvoice');
        Route::post('client/update-invoice/{invoice}', [QuotationController::class, 'updateInvoice'])->name('updateinvoice');
        Route::get('client/profile/{client}', [ClientController::class, 'profile'])->name('clientprofile');
        Route::get('clients', [ClientController::class, 'index'])->name('clients');
        Route::get('payments',[PaymentController::class,'index'])->name('payments');
        Route::get('payments/show/{payment}',[PaymentController::class,'paymentPdf'])->name('showpayment');
        Route::get('payments/edit/{payment}',[PaymentController::class,'show'])->name('editpayment');
        Route::get('items',[SalesController::class,'index'])->name('items');
    });

Route::middleware('agent')->prefix('agent')->name('agent.')->group(function(){
    Route::get('/',[AgentController::class,'index'])->name('dashboard');
    // Route::get('clients',[ClientController::class,'index'])->name('clients');
    // Route::get('client/profile/{client}',[ClientController::class,'profile'])->name('clientprofile');
    Route::post('client/register',[ClientController::class,'store'])->name('registerclient');
    Route::put('client/update/{client}',[ClientController::class,'update'])->name('updateclient');
    Route::post('client/store-payment/{client}',[PaymentController::class,'store'])->name('addpayment');
    // Route::get('payments',[PaymentController::class,'index'])->name('payments');
    // Route::get('payments/show/{payment}',[PaymentController::class,'show'])->name('showpayment');
    Route::post('payments/update/{payment}',[PaymentController::class,'update'])->name('updatepayment');
    Route::get('payment-processing/{payment}',[PaymentController::class,'processPayment'])->name('processpayment');
  
    // Route::get('items',[SalesController::class,'index'])->name('items');
   
    Route::controller(QuotationController::class)->group(function(){
        Route::get('quotations','index')->name('quotations');
        Route::get('make-quotation','makeQuotation')->name('makequotation');
        Route::get('client/add-quotation/{client}','addQuotation')->name('addquotation');
        Route::get('client/view-quotation/{quotation}','quotation')->name('viewquotation');
        Route::get('client/edit-draft/{quotation}','editDraft')->name('editdraft');
        Route::get('client/edit-quotation/{quotation}','editQuotation')->name('editquotation');
        Route::get('update-quotation-to-invoice/{quotation}','updateQuotation2Invoice')->name('updateQ2I');
        Route::post('client/update-quotation/{quotation}','updateQuotation')->name('updatequotation');
        Route::post('client/complete-draft/{quotation}','completeDraft')->name('completedraft');
        Route::post('client/quotation/{client}','store')->name('savequotation');
        Route::post('quotation/store','storeQuotation')->name('storequotation');
        Route::post('client/quotation/store/{client}','storeClientQuotation')->name('storeclientquotation');
        Route::post('quotation/save-draft','storeDraft')->name('savedraft');
        Route::delete('quotation/destroy/{quotation}','destroy')->name('deletequotation');
        
        Route::get('invoices','invoices')->name('invoices');
        // Route::get('client/edit-invoice/{invoice}','editInvoice')->name('editinvoice');
        Route::delete('invoice/destroy/{invoice}','destroyInvoice')->name('deleteinvoice');
        // Route::get('view-invoice/{invoice}','invoice')->name('viewinvoice');
        // Route::get('invoice/{invoice}/pdf','invoicePdf')->name('invoicepdf');
        // Route::get('quotation/{quotation}/pdf','quotationPdf')->name('quotationpdf');
        // Route::post('client/update-invoice/{invoice}','updateInvoice')->name('updateinvoice');
        // Route::get('invoice-processing','processInvoice')->name('processinvoice');

    });
    
});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function(){
    Route::get('/',[AdminController::class,'index'])->name('dashboard');

    Route::controller(AdminSalesRepController::class)->prefix('sales-representatives')->group(function(){
        Route::get('/','index')->name('salesreps');
        Route::get('profile/{rep}','profile')->name('repprofile');

    });
    Route::controller(QuotationController::class)->group(function(){
        Route::get('quotations','index')->name('quotations');
        // Route::get('make-quotation','makeQuotation')->name('makequotation');
        // Route::get('client/add-quotation/{client}','addQuotation')->name('addquotation');
        // Route::get('client/view-quotation/{quotation}','quotation')->name('viewquotation');
        // Route::get('client/edit-draft/{quotation}','editDraft')->name('editdraft');
        // Route::get('client/edit-quotation/{quotation}','editQuotation')->name('editquotation');
        // Route::get('update-quotation-to-invoice/{quotation}','updateQuotation2Invoice')->name('updateQ2I');
        // Route::post('client/update-quotation/{quotation}','updateQuotation')->name('updatequotation');
        // Route::post('client/complete-draft/{quotation}','completeDraft')->name('completedraft');
        // Route::post('client/quotation/{client}','store')->name('savequotation');
        // Route::post('quotation/store','storeQuotation')->name('storequotation');
        // Route::post('client/quotation/store/{client}','storeClientQuotation')->name('storeclientquotation');
        // Route::post('quotation/save-draft','storeDraft')->name('savedraft');
        // Route::delete('quotation/destroy/{quotation}','destroy')->name('deletequotation');
        
        Route::get('invoices','invoices')->name('invoices');
        // Route::get('client/edit-invoice/{invoice}','editInvoice')->name('editinvoice');
        // Route::delete('invoice/destroy/{invoice}','destroyInvoice')->name('deleteinvoice');
        // Route::get('view-invoice/{invoice}','invoice')->name('viewinvoice');
        // // Route::get('invoice/{invoice}/pdf','invoicePdf')->name('invoicepdf');
        // // Route::get('quotation/{quotation}/pdf','quotationPdf')->name('quotationpdf');
        // Route::post('client/update-invoice/{invoice}','updateInvoice')->name('updateinvoice');
        // Route::get('invoice-processing','processInvoice')->name('processinvoice');

    });
});

require __DIR__.'/auth.php';
