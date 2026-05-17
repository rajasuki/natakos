<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\KosProfile;
use App\Models\Room;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        return view('public.rooms.index', [
            'profile' => $this->profileData(),
            'rooms' => Room::query()
                ->with([
                    'facilities' => fn ($query) => $query->orderBy('type')->orderBy('name'),
                    'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
                ])
                ->latest('id')
                ->get(),
            'roomStatusLabels' => $this->roomStatusLabels(),
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
            'whatsappUrl' => $this->whatsappUrl(
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
