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
    Route::get('client/quotation/{client}',[QuotationController::class,'create'])->name('createquotation');
    Route::post('client/quotation/{client}',[QuotationController::class,'store'])->name('savequotation');
    Route::post('client/register',[ClientController::class,'store'])->name('registerclient');
    // Route::get('users/filter/{role}',[AdminUserController::class,'filterUsers']);
    // Route::get('users/search',[AdminUserController::class,'searchUser']);

});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function(){
    Route::get('/',[AdminController::class,'index'])->name('dashboard');
    // Route::get('users',[AdminUserController::class,'index'])->name('users');
    // Route::get('users/filter/{role}',[AdminUserController::class,'filterUsers']);
    // Route::get('users/search',[AdminUserController::class,'searchUser']);

});

require __DIR__.'/auth.php';
