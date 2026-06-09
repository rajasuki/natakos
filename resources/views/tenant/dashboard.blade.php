@extends('tenant.layout')

@section('title', 'Dashboard Penghuni')
@section('eyebrow', '')

@push('styles')
<style>
    .page-header { display: none; }

    /* ── Welcome Hero ── */
    .tenant-welcome {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: var(--radius-xl);
        padding: 24px 28px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 20px;
    }

    @media (min-width: 768px) {
        .tenant-welcome {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .tenant-welcome-glow {
        position: absolute;
        right: 0;
        top: 0;
        width: 33%;
        height: 100%;
        background: linear-gradient(to left, rgba(74,124,89,.06), transparent);
        pointer-events: none;
    }

    .tenant-welcome h1 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 700;
        color: var(--ui-ink);
        letter-spacing: -0.3px;
    }

    @media (min-width: 640px) {
        .tenant-welcome h1 { font-size: 32px; }
    }

    .tenant-pill-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tenant-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: var(--radius-pill);
        font-size: 12px;
        font-weight: 600;
        line-height: 1.3;
        border: 1px solid var(--ui-border);
        background: var(--gray-100);
        color: var(--gray-600);
    }

    .tenant-pill .material-symbols-outlined { font-size: 16px; }

    .tenant-pill-success {
        background: var(--ui-success);
        color: #166534;
        border-color: #86efac;
    }
    .tenant-pill-warning {
        background: var(--ui-warning);
        color: #92400e;
        border-color: var(--ui-warning-border);
    }
    .tenant-pill-danger {
        background: var(--ui-danger);
        color: #9f1239;
        border-color: var(--ui-danger-border);
    }

    .tenant-whatsapp-btn {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* ── Stats Grid ── */
    .tenant-stats {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (min-width: 640px) {
        .tenant-stats { grid-template-columns: repeat(3, 1fr); }
    }

    .tenant-stat {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: var(--radius-lg);
        padding: 18px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .tenant-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: var(--radius-md);
        flex-shrink: 0;
    }

    .tenant-stat-icon .material-symbols-outlined { font-size: 24px; }

    .tenant-stat-icon-accent {
        background: var(--ui-accent-soft);
        color: var(--ui-accent);
    }
    .tenant-stat-icon-success {
        background: var(--ui-success);
        color: #166534;
        border: 1px solid #86efac;
    }
    .tenant-stat-icon-warning {
        background: var(--ui-warning);
        color: #92400e;
        border: 1px solid var(--ui-warning-border);
    }

    .tenant-stat-label {
        margin: 0 0 4px;
        font-size: 12px;
        font-weight: 600;
        color: var(--ui-body);
    }

    .tenant-stat-value {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.2;
        color: var(--ui-ink);
        letter-spacing: -0.2px;
    }

    /* ── Dashboard Grid ── */
    .tenant-dash-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    @media (min-width: 900px) {
        .tenant-dash-grid { grid-template-columns: 1fr 1.6fr; }
    }

    .tenant-dash-right {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* ── Shared card parts ── */
    .tenant-card {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .tenant-card-head {
        padding: 16px 22px;
        border-bottom: 1px solid var(--ui-border);
        background: var(--gray-50);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tenant-card-head h2 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--ui-ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tenant-card-head h2 .material-symbols-outlined {
        font-size: 20px;
        color: var(--ui-body);
    }

    .tenant-card-body {
        padding: 20px 22px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* ── Info Card ── */
    .tenant-info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .tenant-info-row-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--ui-body);
        flex-shrink: 0;
    }

    .tenant-info-row-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--ui-ink);
        text-align: right;
    }

    .tenant-info-sep {
        height: 1px;
        background: var(--ui-border);
        border: 0;
        margin: 0;
        opacity: .5;
    }

    /* ── Stay Card ── */
    .tenant-stay-range {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        background: var(--gray-50);
        border-radius: var(--radius-md);
        border: 1px solid var(--ui-border);
    }

    .tenant-stay-range-block {
        flex: 1;
    }

    .tenant-stay-range-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ui-body);
        margin-bottom: 4px;
    }

    .tenant-stay-range-date {
        font-size: 14px;
        font-weight: 600;
        color: var(--ui-ink);
        margin: 0;
    }

    .tenant-stay-range-arrow {
        color: var(--ui-border);
        font-size: 22px;
        flex-shrink: 0;
    }

    .tenant-stay-range-right {
        text-align: right;
    }

    .tenant-stay-note {
        padding: 12px 14px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 13px;
        line-height: 1.6;
    }

    .tenant-stay-note .material-symbols-outlined {
        font-size: 20px;
        flex-shrink: 0;
    }

    .tenant-stay-note-warning {
        background: rgba(74,124,89,.08);
        border: 1px solid rgba(74,124,89,.15);
        color: var(--ui-ink);
    }

    .tenant-stay-note-warning .material-symbols-outlined { color: var(--ui-accent); }

    .tenant-stay-note-danger {
        background: var(--ui-danger);
        border: 1px solid var(--ui-danger-border);
        color: #9f1239;
    }

    .tenant-stay-note-danger .material-symbols-outlined { color: #9f1239; }

    .tenant-stay-note-safe {
        background: var(--ui-success);
        border: 1px solid #86efac;
        color: #166534;
    }

    .tenant-stay-note-safe .material-symbols-outlined { color: #166534; }

    .tenant-stay-note p { margin: 0; }

    /* ── Payment Card ── */
    .tenant-payment-list {
        padding: 16px 22px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .tenant-payment-item {
        border: 1px solid var(--ui-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .tenant-payment-item-header {
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        background: var(--gray-50);
    }

    @media (min-width: 640px) {
        .tenant-payment-item-header {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .tenant-payment-item-title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--ui-ink);
    }

    .tenant-payment-item-period {
        margin: 4px 0 0;
        font-size: 12px;
        color: var(--ui-body);
    }

    .tenant-payment-item-price {
        font-size: 22px;
        font-weight: 800;
        color: var(--ui-accent);
        letter-spacing: -0.3px;
        line-height: 1.2;
        margin: 0;
    }

    .tenant-payment-item-due {
        margin-top: 4px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .tenant-payment-item-due .material-symbols-outlined { font-size: 14px; }

    .tenant-payment-item-due-error { color: #9f1239; }

    .tenant-payment-item-body {
        padding: 16px 20px;
        background: #fff;
        border-top: 1px solid var(--ui-border);
    }

    .tenant-payment-status-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 14px;
    }

    .tenant-payment-status-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ui-body);
    }

    .tenant-payment-upload-area {
        background: var(--gray-50);
        border: 1.5px dashed var(--ui-border);
        border-radius: var(--radius-md);
        padding: 18px;
    }

    .tenant-payment-upload-area h3 {
        margin: 0 0 4px;
        font-size: 14px;
        font-weight: 600;
        color: var(--ui-ink);
    }

    .tenant-payment-upload-area p {
        margin: 0 0 14px;
        font-size: 13px;
        color: var(--ui-body);
    }

    .tenant-upload-row {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    @media (min-width: 640px) {
        .tenant-upload-row {
            flex-direction: row;
            align-items: center;
        }
    }

    .tenant-upload-label {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: var(--radius-md);
        font-size: 13px;
        font-weight: 600;
        color: var(--ui-ink);
        transition: background .15s;
        flex: 1;
        white-space: nowrap;
    }

    .tenant-upload-label:hover { background: var(--gray-100); }

    .tenant-upload-label .material-symbols-outlined {
        font-size: 20px;
        color: var(--ui-body);
    }

    .tenant-proof-state-box {
        padding: 12px 16px;
        border-radius: var(--radius-md);
        border: 1px solid var(--ui-border);
        background: #fff;
        font-size: 13px;
        line-height: 1.6;
    }

    .tenant-payment-history { opacity: .55; }

    .tenant-payment-history .tenant-payment-item-title {
        text-decoration: line-through;
        color: var(--ui-body);
    }

    .tenant-payment-history .tenant-payment-item-price { color: var(--ui-body); }

    .tenant-price-right { text-align: left; }

    @media (min-width: 640px) {
        .tenant-price-right { text-align: right; }
    }

    .tenant-payment-footer {
        padding: 14px 22px 20px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* ── Empty state ── */
    .tenant-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.3;
    }

    .tenant-status-badge.badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .tenant-status-badge.badge-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .tenant-status-badge.badge-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .tenant-status-badge.badge-unpaid {
        background: var(--ui-soft);
        color: var(--ui-body);
    }

    .tenant-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 32px;
        text-align: center;
    }

    .tenant-empty h2 {
        margin: 0 0 8px;
        font-size: 18px;
        font-weight: 700;
        color: var(--ui-ink);
    }

    .tenant-empty p {
        margin: 0 auto;
        max-width: 480px;
        color: var(--ui-body);
        line-height: 1.7;
        font-size: 14px;
    }

    .tenant-empty-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
        justify-content: center;
    }

    /* ── Misc helpers ── */
    .tenant-flex-center {
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endpush

@section('content')
    @php
        $roomStatus = $tenant?->room?->status;
        $rentStatus = $rentSummary?->rent_period_status ?? 'safe';
        $paymentStatus = $featuredPayment?->status;
        $paymentActionId = (string) session('payment_action_id');
        $erroredPaymentId = (string) old('payment_id');
        $totalTagihan = $payments?->sum(fn ($p) => (int) ($p->status !== 'paid' ? $p->amount : 0)) ?? 0;
    @endphp

    {{-- ── WELCOME HERO ── --}}
    <section class="tenant-welcome">
        <div style="position:relative;z-index:1;">
            <h1>Halo, {{ $user->name }}</h1>

            @if ($tenant)
                <div class="tenant-pill-group">
                    <span class="tenant-pill">
                        <span class="material-symbols-outlined">door_front</span>
                        {{ $tenant->room?->name ?: 'Kamar tidak tersedia' }}
                    </span>

                    @php
                        $rentBadgeClass = match($rentStatus) {
                            'ending_soon', 'ends_today' => 'tenant-pill-warning',
                            'ended' => 'tenant-pill-danger',
                            default => 'tenant-pill-success',
                        };
                        $rentIcon = match($rentStatus) {
                            'ending_soon', 'ends_today' => 'hourglass_bottom',
                            'ended' => 'event_busy',
                            default => 'check_circle',
                        };
                    @endphp
                    <span class="tenant-pill {{ $rentBadgeClass }}">
                        <span class="material-symbols-outlined">{{ $rentIcon }}</span>
                        {{ $rentStatusLabels[$rentStatus] ?? 'Aman' }}
                    </span>

                    @if ($paymentStatus)
                        @php
                            $payBadgeClass = match($paymentStatus) {
                                'pending_verification' => 'tenant-pill-warning',
                                'rejected' => 'tenant-pill-danger',
                                'paid' => 'tenant-pill-success',
                                default => 'tenant-pill',
                            };
                            $payIcon = match($paymentStatus) {
                                'pending_verification' => 'hourglass_empty',
                                'rejected' => 'error',
                                'paid' => 'check_circle',
                                default => 'payments',
                            };
                        @endphp
                        <span class="tenant-pill {{ $payBadgeClass }}">
                            <span class="material-symbols-outlined">{{ $payIcon }}</span>
                            {{ $paymentStatusLabels[$paymentStatus] ?? $paymentStatus }}
                        </span>
                    @endif
                </div>
            @else
                <div class="tenant-pill-group">
                    <span class="tenant-pill tenant-pill-danger">
                        <span class="material-symbols-outlined">error</span>
                        Akun belum terhubung ke tenant aktif
                    </span>
                </div>
            @endif
        </div>

        @if ($tenant)
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-primary tenant-whatsapp-btn">
                <span class="material-symbols-outlined" style="font-size:20px;">chat</span>
                Hubungi Pemilik via WhatsApp
            </a>
        @endif

        <div class="tenant-welcome-glow"></div>
    </section>

    {{-- ── EMPTY STATE ── --}}
    @if ($tenant === null)
        <section class="tenant-empty">
            <h2>Data penghuni belum tersedia</h2>
            <p>Akun Anda belum terhubung ke data tenant aktif. Ajukan sewa kamar terlebih dahulu melalui halaman kamar, atau hubungi pengelola IchiKOS jika Anda sudah memiliki kamar.</p>
            <div class="tenant-empty-actions">
                <a href="{{ route('rooms.index') }}" class="button button-primary">Lihat Kamar & Ajukan Sewa</a>
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Hubungi Pemilik via WhatsApp</a>
            </div>
        </section>
    @else
        {{-- ── ALERTS ── --}}
        @if ($paymentWarning || $rentWarning)
            <div class="alert-stack" style="margin-bottom:20px;">
                @if ($paymentWarning)
                    <article class="alert {{ $paymentWarning['tone'] === 'danger' ? 'alert-danger' : 'alert-warning' }}">
                        <h2>{{ $paymentWarning['title'] }}</h2>
                        <p>{{ $paymentWarning['message'] }}</p>
                    </article>
                @endif
                @if ($rentWarning)
                    <article class="alert {{ $rentWarning['tone'] === 'danger' ? 'alert-danger' : 'alert-warning' }}">
                        <h2>{{ $rentWarning['title'] }}</h2>
                        <p>{{ $rentWarning['message'] }}</p>
                    </article>
                @endif
            </div>
        @endif

        {{-- ── QUICK STATS ── --}}
        <section class="tenant-stats">
            <div class="tenant-stat">
                <div class="tenant-stat-icon tenant-stat-icon-accent">
                    <span class="material-symbols-outlined">bed</span>
                </div>
                <div>
                    <p class="tenant-stat-label">Kamar</p>
                    <p class="tenant-stat-value">{{ $tenant->room?->name ?: '-' }}</p>
                </div>
            </div>
            <div class="tenant-stat">
                <div class="tenant-stat-icon tenant-stat-icon-success">
                    <span class="material-symbols-outlined">calendar_today</span>
                </div>
                <div>
                    <p class="tenant-stat-label">Status Masa Tinggal</p>
                    <p class="tenant-stat-value">{{ $rentStatusLabels[$rentStatus] ?? '-' }}</p>
                </div>
            </div>
            <div class="tenant-stat">
                <div class="tenant-stat-icon tenant-stat-icon-warning">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <div>
                    <p class="tenant-stat-label">Total Tagihan</p>
                    <p class="tenant-stat-value">{{ \App\Support\UiFormatter::currency($totalTagihan) }}</p>
                </div>
            </div>
        </section>

        {{-- ── MAIN DASHBOARD GRID ── --}}
        <section class="tenant-dash-grid">

            {{-- LEFT COLUMN --}}
            <div class="tenant-dash-right">

                {{-- Informasi Kamar --}}
                <article class="tenant-card">
                    <div class="tenant-card-head">
                        <h2><span class="material-symbols-outlined">info</span> Informasi Kamar</h2>
                    </div>
                    <div class="tenant-card-body">
                        <div class="tenant-info-row">
                            <span class="tenant-info-row-label">Penyewa</span>
                            <span class="tenant-info-row-value">{{ $user->name }}</span>
                        </div>
                        <hr class="tenant-info-sep">
                        <div class="tenant-info-row">
                            <span class="tenant-info-row-label">Kamar</span>
                            <span class="tenant-info-row-value">{{ $tenant->room?->name ?: '-' }}</span>
                        </div>
                        <hr class="tenant-info-sep">
                        <div class="tenant-info-row">
                            <span class="tenant-info-row-label">Harga Sewa / Bulan</span>
                            <span class="tenant-info-row-value">{{ $tenant->room ? \App\Support\UiFormatter::currency($tenant->room->price) : '-' }}</span>
                        </div>
                        <hr class="tenant-info-sep">
                        <div class="tenant-info-row">
                            <span class="tenant-info-row-label">Status</span>
                            <span class="tenant-info-row-value">
                                <span class="badge badge-{{ $tenant->room?->status ?? 'maintenance' }}">{{ $roomStatusLabels[$tenant->room?->status] ?? 'Tidak tersedia' }}</span>
                            </span>
                        </div>
                    </div>
                </article>

                {{-- Masa Tinggal --}}
                <article class="tenant-card">
                    <div class="tenant-card-head">
                        <h2><span class="material-symbols-outlined">event_available</span> Masa Tinggal</h2>
                    </div>
                    <div class="tenant-card-body">
                        <div class="tenant-stay-range">
                            <div class="tenant-stay-range-block">
                                <p class="tenant-stay-range-label">Check-in</p>
                                <p class="tenant-stay-range-date">{{ \App\Support\UiFormatter::date($tenant->start_date) }}</p>
                            </div>
                            <span class="material-symbols-outlined tenant-stay-range-arrow">arrow_forward</span>
                            <div class="tenant-stay-range-block tenant-stay-range-right">
                                <p class="tenant-stay-range-label">Check-out</p>
                                <p class="tenant-stay-range-date">{{ \App\Support\UiFormatter::date($tenant->end_date) }}</p>
                            </div>
                        </div>

                        @php
                            $stayNoteClass = match($rentStatus) {
                                'ending_soon', 'ends_today' => 'tenant-stay-note-warning',
                                'ended' => 'tenant-stay-note-danger',
                                default => 'tenant-stay-note-safe',
                            };
                            $stayNoteIcon = match($rentStatus) {
                                'ending_soon' => 'timer',
                                'ends_today' => 'hourglass_top',
                                'ended' => 'event_busy',
                                'safe' => 'check_circle',
                                default => 'info',
                            };
                            $stayNoteMsg = match($rentStatus) {
                                'ending_soon' => 'Berakhir dalam <strong>'.$rentSummary->days_until_end.' hari</strong>. Harap lakukan pembayaran sewa bulan depan untuk memperpanjang.',
                                'ends_today' => 'Masa tinggal berakhir <strong>hari ini</strong>. Segera koordinasikan perpanjangan.',
                                'ended' => 'Sudah berakhir <strong>'.abs((int) ($rentSummary->days_until_end ?? 0)).' hari</strong> yang lalu. Mohon segera hubungi pengelola.',
                                'no_end_date' => 'Tanggal keluar belum ditentukan. Pastikan selalu terkini.',
                                'inactive' => 'Status tidak aktif.',
                                default => 'Masa tinggal masih dalam kondisi aman.',
                            };
                        @endphp
                        <div class="tenant-stay-note {{ $stayNoteClass }}">
                            <span class="material-symbols-outlined">{{ $stayNoteIcon }}</span>
                            <p>{!! $stayNoteMsg !!}</p>
                        </div>
                    </div>
                </article>

                {{-- Pengajuan Perbaikan --}}
                <article class="tenant-card">
                    <div class="tenant-card-head">
                        <h2><span class="material-symbols-outlined">build</span> Pengajuan Perbaikan</h2>
                        <a href="{{ route('tenant.maintenance-requests.create') }}" class="button button-primary" style="padding:6px 14px;font-size:12px;">Ajukan</a>
                    </div>
                    <div class="tenant-payment-list">
                        @forelse ($maintenanceRequests as $mr)
                            @php
                                $mrPriorityLabels = ['low' => 'Rendah', 'normal' => 'Normal', 'high' => 'Tinggi', 'urgent' => 'Darurat'];
                                $mrStatusLabels = ['pending' => 'Menunggu', 'in_progress' => 'Ditangani', 'resolved' => 'Selesai', 'rejected' => 'Ditolak'];
                                $mrPriorityBadge = match($mr->priority) {
                                    'urgent' => 'badge-rejected',
                                    'high' => 'badge-unpaid',
                                    'normal' => 'badge-pending',
                                    default => 'badge-pending',
                                };
                                $mrStatusBadge = match($mr->status) {
                                    'pending' => 'badge-pending',
                                    'in_progress' => 'badge-unpaid',
                                    'resolved' => 'badge-paid',
                                    'rejected' => 'badge-rejected',
                                    default => 'badge-unpaid',
                                };
                            @endphp
                            <div class="tenant-payment-row">
                                <div class="tenant-proof-main">
                                    <div class="tenant-proof-period">{{ $mr->title }}</div>
                                    <div class="tenant-proof-dates" style="margin-top:2px;">
                                        <span class="tag tag-small" style="background:var(--ui-soft);padding:2px 8px;border-radius:6px;font-size:11px;">{{ $mrPriorityLabels[$mr->priority] ?? $mr->priority }}</span>
                                    </div>
                                </div>
                                <div class="tenant-proof-status-wrap">
                                    <span class="tenant-status-badge {{ $mrStatusBadge }}">{{ $mrStatusLabels[$mr->status] ?? $mr->status }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="tenant-empty">
                                <p>Belum ada pengajuan perbaikan. Laporkan kerusakan kamar jika ada.</p>
                                <div class="tenant-empty-actions">
                                    <a href="{{ route('tenant.maintenance-requests.create') }}" class="button button-primary">Ajukan Perbaikan</a>
                                </div>
                            </div>
                        @endforelse
                        @if ($maintenanceRequests->isNotEmpty())
                            <div class="tenant-payment-footer" style="border-top:0;padding-top:8px;">
                                <a href="{{ route('tenant.maintenance-requests.index') }}" class="button button-subtle" style="font-size:12px;">Lihat semua pengajuan</a>
                            </div>
                        @endif
                    </div>
                </article>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="tenant-dash-right">

                {{-- Booking Requests --}}
                <article class="tenant-card">
                    <div class="tenant-card-head">
                        <h2><span class="material-symbols-outlined">how_to_reg</span> Pengajuan Sewa</h2>
                        @if ($bookingRequests->isNotEmpty())
                            <a href="{{ route('rooms.index') }}" class="button button-subtle" style="padding:6px 14px;font-size:12px;">Cari kamar lain</a>
                        @endif
                    </div>
                    <div class="tenant-payment-list">
                        @forelse ($bookingRequests as $br)
                            @php
                                $brStatusLabel = match($br->status) {
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => $br->status,
                                };
                                $brBadgeClass = match($br->status) {
                                    'pending' => 'badge-pending',
                                    'approved' => 'badge-paid',
                                    'rejected' => 'badge-rejected',
                                    default => 'badge-unpaid',
                                };
                            @endphp
                            <div class="tenant-payment-row">
                                <div class="tenant-proof-main">
                                    <div class="tenant-proof-period">
                                        {{ $br->room?->name ?? 'Kamar dihapus' }}
                                    </div>
                                    <div class="tenant-proof-dates">
                                        {{ \App\Support\UiFormatter::date($br->start_date) }} &mdash; {{ \App\Support\UiFormatter::date($br->end_date) }}
                                    </div>
                                    @if ($br->notes)
                                        <div style="font-size:12px;color:var(--ui-body);margin-top:4px;">{{ $br->notes }}</div>
                                    @endif
                                    @if ($br->status === 'rejected' && $br->rejection_reason)
                                        <div style="font-size:12px;color:#991b1b;margin-top:4px;">Alasan: {{ $br->rejection_reason }}</div>
                                    @endif
                                </div>
                                <div class="tenant-proof-status-wrap">
                                    <span class="tenant-status-badge {{ $brBadgeClass }}">{{ $brStatusLabel }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="tenant-empty">
                                <p>Belum ada pengajuan sewa. Anda bisa mengajukan sewa langsung dari halaman detail kamar.</p>
                                <div class="tenant-empty-actions">
                                    <a href="{{ route('rooms.index') }}" class="button button-primary">Lihat kamar tersedia</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </article>

                {{-- Pembayaran --}}
                <article class="tenant-card">
                    <div class="tenant-card-head">
                        <h2><span class="material-symbols-outlined">receipt_long</span> Pembayaran Aktif</h2>
                    </div>

                    @if ($payments === null || $payments->isEmpty())
                        <div class="tenant-payment-list">
                            <div class="tenant-empty">
                                <h2>Belum ada data pembayaran</h2>
                                <p>Belum ada tagihan atau riwayat pembayaran yang tercatat untuk akun Anda saat ini.</p>
                                <div class="tenant-empty-actions">
                                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Tanya soal pembayaran</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="tenant-payment-list">
                            @foreach ($payments as $payment)
                                @php
                                    $deadlineItem = $paymentDeadlines->get($payment->id);
                                    $canUploadProof = in_array($payment->status, ['unpaid', 'rejected'], true);
                                    $isPendingVerification = $payment->status === 'pending_verification';
                                    $isPaid = $payment->status === 'paid';
                                    $isActionTarget = $paymentActionId !== '' && $paymentActionId === (string) $payment->id;
                                    $isErrorTarget = $erroredPaymentId !== '' && $erroredPaymentId === (string) $payment->id;
                                    $isHistory = $isPaid;
                                    $dlIcon = match($deadlineItem?->deadline_status ?? 'safe') {
                                        'overdue' => 'warning',
                                        'due_today' => 'schedule',
                                        'due_soon' => 'timer',
                                        'paid' => 'check_circle',
                                        default => 'info',
                                    };
                                    $dlColor = match($deadlineItem?->deadline_status ?? 'safe') {
                                        'overdue' => 'tenant-payment-item-due-error',
                                        default => '',
                                    };
                                @endphp

                                <article class="tenant-payment-item {{ $isHistory ? 'tenant-payment-history' : '' }}" id="payment-{{ $payment->id }}">
                                    <div class="tenant-payment-item-header">
                                        <div>
                                            <h3 class="tenant-payment-item-title">{{ \App\Support\UiFormatter::currency($payment->amount) }}</h3>
                                            <p class="tenant-payment-item-period">Periode: {{ \App\Support\UiFormatter::date($payment->period_start) }} &mdash; {{ \App\Support\UiFormatter::date($payment->period_end) }}</p>
                                        </div>
                                        <div class="tenant-price-right">
                                            <p class="tenant-payment-item-price">{{ \App\Support\UiFormatter::currency($payment->amount) }}</p>
                                            @if ($payment->status !== 'paid')
                                                <p class="tenant-payment-item-due {{ $dlColor }}">
                                                    <span class="material-symbols-outlined">{{ $dlIcon }}</span>
                                                    Jatuh Tempo: {{ \App\Support\UiFormatter::date($payment->due_date) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tenant-payment-item-body">
                                        <div class="tenant-payment-status-row">
                                            <div class="tenant-flex-center">
                                                <span class="tenant-payment-status-label">Status:</span>
                                                <span class="badge badge-{{ str_replace('_', '-', $payment->status) }}">{{ $paymentStatusLabels[$payment->status] ?? $payment->status }}</span>
                                            </div>
                                            @if ($isPaid)
                                                <span style="font-size:12px;color:var(--ui-body);">
                                                    <span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle;">check_circle</span>
                                                    Lunas
                                                </span>
                                            @endif
                                        </div>

                                        @if ($isActionTarget && session('success'))
                                            <div class="flash flash-success" style="margin-bottom:12px;">{{ session('success') }}</div>
                                        @endif
                                        @if ($isActionTarget && session('error'))
                                            <div class="flash flash-error" style="margin-bottom:12px;">{{ session('error') }}</div>
                                        @endif

                                        @if ($payment->proof_image)
                                            <div class="tenant-proof-state-box" style="margin-bottom:12px;">
                                                Bukti bayar saat ini sudah tersimpan di sistem.
                                                @if ($payment->status === 'rejected')
                                                    Upload ulang gambar baru agar admin dapat meninjau ulang pembayaran ini.
                                                    @if ($payment->rejection_reason)
                                                        <br>Alasan penolakan terakhir: <strong>{{ $payment->rejection_reason }}</strong>.
                                                    @endif
                                                @endif
                                            </div>
                                        @endif

                                        @if ($canUploadProof)
                                            <div class="tenant-payment-upload-area">
                                                <h3>Upload Bukti Bayar Baru</h3>
                                                <p>Format: JPG, JPEG, PNG, WEBP (Maks. 2MB)</p>
                                                <form method="POST" action="{{ route('tenant.payments.proof.store', $payment) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                                    <div class="tenant-upload-row">
                                                        <label for="proof_image_{{ $payment->id }}" class="tenant-upload-label">
                                                            <span class="material-symbols-outlined">upload_file</span>
                                                            <span class="file-name">Pilih File...</span>
                                                            <input id="proof_image_{{ $payment->id }}" name="proof_image" type="file" accept="image/*" style="display:none;" required onchange="this.parentElement.querySelector('.file-name').textContent = this.files[0]?.name || 'Pilih File...';">
                                                        </label>
                                                        <button type="submit" class="button button-primary" style="flex-shrink:0;">Upload</button>
                                                    </div>
                                                    @if ($isErrorTarget && $errors->has('proof_image'))
                                                        <div style="color:#9f1239;font-size:12px;font-weight:600;margin-top:8px;">{{ $errors->first('proof_image') }}</div>
                                                    @endif
                                                    @if ($payment->status === 'rejected')
                                                        <p style="font-size:12px;color:var(--ui-body);margin-top:8px;">Bukti sebelumnya ditolak. Unggah file gambar baru untuk diverifikasi ulang.</p>
                                                    @endif
                                                </form>
                                            </div>
                                        @elseif ($isPendingVerification)
                                            <div class="tenant-proof-state-box">Bukti bayar sedang menunggu verifikasi admin. Upload ulang dinonaktifkan sementara.</div>
                                        @elseif ($isPaid)
                                            <div class="tenant-proof-state-box">Pembayaran ini sudah lunas. Upload ulang dinonaktifkan.</div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="tenant-payment-footer">
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Hubungi pemilik soal pembayaran</a>
                        </div>
                    @endif
                </article>

                {{-- Tagihan Utilitas --}}
                @if ($tenant && $utilityBills->isNotEmpty())
                <article class="tenant-card">
                    <div class="tenant-card-head">
                        <h2><span class="material-symbols-outlined">bolt</span> Tagihan Utilitas</h2>
                    </div>
                    <div class="tenant-payment-list">
                        @foreach ($utilityBills as $bill)
                            <article class="tenant-payment-item {{ $bill->status === 'paid' ? 'tenant-payment-history' : '' }}" id="utility-{{ $bill->id }}">
                                <div class="tenant-payment-item-header">
                                    <div>
                                        <h3 class="tenant-payment-item-title">{{ $utilityTypeLabels[$bill->type] ?? $bill->type }}</h3>
                                        <p class="tenant-payment-item-period">Periode: {{ $bill->period }}</p>
                                    </div>
                                    <div class="tenant-price-right">
                                        <p class="tenant-payment-item-price">{{ \App\Support\UiFormatter::currency($bill->amount) }}</p>
                                        @if ($bill->status !== 'paid')
                                            <p class="tenant-payment-item-due {{ $bill->due_date?->isPast() ? 'tenant-payment-item-due-error' : '' }}">
                                                <span class="material-symbols-outlined">calendar_today</span>
                                                Jatuh Tempo: {{ \App\Support\UiFormatter::date($bill->due_date) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="tenant-payment-item-body">
                                    <div class="tenant-payment-status-row">
                                        <div class="tenant-flex-center">
                                            <span class="tenant-payment-status-label">Status:</span>
                                            <span class="badge badge-{{ $bill->status }}">{{ $bill->status === 'paid' ? 'Lunas' : 'Belum Bayar' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </article>
                @endif
            </div>

        </section>
    @endif
@endsection
