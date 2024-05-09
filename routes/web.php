<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\QuotationController;


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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard',[PageController::class,'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('agent')->prefix('agent')->name('agent.')->group(function(){
    Route::get('/',[AgentController::class,'index'])->name('dashboard');
    Route::get('clients',[ClientController::class,'index'])->name('clients');
    Route::get('client/profile/{client}',[ClientController::class,'profile'])->name('clientprofile');
    Route::post('client/register',[ClientController::class,'store'])->name('registerclient');
  
    Route::get('make-quotation',[QuotationController::class,'makeQuotation'])->name('makequotation');
    // Route::get('client/quotation/{client}',[QuotationController::class,'create'])->name('createquotation');
   
    Route::controller(QuotationController::class)->group(function(){
        Route::get('quotations','index')->name('quotations');
        Route::get('client/view-quotation/{quotation}','quotation')->name('viewquotation');
        Route::get('client/edit-draft/{quotation}','editDraft')->name('editdraft');
        Route::get('client/edit-quotation/{quotation}','editQuotation')->name('editquotation');
        Route::get('update-quotation-to-invoice/{quotation}','updateQuotation2Invoice')->name('updateQ2I');
        Route::post('client/update-quotation/{quotation}','updateQuotation')->name('updatequotation');
        Route::post('client/complete-draft/{quotation}','completeDraft')->name('completedraft');
        Route::post('client/quotation/{client}','store')->name('savequotation');
        Route::post('quotation/store','storeQuotation')->name('storequotation');
        Route::post('quotation/save-draft','storeDraft')->name('savedraft');
        Route::delete('quotation/destroy/{quotation}','destroy')->name('deletequotation');
        
        Route::get('invoices','invoices')->name('invoices');
    
    });
    
});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function(){
    Route::get('/',[AdminController::class,'index'])->name('dashboard');
    // Route::get('users',[AdminUserController::class,'index'])->name('users');
    // Route::get('users/filter/{role}',[AdminUserController::class,'filterUsers']);
    // Route::get('users/search',[AdminUserController::class,'searchUser']);

});

require __DIR__.'/auth.php';
