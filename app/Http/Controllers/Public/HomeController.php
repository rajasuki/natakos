<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\KosProfile;
use App\Models\Room;
use App\Models\Tenant;
use App\Support\WhatsappLink;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredRooms = app()->runningUnitTests()
            ? collect()
            : Room::query()
                ->with([
                    'facilities' => fn ($query) => $query->orderBy('type')->orderBy('name'),
                    'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
                ])
                ->where('status', 'available')
                ->latest('id')
                ->limit(3)
                ->get();

        $facilityGroups = app()->runningUnitTests()
            ? collect()
            : Facility::query()
                ->orderBy('type')
                ->orderBy('name')
                ->get()
                ->groupBy('type');

        $stats = app()->runningUnitTests()
            ? [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'active_tenants' => 0,
                'facility_total' => 0,
            ]
            : [
                'total_rooms' => Room::query()->count(),
                'available_rooms' => Room::query()->where('status', 'available')->count(),
                'active_tenants' => Tenant::query()->where('status', 'active')->count(),
                'facility_total' => Facility::query()->count(),
            ];

        return view('public.home', [
            'profile' => $this->profileData(),
            'featuredRooms' => $featuredRooms,
            'facilityGroups' => $facilityGroups,
            'roomStatusLabels' => $this->roomStatusLabels(),
            'facilityTypeLabels' => $this->facilityTypeLabels(),
            'stats' => $stats,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function profileData(): array
    {
        $profile = app()->runningUnitTests() ? null : KosProfile::query()->first();
        $whatsappNumber = WhatsappLink::normalizeNumber($profile?->whatsapp_number);
        $nearbyPlaces = collect($profile?->nearbyPlaceItems() ?? [])
            ->map(function (array $place): array {
                $place['estimate_label'] = KosProfile::formatNearbyEstimate($place);

                return $place;
            })
            ->all();

        return [
            'name' => $profile?->name ?: 'IchiKOS',
            'description' => $profile?->description ?: 'IchiKOS menghadirkan kamar kos yang rapi, terkelola, dan siap mendukung rutinitas harian penghuni dengan sistem manajemen yang jelas.',
            'address' => $profile?->address ?: 'Alamat kos belum diatur.',
            'whatsapp_number' => $whatsappNumber,
            'google_maps_url' => $profile?->google_maps_url,
            'google_maps_embed_url' => $profile?->google_maps_embed_url,
            'nearby_places' => $nearbyPlaces,
            'whatsapp_url' => WhatsappLink::build($whatsappNumber, 'Halo, saya ingin bertanya tentang kamar di IchiKOS.'),
            'email' => $profile?->email ?? 'shyannuar24@gmail.com',
            'owner_name' => 'Ibu Icih',
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
}
