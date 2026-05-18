<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

        $deadlineCounts = DB::table('payment_deadline_view')
            ->selectRaw('deadline_status, COUNT(*) as total')
            ->groupBy('deadline_status')
            ->pluck('total', 'deadline_status');

        $rentCounts = DB::table('tenant_end_date_view')
            ->selectRaw('rent_period_status, COUNT(*) as total')
            ->groupBy('rent_period_status')
            ->pluck('total', 'rent_period_status');

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
        return DB::table('payment_deadline_view as payment_deadlines')
            ->join('users', 'users.id', '=', 'payment_deadlines.user_id')
            ->join('rooms', 'rooms.id', '=', 'payment_deadlines.room_id')
            ->select([
                'payment_deadlines.id',
                'payment_deadlines.amount',
                'payment_deadlines.period_start',
                'payment_deadlines.period_end',
                'payment_deadlines.due_date',
                'payment_deadlines.days_remaining',
                'payment_deadlines.deadline_status',
                'users.name as tenant_name',
                'users.phone as tenant_phone',
                'rooms.name as room_name',
            ])
            ->orderBy('payment_deadlines.due_date')
            ->get();
    }

    private function tenantEndWarnings(): Collection
    {
        return DB::table('tenant_end_date_view as tenant_end_dates')
            ->join('users', 'users.id', '=', 'tenant_end_dates.user_id')
            ->join('rooms', 'rooms.id', '=', 'tenant_end_dates.room_id')
            ->select([
                'tenant_end_dates.tenant_id',
                'tenant_end_dates.start_date',
                'tenant_end_dates.end_date',
                'tenant_end_dates.days_until_end',
                'tenant_end_dates.rent_period_status',
                'users.name as tenant_name',
                'rooms.name as room_name',
            ])
            ->orderBy('tenant_end_dates.end_date')
            ->get()
            ->filter(fn (object $tenant): bool => in_array($tenant->rent_period_status, ['ending_soon', 'ends_today', 'ended'], true))
            ->values()
            ->take(5);
    }
}
