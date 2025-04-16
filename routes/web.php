<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FinanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/profile', [StudentController::class, 'profile'])->name('student.profile');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.studentDashboard');
    })->name('student.studentDashboard');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/payments', function () {
        return view('student.studentPayments');
    })->name('student.studentPayments');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/', function () {
        return view('student.studentUpload');
    })->name('student.studentUpload');
});

Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/finance/dashboard', function () {
        return view('finance.financeDashboard');
    })->name('finance.financeDashboard');
});
Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/finance/payments', function () {
        return view('finance.financePayments');
    })->name('finance.financePayments');
});
Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/finance/reports', function () {
        return view('finance.financeReports');
    })->name('finance.financeReports');
});

Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/finance/profile', [FinanceController::class, 'profile'])->name('finance.profile');
});

Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/finance/dashboard', function () {
        return view('finance.financeDashboard');
    })->name('finance.financeDashboard');
});

require __DIR__.'/auth.php';