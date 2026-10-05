<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class CancellationFeeController extends Controller
{
    /**
     * Menampilkan pengaturan biaya pembatalan.
     */
    public function edit()
    {
        $settings = Setting::current();

        return view('settings.cancellation-fees', compact('settings'));
    }

    /**
     * Menyimpan pengaturan biaya pembatalan.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'cancellation_fee_h7' => 'required|numeric|min:0|max:100',
            'cancellation_fee_h3' => 'required|numeric|min:0|max:100',
            'cancellation_fee_h2' => 'required|numeric|min:0|max:100',
            'cancellation_fee_h1' => 'required|numeric|min:0|max:100',
            'cancellation_fee_same_day' => 'required|numeric|min:0|max:100',
        ], [
            'cancellation_fee_h7.required' => 'Persentase H-7 wajib diisi.',
            'cancellation_fee_h3.required' => 'Persentase H-3 s/d H-6 wajib diisi.',
            'cancellation_fee_h2.required' => 'Persentase H-2 wajib diisi.',
            'cancellation_fee_h1.required' => 'Persentase H-1 wajib diisi.',
            'cancellation_fee_same_day.required' => 'Persentase Hari H wajib diisi.',

            '*.numeric' => 'Nilai harus berupa angka.',
            '*.min' => 'Persentase tidak boleh kurang dari 0%.',
            '*.max' => 'Persentase tidak boleh lebih dari 100%.',
        ]);

        Setting::current()->update($validated);

        return back()->with(
            'success',
            'Pengaturan biaya pembatalan berhasil disimpan.'
        );
    }
}