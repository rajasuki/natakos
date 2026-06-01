<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\KosProfile;
use App\Models\Room;
use App\Support\WhatsappLink;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $roomStatusLabels = $this->roomStatusLabels();
        $facilityTypeLabels = $this->facilityTypeLabels();
        $facilities = Facility::query()
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $filters = $this->filters($request, $facilities, array_keys($roomStatusLabels));
        $searchTerm = $filters['q'] !== null ? $this->escapedLikeValue($filters['q']) : null;

        $rooms = Room::query()
            ->with([
                'facilities' => fn ($query) => $query->orderBy('type')->orderBy('name'),
                'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
            ])
            ->when($searchTerm !== null, function ($query) use ($searchTerm) {
                $query->where(function ($searchQuery) use ($searchTerm) {
                    $searchQuery
                        ->where('name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('description', 'like', '%'.$searchTerm.'%')
                        ->orWhere('size', 'like', '%'.$searchTerm.'%')
                        ->orWhere('floor', 'like', '%'.$searchTerm.'%');
                });
            })
            ->when($filters['min_price'] !== null, fn ($query) => $query->where('price', '>=', $filters['min_price']))
            ->when($filters['max_price'] !== null, fn ($query) => $query->where('price', '<=', $filters['max_price']))
            ->when($filters['status'] !== null, fn ($query) => $query->where('status', $filters['status']))
            ->when($filters['facilities'] !== [], function ($query) use ($filters) {
                $query->whereHas(
                    'facilities',
                    fn ($facilityQuery) => $facilityQuery->whereIn('facilities.id', $filters['facilities']),
                    '=',
                    count($filters['facilities'])
                );
            })
            ->when($filters['sort'] === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($filters['sort'] === 'price_desc', fn ($query) => $query->orderBy('price', 'desc'))
            ->when($filters['sort'] === null, fn ($query) => $query->latest('id'))
            ->get();

        return view('public.rooms.index', [
            'profile' => $this->profileData(),
            'rooms' => $rooms,
            'roomStatusLabels' => $roomStatusLabels,
            'facilityTypeLabels' => $facilityTypeLabels,
            'facilityGroups' => $this->facilityGroups($facilities, $facilityTypeLabels),
            'filters' => $filters,
            'hasActiveFilters' => $this->hasActiveFilters($filters),
        ]);
    }

    public function show(Room $room): View
    {
        $room->load([
            'facilities' => fn ($query) => $query->orderBy('type')->orderBy('name'),
            'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
        ]);

        $profile = $this->profileData();

        return view('public.rooms.show', [
            'profile' => $profile,
            'room' => $room,
            'roomStatusLabels' => $this->roomStatusLabels(),
            'facilityTypeLabels' => $this->facilityTypeLabels(),
            'facilityGroups' => $room->facilities->groupBy('type'),
            'whatsappUrl' => WhatsappLink::build(
                $profile['whatsapp_number'],
                'Halo, saya tertarik dengan '.$room->name.' di NATAKOS. Apakah masih tersedia?'
            ),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function profileData(): array
    {
        $profile = KosProfile::query()->first();
        $whatsappNumber = WhatsappLink::normalizeNumber($profile?->whatsapp_number);

        return [
            'name' => $profile?->name ?: 'NATAKOS',
            'description' => $profile?->description ?: 'NATAKOS menghadirkan kamar kos yang rapi, terkelola, dan siap mendukung rutinitas harian penghuni dengan sistem manajemen yang jelas.',
            'address' => $profile?->address ?: 'Alamat kos belum diatur.',
            'whatsapp_number' => $whatsappNumber,
            'whatsapp_url' => WhatsappLink::build($whatsappNumber, 'Halo, saya ingin bertanya tentang kamar di NATAKOS.'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function roomStatusLabels(): array
    {
        return [
            'available' => 'Tersedia',
            'occupied' => 'Terisi',
            'maintenance' => 'Perbaikan',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function facilityTypeLabels(): array
    {
        return [
            'room' => 'Fasilitas Kamar',
            'public' => 'Fasilitas Umum',
        ];
    }

    /**
     * @param  array<string, string>  $facilityTypeLabels
     * @return array<string, Collection<int, Facility>>
     */
    private function facilityGroups(Collection $facilities, array $facilityTypeLabels): array
    {
        $groupedFacilities = $facilities->groupBy('type');

        return collect(array_keys($facilityTypeLabels))
            ->mapWithKeys(fn (string $type) => [$type => $groupedFacilities->get($type, collect())])
            ->all();
    }

    /**
     * @param  array{q:string|null,min_price:int|null,max_price:int|null,status:string|null,facilities:array<int,int>,sort:string|null}  $filters
     */
    private function filters(Request $request, Collection $facilities, array $allowedStatuses): array
    {
        $status = (string) $request->query('status', '');
        $sort = (string) $request->query('sort', '');
        $validFacilityIds = $facilities->modelKeys();
        $selectedFacilityIds = collect((array) $request->query('facilities', []))
            ->map(function ($id): ?int {
                $value = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

                return $value === false ? null : (int) $value;
            })
            ->filter()
            ->unique()
            ->intersect($validFacilityIds)
            ->values()
            ->all();

        return [
            'q' => $this->searchTerm($request->query('q')),
            'min_price' => $this->numericQuery($request->query('min_price')),
            'max_price' => $this->numericQuery($request->query('max_price')),
            'status' => in_array($status, $allowedStatuses, true) ? $status : null,
            'facilities' => $selectedFacilityIds,
            'sort' => in_array($sort, ['price_asc', 'price_desc'], true) ? $sort : null,
        ];
    }

    private function hasActiveFilters(array $filters): bool
    {
        return $filters['q'] !== null
            || $filters['min_price'] !== null
            || $filters['max_price'] !== null
            || $filters['status'] !== null
            || $filters['facilities'] !== [];
    }

    private function numericQuery(mixed $value): ?int
    {
        $validated = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

        return $validated === false ? null : (int) $validated;
    }

    private function searchTerm(mixed $value): ?string
    {
        $search = Str::squish((string) ($value ?? ''));

        if ($search === '') {
            return null;
        }

        return $search;
    }

    private function escapedLikeValue(string $value): string
    {
        return addcslashes($value, '\\%_');
    }

}
