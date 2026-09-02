<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Halaman "Hubungi Admin" — tampilannya kayak chat, tapi cara kerjanya
     * kirim pesan ke admin (tersimpan di DB), bukan chat real-time dua arah.
     * Kalau user login, tampilkan juga histori pesan yang pernah dia kirim +
     * status balasannya (open/answered/closed).
     */
    public function chat(Request $request)
    {
        $messages = $request->user()
            ? SupportMessage::where('user_id', $request->user()->id)->latest()->get()
            : collect();

        return view('support.chat', compact('messages'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        SupportMessage::create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'status' => 'open',
        ]);

        // TODO: kirim notifikasi ke admin (email/Slack/dsb) setiap ada pesan baru masuk.

        return back()->with('success', 'Pesan kamu sudah terkirim ke admin. Kami akan balas secepatnya lewat email.');
    }
}