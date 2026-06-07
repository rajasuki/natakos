<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KosProfile;
use App\Support\WhatsappLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KosProfileController extends Controller
{
    public function edit(): View
    {
        $profile = $this->profile();

        return view('admin.kos-profile.edit', [
            'profile' => $profile,
            'nearbyPlaces' => $profile->nearbyPlaceItems(),
            'estimateUnits' => KosProfile::estimateUnitOptions(),
            'travelModes' => KosProfile::travelModeOptions(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = $this->profile();
        $data = $this->validatedData($request);
        $oldLogo = $profile->logo;

        $data['whatsapp_number'] = WhatsappLink::normalizeNumber($data['whatsapp_number']);

        $nearbyPlaces = $this->collectNearbyPlaces($request);
        $data['nearby_places'] = KosProfile::serializeNearbyPlaces($nearbyPlaces);

        $profile->fill($data);

        if (array_key_exists('logo', $data) && $oldLogo !== $data['logo']) {
            $this->deleteLogo($oldLogo);
        }

        $profile->save();

        return redirect()
            ->route('admin.kos-profile.edit')
            ->with('success', 'Profil kos berhasil diperbarui.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:500'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'google_maps_embed_url' => ['nullable', 'string', 'max:2048'],
            'google_maps_url' => ['nullable', 'url', 'max:2048'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'late_fee_per_day' => ['nullable', 'integer', 'min:0'],
            'max_late_fee' => ['nullable', 'integer', 'min:0'],
        ]);

        $logo = $validated['logo'] ?? null;
        unset($validated['logo']);

        if ($logo !== null) {
            $validated['logo'] = $logo->store('kos', 'public');
        }

        return $validated;
    }

    private function profile(): KosProfile
    {
        return KosProfile::query()->first() ?? KosProfile::query()->create($this->defaultValues());
    }

    /**
     * @return array<string, string|null>
     */
    private function defaultValues(): array
    {
        return [
            'name' => 'NATAKOS',
            'description' => 'Website manajemen kos untuk mengelola kamar, penghuni, fasilitas, dan pembayaran manual.',
            'address' => 'Alamat kos belum diatur.',
            'whatsapp_number' => '6285217430009',
            'google_maps_url' => null,
            'logo' => null,
            'late_fee_per_day' => 0,
            'max_late_fee' => null,
        ];
    }

    private function deleteLogo(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function collectNearbyPlaces(Request $request): array
    {
        $names = (array) $request->input('nearby_name', []);
        $values = (array) $request->input('nearby_value', []);
        $units = (array) $request->input('nearby_unit', []);
        $modes = (array) $request->input('nearby_mode', []);

        $places = [];

        foreach ($names as $index => $name) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }

            $places[] = [
                'name' => $name,
                'estimate_value' => trim((string) ($values[$index] ?? '')),
                'estimate_unit' => trim((string) ($units[$index] ?? '')),
                'travel_mode' => trim((string) ($modes[$index] ?? '')),
            ];
        }

        return $places;
    }
}
