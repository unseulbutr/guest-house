<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PropertyReview;
use Illuminate\Http\Request;

class PropertyReviewController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $user = $request->user();

        // Pastikan hanya customer
        if (!$user->hasRole('customer')) {
            abort(403, 'Hanya customer yang dapat memberikan ulasan.');
        }

        // Pastikan booking milik customer
        if ((int) $booking->customer_id !== (int) $user->id) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }

        // Pembayaran harus sudah lunas
        if ($booking->payment_status !== 'paid') {
            return back()->with(
                'error',
                'Ulasan hanya dapat diberikan setelah pembayaran berhasil.'
            );
        }

        // Check-out harus sudah lewat
        if (!$booking->check_out || $booking->check_out->isFuture()) {
            return back()->with(
                'error',
                'Ulasan baru dapat diberikan setelah masa menginap selesai.'
            );
        }

        // Satu booking hanya boleh satu ulasan
        if ($booking->review()->exists()) {
            return back()->with(
                'error',
                'Booking ini sudah memiliki ulasan.'
            );
        }

        $validated = $request->validate([
            'score' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],
            'comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'score.required' => 'Silakan pilih rating.',
            'score.integer' => 'Rating harus berupa angka.',
            'score.min' => 'Rating minimal 1.',
            'score.max' => 'Rating maksimal 10.',
            'comment.max' => 'Komentar maksimal 2000 karakter.',
        ]);

        PropertyReview::create([
            'property_id' => $booking->property_id,
            'booking_id' => $booking->id,
            'customer_id' => $user->id,
            'score' => $validated['score'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with(
            'success',
            'Terima kasih! Rating dan ulasan kamu berhasil dikirim.'
        );
    }
}