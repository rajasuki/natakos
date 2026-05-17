<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'rooms' => Room::query()->latest('id')->get(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function create(): View
    {
        return view('admin.rooms.create', [
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        Room::create($data);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room): View
    {
        return view('admin.rooms.edit', [
            'room' => $room,
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $oldImage = $room->main_image;
        $data = $this->validatedData($request);
        $data['slug'] = $this->generateUniqueSlug($data['name'], $room);

        $room->update($data);

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
        ]);

        $image = $validated['main_image'] ?? null;
        unset($validated['main_image']);

        if ($image !== null) {
            $validated['main_image'] = $image->store('rooms', 'public');
        }

        return $validated;
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

    private function deleteImage(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
