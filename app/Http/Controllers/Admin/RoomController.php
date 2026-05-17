<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        return view('admin.rooms.index', [
            'rooms' => Room::query()
                ->with(['facilities' => fn ($query) => $query->orderBy('type')->orderBy('name')])
                ->latest('id')
                ->get(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function create(): View
    {
        return view('admin.rooms.create', [
            'statusLabels' => $this->statusLabels(),
            'facilityGroups' => $this->facilityGroups(),
            'facilityTypeLabels' => $this->facilityTypeLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $facilityIds = $this->extractFacilityIds($data);
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        $room = Room::create($data);
        $room->facilities()->sync($facilityIds);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room): View
    {
        return view('admin.rooms.edit', [
            'room' => $room->load(['facilities' => fn ($query) => $query->orderBy('type')->orderBy('name')]),
            'statusLabels' => $this->statusLabels(),
            'facilityGroups' => $this->facilityGroups(),
            'facilityTypeLabels' => $this->facilityTypeLabels(),
        ]);
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $oldImage = $room->main_image;
        $data = $this->validatedData($request);
        $facilityIds = $this->extractFacilityIds($data);
        $data['slug'] = $this->generateUniqueSlug($data['name'], $room);

        $room->update($data);
        $room->facilities()->sync($facilityIds);

        if (array_key_exists('main_image', $data) && $oldImage !== $data['main_image']) {
            $this->deleteImage($oldImage);
        }

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if ($room->tenants()->where('status', 'active')->exists()) {
            return redirect()
                ->route('admin.rooms.index')
                ->with('error', 'Kamar tidak dapat dihapus karena masih ditempati penghuni aktif.');
        }

        if ($room->tenants()->exists()) {
            return redirect()
                ->route('admin.rooms.index')
                ->with('error', 'Kamar tidak dapat dihapus karena masih memiliki riwayat penghuni.');
        }

        $mainImage = $room->main_image;

        try {
            $room->delete();
        } catch (QueryException) {
            return redirect()
                ->route('admin.rooms.index')
                ->with('error', 'Kamar tidak dapat dihapus karena masih memiliki data terkait.');
        }

        $this->deleteImage($mainImage);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:100'],
            'floor' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys($this->statusLabels()))],
            'main_image' => ['nullable', 'image'],
            'facility_ids' => ['nullable', 'array'],
            'facility_ids.*' => ['integer', 'distinct', Rule::exists('facilities', 'id')],
        ]);

        $image = $validated['main_image'] ?? null;
        unset($validated['main_image']);

        if ($image !== null) {
            $validated['main_image'] = $image->store('rooms', 'public');
        }

        return $validated;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, int>
     */
    private function extractFacilityIds(array &$data): array
    {
        $facilityIds = array_values($data['facility_ids'] ?? []);
        unset($data['facility_ids']);

        return $facilityIds;
    }

    private function generateUniqueSlug(string $name, ?Room $room = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'room';
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->slugExists($slug, $room)) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?Room $room = null): bool
    {
        return Room::query()
            ->where('slug', $slug)
            ->when($room !== null, function ($query) use ($room) {
                $query->where('id', '!=', $room->id);
            })
            ->exists();
    }

    /**
     * @return array<string, string>
     */
    private function statusLabels(): array
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
     * @return array<string, \Illuminate\Support\Collection<int, Facility>>
     */
    private function facilityGroups(): array
    {
        $groupedFacilities = Facility::query()
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return collect(array_keys($this->facilityTypeLabels()))
            ->mapWithKeys(fn (string $type) => [$type => $groupedFacilities->get($type, collect())])
            ->all();
    }

    private function deleteImage(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
