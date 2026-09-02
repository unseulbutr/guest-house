<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class SavedPropertyController extends Controller
{
    /**
     * Daftar properti yang di-save/wishlist oleh customer yang sedang login.
     */
    public function index(Request $request)
    {
        $properties = $request->user()->savedProperties()->latest()->paginate(9);

        return view('customer.saved', compact('properties'));
    }

    /**
     * Toggle save/unsave sebuah properti (dipanggil dari tombol "save" di listing/detail).
     */
    public function toggle(Request $request, Property $property)
    {
        $user = $request->user();
        $alreadySaved = $user->savedProperties()->where('property_id', $property->id)->exists();

        if ($alreadySaved) {
            $user->savedProperties()->detach($property->id);
            $saved = false;
        } else {
            $user->savedProperties()->attach($property->id);
            $saved = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['saved' => $saved]);
        }

        return back()->with('success', $saved ? 'Properti disimpan ke wishlist.' : 'Properti dihapus dari wishlist.');
    }
}