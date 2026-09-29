<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    // ============================================================
    // PUBLIC PROPERTY INDEX
    // ============================================================

    public function index(Request $request)
    {
        $query = Property::query()
            ->where('status', 'active')
            ->with([
                'facilities',
                'reviews',
            ])
            ->withAvg('reviews', 'score')
            ->withCount('reviews');

        // Bintang
        if ($request->filled('stars')) {
            $query->whereIn(
                'star_rating',
                (array) $request->stars
            );
        }

        // Rating
        if ($request->filled('rating')) {
            match ($request->rating) {
                '7' => $query->having('reviews_avg_score', '>=', 7)
                    ->having('reviews_avg_score', '<', 8),

                '8' => $query->having('reviews_avg_score', '>=', 8)
                    ->having('reviews_avg_score', '<', 9),

                '9' => $query->having('reviews_avg_score', '>=', 9),

                default => null,
            };
        }

        // Harga minimum
        if ($request->filled('min_price')) {
            $query->where(
                'price_per_night',
                '>=',
                $request->min_price
            );
        }

        // Harga maksimum
        if ($request->filled('max_price')) {
            $query->where(
                'price_per_night',
                '<=',
                $request->max_price
            );
        }

        // Lokasi
        if ($request->filled('location')) {
            $query->where(function ($q) use ($request) {
                $q->where(
                    'city',
                    'like',
                    '%' . $request->location . '%'
                )
                ->orWhere(
                    'address',
                    'like',
                    '%' . $request->location . '%'
                );
            });
        }

        // Tipe
        if ($request->filled('type')) {
            $query->where(
                'type',
                $request->type
            );
        }

        // Keyword
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where(
                    'name',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhere(
                    'city',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhere(
                    'address',
                    'like',
                    '%' . $keyword . '%'
                );
            });
        }

        // Kapasitas tamu
        if ($request->filled('guests')) {
            $query->where(
                'guest_capacity',
                '>=',
                $request->guests
            );
        }

        // Filter tanggal booking
        if (
            $request->filled('check_in') &&
            $request->filled('check_out')
        ) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;

            $query->whereDoesntHave(
                'bookings',
                function ($booking) use ($checkIn, $checkOut) {
                    $booking
                        ->whereIn('status', [
                            'pending',
                            'confirmed',
                        ])
                        ->where(function ($q) use ($checkIn, $checkOut) {
                            $q->where(
                                'check_in',
                                '<',
                                $checkOut
                            )
                            ->where(
                                'check_out',
                                '>',
                                $checkIn
                            );
                        });
                }
            );
        }

        $properties = $query
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view(
            'properties.index',
            compact('properties')
        );
    }


    // ============================================================
    // MITRA INDEX
    // ============================================================

    public function mitraIndex(Request $request)
    {
        $properties = Property::where(
            'mitra_id',
            auth()->id()
        )
        ->with([
            'facilities',
            'images',
        ])
        ->latest()
        ->paginate(10);

        return view(
            'properties.manage',
            compact('properties')
        );
    }


    // ============================================================
    // MITRA CREATE
    // ============================================================

    public function create()
    {
        $facilities = Facility::orderBy('name')->get();

        return view(
            'properties.create',
            compact('facilities')
        );
    }


    // ============================================================
    // MITRA STORE
    // ============================================================

    public function store(Request $request)
    {
        $validated = $this->validateProperty(
            $request
        );

        $validated['mitra_id'] = auth()->id();
        $validated['status'] = 'pending';

        $property = $this->saveProperty(
            $request,
            $validated
        );

        return redirect()
            ->route('mitra.properties.index')
            ->with(
                'success',
                'Properti berhasil ditambahkan dan menunggu verifikasi admin.'
            );
    }


    // ============================================================
    // ADMIN PROPERTY INDEX
    // ============================================================

    public function adminIndex(Request $request)
    {
        $query = Property::with([
            'mitra',
        ])
        ->withCount([
            'bookings',
            'reviews',
        ])
        ->withAvg(
            'reviews',
            'score'
        );

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'city',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'address',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        // Status
        if (
            $request->filled('status') &&
            in_array(
                $request->status,
                [
                    'active',
                    'pending',
                    'rejected',
                ]
            )
        ) {
            $query->where(
                'status',
                $request->status
            );
        }

        $properties = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalProperties = Property::count();

        $activeProperties = Property::where(
            'status',
            'active'
        )->count();

        $pendingProperties = Property::where(
            'status',
            'pending'
        )->count();

        $rejectedProperties = Property::where(
            'status',
            'rejected'
        )->count();

        return view(
            'admin.properties.index',
            compact(
                'properties',
                'totalProperties',
                'activeProperties',
                'pendingProperties',
                'rejectedProperties'
            )
        );
    }


    // ============================================================
    // ADMIN CREATE PROPERTY
    // ============================================================

    public function adminCreate()
    {
        $mitras = User::role('mitra')
            ->orderBy('name')
            ->get();

        $facilities = Facility::orderBy('name')
            ->get();

        return view(
            'admin.properties.create',
            compact(
                'mitras',
                'facilities'
            )
        );
    }


    // ============================================================
    // ADMIN STORE PROPERTY
    // ============================================================

    public function adminStore(Request $request)
    {
        $validated = $this->validateProperty(
            $request,
            true
        );

        $mitra = User::role('mitra')
            ->where(
                'id',
                $validated['mitra_id']
            )
            ->first();

        if (!$mitra) {
            return back()
                ->withErrors([
                    'mitra_id' =>
                        'Mitra yang dipilih tidak valid.',
                ])
                ->withInput();
        }

        // Properti yang dibuat admin langsung aktif
        $validated['status'] = 'active';

        $this->saveProperty(
            $request,
            $validated
        );

        return redirect()
            ->route('admin.properties.index')
            ->with(
                'success',
                'Properti berhasil ditambahkan dan langsung aktif.'
            );
    }


    // ============================================================
    // SHOW
    // ============================================================

    public function show(Property $property)
    {
        $property->load([
            'facilities',
            'mitra',
            'images',
            'reviews.customer',
        ]);

        $property->loadAvg(
            'reviews',
            'score'
        );

        $property->loadCount(
            'reviews'
        );

        $recommendations = Property::where(
            'status',
            'active'
        )
        ->where(
            'id',
            '!=',
            $property->id
        )
        ->where(
            'city',
            $property->city
        )
        ->latest()
        ->take(5)
        ->get();

        if ($recommendations->count() < 5) {
            $additional = Property::where(
                'status',
                'active'
            )
            ->where(
                'id',
                '!=',
                $property->id
            )
            ->whereNotIn(
                'id',
                $recommendations->pluck('id')
            )
            ->latest()
            ->take(
                5 - $recommendations->count()
            )
            ->get();

            $recommendations = $recommendations
                ->concat($additional);
        }

        return view(
            'properties.show',
            compact(
                'property',
                'recommendations'
            )
        );
    }


    // ============================================================
    // EDIT MITRA
    // ============================================================

    public function edit(Property $property)
    {
        $this->authorizeOwner(
            $property
        );

        $facilities = Facility::orderBy('name')->get();

        $property->load([
            'facilities',
            'images',
        ]);

        return view(
            'properties.edit',
            compact(
                'property',
                'facilities'
            )
        );
    }


    // ============================================================
    // UPDATE MITRA
    // ============================================================

    public function update(
        Request $request,
        Property $property
    ) {
        $this->authorizeOwner(
            $property
        );

        $validated = $this->validateProperty(
            $request
        );

        $this->updateProperty(
            $request,
            $property,
            $validated
        );

        return redirect()
            ->route('mitra.properties.index')
            ->with(
                'success',
                'Properti berhasil diperbarui.'
            );
    }


    // ============================================================
    // DESTROY IMAGE
    // ============================================================

    public function destroyImage(
        PropertyImage $image
    ) {
        $property = $image->property;

        $this->authorizeOwner(
            $property
        );

        if ($image->path) {
            Storage::disk('public')->delete(
                $image->path
            );
        }

        $image->delete();

        return back()
            ->with(
                'success',
                'Foto berhasil dihapus.'
            );
    }


    // ============================================================
    // DESTROY
    // ============================================================

    public function destroy(Property $property)
    {
        $this->authorizeOwner(
            $property
        );

        if ($property->cover_image) {
            Storage::disk('public')->delete(
                $property->cover_image
            );
        }

        foreach ($property->images as $image) {
            if ($image->path) {
                Storage::disk('public')->delete(
                    $image->path
                );
            }
        }

        $property->delete();

        return redirect()
            ->route('mitra.properties.index')
            ->with(
                'success',
                'Properti berhasil dihapus.'
            );
    }


    // ============================================================
    // APPROVE
    // ============================================================

    public function approve(Property $property)
    {
        $property->update([
            'status' => 'active',
        ]);

        return back()
            ->with(
                'success',
                'Properti berhasil disetujui.'
            );
    }


    // ============================================================
    // REJECT
    // ============================================================

    public function reject(Property $property)
    {
        $property->update([
            'status' => 'rejected',
        ]);

        return back()
            ->with(
                'success',
                'Properti berhasil ditolak.'
            );
    }


    // ============================================================
    // VALIDATION
    // ============================================================

    private function validateProperty(
        Request $request,
        bool $isAdmin = false
    ) {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'type' => [
                'required',
                Rule::in([
                    'guesthouse',
                    'kost_harian',
                    'villa',
                ]),
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'latitude' => [
                'nullable',
                'numeric',
            ],

            'longitude' => [
                'nullable',
                'numeric',
            ],

            'bedroom_count' => [
                'required',
                'integer',
                'min:1',
            ],

            'guest_capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'price_per_night' => [
                'required',
                'numeric',
                'min:0',
            ],

            'star_rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'management_type' => [
                'required',
                Rule::in([
                    'mandiri',
                    'dikelola',
                ]),
            ],

            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'photos' => [
                'nullable',
                'array',
            ],

            'photos.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'facilities' => [
                'nullable',
                'array',
            ],

            'facilities.*' => [
                'exists:facilities,id',
            ],
        ];

        if ($isAdmin) {
            $rules['mitra_id'] = [
                'required',
                'integer',
                'exists:users,id',
            ];
        }

        return $request->validate($rules);
    }


    // ============================================================
    // SAVE PROPERTY
    // ============================================================

    private function saveProperty(
        Request $request,
        array $validated
    ) {
        $propertyData = collect($validated)
            ->except([
                'cover_image',
                'photos',
                'facilities',
            ])
            ->toArray();

        if ($request->hasFile('cover_image')) {
            $propertyData['cover_image'] =
                $request
                    ->file('cover_image')
                    ->store(
                        'properties',
                        'public'
                    );
        }

        $property = Property::create(
            $propertyData
        );

        $this->syncFacilities(
            $property,
            $request->input(
                'facilities',
                []
            )
        );

        if ($request->hasFile('photos')) {
            foreach (
                $request->file('photos')
                as $index => $photo
            ) {
                $path = $photo->store(
                    'properties/gallery',
                    'public'
                );

                $property->images()->create([
                    'path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return $property;
    }


    // ============================================================
    // UPDATE PROPERTY
    // ============================================================

    private function updateProperty(
        Request $request,
        Property $property,
        array $validated
    ) {
        $propertyData = collect($validated)
            ->except([
                'cover_image',
                'photos',
                'facilities',
            ])
            ->toArray();

        if ($request->hasFile('cover_image')) {
            if ($property->cover_image) {
                Storage::disk('public')->delete(
                    $property->cover_image
                );
            }

            $propertyData['cover_image'] =
                $request
                    ->file('cover_image')
                    ->store(
                        'properties',
                        'public'
                    );
        }

        $property->update(
            $propertyData
        );

        $this->syncFacilities(
            $property,
            $request->input(
                'facilities',
                []
            )
        );

        if ($request->hasFile('photos')) {
            $startOrder = $property
                ->images()
                ->max('sort_order');

            $startOrder = $startOrder === null
                ? 0
                : $startOrder + 1;

            foreach (
                $request->file('photos')
                as $index => $photo
            ) {
                $path = $photo->store(
                    'properties/gallery',
                    'public'
                );

                $property->images()->create([
                    'path' => $path,
                    'sort_order' => $startOrder + $index,
                ]);
            }
        }
    }


    // ============================================================
    // SYNC FACILITIES
    // ============================================================

    private function syncFacilities(
        Property $property,
        array $selectedFacilities
    ) {
        $allFacilities = Facility::pluck(
            'id'
        );

        $syncData = [];

        foreach ($allFacilities as $facilityId) {
            $syncData[$facilityId] = [
                'is_available' =>
                    in_array(
                        $facilityId,
                        $selectedFacilities
                    ),
            ];
        }

        $property->facilities()->sync(
            $syncData
        );
    }


    // ============================================================
    // AUTHORIZE OWNER
    // ============================================================

    private function authorizeOwner(
        Property $property
    ) {
        $user = auth()->user();

        if (
            $user->hasRole('mitra') &&
            $property->mitra_id !== $user->id
        ) {
            abort(403);
        }
    }
}