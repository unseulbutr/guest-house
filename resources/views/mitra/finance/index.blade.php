@extends('layouts.app')

@section('title', 'Keuangan Mitra')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Mitra Finance</p>
            <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Keuangan</h1>
            <p class="mt-2 text-sm text-slate-500">
                Pantau hak pendapatan dari booking dan ajukan pencairan kepada Super Admin.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Hak Pendapatan</p>
                <p class="mt-3 text-3xl font-extrabold text-slate-900">
                    Rp {{ number_format($ledgerBalance, 0, ',', '.') }}
                </p>
                <p class="mt-2 text-xs text-slate-500">Catatan hak pendapatan Mitra, bukan saldo uang platform.</p>
            </div>

            <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Sudah / Sedang Dicairkan</p>
                <p class="mt-3 text-3xl font-extrabold text-slate-900">
                    Rp {{ number_format($reservedAmount, 0, ',', '.') }}
                </p>
                <p class="mt-2 text-xs text-slate-500">Pencairan pending, disetujui, atau sudah dibayar.</p>
            </div>

            <div class="rounded-3xl bg-blue-600 p-6 shadow-sm text-white">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-100">Bisa Diajukan</p>
                <p class="mt-3 text-3xl font-extrabold">
                    Rp {{ number_format($availableAmount, 0, ',', '.') }}
                </p>
                <p class="mt-2 text-xs text-blue-100">Super Admin yang memegang uang dan memproses pencairan.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="font-extrabold text-slate-900">Riwayat Pendapatan</h2>
                    <p class="text-sm text-slate-500 mt-1">Setiap pembayaran customer yang menghasilkan hak Mitra.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="text-left px-6 py-3 font-bold">Tanggal</th>
                                <th class="text-left px-6 py-3 font-bold">Booking</th>
                                <th class="text-left px-6 py-3 font-bold">Properti</th>
                                <th class="text-right px-6 py-3 font-bold">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($earnings as $earning)
                                <tr>
                                    <td class="px-6 py-4 text-slate-500">{{ $earning->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $earning->booking->booking_code ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $earning->property->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right font-bold {{ $earning->amount < 0 ? 'text-red-600' : 'text-green-700' }}">
                                        {{ $earning->amount < 0 ? '-' : '+' }}Rp {{ number_format(abs($earning->amount), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-500">Belum ada pendapatan tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4">{{ $earnings->links() }}</div>
            </div>

            <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <h2 class="font-extrabold text-slate-900">Ajukan Pencairan</h2>
                <p class="text-sm text-slate-500 mt-1 mb-6">Permintaan akan diperiksa dan dibayar oleh Super Admin.</p>

                <form method="POST" action="{{ route('mitra.finance.withdraw') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Jumlah</label>
                        <input type="number" name="amount" min="1" max="{{ $availableAmount }}" step="0.01" required
                               class="w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="250000">
                        @error('amount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Metode</label>
                        <select name="payment_method" required class="w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="cash">Tunai</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Rekening / Akun Penerima</label>
                        <input type="text" name="payment_account" required
                               class="w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="BCA 123456789 a.n. Nama">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Catatan</label>
                        <textarea name="note" rows="3" class="w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>

                    <button type="submit" @disabled($availableAmount <= 0)
                            class="w-full rounded-xl bg-slate-900 hover:bg-slate-800 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold py-3.5 transition">
                        Ajukan Pencairan
                    </button>
                </form>
            </div>
        </div>

        <div class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-900">Riwayat Pencairan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="text-left px-6 py-3">Tanggal</th>
                            <th class="text-left px-6 py-3">Nominal</th>
                            <th class="text-left px-6 py-3">Metode</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-left px-6 py-3">Catatan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td class="px-6 py-4 text-slate-500">{{ optional($withdrawal->requested_at)->format('d/m/Y H:i') ?? $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 font-bold">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $withdrawal->payment_method === 'bank_transfer' ? 'Transfer Bank' : ($withdrawal->payment_method === 'ewallet' ? 'E-Wallet' : 'Tunai') }}</td>
                                <td class="px-6 py-4 font-semibold">{{ ucfirst($withdrawal->status) }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $withdrawal->admin_note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada pengajuan pencairan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $withdrawals->links() }}</div>
        </div>
    </div>
</div>
@endsection
