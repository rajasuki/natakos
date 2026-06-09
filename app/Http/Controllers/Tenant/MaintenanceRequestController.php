<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MaintenanceRequestController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $this->activeTenant($request);

        if ($tenant === null) {
            abort(404, 'Akun belum terhubung ke tenant aktif.');
        }

        $requests = MaintenanceRequest::query()
            ->where('tenant_id', $tenant->id)
            ->orderByDesc('id')
            ->paginate(10);

        return view('tenant.maintenance-requests.index', [
            'requests' => $requests,
            'statusLabels' => $this->statusLabels(),
            'priorityLabels' => $this->priorityLabels(),
        ]);
    }

    public function create(Request $request): View
    {
        $tenant = $this->activeTenant($request);

        if ($tenant === null) {
            abort(404);
        }

        return view('tenant.maintenance-requests.create', [
            'tenant' => $tenant,
            'priorityLabels' => $this->priorityLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = $this->activeTenant($request);

        if ($tenant === null) {
            return redirect()
                ->route('tenant.dashboard')
                ->with('error', 'Akun belum terhubung ke tenant aktif.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', Rule::in(array_keys($this->priorityLabels()))],
        ]);

        MaintenanceRequest::create([
            'tenant_id' => $tenant->id,
            'room_id' => $tenant->room_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('tenant.maintenance-requests.index')
            ->with('success', 'Pengajuan perbaikan berhasil dikirim.');
    }

    private function activeTenant(Request $request): ?Tenant
    {
        return Tenant::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->first();
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
}
//