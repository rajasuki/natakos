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
        return view('admin.settings.kos-profile', [
            'profile' => $this->profile(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = $this->profile();
        $data = $this->validatedData($request);
        $oldLogo = $profile->logo;

        $data['whatsapp_number'] = WhatsappLink::normalizeNumber($data['whatsapp_number']);

        $profile->update($data);

        if (array_key_exists('logo', $data) && $oldLogo !== $data['logo']) {
            $this->deleteLogo($oldLogo);
        }

        return redirect()
            ->route('admin.settings.kos-profile.edit')
            ->with('success', 'Profil kos berhasil diperbarui.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'google_maps_url' => ['nullable', 'url', 'max:2048'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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
        ];
    }

    private function deleteLogo(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
