<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ParticularController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('home');

Route::get('/dashboard', function () {
    $recentBookings = \App\Models\Booking::latest()->take(5)->get();
    $recentExpenses = \App\Models\Expense::latest('id')->take(5)->get();
    $totalExpenses = \App\Models\Expense::sum('Total');
    $bookingCount = \App\Models\Booking::count();
    
    return view('dashboard', compact('recentBookings', 'recentExpenses', 'totalExpenses', 'bookingCount'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('addresses/export', [AddressController::class, 'export'])->name('addresses.export');
    Route::resource('addresses', AddressController::class);
    Route::get('company_details/export', [\App\Http\Controllers\CompanyDetailController::class, 'export'])->name('company_details.export');
    Route::resource('company_details', \App\Http\Controllers\CompanyDetailController::class);
    Route::resource('bank_details', \App\Http\Controllers\BankDetailController::class);
    Route::get('particulars/export', [ParticularController::class, 'export'])->name('particulars.export');
    Route::resource('particulars', ParticularController::class);
    Route::get('bookings/export', [BookingController::class, 'export'])->name('bookings.export');
    Route::resource('bookings', BookingController::class);
    Route::get('expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::resource('expenses', ExpenseController::class);
    Route::get('invoice_particulars/export', [\App\Http\Controllers\InvoiceParticularController::class, 'export'])->name('invoice_particulars.export');
    Route::resource('invoice_particulars', \App\Http\Controllers\InvoiceParticularController::class);
    Route::get('invoices/export', [\App\Http\Controllers\InvoiceController::class, 'export'])->name('invoices.export');
    Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
