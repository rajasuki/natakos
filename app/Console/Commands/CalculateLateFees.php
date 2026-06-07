<?php

namespace App\Console\Commands;

use App\Models\KosProfile;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateLateFees extends Command
{
    protected $signature = 'payments:calculate-late-fees';

    protected $description = 'Calculate late fees for overdue payments';

    public function handle(): void
    {
        $profile = KosProfile::query()->first();

        if ($profile === null || ($profile->late_fee_per_day ?? 0) <= 0) {
            $this->info('Denda per hari belum diatur. Skip.');

            return;
        }

        $today = Carbon::today();
        $count = 0;

        Payment::query()
            ->where('status', 'unpaid')
            ->where('due_date', '<', $today)
            ->chunk(50, function ($payments) use ($profile, $today, &$count): void {
                foreach ($payments as $payment) {
                    $daysOverdue = (int) $today->diffInDays($payment->due_date);
                    $rawLateFee = $daysOverdue * $profile->late_fee_per_day;
                    $lateFee = $profile->max_late_fee !== null
                        ? min($rawLateFee, $profile->max_late_fee)
                        : $rawLateFee;
                    $payment->update([
                        'late_fee' => $lateFee,
                        'late_fee_days' => $daysOverdue,
                    ]);
                    $count++;
                }
            });

        $this->info("{$count} tagihan diperbarui dengan denda keterlambatan.");
    }
}
