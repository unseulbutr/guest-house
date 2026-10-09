@extends('layouts.app')

@section('title', 'Invoice ' . $invoiceNumber)

@section('content')

<style>
    .invoice-page {
        min-height: 100vh;
        background: #f5f5f4;
        padding: 35px 15px 70px;
    }

    .invoice-wrapper {
        width: min(900px, 100%);
        margin: 0 auto;
    }

    .invoice-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .invoice-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 17px;
        border-radius: 10px;
        border: 1px solid #e4e4e7;
        background: white;
        color: #27272a;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .invoice-button:hover {
        background: #fafafa;
    }

    .invoice-button-primary {
        background: #18181b;
        color: white;
        border-color: #18181b;
    }

    .invoice-button-primary:hover {
        background: #27272a;
    }

    .invoice-card {
        background: white;
        border-radius: 18px;
        border: 1px solid #e4e4e7;
        overflow: hidden;
        box-shadow:
            0 20px 50px rgba(0, 0, 0, .06);
    }

    .invoice-header {
        padding: 35px 40px;
        border-bottom: 1px solid #e4e4e7;
        display: flex;
        justify-content: space-between;
        gap: 30px;
    }

    .invoice-brand {
        font-size: 22px;
        font-weight: 900;
        color: #18181b;
        letter-spacing: -.03em;
    }

    .invoice-brand-subtitle {
        margin-top: 4px;
        color: #71717a;
        font-size: 12px;
    }

    .invoice-title {
        text-align: right;
    }

    .invoice-title h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 900;
        color: #18181b;
    }

    .invoice-number {
        margin-top: 5px;
        color: #71717a;
        font-size: 12px;
        font-weight: 700;
    }

    .invoice-status {
        display: inline-flex;
        margin-top: 10px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #ecfdf5;
        color: #047857;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .04em;
    }

    .invoice-status-refunded {
        background: #fff7ed;
        color: #c2410c;
    }

    .invoice-body {
        padding: 35px 40px;
    }

    .invoice-section {
        margin-bottom: 30px;
    }

    .invoice-section:last-child {
        margin-bottom: 0;
    }

    .invoice-section-title {
        margin-bottom: 13px;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: #a1a1aa;
    }

    .invoice-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px 35px;
    }

    .invoice-info {
        padding: 10px 0;
        border-bottom: 1px solid #f4f4f5;
    }

    .invoice-label {
        font-size: 10px;
        color: #a1a1aa;
        text-transform: uppercase;
        letter-spacing: .08em;
        font-weight: 800;
    }

    .invoice-value {
        margin-top: 4px;
        color: #27272a;
        font-size: 13px;
        font-weight: 800;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-table th {
        padding: 12px 0;
        border-bottom: 1px solid #e4e4e7;
        color: #71717a;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .08em;
        text-align: left;
    }

    .invoice-table th:last-child,
    .invoice-table td:last-child {
        text-align: right;
    }

    .invoice-table td {
        padding: 15px 0;
        border-bottom: 1px solid #f4f4f5;
        color: #27272a;
        font-size: 13px;
    }

    .invoice-table .total-row td {
        border-top: 2px solid #18181b;
        border-bottom: 0;
        padding-top: 18px;
        font-size: 15px;
        font-weight: 900;
    }

    .payment-box {
        border: 1px solid #e4e4e7;
        border-radius: 14px;
        overflow: hidden;
    }

    .payment-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 14px 16px;
        border-bottom: 1px solid #f4f4f5;
    }

    .payment-row:last-child {
        border-bottom: 0;
    }

    .payment-label {
        color: #71717a;
        font-size: 12px;
    }

    .payment-value {
        color: #27272a;
        font-size: 12px;
        font-weight: 800;
        text-align: right;
    }

    .payment-success {
        color: #047857;
    }

    .payment-warning {
        color: #b45309;
    }

    .invoice-total-box {
        margin-top: 20px;
        padding: 22px;
        border-radius: 15px;
        background: #18181b;
        color: white;
    }

    .invoice-total-label {
        color: #a1a1aa;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .1em;
    }

    .invoice-total-value {
        margin-top: 5px;
        color: white;
        font-size: 25px;
        font-weight: 900;
    }

    .invoice-footer {
        padding: 25px 40px;
        border-top: 1px solid #e4e4e7;
        background: #fafafa;
        text-align: center;
    }

    .invoice-footer-title {
        color: #27272a;
        font-size: 13px;
        font-weight: 800;
    }

    .invoice-footer-text {
        margin-top: 4px;
        color: #71717a;
        font-size: 11px;
        line-height: 1.6;
    }

    @media (max-width: 640px) {

        .invoice-page {
            padding: 20px 10px 50px;
        }

        .invoice-header {
            padding: 25px 20px;
            flex-direction: column;
        }

        .invoice-title {
            text-align: left;
        }

        .invoice-body {
            padding: 25px 20px;
        }

        .invoice-footer {
            padding: 20px;
        }

        .invoice-info-grid {
            grid-template-columns: 1fr;
        }

        .invoice-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .invoice-button {
            width: 100%;
        }
    }

    @media print {

        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            background: white !important;
        }

        .invoice-page {
            padding: 0 !important;
            background: white !important;
            min-height: auto !important;
        }

        .invoice-wrapper {
            width: 100% !important;
        }

        .invoice-actions {
            display: none !important;
        }

        .invoice-card {
            border: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .invoice-header {
            padding: 0 0 25px !important;
        }

        .invoice-body {
            padding: 25px 0 !important;
        }

        .invoice-footer {
            padding: 20px 0 0 !important;
        }

        .invoice-page,
        .invoice-card {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>


<div class="invoice-page">

    <div class="invoice-wrapper">

        {{-- ACTION --}}
        <div class="invoice-actions">

            <a
                href="{{ url()->previous() }}"
                class="invoice-button"
            >
                ← Kembali
            </a>

            <button
                type="button"
                onclick="window.print()"
                class="invoice-button invoice-button-primary"
            >
                🖨 Cetak / Simpan PDF
            </button>

        </div>


        {{-- INVOICE --}}
        <div class="invoice-card">


            {{-- HEADER --}}
            <div class="invoice-header">

                <div>

                    <div class="invoice-brand">
                        {{ $booking->property->name ?? 'Guest House & Kost' }}
                    </div>

                    <div class="invoice-brand-subtitle">
                        Bukti Pembayaran Booking
                    </div>

                </div>


                <div class="invoice-title">

                    <h1>
                        INVOICE
                    </h1>

                    <div class="invoice-number">
                        {{ $invoiceNumber }}
                    </div>

                    @if($paymentStatus === 'refunded')

                        <div class="invoice-status invoice-status-refunded">
                            REFUNDED
                        </div>

                    @elseif($paymentStatus === 'paid')

                        <div class="invoice-status">
                            LUNAS
                        </div>

                    @else

                        <div class="invoice-status">
                            DP TERBAYAR
                        </div>

                    @endif

                </div>

            </div>


            {{-- BODY --}}
            <div class="invoice-body">


                {{-- DATA CUSTOMER --}}
                <div class="invoice-section">

                    <div class="invoice-section-title">
                        Data Customer
                    </div>

                    <div class="invoice-info-grid">

                        <div class="invoice-info">

                            <div class="invoice-label">
                                Nama Tamu
                            </div>

                            <div class="invoice-value">
                                {{ $booking->guest_name ?? $booking->customer->name ?? '-' }}
                            </div>

                        </div>


                        <div class="invoice-info">

                            <div class="invoice-label">
                                No. Telepon
                            </div>

                            <div class="invoice-value">
                                {{ $booking->guest_phone ?? '-' }}
                            </div>

                        </div>


                        <div class="invoice-info">

                            <div class="invoice-label">
                                Email
                            </div>

                            <div class="invoice-value">
                                {{ $booking->guest_email ?? $booking->customer->email ?? '-' }}
                            </div>

                        </div>


                        <div class="invoice-info">

                            <div class="invoice-label">
                                Kode Booking
                            </div>

                            <div class="invoice-value">
                                {{ $booking->booking_code }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- DATA BOOKING --}}
                <div class="invoice-section">

                    <div class="invoice-section-title">
                        Data Booking
                    </div>

                    <div class="invoice-info-grid">

                        <div class="invoice-info">

                            <div class="invoice-label">
                                Properti
                            </div>

                            <div class="invoice-value">
                                {{ $booking->property->name ?? '-' }}
                            </div>

                        </div>


                        <div class="invoice-info">

                            <div class="invoice-label">
                                Mitra
                            </div>

                            <div class="invoice-value">
                                {{ $booking->property->mitra->name ?? '-' }}
                            </div>

                        </div>


                        <div class="invoice-info">

                            <div class="invoice-label">
                                Check-in
                            </div>

                            <div class="invoice-value">
                                {{ $booking->check_in
                                    ? \Carbon\Carbon::parse($booking->check_in)->translatedFormat('d F Y')
                                    : '-'
                                }}
                            </div>

                        </div>


                        <div class="invoice-info">

                            <div class="invoice-label">
                                Check-out
                            </div>

                            <div class="invoice-value">
                                {{ $booking->check_out
                                    ? \Carbon\Carbon::parse($booking->check_out)->translatedFormat('d F Y')
                                    : '-'
                                }}
                            </div>

                        </div>


                        <div class="invoice-info">

                            <div class="invoice-label">
                                Jumlah Tamu
                            </div>

                            <div class="invoice-value">
                                {{ $booking->guest_count ?? '-' }} orang
                            </div>

                        </div>


                        <div class="invoice-info">

                            <div class="invoice-label">
                                Metode Pembayaran
                            </div>

                            <div class="invoice-value">
                                {{ strtoupper($booking->payment_method ?? 'QRIS') }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- RINCIAN --}}
                <div class="invoice-section">

                    <div class="invoice-section-title">
                        Rincian Pembayaran
                    </div>

                    <table class="invoice-table">

                        <thead>
                            <tr>
                                <th>
                                    Keterangan
                                </th>

                                <th>
                                    Nominal
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td>
                                    Total Booking
                                </td>

                                <td>
                                    Rp {{ number_format($totalPrice, 0, ',', '.') }}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    DP 50%
                                </td>

                                <td>
                                    Rp {{ number_format($dpAmount, 0, ',', '.') }}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Pelunasan
                                </td>

                                <td>
                                    Rp {{ number_format(max($totalPrice - $dpAmount, 0), 0, ',', '.') }}
                                </td>
                            </tr>

                            <tr class="total-row">

                                <td>
                                    Total Terbayar
                                </td>

                                <td>
                                    Rp {{ number_format($totalPaid, 0, ',', '.') }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                {{-- RIWAYAT PEMBAYARAN --}}
                <div class="invoice-section">

                    <div class="invoice-section-title">
                        Riwayat Pembayaran
                    </div>

                    <div class="payment-box">


                        {{-- DP --}}
                        <div class="payment-row">

                            <div class="payment-label">
                                Pembayaran DP 50%
                            </div>

                            <div class="payment-value">

                                @if($dpPaidAt)

                                    <span class="payment-success">
                                        Rp {{ number_format($dpAmount, 0, ',', '.') }}
                                        <br>

                                        {{ $dpPaidAt->format('d/m/Y H:i') }}
                                    </span>

                                @else

                                    <span class="payment-warning">
                                        Belum dibayar
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- PELUNASAN --}}
                        <div class="payment-row">

                            <div class="payment-label">
                                Pelunasan 50%
                            </div>

                            <div class="payment-value">

                                @if($settlementPaidAt)

                                    <span class="payment-success">
                                        Rp {{ number_format(max($totalPrice - $dpAmount, 0), 0, ',', '.') }}
                                        <br>

                                        {{ $settlementPaidAt->format('d/m/Y H:i') }}
                                    </span>

                                @else

                                    <span class="payment-warning">
                                        Belum dilunasi
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- SISA --}}
                        <div class="payment-row">

                            <div class="payment-label">
                                Sisa Pembayaran
                            </div>

                            <div class="payment-value">

                                @if($paymentStatus === 'paid')

                                    <span class="payment-success">
                                        Rp 0
                                    </span>

                                @else

                                    <span class="payment-warning">
                                        Rp {{ number_format($remainingAmount, 0, ',', '.') }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- TRANSACTION ID --}}
                        @if($booking->qris_transaction_id || $booking->transaction_id)

                            <div class="payment-row">

                                <div class="payment-label">
                                    ID Transaksi
                                </div>

                                <div class="payment-value">
                                    {{ $booking->qris_transaction_id ?? $booking->transaction_id }}
                                </div>

                            </div>

                        @endif

                    </div>


                    {{-- TOTAL --}}
                    <div class="invoice-total-box">

                        <div class="invoice-total-label">
                            Total Pembayaran Diterima
                        </div>

                        <div class="invoice-total-value">
                            Rp {{ number_format($totalPaid, 0, ',', '.') }}
                        </div>

                    </div>

                </div>


                {{-- CATATAN --}}
                <div class="invoice-section">

                    <div class="invoice-section-title">
                        Catatan
                    </div>

                    <div class="text-xs leading-6 text-zinc-500">

                        @if($paymentStatus === 'paid')

                            Pembayaran booking telah diterima sepenuhnya.
                            Invoice ini merupakan bukti pembayaran resmi dari sistem.

                        @elseif($paymentStatus === 'refunded')

                            Booking telah direfund.
                            Status invoice ini adalah refunded.

                        @else

                            Invoice ini merupakan bukti pembayaran DP 50%.
                            Sisa pembayaran harus diselesaikan sebelum tanggal check-in.

                        @endif

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="invoice-footer">

                <div class="invoice-footer-title">
                    Terima kasih telah melakukan booking.
                </div>

                <div class="invoice-footer-text">
                    Dokumen ini dibuat secara otomatis oleh sistem.
                    Simpan invoice ini sebagai bukti pembayaran booking.
                </div>

            </div>

        </div>

    </div>

</div>

@endsection