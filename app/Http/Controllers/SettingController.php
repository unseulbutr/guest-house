<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::current();
        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'default_commission_mandiri' => 'required|numeric|min:0|max:100',
            'default_commission_dikelola' => 'required|numeric|min:0|max:100',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        $validated['maintenance_mode'] = $request->boolean('maintenance_mode');

        Setting::current()->update($validated);

        return back()->with('success', 'Pengaturan platform berhasil disimpan.');
    }
}