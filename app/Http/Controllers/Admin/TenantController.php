<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(): View
    {
        return view('admin.tenants.index', [
            'tenants' => Tenant::query()
                ->with(['user', 'room'])
                ->latest('id')
                ->get(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function create(): View
    {
        return view('admin.tenants.create', [
            'rooms' => $this->rooms(),
            'roomStatusLabels' => $this->roomStatusLabels(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $this->validateRoomAssignment($validated['room_id'], $validated['status']);

        DB::transaction(function () use ($validated): void {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => $validated['password'],
                'role' => 'tenant',
            ]);

            $tenant = Tenant::create([
                'user_id' => $user->id,
                'room_id' => $validated['room_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncRoomStatuses([$tenant->room_id]);
        });

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Penghuni berhasil ditambahkan.');
    }

    public function edit(Tenant $tenant): View
    {
        return view('admin.tenants.edit', [
            'tenant' => $tenant->load(['user', 'room']),
            'rooms' => $this->rooms(),
            'roomStatusLabels' => $this->roomStatusLabels(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $tenant->load('user');

        $validated = $this->validatedData($request, $tenant);
        $this->validateRoomAssignment($validated['room_id'], $validated['status'], $tenant);
        $previousRoomId = $tenant->room_id;

        DB::transaction(function () use ($tenant, $validated, $previousRoomId): void {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'role' => 'tenant',
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $tenant->user->update($userData);

            $tenant->update([
                'room_id' => $validated['room_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncRoomStatuses([$previousRoomId, $tenant->room_id]);
        });

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Penghuni berhasil diperbarui.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $tenant->load(['user', 'payments']);

        if ($tenant->payments->isNotEmpty()) {
            return redirect()
                ->route('admin.tenants.index')
                ->with('error', 'Penghuni tidak dapat dihapus karena sudah memiliki data pembayaran.');
        }

        $roomId = $tenant->room_id;
        $user = $tenant->user;

        try {
            DB::transaction(function () use ($tenant, $user, $roomId): void {
                $tenant->delete();

                $this->syncRoomStatuses([$roomId]);

                if ($user !== null && $user->role === 'tenant') {
                    $stillHasTenant = Tenant::query()->where('user_id', $user->id)->exists();

                    if (! $stillHasTenant) {
                        $user->delete();
                    }
                }
            });
        } catch (QueryException) {
            return redirect()
                ->route('admin.tenants.index')
                ->with('error', 'Penghuni tidak dapat dihapus karena masih memiliki data terkait.');
        }

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Penghuni berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Tenant $tenant = null): array
    {
        $passwordRules = $tenant === null
            ? ['required', 'string', 'min:8']
            : ['nullable', 'string', 'min:8'];

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($tenant?->user_id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => $passwordRules,
            'room_id' => ['required', 'integer', Rule::exists('rooms', 'id')],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(array_keys($this->statusLabels()))],
            'notes' => ['nullable', 'string'],
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Room>
     */
    private function rooms()
    {
        return Room::query()->orderBy('name')->get();
    }

    /**
     * @return array<string, string>
     */
    private function statusLabels(): array
    {
        return [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'moved_out' => 'Sudah Keluar',
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
     * @param  array<int, int|null>  $roomIds
     */
    private function syncRoomStatuses(array $roomIds): void
    {
        $roomIds = array_values(array_unique(array_filter($roomIds)));

        foreach ($roomIds as $roomId) {
            $room = Room::query()->find($roomId);

            if ($room === null) {
                continue;
            }

            $hasActiveTenant = Tenant::query()
                ->where('room_id', $roomId)
                ->where('status', 'active')
                ->exists();

            if ($hasActiveTenant) {
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

    private function validateRoomAssignment(int $roomId, string $tenantStatus, ?Tenant $tenant = null): void
    {
        if ($tenantStatus !== 'active') {
            return;
        }

        $room = Room::query()->find($roomId);

        if ($room === null) {
            return;
        }

        if ($room->status === 'maintenance') {
            throw ValidationException::withMessages([
                'room_id' => 'Kamar yang sedang dalam perbaikan tidak bisa dipilih untuk penghuni aktif.',
            ]);
        }

        $hasOtherActiveTenant = Tenant::query()
            ->where('room_id', $roomId)
            ->where('status', 'active')
            ->when($tenant !== null, fn ($query) => $query->where('id', '!=', $tenant->id))
            ->exists();

        if ($hasOtherActiveTenant) {
            throw ValidationException::withMessages([
                'room_id' => 'Kamar ini sudah ditempati penghuni aktif lain.',
            ]);
        }
    }
}
