<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\KosProfile;
use App\Models\Payment;
use App\Models\Tenant;
use App\Models\UtilityBill;
use App\Support\WhatsappLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $tenant = Tenant::query()
            ->with('room')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->first();

        $whatsappUrl = $this->whatsappUrl(KosProfile::query()->value('whatsapp_number'));

        $bookingRequests = BookingRequest::query()
            ->where('user_id', $user->id)
            ->with('room')
            ->orderByDesc('id')
            ->get();

        if ($tenant === null) {
            return view('tenant.dashboard', [
                'user' => $user,
                'tenant' => null,
                'bookingRequests' => $bookingRequests,
                'payments' => collect(),
                'paymentDeadlines' => collect(),
                'featuredPayment' => null,
                'paymentDeadline' => null,
                'paymentWarning' => null,
                'rentSummary' => null,
                'rentWarning' => null,
                'utilityBills' => collect(),
                'whatsappUrl' => $whatsappUrl,
                'roomStatusLabels' => $this->roomStatusLabels(),
                'paymentStatusLabels' => $this->paymentStatusLabels(),
                'deadlineStatusLabels' => $this->deadlineStatusLabels(),
                'rentStatusLabels' => $this->rentStatusLabels(),
                'utilityTypeLabels' => $this->utilityTypeLabels(),
            ]);
        }

        $payments = Payment::query()
            ->where('tenant_id', $tenant->id)
            ->orderByDesc('due_date')
            ->orderByDesc('id')
            ->get();

        $utilityBills = UtilityBill::query()
            ->where('tenant_id', $tenant->id)
            ->orderByDesc('due_date')
            ->orderByDesc('id')
            ->get();

        $paymentDeadlines = $this->paymentDeadlines($payments);

        $featuredPayment = $this->featuredPayment($payments);
        $paymentDeadline = $featuredPayment !== null
            ? $paymentDeadlines->get($featuredPayment->id)
            : null;

        $rentSummary = $this->rentSummary($tenant);

        return view('tenant.dashboard', [
            'user' => $user,
            'tenant' => $tenant,
            'bookingRequests' => $bookingRequests,
            'payments' => $payments,
            'paymentDeadlines' => $paymentDeadlines,
            'featuredPayment' => $featuredPayment,
            'paymentDeadline' => $paymentDeadline,
            'paymentWarning' => $this->paymentWarning($paymentDeadline),
            'rentSummary' => $rentSummary,
            'rentWarning' => $this->rentWarning($rentSummary),
            'utilityBills' => $utilityBills,
            'whatsappUrl' => $whatsappUrl,
            'roomStatusLabels' => $this->roomStatusLabels(),
            'paymentStatusLabels' => $this->paymentStatusLabels(),
            'deadlineStatusLabels' => $this->deadlineStatusLabels(),
            'rentStatusLabels' => $this->rentStatusLabels(),
            'utilityTypeLabels' => $this->utilityTypeLabels(),
        ]);
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
     * @return array<string, string>
     */
    private function paymentStatusLabels(): array
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

    private function utilityTypeLabels(): array
    {
        return [
            'water' => 'Air',
            'electricity' => 'Listrik',
            'internet' => 'Internet',
        ];
    }

    private function whatsappUrl(?string $number): string
    {
        return WhatsappLink::build(
            WhatsappLink::normalizeNumber($number),
            'Halo, saya ingin bertanya tentang pembayaran kos saya di IchiKOS.'
        );
    }

    private function featuredPayment(Collection $payments): ?Payment
    {
        $outstanding = $payments
            ->filter(fn (Payment $payment): bool => in_array($payment->status, ['unpaid', 'pending_verification', 'rejected'], true))
            ->sortBy(fn (Payment $payment): int => $payment->due_date?->timestamp ?? PHP_INT_MAX)
            ->first();

        return $outstanding ?? $payments->first();
    }

    /**
     * @return array<string, string>|null
     */
    private function paymentWarning(?object $paymentDeadline): ?array
    {
        if ($paymentDeadline === null) {
            return null;
        }

        return match ($paymentDeadline->deadline_status) {
            'due_soon' => [
                'tone' => 'warning',
                'title' => 'Pembayaran Mendekati Tenggat',
                'message' => 'Tagihan Anda akan jatuh tempo dalam '.$paymentDeadline->days_remaining.' hari. Segera hubungi pengelola jika perlu konfirmasi.',
            ],
            'due_today' => [
                'tone' => 'warning',
                'title' => 'Pembayaran Jatuh Tempo Hari Ini',
                'message' => 'Tagihan Anda jatuh tempo hari ini. Pastikan pembayaran segera dicatat oleh admin.',
            ],
            'overdue' => [
                'tone' => 'danger',
                'title' => 'Pembayaran Terlambat',
                'message' => 'Tagihan Anda sudah terlambat '.abs((int) $paymentDeadline->days_remaining).' hari. Segera hubungi pengelola untuk tindak lanjut.',
            ],
            default => null,
        };
    }

    /**
     * @return array<string, string>|null
     */
    private function rentWarning(?object $rentSummary): ?array
    {
        if ($rentSummary === null) {
            return null;
        }

        return match ($rentSummary->rent_period_status) {
            'ending_soon' => [
                'tone' => 'warning',
                'title' => 'Masa Tinggal Hampir Berakhir',
                'message' => 'Masa tinggal Anda akan berakhir dalam '.$rentSummary->days_until_end.' hari. Silakan koordinasikan perpanjangan bila diperlukan.',
            ],
            'ends_today' => [
                'tone' => 'warning',
                'title' => 'Masa Tinggal Berakhir Hari Ini',
                'message' => 'Masa tinggal Anda berakhir hari ini. Segera hubungi pengelola untuk tindak lanjut.',
            ],
            'ended' => [
                'tone' => 'danger',
                'title' => 'Masa Tinggal Sudah Berakhir',
                'message' => 'Masa tinggal Anda sudah berakhir '.abs((int) $rentSummary->days_until_end).' hari yang lalu. Mohon segera hubungi pengelola.',
            ],
            default => null,
        };
    }

    /**
     * @param  Collection<int, Payment>  $payments
     * @return Collection<string, object>
     */
    private function paymentDeadlines(Collection $payments): Collection
    {
        $today = Carbon::today();

        return $payments->mapWithKeys(function ($payment) use ($today) {
            $id = $payment->id;

            if ($payment->status === 'paid') {
                return [(string) $id => (object) [
                    'id' => $id,
                    'deadline_status' => 'paid',
                    'days_remaining' => null,
                ]];
            }

            $dueDate = $payment->due_date ? Carbon::parse($payment->due_date)->startOfDay() : null;

            if ($dueDate === null) {
                return [(string) $id => (object) [
                    'id' => $id,
                    'deadline_status' => 'safe',
                    'days_remaining' => null,
                ]];
            }

            $diff = $today->diffInDays($dueDate, false);

            $status = match (true) {
                $diff < 0 => 'overdue',
                $diff === 0 => 'due_today',
                $diff <= 5 => 'due_soon',
                default => 'safe',
            };

            return [(string) $id => (object) [
                'id' => $id,
                'deadline_status' => $status,
                'days_remaining' => (int) $diff,
            ]];
        });
    }

    /**
     * @return object{rent_period_status:string, days_until_end:int|null}|null
     */
    private function rentSummary(Tenant $tenant): ?object
    {
        if ($tenant->end_date === null) {
            return (object) [
                'rent_period_status' => 'no_end_date',
                'days_until_end' => null,
            ];
        }

        $today = Carbon::today();
        $endDate = Carbon::parse($tenant->end_date)->startOfDay();
        $diff = $today->diffInDays($endDate, false);

        $status = match (true) {
            $diff < 0 => 'ended',
            $diff === 0 => 'ends_today',
            $diff <= 14 => 'ending_soon',
            default => 'safe',
        };

        return (object) [
            'rent_period_status' => $status,
            'days_until_end' => (int) $diff,
        ];
    }
}
