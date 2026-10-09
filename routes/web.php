<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingExtensionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyReviewController;
use App\Http\Controllers\SavedPropertyController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CancellationFeeController;
use App\Http\Controllers\MitraFinanceController;
use App\Http\Controllers\AdminFinanceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MitraRegistrationController;
use App\Http\Controllers\MitraVerificationController;
use Illuminate\Support\Facades\Route;


// ============================================================
// SEO
// ============================================================

Route::get(
    '/sitemap.xml',
    [SeoController::class, 'sitemap']
)->name('seo.sitemap');

Route::get(
    '/robots.txt',
    [SeoController::class, 'robots']
)->name('seo.robots');


// ============================================================
// PUBLIC
// ============================================================

Route::get(
    '/',
    [PropertyController::class, 'index']
)->name('home');

Route::get(
    '/properties/{property}',
    [PropertyController::class, 'show']
)->name('properties.show');

Route::get(
    '/artikel',
    [ArticleController::class, 'index']
)->name('articles.index');

Route::get(
    '/artikel/{slug}',
    [ArticleController::class, 'show']
)->name('articles.show');


// ============================================================
// PENDAFTARAN MITRA - PUBLIC
// ============================================================
//
// Guest:
//
// /daftar/mitra
//      ↓
// Landing Mitra
//      ↓
// /daftar/mitra/form
//      ↓
// Form Pendaftaran Mitra
//      ↓
// Data akun + identitas + selfie
//      ↓
// Kirim
//      ↓
// Akun dibuat sebagai CUSTOMER sementara
//      ↓
// Menunggu Verifikasi
//      ↓
// Admin / Super Admin
//      ↓
// APPROVE
//      ↓
// Role berubah menjadi MITRA
//      ↓
// Dashboard Mitra
//
// Tidak perlu membuat properti saat daftar.
//
// ============================================================


// LANDING MITRA
// LANDING MITRA PUBLIC
Route::get(
    '/daftar/mitra',
    [MitraRegistrationController::class, 'landing']
)->name('mitra.landing');


// FORM MITRA PUBLIC
Route::get(
    '/daftar/mitra/form',
    [MitraRegistrationController::class, 'create']
)->name('mitra.register');


// PROSES PENDAFTARAN MITRA
Route::post(
    '/daftar/mitra',
    [MitraRegistrationController::class, 'store']
)->name('mitra.register.store');


// ============================================================
// HUBUNGI ADMIN
// ============================================================

Route::get(
    '/bantuan',
    [SupportController::class, 'chat']
)->name('support.chat');

Route::post(
    '/bantuan',
    [SupportController::class, 'send']
)->name('support.send');


// ============================================================
// WEBHOOK PEMBAYARAN
// ============================================================

Route::patch(
    '/webhooks/bookings/{booking}/mark-paid',
    [BookingController::class, 'markAsPaid']
)->name('bookings.mark-paid');


// ============================================================
// AUTH REQUIRED
// ============================================================

Route::middleware([
    'auth',
    'active',
])->group(function () {

    // ========================================================
    // DASHBOARD
    // ========================================================

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');


    // ========================================================
    // PROFILE
    // ========================================================

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::put(
        '/profile/password',
        [ProfileController::class, 'updatePassword']
    )->name('profile.password');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');


    // ========================================================
    // INVOICE / BUKTI PEMBAYARAN
    // ========================================================

    Route::get(
        '/bookings/{booking}/invoice',
        [InvoiceController::class, 'show']
    )->name('bookings.invoice');


    // ========================================================
    // STATUS PENGAJUAN MITRA
    // ========================================================
    //
    // Bisa digunakan oleh customer yang sedang menunggu.
    // Setelah disetujui, controller akan mengarahkan
    // user ke dashboard Mitra.
    //
    // ========================================================

    Route::get(
        '/mitra/status',
        [MitraRegistrationController::class, 'status']
    )->name('mitra.apply.status');


    // ========================================================
    // CUSTOMER MENGAJUKAN MENJADI MITRA
    // ========================================================
    //
    // Customer yang sudah memiliki akun:
    //
    // Customer
    //    ↓
    // Landing Mitra
    //    ↓
    // Form Mitra
    //    ↓
    // Upload Identitas
    //    ↓
    // Selfie
    //    ↓
    // Kirim
    //    ↓
    // Menunggu Verifikasi
    //    ↓
    // Approve
    //    ↓
    // Role menjadi Mitra
    //
    // ========================================================

    Route::middleware([
        'role:customer',
    ])->group(function () {

        // ----------------------------------------------------
        // LANDING MITRA
        // ----------------------------------------------------

        Route::get(
            '/mitra/daftar',
            [MitraRegistrationController::class, 'landing']
        )->name('mitra.apply');


        // ----------------------------------------------------
        // FORM MITRA
        // ----------------------------------------------------

        Route::get(
            '/mitra/daftar/form',
            [MitraRegistrationController::class, 'create']
        )->name('mitra.apply.form');


        // ----------------------------------------------------
        // KIRIM PENGAJUAN MITRA
        // ----------------------------------------------------

        Route::post(
            '/mitra/daftar',
            [MitraRegistrationController::class, 'store']
        )->name('mitra.apply.store');
    });


    // ========================================================
    // CUSTOMER
    // ========================================================

    Route::middleware([
        'role:customer',
    ])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        // ----------------------------------------------------
        // BOOKING
        // ----------------------------------------------------

        Route::get(
            'bookings',
            [BookingController::class, 'index']
        )->name('bookings.index');

        Route::get(
            'bookings/create/{property}',
            [BookingController::class, 'create']
        )->name('bookings.create');

        Route::post(
            'bookings',
            [BookingController::class, 'store']
        )->name('bookings.store');

        Route::get(
            'bookings/{booking}',
            [BookingController::class, 'show']
        )->name('bookings.show');

        Route::post(
            'bookings/{booking}/review',
            [PropertyReviewController::class, 'store']
        )->name('bookings.review.store');

        Route::patch(
            'bookings/{booking}/cancel',
            [BookingController::class, 'cancel']
        )->name('bookings.cancel');

        Route::patch(
            'bookings/{booking}/simulate-pay',
            [BookingController::class, 'simulatePay']
        )->name('bookings.simulate-pay');

        Route::patch(
            'bookings/{booking}/simulate-settlement',
            [BookingController::class, 'simulateSettlement']
        )->name('bookings.simulate-settlement');


        // ====================================================
        // BOOKING EXTENSION
        // ====================================================

        Route::get(
            'bookings/{booking}/extension',
            [BookingExtensionController::class, 'create']
        )->name('booking-extensions.create');

        Route::post(
            'bookings/{booking}/extension',
            [BookingExtensionController::class, 'store']
        )->name('booking-extensions.store');

        Route::patch(
            'booking-extensions/{extension}/cancel',
            [BookingExtensionController::class, 'cancel']
        )->name('booking-extensions.cancel');

        Route::get(
            'booking-extensions/{extension}/payment',
            [BookingExtensionController::class, 'payment']
        )->name('booking-extensions.payment');

        Route::patch(
            'booking-extensions/{extension}/simulate-pay',
            [BookingExtensionController::class, 'simulatePay']
        )->name('booking-extensions.simulate-pay');


        // ====================================================
        // SAVED / WISHLIST
        // ====================================================

        Route::get(
            'saved',
            [SavedPropertyController::class, 'index']
        )->name('saved.index');

        Route::post(
            'saved/{property}/toggle',
            [SavedPropertyController::class, 'toggle']
        )->name('saved.toggle');
    });


    // ========================================================
    // MITRA
    // ========================================================

    Route::middleware([
        'role:mitra',
    ])
    ->prefix('mitra')
    ->name('mitra.')
    ->group(function () {

        // ----------------------------------------------------
        // PROPERTI MITRA
        // ----------------------------------------------------

        Route::get(
            'properties',
            [PropertyController::class, 'mitraIndex']
        )->name('properties.index');

        Route::resource(
            'properties',
            PropertyController::class
        )->except([
            'index',
            'show',
        ]);

        Route::delete(
            'properties/images/{image}',
            [PropertyController::class, 'destroyImage']
        )->name('properties.images.destroy');


        // ====================================================
        // BOOKING MASUK
        // ====================================================

        Route::get(
            'bookings',
            [BookingController::class, 'index']
        )->name('bookings.index');

        Route::get(
            'bookings/{booking}',
            [BookingController::class, 'show']
        )->name('bookings.show');

        Route::patch(
            'bookings/{booking}/confirm',
            [BookingController::class, 'confirm']
        )->name('bookings.confirm');

        Route::patch(
            'bookings/{booking}/reject',
            [BookingController::class, 'reject']
        )->name('bookings.reject');


        // ====================================================
        // KEUANGAN MITRA
        // ====================================================

        Route::get(
            'finance',
            [MitraFinanceController::class, 'index']
        )->name('finance.index');

        Route::post(
            'finance/withdraw',
            [MitraFinanceController::class, 'withdraw']
        )->name('finance.withdraw');


        // ====================================================
        // BOOKING EXTENSION
        // ====================================================

        Route::get(
            'booking-extensions',
            [BookingExtensionController::class, 'indexForMitra']
        )->name('booking-extensions.index');

        Route::get(
            'booking-extensions/{extension}',
            [BookingExtensionController::class, 'show']
        )->name('booking-extensions.show');

        Route::patch(
            'booking-extensions/{extension}/approve',
            [BookingExtensionController::class, 'approve']
        )->name('booking-extensions.approve');

        Route::patch(
            'booking-extensions/{extension}/reject',
            [BookingExtensionController::class, 'reject']
        )->name('booking-extensions.reject');
    });


    // ========================================================
    // ADMIN & SUPER ADMIN
    // ========================================================

    Route::middleware([
        'role:admin|super_admin',
    ])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ====================================================
        // PROPERTI ADMIN
        // ====================================================

        Route::get(
            'properties',
            [PropertyController::class, 'adminIndex']
        )->name('properties.index');

        Route::get(
            'properties/create',
            [PropertyController::class, 'adminCreate']
        )->name('properties.create');

        Route::post(
            'properties',
            [PropertyController::class, 'adminStore']
        )->name('properties.store');


        // ====================================================
        // APPROVAL PROPERTI
        // ====================================================

        Route::patch(
            'properties/{property}/approve',
            [PropertyController::class, 'approve']
        )->name('properties.approve');

        Route::patch(
            'properties/{property}/reject',
            [PropertyController::class, 'reject']
        )->name('properties.reject');


        // ====================================================
        // VERIFIKASI MITRA
        // ====================================================

        Route::get(
            'mitra-verifications',
            [MitraVerificationController::class, 'index']
        )->name('mitra-verifications.index');

        Route::get(
            'mitra-verifications/{verification}',
            [MitraVerificationController::class, 'show']
        )->name('mitra-verifications.show');

        Route::patch(
            'mitra-verifications/{verification}/approve',
            [MitraVerificationController::class, 'approve']
        )->name('mitra-verifications.approve');

        Route::patch(
            'mitra-verifications/{verification}/reject',
            [MitraVerificationController::class, 'reject']
        )->name('mitra-verifications.reject');

        Route::get(
            'mitra-verifications/{verification}/document/{type}',
            [MitraVerificationController::class, 'document']
        )->name('admin.mitra-verifications.document');


        // ====================================================
        // FACILITIES
        // ====================================================

        Route::resource(
            'facilities',
            FacilityController::class
        )->except([
            'show',
            'create',
            'store',
        ]);


        // ====================================================
        // ARTICLES
        // ====================================================

        Route::get(
            'articles',
            [ArticleController::class, 'adminIndex']
        )->name('articles.index');

        Route::get(
            'articles/create',
            [ArticleController::class, 'create']
        )->name('articles.create');

        Route::post(
            'articles',
            [ArticleController::class, 'store']
        )->name('articles.store');

        Route::get(
            'articles/{article}/edit',
            [ArticleController::class, 'edit']
        )->name('articles.edit');

        Route::patch(
            'articles/{article}',
            [ArticleController::class, 'update']
        )->name('articles.update');

        Route::delete(
            'articles/{article}',
            [ArticleController::class, 'destroy']
        )->name('articles.destroy');


        // ====================================================
        // BOOKINGS
        // ====================================================

        Route::get(
            'bookings',
            [BookingController::class, 'index']
        )->name('bookings.index');

        Route::get(
            'bookings/{booking}',
            [BookingController::class, 'show']
        )->name('bookings.show');

        Route::patch(
            'bookings/{booking}/refund',
            [BookingController::class, 'processRefund']
        )->name('bookings.refund');
    });


    // ========================================================
    // SUPER ADMIN - KEUANGAN
    // ========================================================

    Route::middleware([
        'role:super_admin',
    ])
    ->prefix('admin/finance')
    ->name('admin.finance.')
    ->group(function () {

        Route::get(
            '/',
            [AdminFinanceController::class, 'index']
        )->name('index');

        Route::patch(
            '/withdrawals/{withdrawal}/approve',
            [AdminFinanceController::class, 'approveWithdrawal']
        )->name('withdrawals.approve');

        Route::patch(
            '/withdrawals/{withdrawal}/paid',
            [AdminFinanceController::class, 'markAsPaid']
        )->name('withdrawals.paid');

        Route::patch(
            '/withdrawals/{withdrawal}/reject',
            [AdminFinanceController::class, 'rejectWithdrawal']
        )->name('withdrawals.reject');
    });


    // ========================================================
    // SUPER ADMIN - FACILITIES
    // ========================================================

    Route::middleware([
        'role:super_admin',
    ])
    ->prefix('admin/facilities')
    ->name('admin.facilities.')
    ->group(function () {

        Route::get(
            'create',
            [FacilityController::class, 'create']
        )->name('create');

        Route::post(
            '/',
            [FacilityController::class, 'store']
        )->name('store');
    });


    // ========================================================
    // SUPER ADMIN - USERS
    // ========================================================

    Route::middleware([
        'role:super_admin',
    ])
    ->prefix('admin/users')
    ->name('admin.users.')
    ->group(function () {

        Route::get(
            '/',
            [UserController::class, 'index']
        )->name('index');

        Route::get(
            '/create',
            [UserController::class, 'create']
        )->name('create');

        Route::post(
            '/',
            [UserController::class, 'store']
        )->name('store');

        Route::get(
            '/{user}/edit',
            [UserController::class, 'edit']
        )->name('edit');

        Route::patch(
            '/{user}',
            [UserController::class, 'update']
        )->name('update');

        Route::patch('/{user}/toggle-active', [UserController::class, 'toggleActive'])
    ->name('toggle-active');
    });


    // ========================================================
    // SUPER ADMIN - PLATFORM SETTINGS
    // ========================================================

    Route::middleware([
        'role:super_admin',
    ])
    ->prefix('admin/settings')
    ->name('admin.settings.')
    ->group(function () {

        Route::get(
            '/',
            [SettingController::class, 'edit']
        )->name('edit');

        Route::patch(
            '/',
            [SettingController::class, 'update']
        )->name('update');
    });


    // ========================================================
    // SUPER ADMIN - CANCELLATION FEES
    // ========================================================

    Route::middleware([
        'role:super_admin',
    ])
    ->prefix('admin/settings')
    ->name('admin.settings.')
    ->group(function () {

        Route::get(
            '/cancellation-fees',
            [CancellationFeeController::class, 'edit']
        )->name('cancellation-fees.edit');

        Route::put(
            '/cancellation-fees',
            [CancellationFeeController::class, 'update']
        )->name('cancellation-fees.update');
    });

});


// ============================================================
// AUTH
// ============================================================

require __DIR__.'/auth.php';