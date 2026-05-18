@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('eyebrow', 'Admin Dashboard')
@section('page_title', 'Dashboard Admin NATAKOS')
@section('page_description', 'Ringkasan kondisi kamar, penghuni, pembayaran, dan masa tinggal terkini berdasarkan data nyata di database NATAKOS.')

@section('page_actions')
    <a href="{{ route('admin.payments.index') }}" class="button button-primary">Lihat pembayaran</a>
    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kelola penghuni</a>
@endsection

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

    <div class="content-stack">
    <section class="metric-grid">
        @foreach ($statCards as $card)
            <article class="card metric-card">
                <p class="metric-label">{{ $card['label'] }}</p>
                <p class="metric-value">{{ number_format($card['value'], 0, ',', '.') }}</p>
                <p class="metric-hint">{{ $card['hint'] }}</p>
            </article>
        @endforeach
    </section>

    @if (collect($alerts)->contains(fn (array $alert) => $alert['show']))
        <section class="alert-stack">
            @foreach ($alerts as $alert)
                @if ($alert['show'])
                    <article class="alert-box {{ $alert['tone'] === 'danger' ? 'alert-box-danger' : 'alert-box-warning' }}">
                        <h2>{{ $alert['title'] }}</h2>
                        <p>{{ $alert['message'] }}</p>
                    </article>
                @endif
            @endforeach
        </section>
    @endif

    <section class="content-stack">
        <article class="card">
            <div class="card-head has-divider">
                <h2 class="card-title">Pembayaran Mendekati Tenggat</h2>
                <p class="card-copy">Daftar ringkas tagihan yang jatuh tempo hari ini atau dalam lima hari ke depan.</p>
            </div>

            @if ($paymentsDueSoon->isEmpty())
                <div class="card-body">
                    <p class="card-copy">Belum ada tagihan yang mendekati tenggat.</p>
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
                                <th>Status Warning</th>
                                <th>Follow Up</th>
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
                                    <td data-label="Follow Up">
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

        <article class="card">
            <div class="card-head has-divider">
                <h2 class="card-title">Pembayaran Terlambat</h2>
                <p class="card-copy">Daftar ringkas tagihan yang telah melewati tenggat pembayaran dan perlu ditindaklanjuti.</p>
            </div>

            @if ($paymentsOverdue->isEmpty())
                <div class="card-body">
                    <p class="card-copy">Belum ada tagihan yang terlambat.</p>
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
                                <th>Status Warning</th>
                                <th>Follow Up</th>
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
                                    <td data-label="Follow Up">
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

        <article class="card">
            <div class="card-head has-divider">
                <h2 class="card-title">Masa Tinggal Perlu Perhatian</h2>
                <p class="card-copy">Daftar ringkas penghuni yang masa tinggalnya hampir berakhir, berakhir hari ini, atau sudah berakhir.</p>
            </div>

            @if ($tenantEndWarnings->isEmpty())
                <div class="card-body">
                    <p class="card-copy">Belum ada masa tinggal yang perlu perhatian khusus.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="responsive-table">
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
    </div>
@endsection
