<?php

use Livewire\Volt\Volt;
use App\Livewire\Attendance;
use App\Livewire\Employee\Add;
use App\Livewire\Employee\Index;
use App\Livewire\Leave\LeaveApproval;
use Illuminate\Support\Facades\Route;
use App\Livewire\Leave\LeaveRequestForm;
use App\Livewire\Leave\LeaveRequestList;
use App\Livewire\Leave\LeaveTypeManager;
use App\Livewire\Attendance\EditAttendance;
use App\Livewire\Attendance\AttendanceTable;
use App\Livewire\Attendance\CreateAttendance;
use App\Livewire\ComplaintAssignment;
use App\Livewire\ComplaintResolution;
use App\Livewire\AddComplaint;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    
    Route::prefix('employees')->group(function () {
        Volt::route('/add/{user_id?}', Add::class)->name('employees.add');
        Volt::route('/list', Index::class)->name('employees.list');
    });
    
    Route::prefix('attendance')->group(function () {
        Route::get('/index/{user_id?}', AttendanceTable::class)->name('attendance.index');
        Route::get('/create', CreateAttendance::class)->name('attendances.create');
        Route::get('/{attendance}/edit', EditAttendance::class)->name('attendances.edit');
    });

    Route::get('/leave/request', LeaveRequestForm::class)->name('leave.request');
    Route::get('/leave/requests', LeaveRequestList::class)->name('leave.requests');
    Route::get('/leave/approvals', LeaveApproval::class)->name('leave.approvals');//->middleware('can:approve-leave');
    Route::get('/leave/types', LeaveTypeManager::class)->name('leave.types');//->middleware('can:manage-leave-types');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/complaints/assign', ComplaintAssignment::class)->name('complaints.assign');
        Route::get('/complaints/resolve', ComplaintResolution::class)->name('complaints.resolve');
        Route::get('/complaints/add', AddComplaint::class)->name('complaints.add');
    });
});

require __DIR__.'/auth.php';
