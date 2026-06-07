<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyPayments extends Command
{
    protected $signature = 'payments:generate-monthly {--period= : Periode YYYY-MM, default bulan depan}';

    protected $description = 'Buat tagihan bulanan otomatis untuk semua penghuni aktif';

    public function handle(): int
    {
        $period = $this->option('period');
        $now = Carbon::now();

        if ($period) {
            $startOfPeriod = Carbon::parse($period.'-01')->startOfDay();
        } else {
            $startOfPeriod = $now->copy()->addMonth()->startOfMonth();
        }

        $endOfPeriod = $startOfPeriod->copy()->endOfMonth();
        $dueDate = $startOfPeriod->copy()->addDays(10);
        $periodLabel = $startOfPeriod->format('Y-m');

        $activeTenants = Tenant::query()
            ->with('room')
            ->where('status', 'active')
            ->get();

        if ($activeTenants->isEmpty()) {
            $this->warn('Tidak ada penghuni aktif.');

            return 0;
        }

        $created = 0;
        $skipped = 0;

        foreach ($activeTenants as $tenant) {
            if ($tenant->room === null) {
                $this->warn("Tenant #{$tenant->id} tidak punya kamar, dilewati.");
                $skipped++;

                continue;
            }

            $exists = Payment::query()
                ->where('tenant_id', $tenant->id)
                ->where('period_start', $startOfPeriod->format('Y-m-d'))
                ->where('period_end', $endOfPeriod->format('Y-m-d'))
                ->exists();

            if ($exists) {
                $skipped++;

                continue;
            }

            Payment::create([
                'tenant_id' => $tenant->id,
                'amount' => $tenant->room->price,
                'period_start' => $startOfPeriod->format('Y-m-d'),
                'period_end' => $endOfPeriod->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
                'status' => 'unpaid',
            ]);

            $created++;
        }

        $this->info("Selesai. Tagihan {$periodLabel}: {$created} dibuat, {$skipped} dilewati.");

        return 0;
    }
}
