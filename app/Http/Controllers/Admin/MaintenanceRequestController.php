<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MaintenanceRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = MaintenanceRequest::query()
            ->with(['tenant.user', 'tenant.room', 'room', 'resolvedBy'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'in_progress' THEN 1 WHEN 'resolved' THEN 2 ELSE 3 END")
            ->orderByDesc('id');

        $filters = $this->filters($request);

        if ($filters['status'] !== null) {
            $query->where('status', $filters['status']);
        }

        if ($filters['priority'] !== null) {
            $query->where('priority', $filters['priority']);
        }

        if ($filters['q'] !== null) {
            $term = '%'.$filters['q'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhereHas('tenant.user', function ($uq) use ($term) {
                        $uq->where('name', 'like', $term);
                    })->orWhereHas('tenant.room', function ($rq) use ($term) {
                        $rq->where('name', 'like', $term);
                    });
            });
        }

        return view('admin.maintenance-requests.index', [
            'requests' => $query->paginate(10)->withQueryString(),
            'filters' => $filters,
            'hasActiveFilters' => $filters['q'] !== null || $filters['status'] !== null || $filters['priority'] !== null,
            'statusLabels' => $this->statusLabels(),
            'priorityLabels' => $this->priorityLabels(),
            'counts' => $this->counts(),
        ]);
    }

    public function edit(MaintenanceRequest $maintenanceRequest): View
    {
        $maintenanceRequest->load(['tenant.user', 'tenant.room', 'room', 'resolvedBy']);

        return view('admin.maintenance-requests.edit', [
            'request' => $maintenanceRequest,
            'statusLabels' => $this->statusLabels(),
            'priorityLabels' => $this->priorityLabels(),
        ]);
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($this->statusLabels()))],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $data = $validated;

        if (in_array($validated['status'], ['resolved', 'rejected'], true)) {
            $data['resolved_by'] = $request->user()->id;
            $data['resolved_at'] = now();
        } else {
            $data['resolved_by'] = null;
            $data['resolved_at'] = null;
        }

        DB::transaction(function () use ($maintenanceRequest, $data): void {
            $maintenanceRequest->update($data);
            ActivityLogger::updated('pengajuan_perbaikan', $maintenanceRequest->id, $maintenanceRequest->title);
        });

        return redirect()
            ->route('admin.maintenance-requests.index')
            ->with('success', 'Status pengajuan perbaikan berhasil diperbarui.');
    }

    public function destroy(MaintenanceRequest $maintenanceRequest): RedirectResponse
    {
        $title = $maintenanceRequest->title;
        $maintenanceRequest->delete();

        ActivityLogger::deleted('pengajuan_perbaikan', $maintenanceRequest->id, $title);

        return redirect()
            ->route('admin.maintenance-requests.index')
            ->with('success', 'Pengajuan perbaikan berhasil dihapus.');
    }

    private function statusLabels(): array
    {
        return [
            'pending' => 'Menunggu',
            'in_progress' => 'Ditangani',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
        ];
    }

    private function priorityLabels(): array
    {
        return [
            'low' => 'Rendah',
            'normal' => 'Normal',
            'high' => 'Tinggi',
            'urgent' => 'Darurat',
        ];
    }

    private function filters(Request $request): array
    {
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');
        $priority = (string) $request->query('priority', '');

        return [
            'q' => $q !== '' ? $q : null,
            'status' => array_key_exists($status, $this->statusLabels()) ? $status : null,
            'priority' => array_key_exists($priority, $this->priorityLabels()) ? $priority : null,
        ];
    }

    private function counts(): array
    {
        return [
            'total' => MaintenanceRequest::query()->count(),
            'pending' => MaintenanceRequest::query()->where('status', 'pending')->count(),
            'in_progress' => MaintenanceRequest::query()->where('status', 'in_progress')->count(),
            'resolved' => MaintenanceRequest::query()->where('status', 'resolved')->count(),
        ];
    }
}
