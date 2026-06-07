<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class RoomImageController extends Controller
{
    public function index(Room $room): View
    {
        return view('admin.rooms.images', [
            'room' => $room->load([
                'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
            ]),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function store(Request $request, Room $room): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $storedPaths = [];
        $caption = trim((string) ($data['caption'] ?? ''));
        $caption = $caption !== '' ? $caption : null;
        $nextSortOrder = (int) ($room->images()->max('sort_order') ?? 0);
        $newImageIds = [];

        try {
            foreach ($data['images'] as $uploadedImage) {
                $storedPaths[] = $uploadedImage->store('room-images', 'public');
            }

            DB::transaction(function () use ($room, $storedPaths, $caption, &$nextSortOrder, &$newImageIds) {
                foreach ($storedPaths as $path) {
                    $nextSortOrder++;

                    $image = $room->images()->create([
                        'image_path' => $path,
                        'caption' => $caption,
                        'sort_order' => $nextSortOrder,
                    ]);
                    $newImageIds[] = $image->id;
                }
            });
        } catch (Throwable) {
            foreach ($storedPaths as $path) {
                $this->deleteImage($path);
            }

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Foto galeri gagal diunggah. Silakan coba lagi.'], 500);
            }

            return redirect()
                ->route('admin.rooms.edit', $room)
                ->with('error', 'Foto galeri gagal diunggah. Silakan coba lagi.');
        }

        if ($request->ajax()) {
            $newImages = RoomImage::whereIn('id', $newImageIds)
                ->get()
                ->map(fn ($img) => [
                    'id' => $img->id,
                    'image_path' => asset('storage/'.$img->image_path),
                    'delete_url' => route('admin.rooms.images.destroy', [$room, $img]),
                ]);

            return response()->json(['success' => true, 'images' => $newImages]);
        }

        $uploadedCount = count($storedPaths);

        return redirect()
            ->route('admin.rooms.edit', $room)
            ->with('success', $uploadedCount === 1 ? 'Foto galeri berhasil diunggah.' : $uploadedCount.' foto galeri berhasil diunggah.');
    }

    public function destroy(Room $room, RoomImage $image): RedirectResponse
    {
        if ($image->room_id !== $room->id) {
            abort(404);
        }

        $imagePath = $image->image_path;

        try {
            $image->delete();
        } catch (Throwable) {
            return redirect()
                ->route('admin.rooms.edit', $room)
                ->with('error', 'Foto galeri gagal dihapus.');
        }

        $this->deleteImage($imagePath);

        return redirect()
            ->route('admin.rooms.edit', $room)
            ->with('success', 'Foto galeri berhasil dihapus.');
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
