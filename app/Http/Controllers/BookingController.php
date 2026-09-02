<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = match (true) {
            $user->hasRole('customer') => Booking::with('property')
                ->where('customer_id', $user->id)->latest()->paginate(10),
            $user->hasRole('mitra') => Booking::with('property', 'customer')
                ->whereHas('property', fn ($q) => $q->where('mitra_id', $user->id))
                ->latest()->paginate(10),
            default => Booking::with('property', 'customer')->latest()->paginate(10),
        };

        return view('bookings.index', compact('bookings'));
    }

    public function create(Property $property)
    {
        return view('bookings.create', compact('property'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guest_count' => 'required|integer|min:1',
        ]);

        $property = Property::findOrFail($validated['property_id']);

        $pricing = Booking::calculatePricing($property, $validated['check_in'], $validated['check_out']);

        $booking = Booking::create([
            'customer_id' => $request->user()->id,
            'property_id' => $property->id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'guest_count' => $validated['guest_count'],
            'subtotal' => $pricing['subtotal'],
            'commission_percentage' => $pricing['commission_percentage'],
            'commission_amount' => $pricing['commission_amount'],
            'mitra_payout_amount' => $pricing['mitra_payout_amount'],
            'total_price' => $pricing['total_price'],
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        // TODO: integrasi generate QRIS ke payment gateway (Midtrans/Xendit/dsb)
        // $qrisResponse = app(QrisService::class)->generate($booking);
        // $booking->update(['qris_transaction_id' => $qrisResponse['transaction_id']]);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Booking dibuat, silakan selesaikan pembayaran QRIS.');
    }

    public function show(Request $request, Booking $booking)
    {
        $this->authorizeAccess($request, $booking);

        $booking->load('property', 'customer');
        return view('bookings.show', compact('booking'));
    }

    /**
     * Mitra menerima booking yang masuk (skema kelola mandiri).
     * Sesuai spec poin 2c & 9.1.
     */
    public function confirm(Request $request, Booking $booking)
    {
        $this->authorizeMitraOwnsBooking($request, $booking);

        abort_unless($booking->status === 'pending', 400, 'Booking ini sudah diproses sebelumnya.');

        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Booking #' . $booking->booking_code . ' dikonfirmasi.');
    }

    /**
     * Mitra menolak booking yang masuk.
     */
    public function reject(Request $request, Booking $booking)
    {
        $this->authorizeMitraOwnsBooking($request, $booking);

        abort_unless($booking->status === 'pending', 400, 'Booking ini sudah diproses sebelumnya.');

        $booking->update([
            'status' => 'cancelled',
            'rejected_by_mitra' => true,
        ]);

        // TODO: kalau payment_status sudah 'paid', trigger proses refund QRIS di sini.

        return back()->with('success', 'Booking #' . $booking->booking_code . ' ditolak.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        abort_unless(
            $booking->customer_id === $request->user()->id,
            403,
            'Anda tidak berhak membatalkan booking ini.'
        );

        abort_unless(
            in_array($booking->status, ['pending', 'confirmed']),
            400,
            'Booking berstatus "' . $booking->status . '" tidak bisa dibatalkan.'
        );

        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking dibatalkan.');
    }

    /**
     * Setelah pembayaran QRIS masuk (baik lewat webhook asli maupun simulasi),
     * status booking TIDAK langsung 'confirmed' begitu saja — itu keliru, karena
     * bikin mitra kelewat langkah konfirmasi/tolak untuk skema kelola mandiri.
     *
     * Aturannya:
     * - Skema "dikelola" (platform yang urus) -> otomatis confirmed, tidak perlu
     *   approval manual dari mitra.
     * - Skema "mandiri" -> tetap 'pending', menunggu mitra klik Konfirmasi/Tolak
     *   di bookings.show (lihat Booking::needsMitraResponse()).
     */
    protected function resolveStatusAfterPayment(Booking $booking): string
    {
        return $booking->property->management_type === 'dikelola' ? 'confirmed' : 'pending';
    }

    /**
     * Dipanggil oleh webhook payment gateway ketika QRIS dibayar.
     * PENTING: route ini harus di LUAR middleware 'auth' (lihat routes/web.php),
     * karena yang memanggil adalah server payment gateway, bukan browser user.
     * Gantilah pengecekan header di bawah dengan verifikasi signature resmi
     * dari provider (Midtrans/Xendit) sebelum dipakai di production.
     */
    public function markAsPaid(Request $request, Booking $booking)
    {
        // TODO: ganti dengan verifikasi signature asli dari payment gateway.
        abort_unless(
            $request->header('X-Webhook-Secret') === config('services.payment_gateway.webhook_secret'),
            403,
            'Webhook signature tidak valid.'
        );

        $booking->update([
            'payment_status' => 'paid',
            'status' => $this->resolveStatusAfterPayment($booking),
            'qris_transaction_id' => $request->input('transaction_id'),
        ]);

        return response()->json(['message' => 'Pembayaran dikonfirmasi.']);
    }

    /**
     * DEV/DEMO ONLY — simulasi "bayar QRIS" tanpa payment gateway asli,
     * supaya alur booking bisa dites end-to-end di local.
     * HAPUS atau nonaktifkan route ini sebelum go-live production.
     */
    public function simulatePay(Request $request, Booking $booking)
    {
        abort_unless($booking->customer_id === $request->user()->id, 403);
        abort_unless($booking->payment_status === 'pending', 400, 'Booking ini sudah dibayar/diproses.');

        $booking->update([
            'payment_status' => 'paid',
            'status' => $this->resolveStatusAfterPayment($booking),
            'qris_transaction_id' => 'SIMULATED-' . strtoupper(uniqid()),
        ]);

        $message = $booking->property->management_type === 'dikelola'
            ? '(Simulasi) Pembayaran QRIS berhasil, booking dikonfirmasi otomatis.'
            : '(Simulasi) Pembayaran QRIS berhasil. Menunggu konfirmasi dari mitra properti.';

        return back()->with('success', $message);
    }

    protected function authorizeAccess(Request $request, Booking $booking): void
    {
        $user = $request->user();

        $allowed = $user->hasRole('admin')
            || $user->hasRole('super_admin')
            || ($user->hasRole('customer') && $booking->customer_id === $user->id)
            || ($user->hasRole('mitra') && $booking->property->mitra_id === $user->id);

        abort_unless($allowed, 403, 'Anda tidak berhak mengakses booking ini.');
    }

    protected function authorizeMitraOwnsBooking(Request $request, Booking $booking): void
    {
        $user = $request->user();

        abort_unless(
            $user->hasRole('mitra') && $booking->property->mitra_id === $user->id,
            403,
            'Anda tidak berhak memproses booking ini.'
        );
    }
}