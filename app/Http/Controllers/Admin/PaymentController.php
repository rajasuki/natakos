<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Tenant;
use App\Support\ActivityLogger;
use App\Support\PaymentWorkflow;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->filters($request);

        $payments = $this->paymentsQuery($filters)
            ->with(['tenant.user', 'tenant.room', 'verifiedBy'])
            ->orderBy('due_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.payments.index', [
            'payments' => $payments,
            'deadlineData' => $this->deadlineData($payments->getCollection()),
            'paymentCounts' => $this->paymentCounts(),
            'filters' => $filters,
            'hasActiveFilters' => $this->hasActiveFilters($filters),
            'statusLabels' => $this->statusLabels(),
            'deadlineStatusLabels' => $this->deadlineStatusLabels(),
        ]);
    }

    public function export(Request $request): Response
    {
        $filters = $this->filters($request);
        $payments = $this->paymentsQuery($filters)
            ->with(['tenant.user', 'tenant.room'])
            ->orderBy('due_date')
            ->orderByDesc('id')
            ->get();

        $statusLabels = $this->statusLabels();
        $deadlineStatusLabels = $this->deadlineStatusLabels();

        $pdf = Pdf::loadView('admin.exports.payments-pdf', compact('payments', 'statusLabels', 'deadlineStatusLabels'));

        return $pdf->download('payments-export.pdf');
    }

    public function exportCsv(Request $request): Response
    {
        $filters = $this->filters($request);
        $payments = $this->paymentsQuery($filters)
            ->with(['tenant.user', 'tenant.room'])
            ->orderBy('due_date')
            ->orderByDesc('id')
            ->get();

        $statusLabels = $this->statusLabels();

        $headers = ['Penghuni', 'Email', 'Kamar', 'Jumlah', 'Periode Mulai', 'Periode Selesai', 'Tenggat', 'Status', 'Dibayar', 'Catatan'];
        $rows = $payments->map(fn ($p) => [
            $p->tenant?->user?->name ?? '',
            $p->tenant?->user?->email ?? '',
            $p->tenant?->room?->name ?? '',
            $p->amount,
            $p->period_start?->format('Y-m-d') ?? '',
            $p->period_end?->format('Y-m-d') ?? '',
            $p->due_date?->format('Y-m-d') ?? '',
            $statusLabels[$p->status] ?? $p->status,
            $p->paid_at?->format('Y-m-d H:i') ?? '',
            $p->notes ?? '',
        ]);

        $csv = $this->buildCsv($headers, $rows->all());

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="payments-export.csv"',
        ]);
    }

    public function create(): View
    {
        return view('admin.payments.create', [
            'tenants' => $this->tenants(),
            'statusLabels' => $this->statusLabels(),
            'tenantStatusLabels' => $this->tenantStatusLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data = PaymentWorkflow::prepare($data, null, (int) $request->user()->id);

        $payment = Payment::create($data);

        $tenantName = $payment->tenant?->user?->name ?? '#'.$payment->tenant_id;
        ActivityLogger::created('pembayaran', $payment->id, "Pembayaran {$tenantName} Rp ".number_format($payment->amount, 0, ',', '.'));

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    public function edit(Payment $payment): View
    {
        return view('admin.payments.edit', [
            'payment' => $payment->load(['tenant.user', 'tenant.room', 'verifiedBy']),
            'tenants' => $this->tenants(),
            'statusLabels' => $this->statusLabels(),
            'tenantStatusLabels' => $this->tenantStatusLabels(),
        ]);
    }

    public function review(Payment $payment): View
    {
        $payment->load(['tenant.user', 'tenant.room', 'verifiedBy']);

        return view('admin.payments.review', [
            'payment' => $payment,
            'deadline' => $this->deadlineData(collect([$payment]))[$payment->id] ?? null,
            'statusLabels' => $this->statusLabels(),
            'deadlineStatusLabels' => $this->deadlineStatusLabels(),
        ]);
    }

    public function proof(Payment $payment): StreamedResponse
    {
        abort_unless($payment->proof_image, 404);

        $disk = $this->proofImageDisk($payment->proof_image);

        abort_unless($disk !== null, 404);

        return Storage::disk($disk)->response($payment->proof_image);
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $oldImage = $payment->proof_image;

        $data = $this->validatedData($request, $payment);
        $data = PaymentWorkflow::prepare($data, $payment, (int) $request->user()->id);

        $payment->update($data);

        if (array_key_exists('proof_image', $data) && $oldImage !== $data['proof_image']) {
            $this->deleteProofImage($oldImage);
        }

        $tenantName = $payment->tenant?->user?->name ?? '#'.$payment->tenant_id;
        ActivityLogger::updated('pembayaran', $payment->id, "Pembayaran {$tenantName}");

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function updateReview(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'review_action' => ['required', Rule::in(['approve', 'reject', 'pending'])],
            'notes' => ['nullable', 'string'],
            'rejection_reason' => ['nullable', 'string'],
        ]);

        $status = match ($validated['review_action']) {
            'approve' => 'paid',
            'reject' => 'rejected',
            default => 'pending_verification',
        };

        $data = PaymentWorkflow::prepare([
            'tenant_id' => $payment->tenant_id,
            'amount' => $payment->amount,
            'period_start' => $payment->period_start,
            'period_end' => $payment->period_end,
            'due_date' => $payment->due_date,
            'paid_at' => $status === 'paid' ? $payment->paid_at : null,
            'status' => $status,
            'notes' => $validated['notes'] ?? $payment->notes,
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ], $payment, (int) $request->user()->id);

        $payment->update($data);

        $tenantName = $payment->tenant?->user?->name ?? '#'.$payment->tenant_id;
        match ($status) {
            'paid' => ActivityLogger::approved('pembayaran', $payment->id, "Pembayaran {$tenantName}"),
            'rejected' => ActivityLogger::rejected('pembayaran', $payment->id, "Pembayaran {$tenantName}"),
            default => ActivityLogger::updated('pembayaran', $payment->id, "Pembayaran {$tenantName} (kembali ke verifikasi)"),
        };

        return redirect()
            ->route('admin.payments.review', $payment)
            ->with('success', match ($status) {
                'paid' => 'Pembayaran berhasil disetujui dan ditandai lunas.',
                'rejected' => 'Pembayaran berhasil ditolak dengan alasan yang tersimpan.',
                default => 'Pembayaran dikembalikan ke status menunggu verifikasi.',
            });
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $proofImage = $payment->proof_image;
        $payment->load('tenant.user');
        $tenantName = $payment->tenant?->user?->name ?? '#'.$payment->tenant_id;

        $payment->delete();

        $this->deleteProofImage($proofImage);

        ActivityLogger::deleted('pembayaran', $payment->id, "Pembayaran {$tenantName}");

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Payment $payment = null): array
    {
        $validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                Rule::exists('tenants', 'id'),
                Rule::unique('payments')
                    ->ignore($payment?->id)
                    ->where(fn ($query) => $query
                        ->where('period_start', $request->input('period_start'))
                        ->where('period_end', $request->input('period_end'))),
            ],
            'amount' => ['required', 'integer', 'min:0'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'due_date' => ['required', 'date', 'after_or_equal:period_start'],
            'paid_at' => ['nullable', 'date'],
            'status' => ['required', Rule::in(array_keys($this->statusLabels()))],
            'proof_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'notes' => ['nullable', 'string'],
            'rejection_reason' => ['nullable', 'string'],
        ]);

        if (($validated['status'] ?? null) === 'rejected' && trim((string) ($validated['rejection_reason'] ?? '')) === '') {
            throw ValidationException::withMessages([
                'rejection_reason' => 'Alasan penolakan wajib diisi jika pembayaran ditolak.',
            ]);
        }

        $image = $validated['proof_image'] ?? null;
        unset($validated['proof_image']);

        if ($image !== null) {
            $validated['proof_image'] = $image->store('payments');
        }

        return $validated;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Tenant>
     */
    private function tenants()
    {
        return Tenant::query()
            ->with(['user', 'room'])
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * @return array<string, string>
     */
    private function statusLabels(): array
    {
        return [
            'unpaid' => 'Belum Bayar',
            'pending_verification' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
            'rejected' => 'Ditolak',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function tenantStatusLabels(): array
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
    private function deadlineStatusLabels(): array
    {
        return [
            'paid' => 'Lunas',
            'safe' => 'Aman',
            'due_soon' => 'Mendekati Tenggat',
            'due_today' => 'Jatuh Tempo Hari Ini',
            'overdue' => 'Terlambat',
        ];
    }

    /**
     * @param  Collection<int, Payment>  $payments
     * @return array<int, array{status:string,label:string,days_remaining:int|null,message:string}>
     */
    private function deadlineData(Collection $payments): array
    {
        if ($payments->isEmpty()) {
            return [];
        }

        $labels = $this->deadlineStatusLabels();
        $today = Carbon::today();

        return $payments->mapWithKeys(function ($payment) use ($labels, $today): array {
            $id = (int) $payment->id;

            if ($payment->status === 'paid') {
                return [$id => [
                    'status' => 'paid',
                    'label' => $labels['paid'],
                    'days_remaining' => null,
                    'message' => $this->deadlineMessage('paid', null),
                ]];
            }

            $dueDate = $payment->due_date ? Carbon::parse($payment->due_date)->startOfDay() : null;

            if ($dueDate === null) {
                return [$id => [
                    'status' => 'safe',
                    'label' => $labels['safe'],
                    'days_remaining' => null,
                    'message' => $this->deadlineMessage('safe', null),
                ]];
            }

            $diff = $today->diffInDays($dueDate, false);

            if ($diff < 0) {
                $daysOverdue = (int) abs($diff);

                return [$id => [
                    'status' => 'overdue',
                    'label' => $labels['overdue'],
                    'days_remaining' => -$daysOverdue,
                    'message' => $this->deadlineMessage('overdue', $daysOverdue),
                ]];
            }

            if ($diff === 0) {
                return [$id => [
                    'status' => 'due_today',
                    'label' => $labels['due_today'],
                    'days_remaining' => 0,
                    'message' => $this->deadlineMessage('due_today', 0),
                ]];
            }

            if ($diff <= 5) {
                return [$id => [
                    'status' => 'due_soon',
                    'label' => $labels['due_soon'],
                    'days_remaining' => (int) $diff,
                    'message' => $this->deadlineMessage('due_soon', (int) $diff),
                ]];
            }

            return [$id => [
                'status' => 'safe',
                'label' => $labels['safe'],
                'days_remaining' => (int) $diff,
                'message' => $this->deadlineMessage('safe', (int) $diff),
            ]];
        })->all();
    }

    private function deadlineMessage(string $status, ?int $daysRemaining): string
    {
        return match ($status) {
            'paid' => 'Tagihan sudah lunas.',
            'safe' => $daysRemaining === null ? 'Tenggat masih aman.' : 'Masih '.$daysRemaining.' hari menuju tenggat.',
            'due_soon' => $daysRemaining === null ? 'Pembayaran mendekati tenggat.' : 'Pembayaran jatuh tempo dalam '.$daysRemaining.' hari.',
            'due_today' => 'Pembayaran jatuh tempo hari ini.',
            'overdue' => $daysRemaining === null ? 'Pembayaran sudah terlambat.' : 'Pembayaran terlambat '.abs($daysRemaining).' hari.',
            default => '-',
        };
    }

    /**
     * @param  array{q:string|null,status:string|null,deadline_status:string|null}  $filters
     */
    private function paymentsQuery(array $filters)
    {
        return Payment::query()
            ->when($filters['q'] !== null, function ($query) use ($filters) {
                $term = '%'.$filters['q'].'%';

                $query->where(function ($nestedQuery) use ($term) {
                    $nestedQuery
                        ->whereHas('tenant.user', function ($userQuery) use ($term) {
                            $userQuery
                                ->where('name', 'like', $term)
                                ->orWhere('email', 'like', $term)
                                ->orWhere('phone', 'like', $term);
                        })
                        ->orWhereHas('tenant.room', fn ($roomQuery) => $roomQuery->where('name', 'like', $term))
                        ->orWhere('notes', 'like', $term)
                        ->orWhere('rejection_reason', 'like', $term);
                });
            })
            ->when($filters['status'] !== null, fn ($query) => $query->where('status', $filters['status']))
            ->when($filters['deadline_status'] !== null, function ($query) use ($filters) {
                $today = now()->startOfDay();

                match ($filters['deadline_status']) {
                    'paid' => $query->where('status', 'paid'),
                    'overdue' => $query
                        ->where('status', '!=', 'paid')
                        ->where('due_date', '<', $today),
                    'due_today' => $query
                        ->where('status', '!=', 'paid')
                        ->whereDate('due_date', $today),
                    'due_soon' => $query
                        ->where('status', '!=', 'paid')
                        ->where('due_date', '>', $today)
                        ->where('due_date', '<=', $today->copy()->addDays(5)),
                    'safe' => $query
                        ->where('status', '!=', 'paid')
                        ->where('due_date', '>', $today->copy()->addDays(5)),
                    default => null,
                };
            });
    }

    /**
     * @return array{q:string|null,status:string|null,deadline_status:string|null}
     */
    private function filters(Request $request): array
    {
        $status = (string) $request->query('status', '');
        $deadlineStatus = (string) $request->query('deadline_status', '');
        $q = trim((string) $request->query('q', ''));

        return [
            'q' => $q !== '' ? $q : null,
            'status' => array_key_exists($status, $this->statusLabels()) ? $status : null,
            'deadline_status' => array_key_exists($deadlineStatus, $this->deadlineStatusLabels()) ? $deadlineStatus : null,
        ];
    }

    /**
     * @param  array{q:string|null,status:string|null,deadline_status:string|null}  $filters
     */
    private function hasActiveFilters(array $filters): bool
    {
        return $filters['q'] !== null || $filters['status'] !== null || $filters['deadline_status'] !== null;
    }

    /**
     * @return array<string, int>
     */
    private function paymentCounts(): array
    {
        return [
            'Total' => Payment::query()->count(),
            'Belum Bayar' => Payment::query()->where('status', 'unpaid')->count(),
            'Menunggu Verifikasi' => Payment::query()->where('status', 'pending_verification')->count(),
            'Lunas' => Payment::query()->where('status', 'paid')->count(),
            'Ditolak' => Payment::query()->where('status', 'rejected')->count(),
        ];
    }

    /**
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, string>>  $rows
     */
    private function buildCsv(array $headers, array $rows): string
    {
        $output = fopen('php://temp', 'r+');

        fputcsv($output, $headers, ',', '"', '\\');

        foreach ($rows as $row) {
            fputcsv($output, $row, ',', '"', '\\');
        }

        rewind($output);

        return stream_get_contents($output);
    }

    private function deleteProofImage(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        Storage::disk('local')->delete($path);
        Storage::disk('public')->delete($path);
    }

    private function proofImageDisk(string $path): ?string
    {
        if (Storage::disk('local')->exists($path)) {
            return 'local';
        }

        if (Storage::disk('public')->exists($path)) {
            return 'public';
        }

        return null;
    }
}
