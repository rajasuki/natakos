@extends('admin.layout')

@section('title', 'Pengajuan Sewa')
@section('eyebrow', 'Booking')
@section('page_title', 'Pengajuan sewa kamar')
@section('page_description', 'Kelola pengajuan sewa dari calon penghuni. Setujui untuk langsung membuat data penghuni dan tagihan pertama.')

@push('styles')
<style>
    .booking-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .booking-stat {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
    }

    .booking-stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--ui-ink);
        line-height: 1.1;
    }

    .booking-stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--ui-body);
        margin-top: 4px;
    }

    .booking-stat-pending .booking-stat-value { color: #d97706; }
    .booking-stat-approved .booking-stat-value { color: var(--ui-accent); }
    .booking-stat-rejected .booking-stat-value { color: #dc2626; }

    .booking-card {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 14px;
        overflow: hidden;
    }

    .booking-item {
        padding: 18px 20px;
        border-bottom: 1px solid var(--ui-border);
        display: grid;
        gap: 12px;
    }

    .booking-item:last-child { border-bottom: none; }

    .booking-item-top {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
    }

    .booking-user h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: var(--ui-ink);
    }

    .booking-user .email {
        font-size: 12px;
        color: var(--ui-body);
    }

    .booking-room {
        font-size: 13px;
        color: var(--ui-body);
    }

    .booking-room strong { color: var(--ui-ink); }

    .booking-dates {
        display: flex;
        gap: 16px;
        font-size: 13px;
        color: var(--ui-body);
    }

    .booking-dates span strong { color: var(--ui-ink); }

    .booking-notes {
        font-size: 13px;
        color: var(--ui-body);
        background: var(--ui-canvas);
        padding: 10px 14px;
        border-radius: 8px;
        line-height: 1.6;
    }

    .booking-notes:empty { display: none; }

    .booking-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .booking-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .booking-badge-pending { background: #fef3c7; color: #92400e; }
    .booking-badge-approved { background: #d1fae5; color: #065f46; }
    .booking-badge-rejected { background: #fee2e2; color: #991b1b; }

    .booking-empty {
        padding: 48px;
        text-align: center;
        color: var(--ui-body);
        font-size: 14px;
    }

    .reject-form {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .reject-form input {
        flex: 1;
        min-width: 180px;
        padding: 8px 12px;
        border: 1px solid var(--ui-border);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        color: var(--ui-ink);
        background: #fff;
        outline: none;
    }

    .reject-form input:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 2px rgba(74,124,89,.12);
    }

    .btn-approve, .btn-reject, .btn-subtle {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 7px 14px;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
        font-family: inherit;
        white-space: nowrap;
    }

    .btn-approve { background: #059669; color: #fff; }
    .btn-approve:hover { background: #047857; }

    .btn-reject { background: #dc2626; color: #fff; }
    .btn-reject:hover { background: #b91c1c; }

    .btn-subtle { background: var(--ui-soft); color: var(--ui-body); }
    .btn-subtle:hover { background: var(--ui-border); }

    .booking-rejection {
        font-size: 12px;
        color: #991b1b;
        background: #fee2e2;
        padding: 6px 10px;
        border-radius: 6px;
        display: inline-block;
    }

    .booking-approved-by {
        font-size: 12px;
        color: var(--ui-body);
    }

    @media (max-width: 767px) {
        .booking-stats { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    <section class="booking-stats">
        <div class="booking-stat booking-stat-pending">
            <div class="booking-stat-value">{{ $counts['pending'] }}</div>
            <div class="booking-stat-label">Menunggu</div>
        </div>
        <div class="booking-stat booking-stat-approved">
            <div class="booking-stat-value">{{ $counts['approved'] }}</div>
            <div class="booking-stat-label">Disetujui</div>
        </div>
        <div class="booking-stat booking-stat-rejected">
            <div class="booking-stat-value">{{ $counts['rejected'] }}</div>
            <div class="booking-stat-label">Ditolak</div>
        </div>
    </section>

    <div class="booking-card">
        @forelse ($bookingRequests as $booking)
            <div class="booking-item">
                <div class="booking-item-top">
                    <div class="booking-user">
                        <h4>{{ $booking->user?->name ?? 'User dihapus' }}</h4>
                        <div class="email">{{ $booking->user?->email ?? '-' }}</div>
                    </div>
                    <span class="booking-badge booking-badge-{{ $booking->status }}">
                        {{ $statusLabels[$booking->status] ?? $booking->status }}
                    </span>
                </div>

                <div class="booking-room">
                    Kamar: <strong>{{ $booking->room?->name ?? 'Kamar dihapus' }}</strong>
                    &middot; {{ \App\Support\UiFormatter::currency($booking->room?->price ?? 0) }}/bln
                </div>

                <div class="booking-dates">
                    <span>Masuk: <strong>{{ \App\Support\UiFormatter::date($booking->start_date) }}</strong></span>
                    <span>Sampai: <strong>{{ \App\Support\UiFormatter::date($booking->end_date) }}</strong></span>
                    <span>({{ $booking->start_date->diffInDays($booking->end_date) + 1 }} hari)</span>
                </div>

                @if ($booking->notes)
                    <div class="booking-notes">{{ $booking->notes }}</div>
                @endif

                @if ($booking->status === 'pending')
                    <div class="booking-actions">
                        <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-approve" onclick="return confirm('Setujui pengajuan ini? Penghuni dan tagihan pertama akan dibuat otomatis.');">
                                <span class="material-symbols-outlined" style="font-size:14px;">check</span>
                                Setujui
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.bookings.reject', $booking) }}" class="reject-form" onsubmit="return this.querySelector('input').value.trim() !== '' || (alert('Alasan penolakan wajib diisi.'), false);">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="rejection_reason" placeholder="Alasan tolak..." required>
                            <button type="submit" class="btn-reject">
                                <span class="material-symbols-outlined" style="font-size:14px;">close</span>
                                Tolak
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Hapus pengajuan ini?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-subtle" title="Hapus">
                                <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
                            </button>
                        </form>
                    </div>
                @elseif ($booking->status === 'rejected')
                    <div class="booking-rejection">
                        Alasan: {{ $booking->rejection_reason }}
                    </div>
                    @if ($booking->processedBy)
                        <div class="booking-approved-by">Diproses oleh {{ $booking->processedBy->name }}</div>
                    @endif
                    <div class="booking-actions">
                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Hapus pengajuan ini?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-subtle" style="color:#991b1b;">
                                <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
                                Hapus
                            </button>
                        </form>
                    </div>
                @elseif ($booking->status === 'approved')
                    @if ($booking->processedBy)
                        <div class="booking-approved-by" style="color:var(--ui-accent);">
                            Disetujui oleh {{ $booking->processedBy->name }}
                            @if ($booking->approved_at)
                                &middot; {{ \App\Support\UiFormatter::date($booking->approved_at, 'd M Y H:i') }}
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        @empty
            <div class="booking-empty">Belum ada pengajuan sewa masuk.</div>
        @endforelse
    </div>

    <div class="payment-pagination">
        {{ $bookingRequests->links() }}
    </div>
@endsection
