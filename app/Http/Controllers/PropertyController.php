<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX / HOMEPAGE
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER BINTANG PROPERTI
        |--------------------------------------------------------------------------
        */

        $selectedStars = array_values(
            array_filter(
                array_map(
                    'intval',
                    (array) $request->input('stars', [])
                ),
                fn ($star) =>
                    $star >= 1 && $star <= 5
            )
        );

        /*
        |--------------------------------------------------------------------------
        | FILTER RATING DARI TAMU
        |--------------------------------------------------------------------------
        |
        | 7 = Nyaman
        | 8 = Mengesankan
        | 9 = Luar Biasa
        |
        */

        $selectedGuestRatings = array_values(
            array_intersect(
                ['7', '8', '9'],
                array_map(
                    'strval',
                    (array) $request->input(
                        'guest_rating',
                        []
                    )
                )
            )
        );

        $minPrice =
            $request->input('min_price');

        $maxPrice =
            $request->input('max_price');

        /*
        |--------------------------------------------------------------------------
        | QUERY PROPERTY
        |--------------------------------------------------------------------------
        */

        $properties = Property::with([
                'facilities',
            ])

            /*
            |--------------------------------------------------------------------------
            | RATA-RATA RATING TAMU
            |--------------------------------------------------------------------------
            */

            ->withAvg(
                'reviews',
                'score'
            )

            /*
            |--------------------------------------------------------------------------
            | JUMLAH REVIEW
            |--------------------------------------------------------------------------
            */

            ->withCount(
                'reviews'
            )

            /*
            |--------------------------------------------------------------------------
            | HANYA PROPERTY ACTIVE
            |--------------------------------------------------------------------------
            */

            ->where(
                'status',
                'active'
            )

            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */

            ->when(
                $request->filled('location'),
                fn ($q) =>
                    $q->where(
                        'city',
                        'like',
                        '%' .
                        $request->location .
                        '%'
                    )
            )

            /*
            |--------------------------------------------------------------------------
            | GUEST CAPACITY
            |--------------------------------------------------------------------------
            */

            ->when(
                $request->filled('guests'),
                fn ($q) =>
                    $q->where(
                        'guest_capacity',
                        '>=',
                        $request->guests
                    )
            )

            /*
            |--------------------------------------------------------------------------
            | TYPE
            |--------------------------------------------------------------------------
            */

            ->when(
                $request->filled('type'),
                fn ($q) =>
                    $q->where(
                        'type',
                        $request->type
                    )
            )

            /*
            |--------------------------------------------------------------------------
            | BINTANG PROPERTI
            |--------------------------------------------------------------------------
            |
            | Ini BUKAN rating tamu.
            |
            */

            ->when(
                !empty($selectedStars),
                function ($q) use (
                    $selectedStars
                ) {
                    $q->whereIn(
                        'star_rating',
                        $selectedStars
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | KEYWORD
            |--------------------------------------------------------------------------
            */

            ->when(
                $request->filled('keyword'),
                fn ($q) =>
                    $q->where(
                        'name',
                        'like',
                        '%' .
                        $request->keyword .
                        '%'
                    )
            )

            /*
            |--------------------------------------------------------------------------
            | AVAILABILITY BERDASARKAN TANGGAL
            |--------------------------------------------------------------------------
            */

            ->when(
                $request->filled('check_in') &&
                $request->filled('check_out'),

                function ($q) use ($request) {

                    $checkIn =
                        $request->input(
                            'check_in'
                        );

                    $checkOut =
                        $request->input(
                            'check_out'
                        );

                    $q->whereDoesntHave(
                        'bookings',
                        function ($bookingQuery)
                            use (
                                $checkIn,
                                $checkOut
                            ) {

                            $bookingQuery
                                ->whereIn(
                                    'status',
                                    [
                                        'pending',
                                        'confirmed',
                                    ]
                                )
                                ->where(
                                    function ($query)
                                        use (
                                            $checkIn,
                                            $checkOut
                                        ) {

                                        $query
                                            ->where(
                                                'check_in',
                                                '<',
                                                $checkOut
                                            )
                                            ->where(
                                                'check_out',
                                                '>',
                                                $checkIn
                                            );
                                    }
                                );
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | RATING DARI TAMU
            |--------------------------------------------------------------------------
            |
            | Yang digunakan adalah RATA-RATA seluruh review.
            |
            | 7+  = 7.0 sampai 7.99
            | 8+  = 8.0 sampai 8.99
            | 9+  = 9.0 sampai 10
            |
            */

            ->when(
                !empty($selectedGuestRatings),

                function ($q)
                    use ($selectedGuestRatings) {

                    $q->where(
                        function ($query)
                            use ($selectedGuestRatings) {

                            foreach (
                                $selectedGuestRatings
                                as $rating
                            ) {

                                /*
                                |--------------------------------------------------------------------------
                                | 7+ NYAMAN
                                |--------------------------------------------------------------------------
                                */

                                if ($rating === '7') {

                                    $query->orWhere(
                                        function ($subQuery) {

                                            $subQuery
                                                ->whereRaw(
                                                    '(
                                                        SELECT AVG(score)
                                                        FROM property_reviews
                                                        WHERE property_reviews.property_id = properties.id
                                                    ) >= 7'
                                                )
                                                ->whereRaw(
                                                    '(
                                                        SELECT AVG(score)
                                                        FROM property_reviews
                                                        WHERE property_reviews.property_id = properties.id
                                                    ) < 8'
                                                );
                                        }
                                    );
                                }

                                /*
                                |--------------------------------------------------------------------------
                                | 8+ MENGESANKAN
                                |--------------------------------------------------------------------------
                                */

                                if ($rating === '8') {

                                    $query->orWhere(
                                        function ($subQuery) {

                                            $subQuery
                                                ->whereRaw(
                                                    '(
                                                        SELECT AVG(score)
                                                        FROM property_reviews
                                                        WHERE property_reviews.property_id = properties.id
                                                    ) >= 8'
                                                )
                                                ->whereRaw(
                                                    '(
                                                        SELECT AVG(score)
                                                        FROM property_reviews
                                                        WHERE property_reviews.property_id = properties.id
                                                    ) < 9'
                                                );
                                        }
                                    );
                                }

                                /*
                                |--------------------------------------------------------------------------
                                | 9+ LUAR BIASA
                                |--------------------------------------------------------------------------
                                */

                                if ($rating === '9') {

                                    $query->orWhere(
                                        function ($subQuery) {

                                            $subQuery->whereRaw(
                                                '(
                                                    SELECT AVG(score)
                                                    FROM property_reviews
                                                    WHERE property_reviews.property_id = properties.id
                                                ) >= 9'
                                            );
                                        }
                                    );
                                }
                            }
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | HARGA MINIMUM
            |--------------------------------------------------------------------------
            */

            ->when(
                $request->filled('min_price'),
                fn ($q) =>
                    $q->where(
                        'price_per_night',
                        '>=',
                        $minPrice
                    )
            )

            /*
            |--------------------------------------------------------------------------
            | HARGA MAKSIMUM
            |--------------------------------------------------------------------------
            */

            ->when(
                $request->filled('max_price'),
                fn ($q) =>
                    $q->where(
                        'price_per_night',
                        '<=',
                        $maxPrice
                    )
            )

            ->latest()

            ->paginate(9)

            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'properties.index',
            compact(
                'properties',
                'minPrice',
                'maxPrice',
                'selectedStars',
                'selectedGuestRatings'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MITRA INDEX
    |--------------------------------------------------------------------------
    */

    public function mitraIndex(Request $request)
    {
        $user =
            $request->user();

        $properties =
            Property::where(
                'mitra_id',
                $user->id
            )
            ->latest()
            ->paginate(10);

        return view(
            'properties.manage',
            compact(
                'properties'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $facilities =
            Facility::all();

        return view(
            'properties.create',
            compact(
                'facilities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated =
            $request->validate([

                'name' =>
                    'required|string|max:255',

                'description' =>
                    'nullable|string',

                'type' =>
                    'required|in:guesthouse,kost_harian,villa',

                'address' =>
                    'required|string',

                'city' =>
                    'required|string',

                'latitude' =>
                    'nullable|numeric',

                'longitude' =>
                    'nullable|numeric',

                'bedroom_count' =>
                    'required|integer|min:1',

                'guest_capacity' =>
                    'required|integer|min:1',

                'price_per_night' =>
                    'required|numeric|min:0',

                'star_rating' =>
                    'required|integer|min:1|max:5',

                'management_type' =>
                    'required|in:mandiri,dikelola',

                'cover_image' =>
                    'nullable|image|max:2048',

                'photos' =>
                    'nullable|array',

                'photos.*' =>
                    'image|max:2048',

                'facilities' =>
                    'nullable|array',

                'facilities.*' =>
                    'exists:facilities,id',
            ]);

        /*
        |--------------------------------------------------------------------------
        | COVER IMAGE
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'cover_image'
            )
        ) {

            $validated['cover_image'] =
                $request
                    ->file('cover_image')
                    ->store(
                        'properties',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | MITRA
        |--------------------------------------------------------------------------
        */

        $validated['mitra_id'] =
            $request->user()->id;

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $validated['status'] =
            'pending';

        /*
        |--------------------------------------------------------------------------
        | FOTO GALERI
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['photos']
        );

        /*
        |--------------------------------------------------------------------------
        | CREATE PROPERTY
        |--------------------------------------------------------------------------
        */

        $property =
            Property::create(
                $validated
            );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FOTO GALERI
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'photos'
            )
        ) {

            foreach (
                $request->file('photos')
                as $index => $photo
            ) {

                $path =
                    $photo->store(
                        'properties',
                        'public'
                    );

                PropertyImage::create([
                    'property_id' =>
                        $property->id,

                    'path' =>
                        $path,

                    'sort_order' =>
                        $index,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FACILITIES
        |--------------------------------------------------------------------------
        */

        $allFacilities =
            Facility::pluck('id');

        $syncData = [];

        foreach (
            $allFacilities
            as $facilityId
        ) {

            $syncData[$facilityId] = [
                'is_available' =>
                    in_array(
                        $facilityId,
                        $request->input(
                            'facilities',
                            []
                        )
                    ),
            ];
        }

        $property
            ->facilities()
            ->sync(
                $syncData
            );

        return redirect()
            ->route(
                'mitra.properties.index'
            )
            ->with(
                'success',
                'Properti berhasil ditambahkan, menunggu verifikasi admin.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Property $property
    ) {

        $property->load([
            'facilities',
            'mitra',
            'images',
            'reviews.customer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | RATING TAMU
        |--------------------------------------------------------------------------
        */

        $property->loadAvg(
            'reviews',
            'score'
        );

        $property->loadCount(
            'reviews'
        );

        /*
        |--------------------------------------------------------------------------
        | RECOMMENDED
        |--------------------------------------------------------------------------
        */

        $recommendedProperties =
            Property::query()
                ->where(
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

        if (
            $recommendedProperties->count()
            < 5
        ) {

            $remaining =
                5 -
                $recommendedProperties->count();

            $additionalProperties =
                Property::query()
                    ->where(
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
                        $recommendedProperties
                            ->pluck('id')
                    )
                    ->latest()
                    ->take($remaining)
                    ->get();

            $recommendedProperties =
                $recommendedProperties
                    ->concat(
                        $additionalProperties
                    );
        }

        return view(
            'properties.show',
            compact(
                'property',
                'recommendedProperties'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Property $property
    ) {

        $this->authorizeOwner(
            $property
        );

        $facilities =
            Facility::all();

        $property->load(
            'facilities',
            'images'
        );

        return view(
            'properties.edit',
            compact(
                'property',
                'facilities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Property $property
    ) {

        $this->authorizeOwner(
            $property
        );

        $validated =
            $request->validate([

                'name' =>
                    'required|string|max:255',

                'description' =>
                    'nullable|string',

                'type' =>
                    'required|in:guesthouse,kost_harian,villa',

                'address' =>
                    'required|string',

                'city' =>
                    'required|string',

                'latitude' =>
                    'nullable|numeric',

                'longitude' =>
                    'nullable|numeric',

                'bedroom_count' =>
                    'required|integer|min:1',

                'guest_capacity' =>
                    'required|integer|min:1',

                'price_per_night' =>
                    'required|numeric|min:0',

                'star_rating' =>
                    'required|integer|min:1|max:5',

                'management_type' =>
                    'required|in:mandiri,dikelola',

                'cover_image' =>
                    'nullable|image|max:2048',

                'photos' =>
                    'nullable|array',

                'photos.*' =>
                    'image|max:2048',

                'facilities' =>
                    'nullable|array',

                'facilities.*' =>
                    'exists:facilities,id',
            ]);

        /*
        |--------------------------------------------------------------------------
        | COVER
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'cover_image'
            )
        ) {

            $validated['cover_image'] =
                $request
                    ->file('cover_image')
                    ->store(
                        'properties',
                        'public'
                    );
        }

        unset(
            $validated['photos']
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE PROPERTY
        |--------------------------------------------------------------------------
        */

        $property->update(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | GALERI
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'photos'
            )
        ) {

            $nextOrder =
                (int)
                $property
                    ->images()
                    ->max(
                        'sort_order'
                    ) + 1;

            foreach (
                $request->file('photos')
                as $index => $photo
            ) {

                $path =
                    $photo->store(
                        'properties',
                        'public'
                    );

                PropertyImage::create([
                    'property_id' =>
                        $property->id,

                    'path' =>
                        $path,

                    'sort_order' =>
                        $nextOrder + $index,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FACILITIES
        |--------------------------------------------------------------------------
        */

        $allFacilities =
            Facility::pluck('id');

        $syncData = [];

        foreach (
            $allFacilities
            as $facilityId
        ) {

            $syncData[$facilityId] = [
                'is_available' =>
                    in_array(
                        $facilityId,
                        $request->input(
                            'facilities',
                            []
                        )
                    ),
            ];
        }

        $property
            ->facilities()
            ->sync(
                $syncData
            );

        return redirect()
            ->route(
                'mitra.properties.index'
            )
            ->with(
                'success',
                'Properti berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE IMAGE
    |--------------------------------------------------------------------------
    */

    public function destroyImage(
        PropertyImage $image
    ) {

        $this->authorizeOwner(
            $image->property
        );

        $image->delete();

        return back()
            ->with(
                'success',
                'Foto berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE PROPERTY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Property $property
    ) {

        $this->authorizeOwner(
            $property
        );

        $property->delete();

        return redirect()
            ->route(
                'mitra.properties.index'
            )
            ->with(
                'success',
                'Properti berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Property $property
    ) {

        $property->update([
            'status' => 'active',
        ]);

        return back()
            ->with(
                'success',
                'Properti disetujui dan sekarang tayang.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Property $property
    ) {

        $property->update([
            'status' => 'rejected',
        ]);

        return back()
            ->with(
                'success',
                'Properti ditolak.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OWNER AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    private function authorizeOwner(
        Property $property
    ) {

        $user =
            request()->user();

        if (
            $user->hasRole('mitra') &&
            $property->mitra_id !==
                $user->id
        ) {

            abort(
                403,
                'Anda tidak memiliki akses ke properti ini.'
            );
        }
    }
}