<?php

namespace App\Http\Controllers;

use App\Models\MitraEarning;
use App\Models\MitraWithdrawal;
use App\Services\MitraFinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MitraFinanceController extends Controller
{
    public function __construct(
        protected MitraFinanceService $finance
    ) {
    }

    public function index(Request $request)
    {
        $mitraId =
            $request->user()->id;

        $ledgerBalance =
            $this->finance->ledgerBalance(
                $mitraId
            );

        $reservedAmount =
            $this->finance
                ->totalWithdrawnOrReserved(
                    $mitraId
                );

        $availableAmount =
            $this->finance
                ->availableForWithdrawal(
                    $mitraId
                );

        $earnings =
            MitraEarning::query()
                ->with([
                    'booking',
                    'property'
                ])
                ->where(
                    'mitra_id',
                    $mitraId
                )
                ->latest()
                ->paginate(
                    15,
                    ['*'],
                    'earnings_page'
                );

        $withdrawals =
            MitraWithdrawal::query()
                ->where(
                    'mitra_id',
                    $mitraId
                )
                ->latest()
                ->paginate(
                    10,
                    ['*'],
                    'withdrawals_page'
                );

        return view(
            'mitra.finance.index',
            compact(
                'ledgerBalance',
                'reservedAmount',
                'availableAmount',
                'earnings',
                'withdrawals'
            )
        );
    }

    public function withdraw(
        Request $request
    ) {
        $validated =
            $request->validate([
                'amount' => [
                    'required',
                    'numeric',
                    'min:1',
                ],

                'payment_method' => [
                    'required',
                    'in:bank_transfer,cash,ewallet',
                ],

                'payment_account' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'note' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]);

        $mitraId =
            $request->user()->id;

        $withdrawal =
            DB::transaction(
                function () use (
                    $validated,
                    $mitraId
                ) {

                    $available =
                        $this->finance
                            ->availableForWithdrawal(
                                $mitraId
                            );

                    $amount =
                        round(
                            (float)
                            $validated['amount'],
                            2
                        );

                    abort_unless(
                        $amount <= $available,
                        422,
                        'Jumlah pencairan melebihi hak pendapatan yang tersedia.'
                    );

                    return MitraWithdrawal::create([
                        'mitra_id' =>
                            $mitraId,

                        'amount' =>
                            $amount,

                        'status' =>
                            'pending',

                        'payment_method' =>
                            $validated[
                                'payment_method'
                            ],

                        'payment_account' =>
                            $validated[
                                'payment_account'
                            ],

                        'note' =>
                            $validated['note']
                            ?? null,

                        'requested_at' =>
                            now(),
                    ]);
                }
            );

        return back()->with(
            'success',
            'Permintaan pencairan sebesar Rp ' .
            number_format(
                $withdrawal->amount,
                0,
                ',',
                '.'
            ) .
            ' berhasil dikirim ke Super Admin.'
        );
    }
}