@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('eyebrow', 'Admin Dashboard')
@section('page_title', 'Dashboard Admin NATAKOS')
@section('page_description', 'Ringkasan kondisi kamar, penghuni, pembayaran, dan masa tinggal terkini berdasarkan data nyata di database NATAKOS.')

@section('content')
    @php
        $statCards = [
            ['label' => 'Total kamar', 'value' => $metrics['total_rooms'], 'hint' => 'Semua kamar yang terdaftar'],
            ['label' => 'Kamar tersedia', 'value' => $metrics['rooms_available'], 'hint' => 'Siap ditempati penghuni baru'],
            ['label' => 'Kamar terisi', 'value' => $metrics['rooms_occupied'], 'hint' => 'Sedang digunakan penghuni'],
            ['label' => 'Kamar maintenance', 'value' => $metrics['rooms_maintenance'], 'hint' => 'Sedang dalam perbaikan'],
            ['label' => 'Total penghuni aktif', 'value' => $metrics['active_tenants'], 'hint' => 'Status penghuni aktif saat ini'],
            ['label' => 'Pembayaran belum bayar', 'value' => $metrics['payments_unpaid'], 'hint' => 'Tagihan masih belum dibayar'],
            ['label' => 'Menunggu verifikasi', 'value' => $metrics['payments_pending_verification'], 'hint' => 'Perlu dicek admin'],
            ['label' => 'Pembayaran lunas', 'value' => $metrics['payments_paid'], 'hint' => 'Sudah dibayar dan tercatat'],
            ['label' => 'Mendekati tenggat', 'value' => $metrics['payments_due_soon'], 'hint' => 'Jatuh tempo dalam 1-5 hari'],
            ['label' => 'Jatuh tempo hari ini', 'value' => $metrics['payments_due_today'], 'hint' => 'Perlu perhatian segera'],
            ['label' => 'Pembayaran terlambat', 'value' => $metrics['payments_overdue'], 'hint' => 'Lewat dari tenggat bayar'],
            ['label' => 'Masa tinggal hampir berakhir', 'value' => $metrics['tenants_ending_soon'], 'hint' => 'Berakhir dalam 1-5 hari'],
            ['label' => 'Berakhir hari ini', 'value' => $metrics['tenants_end_today'], 'hint' => 'Masa tinggal selesai hari ini'],
            ['label' => 'Masa tinggal sudah berakhir', 'value' => $metrics['tenants_ended'], 'hint' => 'Perlu tindak lanjut admin'],
        ];

        $paymentBadgeClasses = [
            'paid' => 'badge-paid',
            'safe' => 'badge-safe',
            'due_soon' => 'badge-due-soon',
            'due_today' => 'badge-due-today',
            'overdue' => 'badge-overdue',
        ];

        $rentBadgeClasses = [
            'inactive' => 'badge-inactive',
            'no_end_date' => 'badge-unpaid',
            'ended' => 'badge-overdue',
            'ends_today' => 'badge-due-today',
            'ending_soon' => 'badge-due-soon',
            'safe' => 'badge-safe',
        ];

        $alerts = [
            [
                'show' => $metrics['payments_due_today'] > 0,
                'tone' => 'today',
                'title' => 'Pembayaran jatuh tempo hari ini',
                'message' => 'Ada '.$metrics['payments_due_today'].' tagihan yang jatuh tempo hari ini dan perlu ditindaklanjuti sekarang.',
            ],
            [
                'show' => $metrics['payments_overdue'] > 0,
                'tone' => 'danger',
                'title' => 'Pembayaran terlambat',
                'message' => 'Ada '.$metrics['payments_overdue'].' tagihan yang sudah melewati tenggat pembayaran.',
            ],
            [
                'show' => $metrics['tenants_end_today'] > 0,
                'tone' => 'today',
                'title' => 'Masa tinggal berakhir hari ini',
                'message' => 'Ada '.$metrics['tenants_end_today'].' penghuni dengan masa tinggal yang berakhir hari ini.',
            ],
            [
                'show' => $metrics['tenants_ended'] > 0,
                'tone' => 'danger',
                'title' => 'Masa tinggal sudah berakhir',
                'message' => 'Ada '.$metrics['tenants_ended'].' penghuni yang masa tinggalnya sudah berakhir dan perlu tindak lanjut.',
            ],
        ];
    @endphp

    <style>
        .dashboard-stats {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            margin-bottom: 24px;
        }

        .dashboard-stat-card {
            padding: 22px;
        }

        .dashboard-stat-label {
            margin: 0 0 10px;
            color: #5e5e5e;
            font-size: 13px;
            line-height: 1.5;
        }

        .dashboard-stat-value {
            margin: 0;
            font-size: 34px;
            line-height: 1;
            font-weight: 700;
        }

        .dashboard-stat-hint {
            margin: 10px 0 0;
            color: #5e5e5e;
            font-size: 13px;
            line-height: 1.6;
        }

        .dashboard-alerts {
            display: grid;
            gap: 12px;
            margin-bottom: 24px;
        }

        .dashboard-alert {
            border-radius: 16px;
            padding: 18px 20px;
        }

        .dashboard-alert h2 {
            margin: 0 0 6px;
            font-size: 18px;
            line-height: 1.3;
        }

        .dashboard-alert p {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .dashboard-alert-warning {
            background: #fef3c7;
            color: #78350f;
        }

        .dashboard-alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .dashboard-sections {
            display: grid;
            gap: 20px;
        }

        .dashboard-panel {
            padding: 0;
        }

        .dashboard-panel-head {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 20px 22px 0;
        }

        .dashboard-panel-title {
            margin: 0;
            font-size: 24px;
            line-height: 1.25;
        }

        .dashboard-panel-copy {
            margin: 0;
            color: #5e5e5e;
            font-size: 14px;
            line-height: 1.6;
        }

        .dashboard-table {
            min-width: 0;
        }

        .dashboard-table td,
        .dashboard-table th {
            white-space: normal;
        }

        .dashboard-empty {
            padding: 22px;
            color: #5e5e5e;
            font-size: 14px;
            line-height: 1.7;
        }
    </style>

    <section class="dashboard-stats">
        @foreach ($statCards as $card)
            <article class="card dashboard-stat-card">
                <p class="dashboard-stat-label">{{ $card['label'] }}</p>
                <p class="dashboard-stat-value">{{ number_format($card['value'], 0, ',', '.') }}</p>
                <p class="dashboard-stat-hint">{{ $card['hint'] }}</p>
            </article>
        @endforeach
    </section>

    @if (collect($alerts)->contains(fn (array $alert) => $alert['show']))
        <section class="dashboard-alerts">
            @foreach ($alerts as $alert)
                @if ($alert['show'])
                    <article class="dashboard-alert {{ $alert['tone'] === 'danger' ? 'dashboard-alert-danger' : 'dashboard-alert-warning' }}">
                        <h2>{{ $alert['title'] }}</h2>
                        <p>{{ $alert['message'] }}</p>
                    </article>
                @endif
            @endforeach
        </section>
    @endif

    <section class="dashboard-sections">
        <article class="card dashboard-panel">
            <div class="dashboard-panel-head">
                <h2 class="dashboard-panel-title">Pembayaran Mendekati Tenggat</h2>
                <p class="dashboard-panel-copy">Daftar ringkas tagihan yang jatuh tempo hari ini atau dalam lima hari ke depan.</p>
            </div>

            @if ($paymentsDueSoon->isEmpty())
                <div class="dashboard-empty">Belum ada tagihan yang mendekati tenggat.</div>
            @else
                <div class="table-wrap">
                    <table class="dashboard-table responsive-table">
                        <thead>
                            <tr>
                                <th>Penghuni</th>
                                <th>Kamar</th>
                                <th>Nominal</th>
                                <th>Periode</th>
                                <th>Tenggat</th>
                                <th>Status Warning</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentsDueSoon as $payment)
                                <tr>
                                    <td data-label="Penghuni">{{ $payment->tenant_name }}</td>
                                    <td data-label="Kamar">{{ $payment->room_name }}</td>
                                    <td data-label="Nominal">{{ \App\Support\UiFormatter::currency($payment->amount) }}</td>
                                    <td data-label="Periode">
                                        <div>{{ \App\Support\UiFormatter::date($payment->period_start) }}</div>
                                        <div class="muted">s/d {{ \App\Support\UiFormatter::date($payment->period_end) }}</div>
                                    </td>
                                    <td data-label="Tenggat">
                                        <div>{{ \App\Support\UiFormatter::date($payment->due_date) }}</div>
                                        <div class="muted">{{ $payment->deadline_status === 'due_today' ? 'Hari ini' : $payment->days_remaining.' hari lagi' }}</div>
                                    </td>
                                    <td data-label="Status Warning">
                                        <span class="badge {{ $paymentBadgeClasses[$payment->deadline_status] ?? 'badge-safe' }}">
                                            {{ $deadlineStatusLabels[$payment->deadline_status] ?? $payment->deadline_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>

        <article class="card dashboard-panel">
            <div class="dashboard-panel-head">
                <h2 class="dashboard-panel-title">Pembayaran Terlambat</h2>
                <p class="dashboard-panel-copy">Daftar ringkas tagihan yang telah melewati tenggat pembayaran dan perlu ditindaklanjuti.</p>
            </div>

            @if ($paymentsOverdue->isEmpty())
                <div class="dashboard-empty">Belum ada tagihan yang terlambat.</div>
            @else
                <div class="table-wrap">
                    <table class="dashboard-table responsive-table">
                        <thead>
                            <tr>
                                <th>Penghuni</th>
                                <th>Kamar</th>
                                <th>Nominal</th>
                                <th>Tenggat</th>
                                <th>Keterlambatan</th>
                                <th>Status Warning</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentsOverdue as $payment)
                                <tr>
                                    <td data-label="Penghuni">{{ $payment->tenant_name }}</td>
                                    <td data-label="Kamar">{{ $payment->room_name }}</td>
                                    <td data-label="Nominal">{{ \App\Support\UiFormatter::currency($payment->amount) }}</td>
                                    <td data-label="Tenggat">{{ \App\Support\UiFormatter::date($payment->due_date) }}</td>
                                    <td data-label="Keterlambatan">{{ abs((int) $payment->days_remaining) }} hari</td>
                                    <td data-label="Status Warning">
                                        <span class="badge {{ $paymentBadgeClasses[$payment->deadline_status] ?? 'badge-overdue' }}">
                                            {{ $deadlineStatusLabels[$payment->deadline_status] ?? $payment->deadline_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>

        <article class="card dashboard-panel">
            <div class="dashboard-panel-head">
                <h2 class="dashboard-panel-title">Masa Tinggal Perlu Perhatian</h2>
                <p class="dashboard-panel-copy">Daftar ringkas penghuni yang masa tinggalnya hampir berakhir, berakhir hari ini, atau sudah berakhir.</p>
            </div>

            @if ($tenantEndWarnings->isEmpty())
                <div class="dashboard-empty">Belum ada masa tinggal yang perlu perhatian khusus.</div>
            @else
                <div class="table-wrap">
                    <table class="dashboard-table responsive-table">
                        <thead>
                            <tr>
                                <th>Penghuni</th>
                                <th>Kamar</th>
                                <th>Tanggal masuk</th>
                                <th>Tanggal keluar</th>
                                <th>Sisa waktu</th>
                                <th>Status Masa Tinggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenantEndWarnings as $tenant)
                                <tr>
                                    <td data-label="Penghuni">{{ $tenant->tenant_name }}</td>
                                    <td data-label="Kamar">{{ $tenant->room_name }}</td>
                                    <td data-label="Tanggal masuk">{{ \App\Support\UiFormatter::date($tenant->start_date) }}</td>
                                    <td data-label="Tanggal keluar">{{ \App\Support\UiFormatter::date($tenant->end_date) }}</td>
                                    <td data-label="Sisa waktu">
                                        @if ($tenant->rent_period_status === 'ended')
                                            {{ abs((int) $tenant->days_until_end) }} hari lewat
                                        @elseif ($tenant->rent_period_status === 'ends_today')
                                            Berakhir hari ini
                                        @else
                                            {{ (int) $tenant->days_until_end }} hari lagi
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
    </section>
@endsection
