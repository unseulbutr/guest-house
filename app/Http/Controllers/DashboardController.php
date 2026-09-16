<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard utama berdasarkan role user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN / SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return $this->adminDashboard();
        }

        /*
        |--------------------------------------------------------------------------
        | MITRA
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('mitra')) {
            return $this->mitraDashboard($user);
        }

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        return $this->customerDashboard($user);
    }


    // =========================================================================
    // ADMIN
    // =========================================================================

    private function adminDashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | PAID BOOKINGS
        |--------------------------------------------------------------------------
        */

        $paidBookings = Booking::where(
            'payment_status',
            'paid'
        );


        /*
        |--------------------------------------------------------------------------
        | BASIC STATISTICS
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total_properties' => Property::count(),

            'pending_properties' => Property::where(
                'status',
                'pending'
            )->count(),

            'active_properties' => Property::where(
                'status',
                'active'
            )->count(),

            'total_bookings' => Booking::count(),

            'pending_bookings' => Booking::where(
                'status',
                'pending'
            )->count(),

            'confirmed_bookings' => Booking::where(
                'status',
                'confirmed'
            )->count(),

            'completed_bookings' => Booking::where(
                'status',
                'completed'
            )->count(),

            'cancelled_bookings' => Booking::where(
                'status',
                'cancelled'
            )->count(),

            'gross_revenue' => (clone $paidBookings)
                ->sum('total_price'),

            'total_commission' => (clone $paidBookings)
                ->sum('commission_amount'),

            'total_mitra_payout' => (clone $paidBookings)
                ->sum('mitra_payout_amount'),
        ];


        /*
        |--------------------------------------------------------------------------
        | USER STATISTICS
        |--------------------------------------------------------------------------
        */

        $customerCount = User::whereHas(
            'roles',
            function ($query) {
                $query->where('name', 'customer');
            }
        )->count();

        $mitraCount = User::whereHas(
            'roles',
            function ($query) {
                $query->where('name', 'mitra');
            }
        )->count();


        /*
        |--------------------------------------------------------------------------
        | MONTH LABELS
        |--------------------------------------------------------------------------
        */

        $monthLabels = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];


        /*
        |--------------------------------------------------------------------------
        | MONTHLY REVENUE
        |--------------------------------------------------------------------------
        |
        | Menggunakan SQLite karena project kamu sebelumnya juga menggunakan
        | strftime() untuk chart Mitra.
        |
        */

        $monthlyRevenueQuery = Booking::where(
            'payment_status',
            'paid'
        )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->selectRaw(
                "CAST(strftime('%m', created_at) AS INTEGER) as month"
            )
            ->selectRaw(
                'SUM(total_price) as total'
            )
            ->groupByRaw(
                "CAST(strftime('%m', created_at) AS INTEGER)"
            )
            ->pluck(
                'total',
                'month'
            );


        /*
        |--------------------------------------------------------------------------
        | FORMAT MONTHLY REVENUE
        |--------------------------------------------------------------------------
        */

        $monthlyRevenue = [];

        foreach ($monthLabels as $monthNumber => $label) {
            $monthlyRevenue[$monthNumber] = (float) (
                $monthlyRevenueQuery[$monthNumber] ?? 0
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BOOKING STATUS DISTRIBUTION
        |--------------------------------------------------------------------------
        */

        $bookingStatus = [
            'pending' => Booking::where(
                'status',
                'pending'
            )->count(),

            'confirmed' => Booking::where(
                'status',
                'confirmed'
            )->count(),

            'completed' => Booking::where(
                'status',
                'completed'
            )->count(),

            'cancelled' => Booking::where(
                'status',
                'cancelled'
            )->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | RECENT BOOKINGS
        |--------------------------------------------------------------------------
        */

        $recentBookings = Booking::with([
            'property',
            'customer',
        ])
            ->latest()
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PENDING PROPERTIES
        |--------------------------------------------------------------------------
        */

        $pendingProperties = Property::with([
            'mitra',
        ])
            ->where(
                'status',
                'pending'
            )
            ->latest()
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard.admin',
            compact(
                'stats',
                'recentBookings',
                'pendingProperties',
                'monthlyRevenue',
                'monthLabels',
                'bookingStatus',
                'customerCount',
                'mitraCount'
            )
        );
    }


    // =========================================================================
    // MITRA
    // =========================================================================

    private function mitraDashboard($user)
    {
        /*
        |--------------------------------------------------------------------------
        | PROPERTY IDS
        |--------------------------------------------------------------------------
        */

        $propertyIds = Property::where(
            'mitra_id',
            $user->id
        )->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | PAID BOOKINGS
        |--------------------------------------------------------------------------
        */

        $paidBookings = Booking::whereIn(
            'property_id',
            $propertyIds
        )
            ->where(
                'payment_status',
                'paid'
            );


        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | RECENT BOOKINGS
        |--------------------------------------------------------------------------
        */

        $recentBookings = Booking::with([
            'property',
            'customer'
        ])
            ->whereIn(
                'property_id',
                $propertyIds
            )
            ->latest()
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PROPERTIES
        |--------------------------------------------------------------------------
        */

        $properties = Property::where(
            'mitra_id',
            $user->id
        )
            ->withCount('bookings')
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | MONTHS
        |--------------------------------------------------------------------------
        */

        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];


        /*
        |--------------------------------------------------------------------------
        | MONTHLY REVENUE
        |--------------------------------------------------------------------------
        */

        $monthlyRevenue = Booking::whereIn(
            'property_id',
            $propertyIds
        )
            ->where(
                'payment_status',
                'paid'
            )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->selectRaw(
                "CAST(strftime('%m', created_at) AS INTEGER) as month"
            )
            ->selectRaw(
                'SUM(mitra_payout_amount) as total'
            )
            ->groupByRaw(
                "CAST(strftime('%m', created_at) AS INTEGER)"
            )
            ->pluck(
                'total',
                'month'
            );


        /*
        |--------------------------------------------------------------------------
        | REVENUE CHART
        |--------------------------------------------------------------------------
        */

        $revenueChart = [
            'labels' => array_values($months),

            'data' => array_map(
                function ($monthNumber) use ($monthlyRevenue) {

                    return (float) (
                        $monthlyRevenue[$monthNumber] ?? 0
                    );

                },
                array_keys($months)
            ),
        ];


        /*
        |--------------------------------------------------------------------------
        | PROPERTY TYPES
        |--------------------------------------------------------------------------
        */

        $propertyTypeLabels = [
            'guesthouse' => 'Guest House',
            'kost_harian' => 'Kost Harian',
            'villa' => 'Villa',
        ];

        $propertyTypeColors = [
            'guesthouse' => '#059669',
            'kost_harian' => '#f59e0b',
            'villa' => '#8b5cf6',
        ];


        $propertyTypeCounts = Property::where(
            'mitra_id',
            $user->id
        )
            ->selectRaw(
                'type, COUNT(*) as total'
            )
            ->groupBy('type')
            ->pluck(
                'total',
                'type'
            );


        $propertyTypeDistribution = collect(
            $propertyTypeLabels
        )->map(
            function (
                $label,
                $type
            ) use (
                $propertyTypeCounts,
                $propertyTypeColors
            ) {

                return [

                    'type' => $type,

                    'label' => $label,

                    'count' => (int) (
                        $propertyTypeCounts[$type] ?? 0
                    ),

                    'color' => $propertyTypeColors[$type]
                        ?? '#94a3b8',
                ];
            }
        )->values();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard.mitra',
            compact(
                'stats',
                'recentBookings',
                'properties',
                'revenueChart',
                'propertyTypeDistribution'
            )
        );
    }


    // =========================================================================
    // CUSTOMER
    // =========================================================================

    private function customerDashboard($user)
    {
        /*
        |--------------------------------------------------------------------------
        | SEMUA BOOKING CUSTOMER
        |--------------------------------------------------------------------------
        */

        $customerBookings = Booking::where(
            'customer_id',
            $user->id
        );


        /*
        |--------------------------------------------------------------------------
        | BOOKING SUDAH DIBAYAR
        |--------------------------------------------------------------------------
        */

        $paidBookings = Booking::where(
            'customer_id',
            $user->id
        )
            ->where(
                'payment_status',
                'paid'
            );


        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $stats = [

            'total_bookings' => (clone $customerBookings)
                ->count(),

            'upcoming_bookings' => (clone $customerBookings)
                ->where(
                    'status',
                    'confirmed'
                )
                ->where(
                    'check_in',
                    '>=',
                    now()->toDateString()
                )
                ->count(),

            'saved_properties' => $user
                ->savedProperties()
                ->count(),

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
        | BOOKING AKTIF / TERDEKAT
        |--------------------------------------------------------------------------
        */

        $activeBooking = Booking::with([
            'property.images'
        ])
            ->where(
                'customer_id',
                $user->id
            )
            ->where(
                'status',
                'confirmed'
            )
            ->where(
                'check_out',
                '>=',
                now()->toDateString()
            )
            ->orderBy(
                'check_in',
                'asc'
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | ALIAS UPCOMING BOOKING
        |--------------------------------------------------------------------------
        |
        | Disediakan supaya Blade customer yang menggunakan
        | $upcomingBooking tetap kompatibel.
        |
        */

        $upcomingBooking = $activeBooking;


        /*
        |--------------------------------------------------------------------------
        | UPCOMING TRIPS
        |--------------------------------------------------------------------------
        */

        $upcomingTrips = Booking::with([
            'property.images'
        ])
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
            ->orderBy(
                'check_in',
                'asc'
            )
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PAST TRIPS
        |--------------------------------------------------------------------------
        */

        $pastTrips = Booking::with([
            'property.images'
        ])
            ->where(
                'customer_id',
                $user->id
            )
            ->where(
                'check_out',
                '<',
                now()->toDateString()
            )
            ->orderBy(
                'check_out',
                'desc'
            )
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BOOKING TERBARU
        |--------------------------------------------------------------------------
        */

        $recentBookings = Booking::with([
            'property.images'
        ])
            ->where(
                'customer_id',
                $user->id
            )
            ->latest()
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | MOST POPULAR
        |--------------------------------------------------------------------------
        */

        $popularProperties = Property::with([
            'images'
        ])
            ->withCount('bookings')
            ->where(
                'status',
                'active'
            )
            ->orderByDesc(
                'bookings_count'
            )
            ->latest()
            ->take(4)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NEARBY PROPERTIES
        |--------------------------------------------------------------------------
        */

        $nearbyProperties = Property::with([
            'images'
        ])
            ->where(
                'status',
                'active'
            )
            ->latest()
            ->take(4)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECOMMENDED PROPERTIES
        |--------------------------------------------------------------------------
        |
        | Alias tambahan untuk Blade customer.
        |
        */

        $recommendedProperties = $popularProperties;


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard.customer',
            compact(
                'stats',
                'activeBooking',
                'upcomingBooking',
                'upcomingTrips',
                'pastTrips',
                'recentBookings',
                'popularProperties',
                'nearbyProperties',
                'recommendedProperties'
            )
        );
    }
}