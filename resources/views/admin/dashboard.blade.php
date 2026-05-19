@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('eyebrow', 'Overview')
@section('page_title', 'Dashboard Admin')
@section('page_description', 'Ringkasan kondisi kamar, penghuni, pembayaran, dan masa tinggal terkini berdasarkan data nyata di database ' . $kosName . '.')

@section('page_actions')
    <a href="{{ route('admin.payments.index') }}" class="button button-primary">Lihat pembayaran</a>
    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kelola penghuni</a>
@endsection

@push('styles')
<style>
    /* ── METRIC GROUPS ── */
    .metric-section-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--slate-400);
        margin: 0 0 10px;
        padding: 0 2px;
    }

    .metric-group { display: grid; gap: 20px; }

    /* ── TABLE INLINE ACTIONS ── */
    .table-actions-col { white-space: nowrap; }

    /* ── SUMMARY HEADER INSIDE CARD ── */
    .card-subhead {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* ── STATUS DOT ── */
    .status-row {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
    }

    .status-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-dot-warning { background: #f59e0b; }
    .status-dot-danger  { background: #f43f5e; }
    .status-dot-safe    { background: #22c55e; }

    /* ── OVERDUE COUNT PILL ── */
    .count-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 7px;
        border-radius: var(--radius-pill);
        font-size: 11px;
        font-weight: 700;
        background: var(--slate-100);
        color: var(--slate-600);
    }

    .count-pill-danger { background: #ffe4e6; color: #9f1239; }
    .count-pill-warning { background: #fef9c3; color: #854d0e; }
</style>
@endpush

@section('content')
    @php
        $roomCards = [
            ['label' => 'Total kamar',        'value' => $metrics['total_rooms'],       'hint' => 'Semua kamar terdaftar',      'mod' => ''],
            ['label' => 'Tersedia',            'value' => $metrics['rooms_available'],   'hint' => 'Siap ditempati',             'mod' => 'is-success'],
            ['label' => 'Terisi',              'value' => $metrics['rooms_occupied'],    'hint' => 'Sedang dihuni',              'mod' => 'is-info'],
            ['label' => 'Maintenance',         'value' => $metrics['rooms_maintenance'], 'hint' => 'Sedang diperbaiki',          'mod' => 'is-warning'],
        ];

        $tenantCards = [
            ['label' => 'Penghuni aktif',      'value' => $metrics['active_tenants'],               'hint' => 'Penghuni aktif saat ini', 'mod' => 'is-info'],
            ['label' => 'Hampir berakhir',     'value' => $metrics['tenants_ending_soon'],           'hint' => 'Berakhir 1–5 hari ke depan', 'mod' => 'is-warning'],
            ['label' => 'Berakhir hari ini',   'value' => $metrics['tenants_end_today'],             'hint' => 'Perlu konfirmasi segera',     'mod' => $metrics['tenants_end_today'] > 0 ? 'is-warning' : ''],
            ['label' => 'Sudah berakhir',      'value' => $metrics['tenants_ended'],                 'hint' => 'Perlu tindak lanjut',         'mod' => $metrics['tenants_ended'] > 0 ? 'is-danger' : ''],
        ];

        $paymentCards = [
            ['label' => 'Belum bayar',          'value' => $metrics['payments_unpaid'],                'hint' => 'Tagihan belum dibayar',   'mod' => ''],
            ['label' => 'Menunggu verifikasi',  'value' => $metrics['payments_pending_verification'],  'hint' => 'Perlu dicek admin',        'mod' => $metrics['payments_pending_verification'] > 0 ? 'is-warning' : ''],
            ['label' => 'Lunas',                'value' => $metrics['payments_paid'],                  'hint' => 'Sudah tercatat',           'mod' => 'is-success'],
            ['label' => 'Mendekati tenggat',    'value' => $metrics['payments_due_soon'],              'hint' => 'Jatuh tempo 1–5 hari',     'mod' => $metrics['payments_due_soon'] > 0 ? 'is-warning' : ''],
            ['label' => 'Jatuh tempo hari ini', 'value' => $metrics['payments_due_today'],             'hint' => 'Perlu perhatian segera',   'mod' => $metrics['payments_due_today'] > 0 ? 'is-warning' : ''],
            ['label' => 'Terlambat',            'value' => $metrics['payments_overdue'],               'hint' => 'Lewat dari tenggat',       'mod' => $metrics['payments_overdue'] > 0 ? 'is-danger' : ''],
        ];

        $paymentBadgeClasses = [
            'paid'     => 'badge-paid',
            'safe'     => 'badge-safe',
            'due_soon' => 'badge-due-soon',
            'due_today'=> 'badge-due-today',
            'overdue'  => 'badge-overdue',
        ];

        $rentBadgeClasses = [
            'inactive'     => 'badge-inactive',
            'no_end_date'  => 'badge-no-end-date',
            'ended'        => 'badge-overdue',
            'ends_today'   => 'badge-due-today',
            'ending_soon'  => 'badge-due-soon',
            'safe'         => 'badge-safe',
        ];

        $alerts = [
            ['show' => $metrics['payments_due_today'] > 0, 'tone' => 'warning',
             'title' => 'Pembayaran jatuh tempo hari ini',
             'message' => $metrics['payments_due_today'].' tagihan jatuh tempo hari ini dan perlu segera ditindaklanjuti.'],
            ['show' => $metrics['payments_overdue'] > 0, 'tone' => 'danger',
             'title' => 'Pembayaran terlambat',
             'message' => $metrics['payments_overdue'].' tagihan sudah melewati tenggat pembayaran.'],
            ['show' => $metrics['tenants_end_today'] > 0, 'tone' => 'warning',
             'title' => 'Masa tinggal berakhir hari ini',
             'message' => $metrics['tenants_end_today'].' penghuni dengan masa tinggal yang berakhir hari ini.'],
            ['show' => $metrics['tenants_ended'] > 0, 'tone' => 'danger',
             'title' => 'Masa tinggal sudah berakhir',
             'message' => $metrics['tenants_ended'].' penghuni masa tinggalnya sudah berakhir — perlu tindak lanjut.'],
        ];
    @endphp

    <div class="content-stack">

        {{-- ── ALERTS ── --}}
        @if (collect($alerts)->contains(fn (array $a) => $a['show']))
            <section class="alert-stack">
                @foreach ($alerts as $alert)
                    @if ($alert['show'])
                        <article class="alert-box {{ $alert['tone'] === 'danger' ? 'alert-box-danger' : 'alert-box-warning' }}">
                            <svg class="alert-box-icon" viewBox="0 0 20 20" fill="currentColor">
                                @if ($alert['tone'] === 'danger')
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                @else
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                @endif
                            </svg>
                            <div>
                                <h2>{{ $alert['title'] }}</h2>
                                <p>{{ $alert['message'] }}</p>
                            </div>
                        </article>
                    @endif
                @endforeach
            </section>
        @endif

        {{-- ── ROOM METRICS ── --}}
        <div class="metric-group">
            <p class="metric-section-label">Kamar</p>
            <div class="metric-grid">
                @foreach ($roomCards as $card)
                    <article class="metric-card {{ $card['mod'] }}">
                        <div class="metric-accent-bar"></div>
                        <p class="metric-label">{{ $card['label'] }}</p>
                        <p class="metric-value">{{ number_format($card['value'], 0, ',', '.') }}</p>
                        <p class="metric-hint">{{ $card['hint'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- ── TENANT METRICS ── --}}
        <div class="metric-group">
            <p class="metric-section-label">Penghuni</p>
            <div class="metric-grid">
                @foreach ($tenantCards as $card)
                    <article class="metric-card {{ $card['mod'] }}">
                        <div class="metric-accent-bar"></div>
                        <p class="metric-label">{{ $card['label'] }}</p>
                        <p class="metric-value">{{ number_format($card['value'], 0, ',', '.') }}</p>
                        <p class="metric-hint">{{ $card['hint'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- ── PAYMENT METRICS ── --}}
        <div class="metric-group">
            <p class="metric-section-label">Pembayaran</p>
            <div class="metric-grid">
                @foreach ($paymentCards as $card)
                    <article class="metric-card {{ $card['mod'] }}">
                        <div class="metric-accent-bar"></div>
                        <p class="metric-label">{{ $card['label'] }}</p>
                        <p class="metric-value">{{ number_format($card['value'], 0, ',', '.') }}</p>
                        <p class="metric-hint">{{ $card['hint'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- ── TABLES ── --}}

        {{-- Mendekati Tenggat --}}
        <article class="card">
            <div class="card-head has-divider">
                <div class="card-subhead">
                    <div>
                        <h2 class="card-title">Pembayaran Mendekati Tenggat</h2>
                        <p class="card-copy" style="margin-top:3px">Tagihan jatuh tempo hari ini atau dalam lima hari ke depan.</p>
                    </div>
                    @if (!$paymentsDueSoon->isEmpty())
                        <span class="count-pill count-pill-warning">{{ $paymentsDueSoon->count() }}</span>
                    @endif
                </div>
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
                    <table class="responsive-table">
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
                                    <td data-label="Penghuni" style="font-weight:600">{{ $payment->tenant_name }}</td>
                                    <td data-label="Kamar">{{ $payment->room_name }}</td>
                                    <td data-label="Nominal" style="font-weight:600">{{ \App\Support\UiFormatter::currency($payment->amount) }}</td>
                                    <td data-label="Periode">
                                        {{ \App\Support\UiFormatter::date($payment->period_start) }}
                                        <span class="muted">–</span>
                                        {{ \App\Support\UiFormatter::date($payment->period_end) }}
                                    </td>
                                    <td data-label="Tenggat">
                                        <div style="font-weight:600">{{ \App\Support\UiFormatter::date($payment->due_date) }}</div>
                                        <div class="muted">{{ $payment->deadline_status === 'due_today' ? 'Hari ini' : $payment->days_remaining.' hari lagi' }}</div>
                                    </td>
                                    <td data-label="Status">
                                        <span class="badge {{ $paymentBadgeClasses[$payment->deadline_status] ?? 'badge-safe' }}">
                                            {{ $deadlineStatusLabels[$payment->deadline_status] ?? $payment->deadline_status }}
                                        </span>
                                    </td>
                                    <td data-label="Follow Up" class="table-actions-col">
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
                                            <a href="{{ $reminderUrl }}" target="_blank" rel="noopener noreferrer" class="button button-subtle">WhatsApp</a>
                                        @else
                                            <span class="muted">Nomor tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>

        {{-- Terlambat --}}
        <article class="card">
            <div class="card-head has-divider">
                <div class="card-subhead">
                    <div>
                        <h2 class="card-title">Pembayaran Terlambat</h2>
                        <p class="card-copy" style="margin-top:3px">Tagihan yang telah melewati tenggat dan perlu ditindaklanjuti.</p>
                    </div>
                    @if (!$paymentsOverdue->isEmpty())
                        <span class="count-pill count-pill-danger">{{ $paymentsOverdue->count() }}</span>
                    @endif
                </div>
            </div>

            @if ($paymentsOverdue->isEmpty())
                <div class="card-body">
                    <div class="status-row">
                        <span class="status-dot status-dot-safe"></span>
                        <span class="muted">Tidak ada tagihan yang terlambat.</span>
                    </div>
                </div>
            @else
                <div class="table-wrap">
                    <table class="responsive-table">
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
                                    <td data-label="Penghuni" style="font-weight:600">{{ $payment->tenant_name }}</td>
                                    <td data-label="Kamar">{{ $payment->room_name }}</td>
                                    <td data-label="Nominal" style="font-weight:600">{{ \App\Support\UiFormatter::currency($payment->amount) }}</td>
                                    <td data-label="Tenggat">{{ \App\Support\UiFormatter::date($payment->due_date) }}</td>
                                    <td data-label="Keterlambatan">
                                        <span style="color:#9f1239;font-weight:700">{{ abs((int) $payment->days_remaining) }} hari</span>
                                    </td>
                                    <td data-label="Status">
                                        <span class="badge {{ $paymentBadgeClasses[$payment->deadline_status] ?? 'badge-overdue' }}">
                                            {{ $deadlineStatusLabels[$payment->deadline_status] ?? $payment->deadline_status }}
                                        </span>
                                    </td>
                                    <td data-label="Follow Up" class="table-actions-col">
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
                                            <a href="{{ $reminderUrl }}" target="_blank" rel="noopener noreferrer" class="button button-subtle">WhatsApp</a>
                                        @else
                                            <span class="muted">Nomor tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>

        {{-- Masa Tinggal --}}
        <article class="card">
            <div class="card-head has-divider">
                <div class="card-subhead">
                    <div>
                        <h2 class="card-title">Masa Tinggal Perlu Perhatian</h2>
                        <p class="card-copy" style="margin-top:3px">Penghuni yang masa tinggalnya hampir, tepat, atau sudah berakhir.</p>
                    </div>
                    @if (!$tenantEndWarnings->isEmpty())
                        <span class="count-pill {{ collect($tenantEndWarnings)->contains(fn($t) => $t->rent_period_status === 'ended') ? 'count-pill-danger' : 'count-pill-warning' }}">{{ $tenantEndWarnings->count() }}</span>
                    @endif
                </div>
            </div>

            @if ($tenantEndWarnings->isEmpty())
                <div class="card-body">
                    <div class="status-row">
                        <span class="status-dot status-dot-safe"></span>
                        <span class="muted">Tidak ada masa tinggal yang perlu perhatian khusus.</span>
                    </div>
                </div>
            @else
                <div class="table-wrap">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>Penghuni</th>
                                <th>Kamar</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Keluar</th>
                                <th>Sisa / Lewat</th>
                                <th>Status Masa Tinggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenantEndWarnings as $tenant)
                                <tr>
                                    <td data-label="Penghuni" style="font-weight:600">{{ $tenant->tenant_name }}</td>
                                    <td data-label="Kamar">{{ $tenant->room_name }}</td>
                                    <td data-label="Tanggal Masuk">{{ \App\Support\UiFormatter::date($tenant->start_date) }}</td>
                                    <td data-label="Tanggal Keluar">{{ \App\Support\UiFormatter::date($tenant->end_date) }}</td>
                                    <td data-label="Sisa / Lewat">
                                        @if ($tenant->rent_period_status === 'ended')
                                            <span style="color:#9f1239;font-weight:700">{{ abs((int) $tenant->days_until_end) }} hari lewat</span>
                                        @elseif ($tenant->rent_period_status === 'ends_today')
                                            <span style="color:#9a3412;font-weight:700">Berakhir hari ini</span>
                                        @else
                                            <span style="font-weight:600">{{ (int) $tenant->days_until_end }} hari lagi</span>
                                        @endif
                                    </td>
                                    <td data-label="Status Masa Tinggal">
                                        <span class="badge {{ $rentBadgeClasses[$tenant->rent_period_status] ?? 'badge-safe' }}">
                                            {{ $rentStatusLabels[$tenant->rent_period_status] ?? $tenant->rent_period_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>

    </div>
@endsection