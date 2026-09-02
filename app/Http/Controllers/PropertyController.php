<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * ============================================================
     * HALAMAN PUBLIK / HOMEPAGE
     * ============================================================
     *
     * Route:
     * /
     *
     * Name:
     * home
     *
     * Halaman ini SAMA untuk:
     * - Tamu
     * - Customer
     * - Mitra
     * - Admin
     * - Super Admin
     *
     * Jadi walaupun mitra sedang login, ketika klik "Homestay"
     * tetap masuk ke homepage GuestHouse.
     */
    public function index(Request $request)
    {
        // Halaman publik hanya menampilkan properti yang aktif.
        $properties = Property::with('facilities')
            ->where('status', 'active')

            // Filter lokasi / kota
            ->when(
                $request->filled('location'),
                fn ($q) => $q->where(
                    'city',
                    'like',
                    '%' . $request->location . '%'
                )
            )

            // Filter jumlah tamu
            ->when(
                $request->filled('guests'),
                fn ($q) => $q->where(
                    'guest_capacity',
                    '>=',
                    $request->guests
                )
            )

            // Filter tipe properti
            ->when(
                $request->filled('type'),
                fn ($q) => $q->where(
                    'type',
                    $request->type
                )
            )

            // Filter nama / keyword
            ->when(
                $request->filled('keyword'),
                fn ($q) => $q->where(
                    'name',
                    'like',
                    '%' . $request->keyword . '%'
                )
            )

            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('properties.index', compact('properties'));
    }


    /**
     * ============================================================
     * PROPERTI SAYA - KHUSUS MITRA
     * ============================================================
     *
     * Route:
     * /mitra/properties
     *
     * Name:
     * mitra.properties.index
     *
     * Halaman ini hanya menampilkan properti milik mitra yang
     * sedang login.
     */
    public function mitraIndex(Request $request)
    {
        $user = $request->user();

        $properties = Property::where(
                'mitra_id',
                $user->id
            )
            ->latest()
            ->paginate(10);

        return view(
            'properties.manage',
            compact('properties')
        );
    }


    /**
     * ============================================================
     * TAMBAH PROPERTI
     * ============================================================
     */
    public function create()
    {
        $facilities = Facility::all();

        return view(
            'properties.create',
            compact('facilities')
        );
    }


    /**
     * ============================================================
     * SIMPAN PROPERTI BARU
     * ============================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',

            'type' => 'required|in:guesthouse,kost_harian,villa',

            'address' => 'required|string',
            'city' => 'required|string',

            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            'bedroom_count' => 'required|integer|min:1',
            'guest_capacity' => 'required|integer|min:1',

            'price_per_night' => 'required|numeric|min:0',

            'management_type' => 'required|in:mandiri,dikelola',

            'cover_image' => 'nullable|image|max:2048',

            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        /**
         * Upload cover image
         */
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] =
                $request->file('cover_image')
                    ->store('properties', 'public');
        }

        /**
         * Properti otomatis menjadi milik mitra
         */
        $validated['mitra_id'] = $request->user()->id;

        /**
         * Properti baru harus diverifikasi admin terlebih dahulu
         */
        $validated['status'] = 'pending';

        $property = Property::create($validated);


        /**
         * ========================================================
         * SYNC FACILITIES
         * ========================================================
         */
        $allFacilities = Facility::pluck('id');

        $syncData = [];

        foreach ($allFacilities as $facilityId) {
            $syncData[$facilityId] = [
                'is_available' => in_array(
                    $facilityId,
                    $request->input('facilities', [])
                ),
            ];
        }

        $property->facilities()->sync($syncData);


        return redirect()
            ->route('mitra.properties.index')
            ->with(
                'success',
                'Properti berhasil ditambahkan, menunggu verifikasi admin.'
            );
    }


    /**
     * ============================================================
     * DETAIL PROPERTI
     * ============================================================
     */
    public function show(Property $property)
    {
        $property->load(
            'facilities',
            'mitra'
        );

        return view(
            'properties.show',
            compact('property')
        );
    }


    /**
     * ============================================================
     * EDIT PROPERTI
     * ============================================================
     */
    public function edit(Property $property)
    {
        $this->authorizeOwner($property);

        $facilities = Facility::all();

        $property->load('facilities');

        return view(
            'properties.edit',
            compact(
                'property',
                'facilities'
            )
        );
    }


    /**
     * ============================================================
     * UPDATE PROPERTI
     * ============================================================
     */
    public function update(
        Request $request,
        Property $property
    ) {
        $this->authorizeOwner($property);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',

            'type' => 'required|in:guesthouse,kost_harian,villa',

            'address' => 'required|string',
            'city' => 'required|string',

            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            'bedroom_count' => 'required|integer|min:1',
            'guest_capacity' => 'required|integer|min:1',

            'price_per_night' => 'required|numeric|min:0',

            'management_type' => 'required|in:mandiri,dikelola',

            'cover_image' => 'nullable|image|max:2048',

            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);


        /**
         * Upload gambar baru jika ada
         */
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] =
                $request->file('cover_image')
                    ->store('properties', 'public');
        }


        $property->update($validated);


        /**
         * Update fasilitas
         */
        $allFacilities = Facility::pluck('id');

        $syncData = [];

        foreach ($allFacilities as $facilityId) {
            $syncData[$facilityId] = [
                'is_available' => in_array(
                    $facilityId,
                    $request->input('facilities', [])
                ),
            ];
        }

        $property->facilities()->sync($syncData);


        return redirect()
            ->route('mitra.properties.index')
            ->with(
                'success',
                'Properti berhasil diperbarui.'
            );
    }


    /**
     * ============================================================
     * HAPUS PROPERTI
     * ============================================================
     */
    public function destroy(Property $property)
    {
        $this->authorizeOwner($property);

        $property->delete();

        return redirect()
            ->route('mitra.properties.index')
            ->with(
                'success',
                'Properti berhasil dihapus.'
            );
    }


    /**
     * ============================================================
     * ADMIN APPROVE
     * ============================================================
     */
    public function approve(Property $property)
    {
        $property->update([
            'status' => 'active',
        ]);

        return back()->with(
            'success',
            'Properti disetujui dan sekarang tayang.'
        );
    }


    /**
     * ============================================================
     * ADMIN REJECT
     * ============================================================
     */
    public function reject(Property $property)
    {
        $property->update([
            'status' => 'rejected',
        ]);

        return back()->with(
            'success',
            'Properti ditolak.'
        );
    }


    /**
     * ============================================================
     * CEK PEMILIK PROPERTI
     * ============================================================
     */
    private function authorizeOwner(Property $property)
    {
        $user = request()->user();

        if (
            $user->hasRole('mitra') &&
            $property->mitra_id !== $user->id
        ) {
            abort(
                403,
                'Anda tidak memiliki akses ke properti ini.'
            );
        }
    }
}