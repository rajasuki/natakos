<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\KosProfile;
use App\Models\Room;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredRooms = rescue(
            fn () => Room::query()
                ->with([
                    'facilities' => fn ($query) => $query->orderBy('type')->orderBy('name'),
                    'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
                ])
                ->where('status', 'available')
                ->latest('id')
                ->limit(3)
                ->get(),
            collect(),
            report: false,
        );

        $facilityGroups = rescue(
            fn () => Facility::query()
                ->orderBy('type')
                ->orderBy('name')
                ->get()
                ->groupBy('type'),
            collect(),
            report: false,
        );

        $stats = rescue(
            fn () => [
                'total_rooms' => Room::query()->count(),
                'available_rooms' => Room::query()->where('status', 'available')->count(),
                'facility_total' => Facility::query()->count(),
            ],
            [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'facility_total' => 0,
            ],
            report: false,
        );

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
     * @return array<string, string>
     */
    private function profileData(): array
    {
        $profile = rescue(fn () => KosProfile::query()->first(), null, report: false);
        $whatsappNumber = $this->normalizeWhatsappNumber($profile?->whatsapp_number);

        return [
            'name' => $profile?->name ?: 'NATAKOS',
            'description' => $profile?->description ?: 'NATAKOS menghadirkan kamar kos yang rapi, terkelola, dan siap mendukung rutinitas harian penghuni dengan sistem manajemen yang jelas.',
            'address' => $profile?->address ?: 'Alamat kos belum diatur.',
            'whatsapp_number' => $whatsappNumber,
            'whatsapp_url' => $this->whatsappUrl($whatsappNumber, 'Halo, saya ingin bertanya tentang kamar di NATAKOS.'),
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

    private function normalizeWhatsappNumber(?string $number): string
    {
        $normalized = preg_replace('/\D+/', '', $number ?? '') ?? '';

        if ($normalized === '') {
            return '6285217430009';
        }

        if (str_starts_with($normalized, '0')) {
            return '62'.substr($normalized, 1);
        }

        return $normalized;
    }

    private function whatsappUrl(string $number, string $message): string
    {
        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}
