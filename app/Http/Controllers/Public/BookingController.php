<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\KosProfile;
use App\Models\Room;
use App\Support\WhatsappLink;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(Room $room): View
    {
        abort_if($room->status !== 'available', 404);

        return view('public.rooms.book', [
            'room' => $room,
            'profile' => $this->profileData(),
        ]);
    }

    public function store(Request $request, Room $room): RedirectResponse
    {
        abort_if($room->status !== 'available', 404);

        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'duration' => ['required', 'integer', 'in:1,3,6,12'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'start_date.after_or_equal' => 'Tanggal masuk harus hari ini atau setelahnya.',
            'duration.in' => 'Pilih durasi sewa 1, 3, 6, atau 12 bulan.',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addMonths((int) $validated['duration'])->subDay();

        $existing = BookingRequest::query()
            ->where('user_id', $request->user()->id)
            ->where('room_id', $room->id)
            ->whereIn('status', ['pending'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki pengajuan sewa untuk kamar ini yang masih menunggu.');
        }

        BookingRequest::create([
            'user_id' => $request->user()->id,
            'room_id' => $room->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return redirect()->route('tenant.dashboard')
            ->with('success', 'Pengajuan sewa berhasil dikirim. Admin akan memproses pengajuan Anda segera.');
    }

    /**
     * @return array<string, mixed>
     */
    private function profileData(): array
    {
        $profile = app()->runningUnitTests() ? null : KosProfile::query()->first();
        $whatsappNumber = WhatsappLink::normalizeNumber($profile?->whatsapp_number);
        $nearbyPlaces = collect($profile?->nearbyPlaceItems() ?? [])->all();

        return [
            'name' => $profile?->name ?: 'IchiKOS',
            'description' => $profile?->description ?: '',
            'address' => $profile?->address ?: '',
            'owner_name' => $profile?->owner_name ?: '',
            'whatsapp_number' => $whatsappNumber,
            'whatsapp_url' => WhatsappLink::build($whatsappNumber, 'Halo, saya ingin bertanya tentang kamar di IchiKOS.'),
            'google_maps_url' => $profile?->google_maps_url,
            'google_maps_embed_url' => $profile?->google_maps_embed_url,
            'nearby_places' => $nearbyPlaces,
            'email' => $profile?->email ?? 'shyannuar24@gmail.com',
        ];
    }
}
