<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEventsController;
use App\Http\Controllers\AdminMedpartsController;
use App\Http\Controllers\AdminPenontonController;
use App\Http\Controllers\AdminPresenceController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminSponsorshipsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::prefix('/')
    ->middleware(['auth', 'is_user', 'verified'])
    ->group(function () {
        Route::post('contactMedpart', [HomeController::class, 'sendMedpart'])->name('contactMedpart');
        Route::post('contactSponsor', [HomeController::class, 'sendSponsorship'])->name('contactSponsor');

        Route::get("verifikasiPembayaran/{id}", [TicketController::class, 'verifikasi'])->name('verifikasiPembayaran');

        Route::get('verifikasiLogin', function () {
            return view('verifikasi_login');
        })->name('verifikasiLogin');
        Route::get("getPanitia/{id}", [PanitiaController::class, "getData"])->name("getPanitia");
        Route::get("getVoucher/{id}", [VoucherController::class, "getData"])->name("getVoucher");
        Route::patch("uploadPembayaran/{id}", [TicketController::class, "formPembayaran"])->name("uploadPembayaran");
        Route::post('buyTicket', [TicketController::class, "buyTicket"])->name('buyTicket');
    });

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'loginpage')->name('login');
    Route::get('/register', 'registerpage')->name('register');
    Route::post('/login', 'loginmethod');
    Route::post('/register', 'registermethod')->name('registermethod');
    Route::post('/logout', 'logoutmethod')->name('logout');
});


Route::prefix('admin')
    ->middleware(['auth', 'is_admin', 'verified'])
    ->group(function () {
        // dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::controller(AdminPenontonController::class)->group(function () {
            Route::get('/Penonton', 'index')->name('index.penonton');
            Route::get('/Penonton/Ticket/{id}', 'getticketpenonton')->name('tiket.penonton');
            Route::patch('/Penonton/{id}', 'confirm')->name('confirm.tiket.penonton');
        });
        Route::controller(LaporanController::class)->group(function () {
            Route::get('/Laporan', 'index')->name('Laporan.index');
            Route::get('/Laporan/export', 'export')->name('admin.laporan.export');
        });

        // presence
        Route::get('presence', [AdminPresenceController::class, 'index'])->name('admin.presence');
        Route::get('userdata', [AdminPresenceController::class, 'getuserdata'])->name('admin.presence.userdata');
        Route::post('presence', [AdminPresenceController::class, 'presence'])->name('admin.presence.presenced');

        // settings
        Route::get('settings', [AdminSettingsController::class, 'index'])->name('admin.settings');
        Route::put('settings/{setting}', [AdminSettingsController::class, 'update'])->name('admin.settings.update');

        // sponsors
        Route::get('sponsorship', [AdminSponsorshipsController::class, 'index'])->name('admin.sponsorship');
        Route::post('sponsorship/store', [AdminSponsorshipsController::class, 'store'])->name('admin.sponsorship.store');
        Route::patch('sponsorship/update/{id}', [AdminSponsorshipsController::class, 'update'])->name('admin.sponsorship.update');
        Route::get('sponsorship/edit/{id}', [AdminSponsorshipsController::class, 'edit'])->name('admin.sponsorship.edit');
        Route::delete('sponsorship/destroy/{id}', [AdminSponsorshipsController::class, 'destroy'])->name('admin.sponsorship.destroy');

        // events
        Route::get('event', [AdminEventsController::class, 'index'])->name('admin.event');
        Route::post('event/store', [AdminEventsController::class, 'store'])->name('admin.event.store');
        Route::get('event/destroy/{id}', [AdminEventsController::class, 'destroy'])->name('admin.event.destroy');

        // medpart
        Route::get('medpart', [AdminMedpartsController::class, 'index'])->name('admin.medpart');
        Route::post('medpart/store', [AdminMedpartsController::class, 'store'])->name('admin.medpart.store');
        Route::patch('medpart/update/{id}', [AdminMedpartsController::class, 'update'])->name('admin.medpart.update');
        Route::get('medpart/edit/{id}', [AdminMedpartsController::class, 'edit'])->name('admin.medpart.edit');
        Route::get('medpart/destroy/{id}', [AdminMedpartsController::class, 'destroy'])->name('admin.medpart.destroy');

        Route::resource('Ticket', TicketController::class);
        Route::resource('Voucher', VoucherController::class);

        // webhook
        Route::post('/midtrans/webhook',[MidtransController::class,'handleWebhook'])->name('midtrans');
    });

