<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::query()
            ->with(['tenant.user', 'tenant.room', 'verifiedBy'])
            ->orderBy('due_date')
            ->orderByDesc('id')
            ->get();

        return view('admin.payments.index', [
            'payments' => $payments,
            'deadlineData' => $this->deadlineData($payments),
            'statusLabels' => $this->statusLabels(),
            'deadlineStatusLabels' => $this->deadlineStatusLabels(),
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
        $data = $this->preparePaymentData($data, null, (int) $request->user()->id);

        Payment::create($data);

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
        $data = $this->preparePaymentData($data, $payment, (int) $request->user()->id);

        $payment->update($data);

        if (array_key_exists('proof_image', $data) && $oldImage !== $data['proof_image']) {
            $this->deleteProofImage($oldImage);
        }

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $proofImage = $payment->proof_image;

        $payment->delete();

        $this->deleteProofImage($proofImage);

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
        ]);

        $image = $validated['proof_image'] ?? null;
        unset($validated['proof_image']);

        if ($image !== null) {
            $validated['proof_image'] = $image->store('payments');
        }

        return $validated;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function preparePaymentData(array $data, ?Payment $payment, int $adminId): array
    {
        $wasPaid = $payment?->status === 'paid';

        if (($data['status'] ?? null) === 'paid') {
            if (empty($data['paid_at'])) {
                $data['paid_at'] = $payment?->paid_at ?? now();
            }

            if (! $wasPaid || $payment?->verified_at === null || $payment?->verified_by === null) {
                $data['verified_at'] = now();
                $data['verified_by'] = $adminId;
            }

            return $data;
        }

        $data['paid_at'] = null;
        $data['verified_at'] = null;
        $data['verified_by'] = null;

        return $data;
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

        return DB::table('payment_deadline_view')
            ->whereIn('id', $payments->modelKeys())
            ->get()
            ->mapWithKeys(function ($row) use ($labels): array {
                $status = $row->deadline_status;
                $daysRemaining = $row->days_remaining !== null ? (int) $row->days_remaining : null;

                return [
                    (int) $row->id => [
                        'status' => $status,
                        'label' => $labels[$status] ?? $status,
                        'days_remaining' => $daysRemaining,
                        'message' => $this->deadlineMessage($status, $daysRemaining),
                    ],
                ];
            })
            ->all();
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
