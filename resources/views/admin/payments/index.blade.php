@extends('admin.layout')

@section('title', 'Pembayaran')
@section('eyebrow', 'Admin Pembayaran')
@section('page_title', 'Kelola pembayaran manual')
@section('page_description', 'Pantau status pembayaran penghuni, verifikasi bukti transfer, dan catat pembayaran tunai secara manual di sini.')

@section('page_actions')
    <a href="{{ route('admin.payments.create') }}" class="button button-primary">
        <span class="material-symbols-outlined" style="font-size:16px;">add</span>
        Tambah pembayaran
    </a>
@endsection

@push('styles')
<style>
    .material-symbols-outlined.fill {
        font-variation-settings: 'FILL' 1;
    }

    .payment-stats {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (min-width: 640px) {
        .payment-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .payment-stats {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    .payment-stat-card {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 12px;
        padding: 16px;
        position: relative;
        overflow: hidden;
        transition: box-shadow .15s;
    }

    .payment-stat-card:hover {
        box-shadow: var(--ui-shadow);
    }

    .payment-stat-card.border-left-warning {
        border-left: 4px solid #d97706;
    }

    .payment-stat-card.border-left-accent {
        border-left: 4px solid var(--ui-accent);
    }

    .payment-stat-card.border-left-success {
        border-left: 4px solid #059669;
    }

    .payment-stat-card.border-left-error {
        border-left: 4px solid #dc2626;
    }

    .payment-stat-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .payment-stat-header .material-symbols-outlined {
        font-size: 20px;
    }

    .payment-stat-header h3 {
        margin: 0;
        font-size: 12px;
        font-weight: 600;
        color: var(--ui-body);
        letter-spacing: .02em;
    }

    .payment-stat-value {
        margin: 0;
        font-family: 'Sora', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: var(--ui-ink);
        letter-spacing: -0.5px;
        line-height: 1.1;
    }

    .payment-stat-icon-warning { color: #d97706; }
    .payment-stat-icon-accent { color: var(--ui-accent); }
    .payment-stat-icon-success { color: #059669; }
    .payment-stat-icon-error { color: #dc2626; }

    /* ── Main card ── */
    .payment-table-wrap {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 14px;
        overflow: hidden;
    }

    .payment-filter {
        padding: 16px 20px;
        border-bottom: 1px solid var(--ui-border);
        background: var(--gray-50);
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    @media (min-width: 768px) {
        .payment-filter {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .payment-filter-left {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .payment-filter-left {
            flex-direction: row;
            align-items: center;
            width: auto;
        }
    }

    .payment-filter-left .field {
        margin: 0;
    }

    .payment-search-wrap {
        position: relative;
        width: 100%;
    }

    @media (min-width: 640px) {
        .payment-search-wrap {
            width: 240px;
        }
    }

    .payment-search-wrap .material-symbols-outlined {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: var(--ui-body);
        pointer-events: none;
    }

    .payment-search-wrap input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1px solid var(--ui-border);
        border-radius: 8px;
        font-size: 13px;
        background: #fff;
        color: var(--ui-ink);
        outline: none;
    }

    .payment-search-wrap input:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 2px rgba(74,124,89,.15);
    }

    .payment-filter-select {
        padding: 8px 12px;
        border: 1px solid var(--ui-border);
        border-radius: 8px;
        font-size: 13px;
        background: #fff;
        color: var(--ui-ink);
        outline: none;
        min-width: 160px;
    }

    .payment-filter-select:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 2px rgba(74,124,89,.15);
    }

    .payment-filter-right {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    @media (min-width: 768px) {
        .payment-filter-right {
            width: auto;
        }
    }

    .payment-filter-right .button {
        flex: 1;
    }

    @media (min-width: 768px) {
        .payment-filter-right .button {
            flex: none;
        }
    }

    .btn-filter-apply {
        background: var(--ui-accent-soft);
        color: var(--ui-accent);
        border: 1px solid transparent;
        font-weight: 600;
    }

    .btn-filter-apply:hover {
        background: var(--ui-accent);
        color: #fff;
    }

    /* ── Table ── */
    .payment-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .payment-table thead {
        background: var(--gray-50);
    }

    .payment-table th {
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ui-body);
        text-align: left;
        border-bottom: 1px solid var(--ui-border);
    }

    .payment-table th:last-child {
        text-align: right;
    }

    .payment-table td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--ui-ink);
        border-bottom: 1px solid var(--ui-border);
        vertical-align: middle;
    }

    .payment-table tbody tr:last-child td {
        border-bottom: none;
    }

    .payment-table tbody tr {
        transition: background .1s;
    }

    .payment-table tbody tr:hover {
        background: var(--ui-canvas);
    }

    .payment-table-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--ui-ink);
        margin: 0 0 2px;
    }

    .payment-table-email {
        font-size: 12px;
        color: var(--ui-body);
    }

    .payment-table-room {
        font-size: 13px;
        font-weight: 500;
        color: var(--ui-ink);
    }

    .payment-table-amount {
        font-size: 14px;
        font-weight: 600;
        color: var(--ui-ink);
    }

    .payment-table-period {
        font-size: 13px;
        color: var(--ui-ink);
    }

    .payment-table-period-suffix {
        font-size: 12px;
        color: var(--ui-body);
    }

    .payment-table-date {
        font-size: 13px;
        color: var(--ui-ink);
    }

    .payment-table-date-paid {
        font-size: 12px;
        color: #059669;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .payment-table-date-paid .material-symbols-outlined {
        font-size: 14px;
    }

    .payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 600;
        line-height: 1.3;
        border: 1px solid transparent;
    }

    .payment-badge-unpaid {
        background: #fef3c7;
        color: #92400e;
        border-color: rgba(217,119,6,.18);
    }

    .payment-badge-pending-verification {
        background: var(--ui-accent-soft);
        color: var(--ui-accent);
        border-color: rgba(74,124,89,.18);
    }

    .payment-badge-paid {
        background: #d1fae5;
        color: #065f46;
        border-color: rgba(5,150,105,.18);
    }

    .payment-badge-rejected {
        background: #fee2e2;
        color: #991b1b;
        border-color: rgba(220,38,38,.18);
    }

    .payment-deadline-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.3;
    }

    .payment-deadline-badge-paid {
        background: var(--gray-100);
        color: var(--gray-600);
    }

    .payment-deadline-badge-safe {
        background: var(--gray-100);
        color: var(--gray-600);
    }

    .payment-deadline-badge-due-soon {
        background: #fef3c7;
        color: #92400e;
    }

    .payment-deadline-badge-due-today {
        background: #ffedd5;
        color: #9a3412;
    }

    .payment-deadline-badge-overdue {
        background: #dc2626;
        color: #fff;
        box-shadow: 0 1px 4px rgba(220,38,38,.25);
    }

    .payment-table-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
    }

    .payment-table-actions .group-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .payment-table-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: var(--ui-body);
        cursor: pointer;
        transition: background .15s, color .15s;
    }

    .payment-table-btn .material-symbols-outlined {
        font-size: 18px;
    }

    .payment-table-btn:hover {
        background: var(--ui-soft);
    }

    .payment-table-btn-view:hover {
        color: var(--ui-accent);
        background: var(--ui-accent-soft);
    }

    .payment-table-btn-edit:hover {
        color: var(--ui-accent);
        background: var(--ui-accent-soft);
    }

    .payment-table-btn-delete:hover {
        color: #dc2626;
        background: #fee2e2;
    }

    .payment-action-approve {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        background: #059669;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
    }

    .payment-action-approve:hover {
        background: #047857;
    }

    .payment-action-approve .material-symbols-outlined {
        font-size: 14px;
    }

    .payment-action-reject {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
    }

    .payment-action-reject:hover {
        background: #b91c1c;
    }

    .payment-action-reject .material-symbols-outlined {
        font-size: 14px;
    }

    .payment-action-wa {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        background: #d1fae5;
        color: #065f46;
        border: 1px solid rgba(5,150,105,.25);
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background .15s;
    }

    .payment-action-wa:hover {
        background: #a7f3d0;
    }

    .payment-action-wa .material-symbols-outlined {
        font-size: 14px;
    }

    .payment-rejection-note {
        font-size: 11px;
        color: #991b1b;
        margin-top: 2px;
        max-width: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .payment-table tr .always-visible-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── Pagination ── */
    .payment-pagination {
        padding: 14px 20px;
        border-top: 1px solid var(--ui-border);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 13px;
        color: var(--ui-body);
    }

    .payment-pagination-arrows {
        display: flex;
        gap: 4px;
    }

    .payment-pagination-arrows a,
    .payment-pagination-arrows span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--ui-border);
        color: var(--ui-body);
        transition: background .15s, color .15s;
    }

    .payment-pagination-arrows a:hover {
        background: var(--ui-soft);
        color: var(--ui-ink);
    }

    .payment-pagination-arrows span.disabled {
        opacity: .4;
        cursor: default;
    }

    @media (max-width: 767px) {
        .payment-table thead { display: none; }
        .payment-table,
        .payment-table tbody,
        .payment-table tr,
        .payment-table td { display: block; }
        .payment-table tr { padding: 12px 16px; border-bottom: 1px solid var(--ui-border); }
        .payment-table tr:last-child { border-bottom: none; }
        .payment-table td {
            padding: 4px 0;
            border: none;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .payment-table td::before {
            content: attr(data-label);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--ui-body);
            min-width: 90px;
            flex-shrink: 0;
        }
        .payment-table td:first-child { padding-top: 0; }
        .payment-table td:last-child { padding-bottom: 0; }
        .payment-table td[data-label="Aksi"] { justify-content: flex-start; flex-wrap: wrap; }
        .payment-table-actions { justify-content: flex-start; flex-wrap: wrap; }
        .payment-table-actions { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')
    @if ($payments->isEmpty())
        <section class="empty-state">
            <h2>{{ $hasActiveFilters ? 'Tidak ada pembayaran yang cocok' : 'Belum ada pembayaran' }}</h2>
            <p>{{ $hasActiveFilters ? 'Ubah atau reset filter untuk melihat tagihan lain yang tercatat.' : 'Tambahkan data tagihan pertama untuk mulai mencatat nominal, periode pembayaran, tenggat bayar, dan status verifikasi manual penghuni.' }}</p>
            <div class="empty-state-actions">
                @if ($hasActiveFilters)
                    <a href="{{ route('admin.payments.index') }}" class="button button-secondary">Reset filter</a>
                @else
                    <a href="{{ route('admin.payments.create') }}" class="button button-primary">Tambah pembayaran sekarang</a>
                @endif
            </div>
        </section>
    @else
        {{-- Stats Cards --}}
        <section class="payment-stats">
            @php
                $statCards = [
                    ['label' => 'Total', 'icon' => 'receipt_long', 'count' => $paymentCounts['Total'] ?? 0, 'color' => 'accent', 'border' => ''],
                    ['label' => 'Belum Bayar', 'icon' => 'pending_actions', 'count' => $paymentCounts['Belum Bayar'] ?? 0, 'color' => 'warning', 'border' => 'border-left-warning'],
                    ['label' => 'Menunggu Verifikasi', 'icon' => 'rule', 'count' => $paymentCounts['Menunggu Verifikasi'] ?? 0, 'color' => 'accent', 'border' => 'border-left-accent'],
                    ['label' => 'Lunas', 'icon' => 'check_circle', 'count' => $paymentCounts['Lunas'] ?? 0, 'color' => 'success', 'border' => 'border-left-success'],
                    ['label' => 'Ditolak', 'icon' => 'cancel', 'count' => $paymentCounts['Ditolak'] ?? 0, 'color' => 'error', 'border' => 'border-left-error'],
                ];
            @endphp
            @foreach ($statCards as $card)
                <div class="payment-stat-card {{ $card['border'] }}">
                    <div class="payment-stat-header">
                        <span class="material-symbols-outlined fill payment-stat-icon-{{ $card['color'] }}">{{ $card['icon'] }}</span>
                        <h3>{{ $card['label'] }}</h3>
                    </div>
                    <p class="payment-stat-value">{{ $card['count'] }}</p>
                </div>
            @endforeach
        </section>

        {{-- Main Table Card --}}
        <div class="payment-table-wrap">
            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.payments.index') }}" class="payment-filter">
                <div class="payment-filter-left">
                    <div class="payment-search-wrap">
                        <span class="material-symbols-outlined">search</span>
                        <input name="q" type="text" value="{{ $filters['q'] }}" placeholder="Cari pembayaran...">
                    </div>
                    <select name="status" class="payment-filter-select">
                        <option value="">Status pembayaran</option>
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <select name="deadline_status" class="payment-filter-select">
                        <option value="">Warning tenggat</option>
                        @foreach ($deadlineStatusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['deadline_status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="payment-filter-right">
                    <a href="{{ route('admin.payments.index') }}" class="button button-secondary">Reset</a>
                    <button type="submit" class="button btn-filter-apply">Terapkan filter</button>
                    <a href="{{ route('admin.payments.export', request()->query()) }}" class="button button-subtle" title="Export PDF">
                        <span class="material-symbols-outlined" style="font-size:16px;">download</span>
                    </a>
                </div>
            </form>

            {{-- Table --}}
            <div style="overflow-x:auto;">
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>Penghuni</th>
                            <th>Kamar</th>
                            <th>Nominal</th>
                            <th>Periode</th>
                            <th>Tenggat bayar</th>
                            <th>Status pembayaran</th>
                            <th>Warning tenggat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            @php
                                $deadline = $deadlineData[$payment->id] ?? null;
                                $reminderUrl = \App\Support\PaymentReminder::link(
                                    $payment->tenant?->user?->phone,
                                    $payment->tenant?->user?->name ?: 'Penghuni',
                                    $payment->tenant?->room?->name ?: 'Kamar',
                                    $payment->amount,
                                    $payment->period_start,
                                    $payment->period_end,
                                    $payment->due_date,
                                );
                            @endphp
                            <tr>
                                <td data-label="Penghuni">
                                    <div class="payment-table-name">{{ $payment->tenant?->user?->name ?: 'Penghuni tidak tersedia' }}</div>
                                    <div class="payment-table-email">{{ $payment->tenant?->user?->email ?: '-' }}</div>
                                </td>
                                <td data-label="Kamar">
                                    <span class="payment-table-room">{{ $payment->tenant?->room?->name ?: '-' }}</span>
                                </td>
                                <td data-label="Nominal">
                                    <span class="payment-table-amount">{{ \App\Support\UiFormatter::currency($payment->amount) }}</span>
                                </td>
                                <td data-label="Periode">
                                    <div class="payment-table-period">{{ \App\Support\UiFormatter::date($payment->period_start) }}</div>
                                    <div class="payment-table-period-suffix">s/d {{ \App\Support\UiFormatter::date($payment->period_end) }}</div>
                                </td>
                                <td data-label="Tenggat bayar">
                                    <div class="payment-table-date">{{ \App\Support\UiFormatter::date($payment->due_date) }}</div>
                                    @if ($payment->paid_at)
                                        <div class="payment-table-date-paid">
                                            <span class="material-symbols-outlined">check</span>
                                            Dibayar {{ \App\Support\UiFormatter::date($payment->paid_at, 'd M Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td data-label="Status pembayaran">
                                    <div>
                                        <span class="payment-badge payment-badge-{{ $payment->status }}">{{ $statusLabels[$payment->status] ?? $payment->status }}</span>
                                    </div>
                                    @if ($payment->status === 'rejected' && $payment->rejection_reason)
                                        <div class="payment-rejection-note" title="{{ $payment->rejection_reason }}">{{ $payment->rejection_reason }}</div>
                                    @endif
                                </td>
                                <td data-label="Warning tenggat">
                                    @if ($deadline)
                                        <span class="payment-deadline-badge payment-deadline-badge-{{ $deadline['status'] }}">{{ $deadline['label'] }}</span>
                                        <div style="font-size:11px;color:var(--ui-body);margin-top:2px;">{{ $deadline['message'] }}</div>
                                    @else
                                        <span style="font-size:12px;color:var(--ui-body);">-</span>
                                    @endif
                                </td>
                                <td data-label="Aksi">
                                    <div class="payment-table-actions">
                                        <div class="group-actions">
                                            @if ($payment->status === 'pending_verification')
                                                <form method="POST" action="{{ route('admin.payments.review.update', $payment) }}" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="review_action" value="approve">
                                                    <button type="submit" class="payment-action-approve" title="Setujui">
                                                        <span class="material-symbols-outlined">check</span>
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.payments.review.update', $payment) }}" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="review_action" value="reject">
                                                    <button type="submit" class="payment-action-reject" title="Tolak" onclick="return confirm('Tolak pembayaran ini?');">
                                                        <span class="material-symbols-outlined">close</span>
                                                        Tolak
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($reminderUrl && in_array($payment->status, ['unpaid', 'rejected'], true))
                                                <a href="{{ $reminderUrl }}" target="_blank" rel="noopener noreferrer" class="payment-action-wa">
                                                    <span class="material-symbols-outlined">chat</span>
                                                    Follow up WA
                                                </a>
                                            @endif

                                            <a href="{{ route('admin.payments.review', $payment) }}" class="payment-table-btn payment-table-btn-view" title="Review">
                                                <span class="material-symbols-outlined">visibility</span>
                                            </a>
                                            <a href="{{ route('admin.payments.edit', $payment) }}" class="payment-table-btn payment-table-btn-edit" title="Edit">
                                                <span class="material-symbols-outlined">edit</span>
                                            </a>
                                            <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" onsubmit="return confirm('Hapus pembayaran ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="payment-table-btn payment-table-btn-delete" title="Hapus">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="payment-pagination">
                <span>
                    Menampilkan {{ $payments->firstItem() }}-{{ $payments->lastItem() }} dari {{ $payments->total() }} pembayaran
                </span>
                <div class="payment-pagination-arrows">
                    @if ($payments->onFirstPage())
                        <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></span>
                    @else
                        <a href="{{ $payments->previousPageUrl() }}"><span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></a>
                    @endif
                    @if ($payments->hasMorePages())
                        <a href="{{ $payments->nextPageUrl() }}"><span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></a>
                    @else
                        <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></span>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
