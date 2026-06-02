<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $roomCounts = Room::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $paymentCounts = Payment::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $deadlineCounts = $this->deadlineCounts();
        $rentCounts = $this->rentCounts();

        $metrics = [
            'total_rooms' => Room::query()->count(),
            'rooms_available' => $this->countFrom($roomCounts, 'available'),
            'rooms_occupied' => $this->countFrom($roomCounts, 'occupied'),
            'rooms_maintenance' => $this->countFrom($roomCounts, 'maintenance'),
            'active_tenants' => Tenant::query()->where('status', 'active')->count(),
            'payments_unpaid' => $this->countFrom($paymentCounts, 'unpaid'),
            'payments_pending_verification' => $this->countFrom($paymentCounts, 'pending_verification'),
            'payments_paid' => $this->countFrom($paymentCounts, 'paid'),
            'payments_due_soon' => $this->countFrom($deadlineCounts, 'due_soon'),
            'payments_due_today' => $this->countFrom($deadlineCounts, 'due_today'),
            'payments_overdue' => $this->countFrom($deadlineCounts, 'overdue'),
            'tenants_ending_soon' => $this->countFrom($rentCounts, 'ending_soon'),
            'tenants_end_today' => $this->countFrom($rentCounts, 'ends_today'),
            'tenants_ended' => $this->countFrom($rentCounts, 'ended'),
        ];

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'deadlineStatusLabels' => $this->deadlineStatusLabels(),
            'rentStatusLabels' => $this->rentStatusLabels(),
            'paymentsDueSoon' => $this->paymentsDueSoon(),
            'paymentsOverdue' => $this->paymentsOverdue(),
            'tenantEndWarnings' => $this->tenantEndWarnings(),
        ]);
    }

    private function countFrom(Collection $counts, string $key): int
    {
        return (int) $counts->get($key, 0);
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
     * @return array<string, string>
     */
    private function rentStatusLabels(): array
    {
        return [
            'inactive' => 'Tidak Aktif',
            'no_end_date' => 'Tidak Ada Tanggal Keluar',
            'ended' => 'Masa Tinggal Berakhir',
            'ends_today' => 'Berakhir Hari Ini',
            'ending_soon' => 'Hampir Berakhir',
            'safe' => 'Aman',
        ];
    }

    private function paymentsDueSoon(): Collection
    {
        return $this->paymentWarningRows()
            ->filter(fn (object $payment): bool => in_array($payment->deadline_status, ['due_today', 'due_soon'], true))
            ->values()
            ->take(5);
    }

    private function paymentsOverdue(): Collection
    {
        return $this->paymentWarningRows()
            ->filter(fn (object $payment): bool => $payment->deadline_status === 'overdue')
            ->sortBy('days_remaining')
            ->values()
            ->take(5);
    }

    private function paymentWarningRows(): Collection
    {
        $today = Carbon::today();

        return Payment::query()
            ->with(['tenant.user', 'tenant.room'])
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->get()
            ->map(function ($payment) use ($today) {
                $dueDate = $payment->due_date ? Carbon::parse($payment->due_date)->startOfDay() : null;

                if ($dueDate === null) {
                    $deadlineStatus = 'safe';
                    $daysRemaining = null;
                } elseif ($dueDate->lt($today)) {
                    $deadlineStatus = 'overdue';
                    $daysRemaining = (int) $dueDate->diffInDays($today, false);
                } elseif ($dueDate->eq($today)) {
                    $deadlineStatus = 'due_today';
                    $daysRemaining = 0;
                } elseif ($dueDate->diffInDays($today) <= 5) {
                    $deadlineStatus = 'due_soon';
                    $daysRemaining = (int) $dueDate->diffInDays($today);
                } else {
                    $deadlineStatus = 'safe';
                    $daysRemaining = (int) $dueDate->diffInDays($today);
                }

                return (object) [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'period_start' => $payment->period_start,
                    'period_end' => $payment->period_end,
                    'due_date' => $payment->due_date,
                    'days_remaining' => $daysRemaining,
                    'deadline_status' => $deadlineStatus,
                    'tenant_name' => $payment->tenant?->user?->name,
                    'tenant_phone' => $payment->tenant?->user?->phone,
                    'room_name' => $payment->tenant?->room?->name,
                ];
            });
    }

    /**
     * @return array<string, int>
     */
    private function deadlineCounts(): array
    {
        $today = Carbon::today();
        $counts = [
            'paid' => Payment::query()->where('status', 'paid')->count(),
            'due_soon' => 0,
            'due_today' => 0,
            'overdue' => 0,
        ];

        Payment::query()
            ->where('status', '!=', 'paid')
            ->select('due_date')
            ->get()
            ->each(function ($payment) use ($today, &$counts) {
                $dueDate = $payment->due_date ? Carbon::parse($payment->due_date)->startOfDay() : null;

                if ($dueDate === null) {
                    return;
                }

                if ($dueDate->lt($today)) {
                    $counts['overdue']++;
                } elseif ($dueDate->eq($today)) {
                    $counts['due_today']++;
                } elseif ($dueDate->diffInDays($today) <= 5) {
                    $counts['due_soon']++;
                }
            });

        return $counts;
    }

    /**
     * @return array<string, int>
     */
    private function rentCounts(): array
    {
        $today = Carbon::today();
        $counts = [
            'ending_soon' => 0,
            'ends_today' => 0,
            'ended' => 0,
        ];

        Tenant::query()
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->select('end_date')
            ->get()
            ->each(function ($tenant) use ($today, &$counts) {
                $endDate = Carbon::parse($tenant->end_date)->startOfDay();

                if ($endDate->lt($today)) {
                    $counts['ended']++;
                } elseif ($endDate->eq($today)) {
                    $counts['ends_today']++;
                } elseif ($endDate->diffInDays($today) <= 14) {
                    $counts['ending_soon']++;
                }
            });

        return $counts;
    }

    private function tenantEndWarnings(): Collection
    {
        $today = Carbon::today();

        return Tenant::query()
            ->with(['user', 'room'])
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->orderBy('end_date')
            ->get()
            ->map(function ($tenant) use ($today) {
                $endDate = Carbon::parse($tenant->end_date)->startOfDay();
                $diff = $today->diffInDays($endDate, false);

                if ($diff < 0) {
                    $status = 'ended';
                    $daysUntilEnd = (int) abs($diff);
                } elseif ($diff === 0) {
                    $status = 'ends_today';
                    $daysUntilEnd = 0;
                } elseif ($diff <= 14) {
                    $status = 'ending_soon';
                    $daysUntilEnd = (int) $diff;
                } else {
                    $status = 'safe';
                    $daysUntilEnd = (int) $diff;
                }

                return (object) [
                    'tenant_id' => $tenant->id,
                    'start_date' => $tenant->start_date,
                    'end_date' => $tenant->end_date,
                    'days_until_end' => $daysUntilEnd,
                    'rent_period_status' => $status,
                    'tenant_name' => $tenant->user?->name,
                    'room_name' => $tenant->room?->name,
                ];
            })
            ->filter(fn (object $tenant): bool => in_array($tenant->rent_period_status, ['ending_soon', 'ends_today', 'ended'], true))
            ->values()
            ->take(5);
    }
}
