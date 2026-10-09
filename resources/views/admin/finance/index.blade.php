@extends('layouts.app')

@section('title', 'Keuangan Platform')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Platform Finance</p>
            <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Keuangan Platform</h1>
            <p class="mt-2 text-sm text-slate-500">Super Admin memegang uang pembayaran customer dan memproses pencairan Mitra.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
            @foreach([
                ['label' => 'Pembayaran Masuk', 'value' => $grossCollected],
                ['label' => 'Refund Selesai', 'value' => $refunds],
                ['label' => 'Komisi Platform', 'value' => $platformCommission],
                ['label' => 'Hak Mitra (Ledger)', 'value' => $mitraLedger],
            ] as $card)
                <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $card['label'] }}</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-900">Rp {{ number_format($card['value'], 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
            <div class="rounded-3xl bg-amber-50 border border-amber-200 p-6">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Pencairan Menunggu / Disetujui</p>
                <p class="mt-3 text-3xl font-extrabold text-amber-900">Rp {{ number_format($pendingWithdrawals, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-3xl bg-green-50 border border-green-200 p-6">
                <p class="text-xs font-bold uppercase tracking-wider text-green-700">Sudah Dibayar ke Mitra</p>
                <p class="mt-3 text-3xl font-extrabold text-green-900">Rp {{ number_format($paidWithdrawals, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-900">Permintaan Pencairan Mitra</h2>
                <p class="text-sm text-slate-500 mt-1">Tandai disetujui saat siap dibayar, lalu tandai dibayar setelah uang benar-benar ditransfer/diberikan.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="text-left px-6 py-3">Tanggal</th>
                            <th class="text-left px-6 py-3">Mitra</th>
                            <th class="text-left px-6 py-3">Nominal</th>
                            <th class="text-left px-6 py-3">Metode</th>
                            <th class="text-left px-6 py-3">Rekening/Akun</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-right px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td class="px-6 py-4 text-slate-500">{{ optional($withdrawal->requested_at)->format('d/m/Y H:i') ?? $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $withdrawal->mitra->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-bold">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $withdrawal->payment_method === 'bank_transfer' ? 'Transfer Bank' : ($withdrawal->payment_method === 'ewallet' ? 'E-Wallet' : 'Tunai') }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $withdrawal->payment_account }}</td>
                                <td class="px-6 py-4 font-semibold">{{ ucfirst($withdrawal->status) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2 flex-wrap">
                                        @if($withdrawal->status === 'pending')
                                            <form method="POST" action="{{ route('admin.finance.withdrawals.approve', $withdrawal) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700">Setujui</button>
                                            </form>
                                        @endif

                                        @if(in_array($withdrawal->status, ['pending', 'approved'], true))
                                            <form method="POST" action="{{ route('admin.finance.withdrawals.paid', $withdrawal) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white hover:bg-green-700">Sudah Dibayar</button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.finance.withdrawals.reject', $withdrawal) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="admin_note" value="Permintaan pencairan ditolak oleh Super Admin.">
                                                <button class="rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100">Tolak</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-10 text-center text-slate-500">Belum ada permintaan pencairan Mitra.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">{{ $withdrawals->links() }}</div>
        </div>
    </div>
</div>
@endsection
