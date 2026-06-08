@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('eyebrow', 'Overview')
@section('page_title', 'Dashboard Admin')
@section('page_description', 'Ringkasan kondisi kamar, penghuni, pembayaran, dan masa tinggal terkini berdasarkan data nyata di database IchiKOS.')

@section('page_actions')
    <a href="{{ route('admin.payments.index') }}" class="button button-primary">Lihat pembayaran</a>
    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kelola penghuni</a>
@endsection

@push('styles')
<style>
    /* ── STAT CARD GROUP ── */
    .dashboard-stat-grid {
        display: grid;
        gap: 20px;
        grid-template-columns: repeat(3, 1fr);
    }

    .dashboard-stat-card {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .dashboard-stat-head {
        padding: 18px 22px 12px;
        border-bottom: 1px solid var(--ui-border);
    }

    .dashboard-stat-title {
        margin: 0;
        font-family: 'Sora', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--ui-ink);
        letter-spacing: -0.1px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dashboard-stat-title-icon {
        width: 20px;
        height: 20px;
        color: var(--ui-accent);
    }

    .dashboard-stat-body {
        padding: 16px 22px 20px;
    }

    .dashboard-mini-grid {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(2, 1fr);
    }

    .dashboard-mini-stat {
        padding: 14px 14px 12px;
        background: var(--gray-50);
        border: 1px solid var(--ui-border);
        border-radius: var(--radius-md);
        position: relative;
        overflow: hidden;
        transition: box-shadow 0.15s, transform 0.15s;
    }

    .dashboard-mini-stat:hover {
        box-shadow: var(--ui-shadow);
        transform: translateY(-1px);
    }

    .dashboard-mini-label {
        margin: 0 0 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--ui-body);
    }

    .dashboard-mini-value {
        margin: 0;
        font-family: 'Sora', sans-serif;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.3px;
        color: var(--ui-ink);
    }

    .dashboard-mini-value-currency {
        font-size: 17px;
    }

    .dashboard-mini-bar {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 2px;
        background: var(--ui-border);
    }

    .dashboard-mini-stat.is-success .dashboard-mini-bar { background: #22c55e; }
    .dashboard-mini-stat.is-info    .dashboard-mini-bar { background: var(--ui-accent); }
    .dashboard-mini-stat.is-warning .dashboard-mini-bar { background: #f59e0b; }
    .dashboard-mini-stat.is-danger  .dashboard-mini-bar { background: #f43f5e; }
    .dashboard-mini-stat.is-neutral .dashboard-mini-bar { background: var(--ui-border); }

    /* ── ALERTS CARD ── */
    .dashboard-alerts-body {
        display: grid;
        gap: 14px;
    }

    .dashboard-alert-row {
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .dashboard-alert-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .dashboard-alert-icon-warning { color: #d97706; }
    .dashboard-alert-icon-danger  { color: #e11d48; }

    .dashboard-alert-text h3 {
        margin: 0 0 2px;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.3;
        color: var(--ui-ink);
    }

    .dashboard-alert-text p {
        margin: 0;
        font-size: 13px;
        line-height: 1.5;
        color: var(--ui-body);
    }

    /* ── TABLE CARD HEADER ── */
    .dashboard-table-head {
        padding: 16px 22px;
        border-bottom: 1px solid var(--ui-border);
    }

    .dashboard-table-title {
        margin: 0;
        font-family: 'Sora', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--ui-ink);
        letter-spacing: -0.1px;
    }

    .dashboard-table-title.is-danger {
        color: #9f1239;
    }

    /* ── WHATSAPP BUTTON ── */
    .btn-whatsapp {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border: 0;
        border-radius: var(--radius-pill);
        background: var(--gray-100);
        color: var(--gray-600);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .btn-whatsapp:hover {
        background: var(--gray-200);
        color: var(--ui-ink);
    }

    .btn-whatsapp svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        color: #25D366;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1023px) {
        .dashboard-stat-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 520px) {
        .dashboard-mini-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
    @php
        // ── HYBRID DATA: real metrics from controller + dummy placeholder ──
        $m = $metrics;

        $roomStats = [
            ['label' => 'Total Kamar',  'value' => number_format($m['total_rooms'], 0, ',', '.'), 'mod' => 'is-success'],
            ['label' => 'Terisi',       'value' => number_format($m['rooms_occupied'], 0, ',', '.'), 'mod' => 'is-info'],
            ['label' => 'Kosong',       'value' => number_format($m['rooms_available'], 0, ',', '.'), 'mod' => 'is-success'],
            ['label' => 'Perbaikan',    'value' => number_format($m['rooms_maintenance'], 0, ',', '.'), 'mod' => $m['rooms_maintenance'] > 0 ? 'is-warning' : 'is-neutral'],
        ];

        $tenantStats = [
            ['label' => 'Total Penghuni', 'value' => number_format($m['total_tenants'], 0, ',', '.'), 'mod' => 'is-info'],
            ['label' => 'Baru Bulan Ini', 'value' => number_format($m['new_tenants_this_month'], 0, ',', '.'), 'mod' => 'is-success'],
            ['label' => 'Akan Keluar',    'value' => number_format($m['tenants_ending_soon'], 0, ',', '.'), 'mod' => $m['tenants_ending_soon'] > 0 ? 'is-warning' : 'is-neutral'],
            ['label' => 'Overstay',       'value' => number_format($m['tenants_ended'], 0, ',', '.'), 'mod' => $m['tenants_ended'] > 0 ? 'is-danger' : 'is-neutral'],
        ];

        $dueSoonBadge = 'badge-due-soon';
        $overdueBadge = 'badge-overdue';
        $endingSoonBadge = 'badge-ending-soon';
        $endsTodayBadge = 'badge-ends-today';
        $endedBadge = 'badge-ended';

        $alertDueSoon = $m['payments_due_soon'] + $m['payments_due_today'];
        $alertEnding = $m['tenants_ending_soon'] + $m['tenants_end_today'];
    @endphp

    <div class="content-stack">

        {{-- ── 1. ALERTS CARD ── --}}
        <article class="card">
            <div class="card-head has-divider">
                <h2 class="card-title">Alerts</h2>
                <p class="card-copy">Ringkasan kondisi yang perlu mendapat perhatian Anda hari ini.</p>
            </div>
            <div class="card-body">
                <div class="dashboard-alerts-body">
                    <div class="dashboard-alert-row">
                        <svg class="dashboard-alert-icon dashboard-alert-icon-warning" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="dashboard-alert-text">
                            <h3>{{ $alertDueSoon }} pembayaran mendekati tenggat</h3>
                            <p>{{ $alertDueSoon > 0 ? 'Tagihan yang perlu segera ditindaklanjuti.' : 'Tidak ada tagihan yang mendekati tenggat.' }}</p>
                        </div>
                    </div>
                    @if ($m['booking_pending'] > 0)
                    <div class="dashboard-alert-row">
                        <svg class="dashboard-alert-icon dashboard-alert-icon-warning" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="dashboard-alert-text">
                            <h3>{{ $m['booking_pending'] }} pengajuan sewa baru</h3>
                            <p>Calon penghuni menunggu persetujuan Anda. <a href="{{ route('admin.bookings.index') }}" style="text-decoration:underline;color:var(--ui-accent);font-weight:600;">Lihat pengajuan</a></p>
                        </div>
                    </div>
                    @endif
                    @if ($m['maintenance_pending'] > 0)
                    <div class="dashboard-alert-row">
                        <svg class="dashboard-alert-icon dashboard-alert-icon-warning" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="dashboard-alert-text">
                            <h3>{{ $m['maintenance_pending'] }} pengajuan perbaikan baru</h3>
                            <p>Laporan perbaikan dari penghuni menunggu tindakan. <a href="{{ route('admin.maintenance-requests.index') }}" style="text-decoration:underline;color:var(--ui-accent);font-weight:600;">Kelola perbaikan</a></p>
                        </div>
                    </div>
                    @endif
                    @if ($m['utility_unpaid'] > 0)
                    <div class="dashboard-alert-row">
                        <svg class="dashboard-alert-icon dashboard-alert-icon-warning" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="dashboard-alert-text">
                            <h3>{{ $m['utility_unpaid'] }} tagihan utilitas belum dibayar</h3>
                            <p>Tagihan air, listrik, atau internet yang masih outstanding. <a href="{{ route('admin.utility-bills.index') }}" style="text-decoration:underline;color:var(--ui-accent);font-weight:600;">Lihat tagihan</a></p>
                        </div>
                    </div>
                    @endif
                    <div class="dashboard-alert-row">
                        <svg class="dashboard-alert-icon dashboard-alert-icon-danger" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="dashboard-alert-text">
                            <h3>{{ $alertEnding }} masa tinggal perlu perhatian</h3>
                            <p>{{ $alertEnding > 0 ? 'Penghuni dengan masa tinggal yang hampir berakhir atau sudah lewat.' : 'Tidak ada masa tinggal yang perlu perhatian.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        {{-- ── 2. STAT CARDS ── --}}
        <section class="dashboard-stat-grid">

            {{-- Kamar --}}
            <article class="dashboard-stat-card">
                <div class="dashboard-stat-head">
                    <h3 class="dashboard-stat-title">
                        <svg class="dashboard-stat-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9.5L12 3l9 6.5V21a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/>
                            <path d="M9 21V12h6v9"/>
                        </svg>
                        Kamar
                    </h3>
                </div>
                <div class="dashboard-stat-body">
                    <div class="dashboard-mini-grid">
                        @foreach ($roomStats as $stat)
                            <div class="dashboard-mini-stat {{ $stat['mod'] }}">
                                <p class="dashboard-mini-label">{{ $stat['label'] }}</p>
                                <p class="dashboard-mini-value">{{ $stat['value'] }}</p>
                                <div class="dashboard-mini-bar"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>

            {{-- Penghuni --}}
            <article class="dashboard-stat-card">
                <div class="dashboard-stat-head">
                    <h3 class="dashboard-stat-title">
                        <svg class="dashboard-stat-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 4a4 4 0 100 8 4 4 0 000-8z"/>
                            <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                            <circle cx="17" cy="8" r="2"/>
                            <path d="M21 21v-2a3 3 0 00-2-2.87"/>
                        </svg>
                        Penghuni
                    </h3>
                </div>
                <div class="dashboard-stat-body">
                    <div class="dashboard-mini-grid">
                        @foreach ($tenantStats as $stat)
                            <div class="dashboard-mini-stat {{ $stat['mod'] }}">
                                <p class="dashboard-mini-label">{{ $stat['label'] }}</p>
                                <p class="dashboard-mini-value">{{ $stat['value'] }}</p>
                                <div class="dashboard-mini-bar"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>

            {{-- Pembayaran --}}
            <article class="dashboard-stat-card">
                <div class="dashboard-stat-head">
                    <h3 class="dashboard-stat-title">
                        <svg class="dashboard-stat-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="M12 10a2 2 0 100 4 2 2 0 000-4z"/>
                            <path d="M2 8h20"/>
                        </svg>
                        Pembayaran
                    </h3>
                </div>
                <div class="dashboard-stat-body">
                    <div class="dashboard-mini-grid">
                        <div class="dashboard-mini-stat is-success">
                            <p class="dashboard-mini-label">Pendapatan Bulan Ini</p>
                            <p class="dashboard-mini-value"><span class="dashboard-mini-value-currency">{{ \App\Support\UiFormatter::currency($m['monthly_revenue']) }}</span></p>
                            <div class="dashboard-mini-bar"></div>
                        </div>
                        <div class="dashboard-mini-stat {{ $m['payments_unpaid'] > 0 ? 'is-warning' : 'is-neutral' }}">
                            <p class="dashboard-mini-label">Belum Dibayar</p>
                            <p class="dashboard-mini-value">{{ number_format($m['payments_unpaid'], 0, ',', '.') }}</p>
                            <div class="dashboard-mini-bar"></div>
                        </div>
                        <div class="dashboard-mini-stat {{ $m['payments_overdue'] > 0 ? 'is-danger' : 'is-neutral' }}">
                            <p class="dashboard-mini-label">Terlambat</p>
                            <p class="dashboard-mini-value">{{ number_format($m['payments_overdue'], 0, ',', '.') }}</p>
                            <div class="dashboard-mini-bar"></div>
                        </div>
                        <div class="dashboard-mini-stat is-success">
                            <p class="dashboard-mini-label">Sukses</p>
                            <p class="dashboard-mini-value">{{ number_format($m['payments_paid'], 0, ',', '.') }}</p>
                            <div class="dashboard-mini-bar"></div>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Biaya Operasional --}}
            <article class="dashboard-stat-card">
                <div class="dashboard-stat-head">
                    <h3 class="dashboard-stat-title">
                        <svg class="dashboard-stat-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
                        Biaya Operasional
                    </h3>
                </div>
                <div class="dashboard-stat-body">
                    <div class="dashboard-mini-grid">
                        <div class="dashboard-mini-stat is-warning">
                            <p class="dashboard-mini-label">Bulan Ini</p>
                            <p class="dashboard-mini-value">{{ \App\Support\UiFormatter::currency($m['opex_month']) }}</p>
                            <div class="dashboard-mini-bar"></div>
                        </div>
                        <div class="dashboard-mini-stat {{ $m['overdue_with_fees'] > 0 ? 'is-danger' : 'is-neutral' }}">
                            <p class="dashboard-mini-label">Kena Denda</p>
                            <p class="dashboard-mini-value">{{ number_format($m['overdue_with_fees'], 0, ',', '.') }}</p>
                            <div class="dashboard-mini-bar"></div>
                        </div>
                    </div>
                </div>
            </article>

        </section>

        {{-- ── 3. TABLES — stacked ── --}}

        {{-- Pembayaran Mendekati Tenggat --}}
        <section class="card">
            <div class="dashboard-table-head">
                <h3 class="dashboard-table-title">Pembayaran Mendekati Tenggat</h3>
            </div>

            @if ($paymentsDueSoon->isEmpty())
                <div class="card-body">
                    <div class="status-row">
                        <span class="status-dot status-dot-safe"></span>
                        <span class="muted">Tidak ada tagihan yang mendekati tenggat.</span>
                    </div>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Penghuni</th>
                                <th>Kamar</th>
                                <th>Nominal</th>
                                <th>Periode</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th>Follow Up</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentsDueSoon as $payment)
                                <tr>
                                    <td style="font-weight:600">{{ $payment->tenant_name }}</td>
                                    <td>{{ $payment->room_name }}</td>
                                    <td style="font-weight:600">{{ \App\Support\UiFormatter::currency($payment->amount) }}</td>
                                    <td>
                                        {{ \App\Support\UiFormatter::date($payment->period_start) }}
                                        <span class="muted">–</span>
                                        {{ \App\Support\UiFormatter::date($payment->period_end) }}
                                    </td>
                                    <td>
                                        <div style="font-weight:600">{{ \App\Support\UiFormatter::date($payment->due_date) }}</div>
                                        <div class="muted">{{ $payment->deadline_status === 'due_today' ? 'Hari ini' : $payment->days_remaining.' hari lagi' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $payment->deadline_status === 'due_today' ? 'badge-due-today' : 'badge-due-soon' }}">
                                            {{ $deadlineStatusLabels[$payment->deadline_status] ?? $payment->deadline_status }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $reminderUrl = \App\Support\PaymentReminder::link(
                                                $payment->tenant_phone,
                                                $payment->tenant_name,
                                                $payment->room_name,
                                                $payment->amount,
                                                $payment->period_start,
                                                $payment->period_end,
                                                $payment->due_date,
                                            );
                                        @endphp
                                        @if ($reminderUrl)
                                            <a href="{{ $reminderUrl }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M19.05 4.91A9.816 9.816 0 0012.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01zm-7.01 15.24c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.264 8.264 0 01-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24 2.2 0 4.27.86 5.82 2.42a8.183 8.183 0 012.41 5.83c.02 4.54-3.68 8.23-8.22 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.44.12-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.84-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01-.17 0-.44.06-.66.31-.22.25-.86.84-.86 2.05 0 1.21.88 2.38 1 2.54.12.17 1.72 2.63 4.18 3.69.58.25 1.04.4 1.39.51.59.19 1.13.16 1.55.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.12-.22-.19-.47-.31z"/>
                                                </svg>
                                                <span>WhatsApp</span>
                                            </a>
                                        @else
                                            <span class="muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Pembayaran Terlambat --}}
        <section class="card">
            <div class="dashboard-table-head">
                <h3 class="dashboard-table-title is-danger">Pembayaran Terlambat</h3>
            </div>

            @if ($paymentsOverdue->isEmpty())
                <div class="card-body">
                    <div class="status-row">
                        <span class="status-dot status-dot-safe"></span>
                        <span class="muted">Tidak ada tagihan terlambat.</span>
                    </div>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Penghuni</th>
                                <th>Kamar</th>
                                <th>Nominal</th>
                                <th>Tenggat</th>
                                <th>Keterlambatan</th>
                                <th>Status</th>
                                <th>Follow Up</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentsOverdue as $payment)
                                <tr>
                                    <td style="font-weight:600">{{ $payment->tenant_name }}</td>
                                    <td>{{ $payment->room_name }}</td>
                                    <td style="font-weight:600">{{ \App\Support\UiFormatter::currency($payment->amount) }}</td>
                                    <td>{{ \App\Support\UiFormatter::date($payment->due_date) }}</td>
                                    <td style="color:#9f1239;font-weight:700">{{ abs((int) $payment->days_remaining) }} hari</td>
                                    <td>
                                        <span class="badge badge-overdue">{{ $deadlineStatusLabels[$payment->deadline_status] ?? $payment->deadline_status }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $reminderUrl = \App\Support\PaymentReminder::link(
                                                $payment->tenant_phone,
                                                $payment->tenant_name,
                                                $payment->room_name,
                                                $payment->amount,
                                                $payment->period_start,
                                                $payment->period_end,
                                                $payment->due_date,
                                            );
                                        @endphp
                                        @if ($reminderUrl)
                                            <a href="{{ $reminderUrl }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M19.05 4.91A9.816 9.816 0 0012.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01zm-7.01 15.24c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.264 8.264 0 01-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24 2.2 0 4.27.86 5.82 2.42a8.183 8.183 0 012.41 5.83c.02 4.54-3.68 8.23-8.22 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.44.12-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.84-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01-.17 0-.44.06-.66.31-.22.25-.86.84-.86 2.05 0 1.21.88 2.38 1 2.54.12.17 1.72 2.63 4.18 3.69.58.25 1.04.4 1.39.51.59.19 1.13.16 1.55.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.12-.22-.19-.47-.31z"/>
                                                </svg>
                                                <span>WhatsApp</span>
                                            </a>
                                        @else
                                            <span class="muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Penghuni Baru Belum Bayar --}}
        <section class="card">
            <div class="dashboard-table-head">
                <h3 class="dashboard-table-title">Penghuni Baru Belum Bayar</h3>
            </div>

            @if ($newTenantsUnpaid->isEmpty())
                <div class="card-body">
                    <div class="status-row">
                        <span class="status-dot status-dot-safe"></span>
                        <span class="muted">Tidak ada penghuni baru dengan tagihan belum dibayar.</span>
                    </div>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Penghuni</th>
                                <th>Kamar</th>
                                <th>Nominal</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th>Follow Up</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($newTenantsUnpaid as $payment)
                                <tr>
                                    <td style="font-weight:600">{{ $payment->tenant_name }}</td>
                                    <td>{{ $payment->room_name }}</td>
                                    <td style="font-weight:600">{{ \App\Support\UiFormatter::currency($payment->amount) }}</td>
                                    <td>{{ \App\Support\UiFormatter::date($payment->due_date) }}</td>
                                    <td>
                                        <span class="badge badge-due-soon">{{ $deadlineStatusLabels['fresh'] ?? 'Baru' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $reminderUrl = \App\Support\PaymentReminder::link(
                                                $payment->tenant_phone,
                                                $payment->tenant_name,
                                                $payment->room_name,
                                                $payment->amount,
                                                $payment->period_start,
                                                $payment->period_end,
                                                $payment->due_date,
                                            );
                                        @endphp
                                        @if ($reminderUrl)
                                            <a href="{{ $reminderUrl }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M19.05 4.91A9.816 9.816 0 0012.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01zm-7.01 15.24c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.264 8.264 0 01-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24 2.2 0 4.27.86 5.82 2.42a8.183 8.183 0 012.41 5.83c.02 4.54-3.68 8.23-8.22 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.44.12-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.84-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01-.17 0-.44.06-.66.31-.22.25-.86.84-.86 2.05 0 1.21.88 2.38 1 2.54.12.17 1.72 2.63 4.18 3.69.58.25 1.04.4 1.39.51.59.19 1.13.16 1.55.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.12-.22-.19-.47-.31z"/>
                                                </svg>
                                                <span>WhatsApp</span>
                                            </a>
                                        @else
                                            <span class="muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Masa Tinggal Perlu Perhatian --}}
        <section class="card">
            <div class="dashboard-table-head">
                <h3 class="dashboard-table-title">Masa Tinggal Perlu Perhatian</h3>
            </div>

            @if ($tenantEndWarnings->isEmpty())
                <div class="card-body">
                    <div class="status-row">
                        <span class="status-dot status-dot-safe"></span>
                        <span class="muted">Tidak ada masa tinggal yang perlu perhatian.</span>
                    </div>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Penghuni</th>
                                <th>Kamar</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Keluar</th>
                                <th>Sisa / Lewat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenantEndWarnings as $tenant)
                                <tr>
                                    <td style="font-weight:600">{{ $tenant->tenant_name }}</td>
                                    <td>{{ $tenant->room_name }}</td>
                                    <td>{{ \App\Support\UiFormatter::date($tenant->start_date) }}</td>
                                    <td>{{ \App\Support\UiFormatter::date($tenant->end_date) }}</td>
                                    <td>
                                        @if ($tenant->rent_period_status === 'ended')
                                            <span style="color:#9f1239;font-weight:700">{{ abs((int) $tenant->days_until_end) }} hari</span>
                                        @elseif ($tenant->rent_period_status === 'ends_today')
                                            <span style="color:#9a3412;font-weight:700">Hari ini</span>
                                        @else
                                            <span style="font-weight:600">{{ abs((int) $tenant->days_until_end) }} hari</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badge = match ($tenant->rent_period_status) {
                                                'ending_soon' => 'badge-ending-soon',
                                                'ends_today'  => 'badge-ends-today',
                                                'ended'       => 'badge-ended',
                                                default        => 'badge-safe',
                                            };
                                        @endphp
                                        <span class="badge {{ $badge }}">
                                            {{ $rentStatusLabels[$tenant->rent_period_status] ?? $tenant->rent_period_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

    </div>
@endsection
