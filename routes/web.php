<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\PatientAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\PharmacistController;
use App\Http\Controllers\Admin\LabStaffController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\AdminAppointmentController;

use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\LabStaff\LabStaffDashboardController;
use App\Http\Controllers\Pharmacist\PharmacistDashboardController;
use App\Http\Controllers\Patient\PatientDashboardController;

/*
|--------------------------------------------------------------------------
| Home (Public)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication (Combined)
|--------------------------------------------------------------------------
*/
Route::get('/auth/register', [PatientAuthController::class, 'showRegisterForm'])->name('auth.register');
Route::post('/auth/register', [PatientAuthController::class, 'register'])->name('auth.register.submit');

Route::get('/auth/login', [PatientAuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/auth/login', [PatientAuthController::class, 'login'])->name('auth.login.submit');

Route::post('/logout', [PatientAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'isAdmin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('doctors', DoctorController::class);
        Route::resource('pharmacists', PharmacistController::class);
        Route::resource('labstaff', LabStaffController::class);
        Route::resource('patients', PatientController::class);

        Route::resource('appointments', AdminAppointmentController::class)->except(['show', 'edit', 'update']);
        Route::get('/appointments/slots/{doctor}/{date}', [AdminAppointmentController::class, 'getSlots'])->name('appointments.slots');

        // Doctor Schedules
        Route::get('/schedules', [DoctorScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{doctor}/edit', [DoctorScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{doctor}', [DoctorScheduleController::class, 'update'])->name('schedules.update');
    });

/*
|--------------------------------------------------------------------------
| Doctor Routes
|--------------------------------------------------------------------------
*/
Route::prefix('doctor')
    ->middleware(['auth', 'isDoctor'])
    ->name('doctor.')
    ->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/patients/{patient}', [DoctorDashboardController::class, 'viewPatientProfile'])->name('patient.profile');
        Route::post('/appointments/{id}/complete', [DoctorDashboardController::class, 'completeAppointment'])->name('appointments.complete');
        Route::post('/prescriptions/store', [DoctorDashboardController::class, 'storePrescription'])->name('prescriptions.store');
        Route::post('/lab-tests/store', [DoctorDashboardController::class, 'storeLabTest'])->name('lab_tests.store');
        Route::get('/lab-tests/{id}/print', [DoctorDashboardController::class, 'printLabTest'])->name('lab_tests.print');
        Route::get('/medicines/search', [DoctorDashboardController::class, 'searchMedicines'])->name('medicines.search');
    });

/*
|--------------------------------------------------------------------------
| Lab Staff Routes
|--------------------------------------------------------------------------
*/
Route::prefix('lab')
    ->middleware(['auth', 'isLabStaff'])
    ->name('labstaff.')
    ->group(function () {
        Route::get('/dashboard', [LabStaffDashboardController::class, 'index'])->name('dashboard');
        Route::post('/tests/{id}/upload', [LabStaffDashboardController::class, 'uploadReport'])->name('tests.upload');
    });

/*
|--------------------------------------------------------------------------
| Pharmacist Routes
|--------------------------------------------------------------------------
*/
Route::prefix('pharmacist')
    ->middleware(['auth', 'isPharmacist'])
    ->name('pharmacist.')
    ->group(function () {
        Route::get('/dashboard', [PharmacistDashboardController::class, 'index'])->name('dashboard');
        Route::get('/prescriptions', [PharmacistDashboardController::class, 'prescriptions'])->name('prescriptions');
        Route::post('/prescriptions/dispense/{id}', [PharmacistDashboardController::class, 'dispense'])->name('prescriptions.dispense');
        Route::get('/uploaded-prescriptions', [PharmacistDashboardController::class, 'uploadedPrescriptions'])->name('uploaded_prescriptions');
        Route::post('/uploaded-prescriptions/{id}/update', [PharmacistDashboardController::class, 'updateUploadedPrescription'])->name('uploaded_prescriptions.update');

        // Medicine Management
        Route::get('/medicines', [PharmacistDashboardController::class, 'medicines'])->name('medicines.index');
        Route::get('/medicines/create', [PharmacistDashboardController::class, 'createMedicine'])->name('medicines.create');
        Route::post('/medicines/store', [PharmacistDashboardController::class, 'storeMedicine'])->name('medicines.store');
        Route::get('/medicines/{id}/edit', [PharmacistDashboardController::class, 'editMedicine'])->name('medicines.edit');
        Route::put('/medicines/{id}/update', [PharmacistDashboardController::class, 'updateMedicine'])->name('medicines.update');
        Route::delete('/medicines/{id}/destroy', [PharmacistDashboardController::class, 'destroyMedicine'])->name('medicines.destroy');
    });

/*
|--------------------------------------------------------------------------
| Patient Routes
|--------------------------------------------------------------------------
*/
Route::prefix('patient')
    ->middleware(['auth', 'isPatient'])
    ->name('patient.')
    ->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/select-service', [PatientDashboardController::class, 'selectService'])->name('select_service');
        Route::get('/appointments', [PatientDashboardController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/book', [PatientDashboardController::class, 'createAppointment'])->name('appointments.book');
        Route::post('/appointments/book', [PatientDashboardController::class, 'storeAppointment'])->name('appointments.store');
        Route::get('/appointments/slots/{doctor}/{date}', [PatientDashboardController::class, 'getSlots'])->name('appointments.slots');
        Route::get('/prescriptions', [PatientDashboardController::class, 'prescriptions'])->name('prescriptions');
        Route::post('/prescriptions/upload', [PatientDashboardController::class, 'uploadPrescription'])->name('prescriptions.upload');
        Route::get('/lab-reports', [PatientDashboardController::class, 'labReports'])->name('lab_reports');
        Route::get('/lab-booking', [PatientDashboardController::class, 'bookLabTest'])->name('lab.book');
        Route::post('/lab-booking', [PatientDashboardController::class, 'storeLabTestRequest'])->name('lab.store');
        Route::get('/lab-reports/{id}/print', [PatientDashboardController::class, 'printLabTest'])->name('lab_reports.print');
        Route::get('/find-doctor', [PatientDashboardController::class, 'findDoctor'])->name('find_doctor');
    });

// Fallback for generic dashboard (redirects based on role)
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'doctor' => redirect()->route('doctor.dashboard'),
        'labstaff' => redirect()->route('labstaff.dashboard'),
        'pharmacist' => redirect()->route('pharmacist.dashboard'),
        'patient' => redirect()->route('patient.select_service'),
        'reception' => redirect()->route('reception.dashboard'),
        default => redirect()->route('home'),
    };
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Reception Routes
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Reception\ReceptionDashboardController;

Route::prefix('reception')
    ->middleware(['auth', 'isReception'])
    ->name('reception.')
    ->group(function () {
        Route::get('/dashboard', [ReceptionDashboardController::class, 'index'])->name('dashboard');

        // Patients
        Route::get('/patients', [ReceptionDashboardController::class, 'patients'])->name('patients');
        Route::get('/patients/create', [ReceptionDashboardController::class, 'createPatient'])->name('patients.create');
        Route::post('/patients/store', [ReceptionDashboardController::class, 'storePatient'])->name('patients.store');
        Route::get('/patients/{id}', [ReceptionDashboardController::class, 'showPatient'])->name('patients.show');
        Route::get('/patients/{id}/edit', [ReceptionDashboardController::class, 'editPatient'])->name('patients.edit');
        Route::put('/patients/{id}/update', [ReceptionDashboardController::class, 'updatePatient'])->name('patients.update');

        // Appointments
        Route::get('/appointments', [ReceptionDashboardController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/create', [ReceptionDashboardController::class, 'createAppointment'])->name('appointments.create');
        Route::post('/appointments/store', [ReceptionDashboardController::class, 'storeAppointment'])->name('appointments.store');
        Route::get('/appointments/slots/{doctor}/{date}', [ReceptionDashboardController::class, 'getSlots'])->name('appointments.slots');

        // Billings
        Route::get('/billings', [ReceptionDashboardController::class, 'billings'])->name('billings');
        Route::get('/billings/{appointment}/create', [ReceptionDashboardController::class, 'createBilling'])->name('billings.create');
        Route::post('/billings/store', [ReceptionDashboardController::class, 'storeBilling'])->name('billings.store');
        Route::get('/billings/{billing}/receipt', [ReceptionDashboardController::class, 'showReceipt'])->name('billings.receipt');
    });
