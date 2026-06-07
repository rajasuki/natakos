<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\UtilityBill;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UtilityBillController extends Controller
{
    public function index(Request $request): View
    {
        $query = UtilityBill::query()
            ->with(['tenant.user', 'tenant.room'])
            ->orderByDesc('id');

        $filters = $this->filters($request);

        if ($filters['type'] !== null) {
            $query->where('type', $filters['type']);
        }

        if ($filters['status'] !== null) {
            $query->where('status', $filters['status']);
        }

        if ($filters['q'] !== null) {
            $term = '%'.$filters['q'].'%';
            $query->where(function ($q) use ($term) {
                $q->whereHas('tenant.user', function ($uq) use ($term) {
                    $uq->where('name', 'like', $term);
                })->orWhereHas('tenant.room', function ($rq) use ($term) {
                    $rq->where('name', 'like', $term);
                });
            });
        }

        return view('admin.utility-bills.index', [
            'bills' => $query->paginate(10)->withQueryString(),
            'filters' => $filters,
            'hasActiveFilters' => $filters['q'] !== null || $filters['type'] !== null || $filters['status'] !== null,
            'typeLabels' => $this->typeLabels(),
            'statusLabels' => $this->statusLabels(),
            'counts' => $this->counts(),
        ]);
    }

    public function create(): View
    {
        return view('admin.utility-bills.create', [
            'tenants' => $this->tenants(),
            'typeLabels' => $this->typeLabels(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'integer', Rule::exists('tenants', 'id')],
            'type' => ['required', Rule::in(array_keys($this->typeLabels()))],
            'amount' => ['required', 'integer', 'min:0'],
            'period' => ['required', 'regex:/^\d{4}-\d{2}$/'],
            'due_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['unpaid', 'paid'])],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated): void {
            $bill = UtilityBill::create($validated);
            ActivityLogger::created('tagihan_utilitas', $bill->id, "{$this->typeLabels()[$bill->type]} {$bill->period}");
        });

        return redirect()
            ->route('admin.utility-bills.index')
            ->with('success', 'Tagihan utilitas berhasil ditambahkan.');
    }

    public function edit(UtilityBill $utilityBill): View
    {
        $utilityBill->load(['tenant.user', 'tenant.room']);

        return view('admin.utility-bills.edit', [
            'bill' => $utilityBill,
            'tenants' => $this->tenants(),
            'typeLabels' => $this->typeLabels(),
            'statusLabels' => $this->statusLabels(),
        ]);
    }

    public function update(Request $request, UtilityBill $utilityBill): RedirectResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'integer', Rule::exists('tenants', 'id')],
            'type' => ['required', Rule::in(array_keys($this->typeLabels()))],
            'amount' => ['required', 'integer', 'min:0'],
            'period' => ['required', 'regex:/^\d{4}-\d{2}$/'],
            'due_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['unpaid', 'paid'])],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($utilityBill, $validated): void {
            $utilityBill->update($validated);
            ActivityLogger::updated('tagihan_utilitas', $utilityBill->id, "{$this->typeLabels()[$utilityBill->type]} {$utilityBill->period}");
        });

        return redirect()
            ->route('admin.utility-bills.index')
            ->with('success', 'Tagihan utilitas berhasil diperbarui.');
    }

    public function destroy(UtilityBill $utilityBill): RedirectResponse
    {
        $label = "{$this->typeLabels()[$utilityBill->type]} {$utilityBill->period}";
        $utilityBill->delete();

        ActivityLogger::deleted('tagihan_utilitas', $utilityBill->id, $label);

        return redirect()
            ->route('admin.utility-bills.index')
            ->with('success', 'Tagihan utilitas berhasil dihapus.');
    }

    private function typeLabels(): array
    {
        return [
            'water' => 'Air',
            'electricity' => 'Listrik',
            'internet' => 'Internet',
        ];
    }

    private function statusLabels(): array
    {
        return [
            'unpaid' => 'Belum Bayar',
            'paid' => 'Lunas',
        ];
    }

    private function tenants()
    {
        return Tenant::query()->with(['user', 'room'])->orderByDesc('id')->get();
    }

    private function filters(Request $request): array
    {
        $q = trim((string) $request->query('q', ''));
        $type = (string) $request->query('type', '');
        $status = (string) $request->query('status', '');

        return [
            'q' => $q !== '' ? $q : null,
            'type' => array_key_exists($type, $this->typeLabels()) ? $type : null,
            'status' => array_key_exists($status, $this->statusLabels()) ? $status : null,
        ];
    }

    private function counts(): array
    {
        return [
            'total' => UtilityBill::query()->count(),
            'unpaid' => UtilityBill::query()->where('status', 'unpaid')->count(),
            'paid' => UtilityBill::query()->where('status', 'paid')->count(),
        ];
    }
}
