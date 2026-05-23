<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use App\Support\RoomOccupancy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->filters($request);

        return view('admin.tenants.index', [
            'tenants' => $this->tenantsQuery($filters)
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'tenantCounts' => $this->activeTenantCounts(),
            'filters' => $filters,
            'hasActiveFilters' => $this->hasActiveFilters($filters),
            'filterRooms' => $this->rooms(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function history(Request $request): View
    {
        $filters = $this->filters($request, true);

        return view('admin.tenants.history', [
            'tenants' => $this->tenantsQuery($filters, true)
                ->orderByDesc('end_date')
                ->orderByDesc('id')
                ->paginate(10)
                ->withQueryString(),
            'historyCounts' => $this->historyTenantCounts(),
            'filters' => $filters,
            'hasActiveFilters' => $this->hasActiveFilters($filters),
            'filterRooms' => $this->rooms(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $history = $request->boolean('history');
        $filters = $this->filters($request, $history);
        $tenants = $this->tenantsQuery($filters, $history)
            ->orderByDesc($history ? 'end_date' : 'id')
            ->get();

        return response()->streamDownload(function () use ($tenants): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, ['Nama penghuni', 'Email', 'Nomor HP', 'Kamar', 'Tanggal masuk', 'Tanggal keluar', 'Status', 'Catatan']);

            foreach ($tenants as $tenant) {
                fputcsv($handle, [
                    $tenant->user?->name,
                    $tenant->user?->email,
                    $tenant->user?->phone,
                    $tenant->room?->name,
                    $tenant->start_date?->format('Y-m-d'),
                    $tenant->end_date?->format('Y-m-d'),
                    $tenant->status,
                    $tenant->notes,
                ]);
            }

            fclose($handle);
        }, $history ? 'tenant-history-export.csv' : 'active-tenants-export.csv');
    }

    public function create(): View
    {
        return view('admin.tenants.create', [
            'rooms' => $this->rooms(),
            'roomStatusLabels' => $this->roomStatusLabels(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }



    public function createExisting(): View
{
    return view('admin.tenants.create-existing', [
        'rooms'            => $this->rooms(),
        'roomStatusLabels' => $this->roomStatusLabels(),
        'statusLabels'     => $this->statusLabels(),
        'existingUsers'    => User::query()
            ->where('role', 'tenant')
            ->orderBy('name')
            ->get(),
    ]);
}

public function storeExisting(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'user_id'    => ['required', 'integer', Rule::exists('users', 'id')],
        'room_id'    => ['required', 'integer', Rule::exists('rooms', 'id')],
        'start_date' => ['required', 'date'],
        'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        'status'     => ['required', Rule::in(array_keys($this->statusLabels()))],
        'notes'      => ['nullable', 'string'],
    ]);

    if (($validated['status'] ?? null) === 'moved_out' && empty($validated['end_date'])) {
        throw ValidationException::withMessages([
            'end_date' => 'Tanggal keluar wajib diisi jika status penghuni adalah Sudah Keluar.',
        ]);
    }

    $this->validateRoomAssignment($validated['room_id'], $validated['status']);

    DB::transaction(function () use ($validated): void {
        Tenant::create([
            'user_id'    => $validated['user_id'],
            'room_id'    => $validated['room_id'],
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'] ?? null,
            'status'     => $validated['status'],
            'notes'      => $validated['notes'] ?? null,
        ]);

        RoomOccupancy::syncStatuses([$validated['room_id']]);
    });

    return redirect()
        ->route('admin.tenants.index')
        ->with('success', 'Penempatan berhasil ditambahkan ke akun penghuni yang sudah ada.');
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

            RoomOccupancy::syncStatuses([$tenant->room_id]);
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

    public function checkout(Tenant $tenant): View
    {
        $tenant->load(['user', 'room']);

        if ($tenant->status !== 'active') {
            abort(404);
        }

        return view('admin.tenants.checkout', [
            'tenant' => $tenant,
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

            RoomOccupancy::syncStatuses([$previousRoomId, $tenant->room_id]);
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

                RoomOccupancy::syncStatuses([$roomId]);

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

    public function processCheckout(Request $request, Tenant $tenant): RedirectResponse
    {
        $tenant->load(['user', 'room']);

        if ($tenant->status !== 'active') {
            return redirect()
                ->route('admin.tenants.index')
                ->with('error', 'Check-out hanya bisa dilakukan untuk penghuni yang masih aktif.');
        }

        $validated = $request->validate([
            'end_date' => ['required', 'date', 'after_or_equal:'.$tenant->start_date?->format('Y-m-d')],
            'notes' => ['nullable', 'string'],
        ], [], [
            'end_date' => 'tanggal check-out',
        ]);

        $roomId = $tenant->room_id;

        DB::transaction(function () use ($tenant, $validated, $roomId): void {
            $tenant->update([
                'status' => 'moved_out',
                'end_date' => $validated['end_date'],
                'notes' => $validated['notes'] ?? $tenant->notes,
            ]);

            RoomOccupancy::syncStatuses([$roomId]);
        });

        return redirect()
            ->route('admin.tenants.history')
            ->with('success', 'Check-out penghuni berhasil diproses dan riwayat masa tinggal sudah disimpan.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Tenant $tenant = null): array
    {
        $passwordRules = $tenant === null
            ? ['required', 'string', 'min:8']
            : ['nullable', 'string', 'min:8'];

        $validated = $request->validate([
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

        if (($validated['status'] ?? null) === 'moved_out' && empty($validated['end_date'])) {
            throw ValidationException::withMessages([
                'end_date' => 'Tanggal keluar wajib diisi jika status penghuni adalah Sudah Keluar.',
            ]);
        }

        return $validated;
    }

    /**
     * @return Collection<int, Room>
     */
    private function rooms()
    {
        return Room::query()->orderBy('name')->get();
    }

    /**
     * @param  array{q:string|null,status:string|null,room_id:int|null}  $filters
     */
    private function tenantsQuery(array $filters, bool $history = false)
    {
        return Tenant::query()
            ->with(['user', 'room'])
            ->withCount('payments')
            ->when($history, function ($query) use ($filters) {
                $query
                    ->whereIn('status', ['inactive', 'moved_out'])
                    ->when($filters['status'] !== null, fn ($historyQuery) => $historyQuery->where('status', $filters['status']));
            }, fn ($query) => $query->where('status', 'active'))
            ->when($filters['q'] !== null, function ($query) use ($filters) {
                $term = '%'.$filters['q'].'%';

                $query->where(function ($nestedQuery) use ($term) {
                    $nestedQuery
                        ->whereHas('user', function ($userQuery) use ($term) {
                            $userQuery
                                ->where('name', 'like', $term)
                                ->orWhere('email', 'like', $term)
                                ->orWhere('phone', 'like', $term);
                        })
                        ->orWhereHas('room', fn ($roomQuery) => $roomQuery->where('name', 'like', $term))
                        ->orWhere('notes', 'like', $term);
                });
            })
            ->when($filters['room_id'] !== null, fn ($query) => $query->where('room_id', $filters['room_id']));
    }

    /**
     * @return array{q:string|null,status:string|null,room_id:int|null}
     */
    private function filters(Request $request, bool $history = false): array
    {
        $status = (string) $request->query('status', '');
        $roomId = filter_var($request->query('room_id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $q = trim((string) $request->query('q', ''));

        return [
            'q' => $q !== '' ? $q : null,
            'status' => $history && in_array($status, ['inactive', 'moved_out'], true) ? $status : null,
            'room_id' => $roomId === false ? null : (int) $roomId,
        ];
    }

    /**
     * @param  array{q:string|null,status:string|null,room_id:int|null}  $filters
     */
    private function hasActiveFilters(array $filters): bool
    {
        return $filters['q'] !== null || $filters['status'] !== null || $filters['room_id'] !== null;
    }

    /**
     * @return array<string, int>
     */
    private function activeTenantCounts(): array
    {
        return [
            'Penghuni aktif' => Tenant::query()->where('status', 'active')->count(),
        ];
    }

    /**
     * @return array<string, int>
     */
    private function historyTenantCounts(): array
    {
        return [
            'Total riwayat' => Tenant::query()->whereIn('status', ['inactive', 'moved_out'])->count(),
            'Tidak Aktif' => Tenant::query()->where('status', 'inactive')->count(),
            'Sudah Keluar' => Tenant::query()->where('status', 'moved_out')->count(),
        ];
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
