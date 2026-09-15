<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyReviewController;
use App\Http\Controllers\SavedPropertyController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
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
// Customer, Mitra, Admin, Super Admin, dan tamu
// ============================================================

/**
 * HOMEPAGE
 *
 * PENTING:
 * Route ini selalu menuju PropertyController@index.
 *
 * Tidak lagi mengecek role.
 *
 * Jadi mitra juga akan melihat homepage GuestHouse.
 */
Route::get(
    '/',
    [PropertyController::class, 'index']
)->name('home');


/**
 * DETAIL PROPERTI
 */
Route::get(
    '/properties/{property}',
    [PropertyController::class, 'show']
)->name('properties.show');


/**
 * ARTIKEL
 */
Route::get(
    '/artikel',
    [ArticleController::class, 'index']
)->name('articles.index');

Route::get(
    '/artikel/{slug}',
    [ArticleController::class, 'show']
)->name('articles.show');


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
// WEBHOOK PEMBAYARAN QRIS
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
    'active'
])->group(function () {

/*
|--------------------------------------------------------------------------
| BOOKING EXTENSIONS
|--------------------------------------------------------------------------
*/

Route::middleware(['role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get(
            'bookings/{booking}/extension',
            [
                \App\Http\Controllers\BookingExtensionController::class,
                'create'
            ]
        )->name('bookings.extension.create');


        Route::post(
            'bookings/{booking}/extension',
            [
                \App\Http\Controllers\BookingExtensionController::class,
                'store'
            ]
        )->name('bookings.extension.store');


        Route::patch(
            'booking-extensions/{extension}/cancel',
            [
                \App\Http\Controllers\BookingExtensionController::class,
                'cancel'
            ]
        )->name('booking-extensions.cancel');
    });


Route::middleware(['role:mitra'])
    ->prefix('mitra')
    ->name('mitra.')
    ->group(function () {

        Route::get(
            'booking-extensions',
            [
                \App\Http\Controllers\BookingExtensionController::class,
                'indexForMitra'
            ]
        )->name('booking-extensions.index');


        Route::get(
            'booking-extensions/{extension}',
            [
                \App\Http\Controllers\BookingExtensionController::class,
                'show'
            ]
        )->name('booking-extensions.show');


        Route::patch(
            'booking-extensions/{extension}/approve',
            [
                \App\Http\Controllers\BookingExtensionController::class,
                'approve'
            ]
        )->name('booking-extensions.approve');


        Route::patch(
            'booking-extensions/{extension}/reject',
            [
                \App\Http\Controllers\BookingExtensionController::class,
                'reject'
            ]
        )->name('booking-extensions.reject');
    });


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
        'profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        'profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::put(
        'profile/password',
        [ProfileController::class, 'updatePassword']
    )->name('profile.password');

    Route::delete(
        'profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');


    // ========================================================
    // MITRA
    // ========================================================

    Route::middleware([
        'role:mitra'
    ])
    ->prefix('mitra')
    ->name('mitra.')
    ->group(function () {

        /**
         * ====================================================
         * PROPERTI SAYA
         * ====================================================
         *
         * SEBELUMNYA:
         * PropertyController@index
         *
         * SEKARANG:
         * PropertyController@mitraIndex
         *
         * Jadi halaman ini terpisah dari homepage.
         */
        Route::get(
            'properties',
            [PropertyController::class, 'mitraIndex']
        )->name('properties.index');


        /**
         * CRUD PROPERTI MITRA
         *
         * index dan show tidak dibuat oleh resource
         * karena sudah kita atur sendiri.
         */
        Route::resource(
            'properties',
            PropertyController::class
        )->except([
            'index',
            'show'
        ]);


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

         Route::delete(
            'properties/images/{image}', 
            [PropertyController::class, 'destroyImage'])
            ->name('properties.images.destroy');
    });


    // ========================================================
    // CUSTOMER
    // ========================================================

    Route::middleware([
        'role:customer'
    ])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

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
    // ADMIN & SUPER ADMIN
    // ========================================================

    Route::middleware([
        'role:admin|super_admin'
    ])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ====================================================
        // FACILITIES
        // ====================================================

        Route::resource(
            'facilities',
            FacilityController::class
        )->except([
            'show',
            'create',
            'store'
        ]);


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
    // SUPER ADMIN - FACILITY
    // ========================================================

    Route::middleware([
        'role:super_admin'
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
        'role:super_admin'
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

        Route::patch(
            '/{user}/toggle-active',
            [UserController::class, 'toggleActive']
        )->name('toggle-active');
    });


    // ========================================================
    // SUPER ADMIN - SETTINGS
    // ========================================================

    Route::middleware([
        'role:super_admin'
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
});


// ============================================================
// AUTH
// ============================================================

require __DIR__.'/auth.php';