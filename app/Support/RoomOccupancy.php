<?php

namespace App\Support;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Validation\ValidationException;

class RoomOccupancy
{
    public static function ensureStatusIsConsistent(string $status, ?Room $room = null): void
    {
        $hasActiveTenant = $room !== null
            ? self::hasActiveTenant($room)
            : false;

        if ($hasActiveTenant && $status !== 'occupied') {
            throw ValidationException::withMessages([
                'status' => 'Status kamar harus tetap Terisi selama masih ada penghuni aktif di kamar ini.',
            ]);
        }

        if (! $hasActiveTenant && $status === 'occupied') {
            throw ValidationException::withMessages([
                'status' => 'Status Terisi hanya bisa dipakai jika kamar sudah terhubung ke penghuni aktif.',
            ]);
        }
    }

    /**
     * @param  array<int, int|null>  $roomIds
     */
    public static function syncStatuses(array $roomIds): void
    {
        $roomIds = array_values(array_unique(array_filter($roomIds)));

        foreach ($roomIds as $roomId) {
            $room = Room::query()->find($roomId);

            if ($room === null) {
                continue;
            }

            if (self::hasActiveTenant($room)) {
                if ($room->status !== 'occupied') {
                    $room->update(['status' => 'occupied']);
                }

                continue;
            }

            if ($room->status === 'occupied') {
                $room->update(['status' => 'available']);
            }
        }
    }

    public static function hasActiveTenant(Room $room): bool
    {
        return Tenant::query()
            ->where('room_id', $room->id)
            ->where('status', 'active')
            ->exists();
    }
}
