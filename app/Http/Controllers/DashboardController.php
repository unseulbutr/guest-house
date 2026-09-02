<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard utama, isinya beda tergantung role:
     *
     * - Super Admin / Admin: dashboard transaksi, komisi, dan pajak platform
     * - Mitra: dashboard pendapatan & histori transaksi propertinya sendiri
     * - Customer: ringkasan booking miliknya sendiri
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return $this->adminDashboard();
        }

        if ($user->hasRole('mitra')) {
            return $this->mitraDashboard($user);
        }

        return $this->customerDashboard($user);
    }

    /**
     * Admin & Super Admin:
     * rekap transaksi, komisi, dan pajak seluruh platform.
     */
    private function adminDashboard()
    {
        $paidBookings = Booking::where('payment_status', 'paid');

        $stats = [
            'total_properties' => Property::count(),
            'pending_properties' => Property::where('status', 'pending')->count(),
            'active_properties' => Property::where('status', 'active')->count(),

            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),

            'gross_revenue' => (clone $paidBookings)->sum('total_price'),
            'total_commission' => (clone $paidBookings)->sum('commission_amount'),
            'total_mitra_payout' => (clone $paidBookings)->sum('mitra_payout_amount'),
        ];

        $recentBookings = Booking::with('property', 'customer')
            ->latest()
            ->take(10)
            ->get();

        $pendingProperties = Property::with('mitra')
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        return view(
            'dashboard.admin',
            compact('stats', 'recentBookings', 'pendingProperties')
        );
    }

    /**
     * Mitra:
     * pendapatan & histori transaksi propertinya sendiri.
     */
    private function mitraDashboard($user)
    {
        $propertyIds = Property::where('mitra_id', $user->id)
            ->pluck('id');

        $paidBookings = Booking::whereIn('property_id', $propertyIds)
            ->where('payment_status', 'paid');

        $stats = [
            'total_properties' => $propertyIds->count(),

            'total_bookings' => Booking::whereIn(
                'property_id',
                $propertyIds
            )->count(),

            'total_earnings' => (clone $paidBookings)
                ->sum('mitra_payout_amount'),

            'total_commission_paid' => (clone $paidBookings)
                ->sum('commission_amount'),
        ];

        $recentBookings = Booking::with('property', 'customer')
            ->whereIn('property_id', $propertyIds)
            ->latest()
            ->take(10)
            ->get();

        $properties = Property::where('mitra_id', $user->id)
            ->withCount('bookings')
            ->latest()
            ->get();

        return view(
            'dashboard.mitra',
            compact('stats', 'recentBookings', 'properties')
        );
    }

    /**
     * Customer:
     * ringkasan booking miliknya sendiri.
     */
    private function customerDashboard($user)
    {
        /*
        |--------------------------------------------------------------------------
        | Semua booking milik customer
        |--------------------------------------------------------------------------
        */
        $customerBookings = Booking::where(
            'customer_id',
            $user->id
        );

        /*
        |--------------------------------------------------------------------------
        | Booking yang sudah dibayar
        |--------------------------------------------------------------------------
        */
        $paidBookings = Booking::where(
            'customer_id',
            $user->id
        )->where(
            'payment_status',
            'paid'
        );

        /*
        |--------------------------------------------------------------------------
        | Statistik Customer
        |--------------------------------------------------------------------------
        */
        $stats = [

            // Sudah ada sebelumnya
            'total_bookings' => (clone $customerBookings)
                ->count(),

            'upcoming_bookings' => (clone $customerBookings)
                ->where('status', 'confirmed')
                ->where(
                    'check_in',
                    '>=',
                    now()->toDateString()
                )
                ->count(),

            'saved_properties' => $user
                ->savedProperties()
                ->count(),

            // TAMBAHAN
            'completed_bookings' => (clone $customerBookings)
                ->where(
                    'check_out',
                    '<',
                    now()->toDateString()
                )
                ->where(
                    'status',
                    '!=',
                    'cancelled'
                )
                ->count(),

            'cancelled_bookings' => (clone $customerBookings)
                ->where(
                    'status',
                    'cancelled'
                )
                ->count(),

            'total_spending' => (clone $paidBookings)
                ->sum('total_price'),
        ];

        /*
        |--------------------------------------------------------------------------
        | Booking Terbaru
        |--------------------------------------------------------------------------
        */
        $recentBookings = Booking::with('property')
            ->where(
                'customer_id',
                $user->id
            )
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Booking Terdekat
        |--------------------------------------------------------------------------
        */
        $upcomingBooking = Booking::with('property')
            ->where(
                'customer_id',
                $user->id
            )
            ->where(
                'status',
                'confirmed'
            )
            ->where(
                'check_in',
                '>=',
                now()->toDateString()
            )
            ->orderBy('check_in')
            ->first();

        return view(
            'dashboard.customer',
            compact(
                'stats',
                'recentBookings',
                'upcomingBooking'
            )
        );
    }
}