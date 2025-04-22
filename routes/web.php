<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordController;
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


//admin


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.adminDashboard');
    })->name('admin.dashboard');

    Route::get('/admin/manage-users', [AdminController::class, 'manageUsers'])->name('admin.manageUsers');
    Route::get('/admin/add-user', [AdminController::class, 'addUserForm'])->name('admin.addUser');
    Route::post('/admin/add-user', [AdminController::class, 'addUser'])->name('admin.storeUser');
    Route::get('/admin/edit-user/{id}', [AdminController::class, 'editUserForm'])->name('admin.editUser');
    Route::patch('/admin/update-user/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::patch('/admin/deactivate-user/{id}', [AdminController::class, 'deactivateUser'])->name('admin.deactivateUser');
    Route::patch('/admin/activate-user/{id}', [AdminController::class, 'activateUser'])->name('admin.activateUser');
});


Route::get('/test-email', function () {
    $tempPassword = 'Test1234';
    try {
        Mail::to('dagaasgerlieannkatherine@gmail.com')->send(new \App\Mail\TemporaryPasswordMail($tempPassword));
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Failed to send email: ' . $e->getMessage();
    }
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

Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/finance/payments', [FinanceController::class, 'managePayments'])->name('finance.financePayments');
    Route::get('/finance/payments/history/{studentId}', [FinanceController::class, 'getPaymentHistory']);
    Route::post('/finance/payments/add', [FinanceController::class, 'addPayment'])->name('finance.addPayment');
});
Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/finance/payment-history', function () {
        return view('finance.payment-history', [
            'students' => \App\Models\Student::with('payments')->get()
        ]);
    })->name('finance.payment-history');

    Route::get('/finance/payment-history/{studentId}', [FinanceController::class, 'getPaymentHistory'])
        ->name('finance.getPaymentHistory');
});

Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::post('/finance/manage-batches', [FinanceController::class, 'updateBatch'])->name('finance.updateBatch');
});

Route::get('/password/change', [PasswordController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/password/change', [PasswordController::class, 'changePassword'])->name('password.update');

require __DIR__.'/auth.php';