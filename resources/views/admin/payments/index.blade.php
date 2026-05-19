@extends('admin.layout')

@section('title', 'Pembayaran')
@section('eyebrow', 'Admin Pembayaran')
@section('page_title', 'Kelola pembayaran manual')
@section('page_description', 'Catat tagihan, atur tenggat bayar, dan verifikasi pembayaran manual penghuni ' . $kosName . ' dari dashboard admin.')

@section('page_actions')
    <a href="{{ route('admin.payments.create') }}" class="button button-primary">Tambah pembayaran</a>
@endsection

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
        <section class="card">
            <div class="card-head has-divider">
                <div class="split-actions">
                    <div>
                        <h2 class="card-title">Daftar pembayaran</h2>
                        <p class="card-copy">Tinjau tagihan, status verifikasi, dan warning tenggat agar tindak lanjut pembayaran lebih cepat.</p>
                    </div>

                    <div class="tag-list">
                        @foreach ($paymentCounts as $label => $total)
                            <span class="tag">{{ $label }}: {{ number_format($total, 0, ',', '.') }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.payments.index') }}" class="toolbar-form">
                <div class="toolbar-grid">
                    <div class="field">
                        <label for="payment_q">Cari pembayaran</label>
                        <input id="payment_q" name="q" type="text" value="{{ $filters['q'] }}" class="input" placeholder="Penghuni, email, kamar, catatan...">
                    </div>

                    <div class="field">
                        <label for="payment_status">Status pembayaran</label>
                        <select id="payment_status" name="status" class="select">
                            <option value="">Semua status</option>
                            @foreach ($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="payment_deadline_status">Warning tenggat</label>
                        <select id="payment_deadline_status" name="deadline_status" class="select">
                            <option value="">Semua warning</option>
                            @foreach ($deadlineStatusLabels as $value => $label)
                                <option value="{{ $value }}" @selected($filters['deadline_status'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="toolbar-actions">
                        <button type="submit" class="button button-primary">Terapkan filter</button>
                        <a href="{{ route('admin.payments.index') }}" class="button button-secondary">Reset</a>
                        <a href="{{ route('admin.payments.export', request()->query()) }}" class="button button-subtle">Export CSV</a>
                    </div>
                </div>
            </form>

            <div class="table-wrap">
                <table class="responsive-table">
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
                            @endphp
                            <tr>
                                <td data-label="Penghuni">
                                    <p class="room-name">{{ $payment->tenant?->user?->name ?: 'Penghuni tidak tersedia' }}</p>
                                    <div class="muted">{{ $payment->tenant?->user?->email ?: '-' }}</div>
                                </td>
                                <td data-label="Kamar">{{ $payment->tenant?->room?->name ?: 'Kamar tidak tersedia' }}</td>
                                <td data-label="Nominal">{{ \App\Support\UiFormatter::currency($payment->amount) }}</td>
                                <td data-label="Periode">
                                    <div>{{ \App\Support\UiFormatter::date($payment->period_start) }}</div>
                                    <div class="muted">s/d {{ \App\Support\UiFormatter::date($payment->period_end) }}</div>
                                </td>
                                <td data-label="Tenggat bayar">
                                    <div>{{ \App\Support\UiFormatter::date($payment->due_date) }}</div>
                                    @if ($payment->paid_at)
                                        <div class="muted">Dibayar {{ \App\Support\UiFormatter::date($payment->paid_at, 'd M Y H:i') }}</div>
                                    @endif
                                </td>
                                 <td data-label="Status pembayaran">
                                     <span class="badge badge-{{ str_replace('_', '-', $payment->status) }}">{{ $statusLabels[$payment->status] ?? $payment->status }}</span>
                                    @if ($payment->status === 'rejected' && $payment->rejection_reason)
                                        <div class="muted muted-note">Alasan: {{ $payment->rejection_reason }}</div>
                                    @endif
                                 </td>
                                <td data-label="Warning tenggat">
                                    @if ($deadline)
                                        <div class="tag-list">
                                            <span class="badge badge-{{ str_replace('_', '-', $deadline['status']) }}">{{ $deadline['label'] }}</span>
                                        </div>
                                        <div class="muted muted-note">{{ $deadline['message'] }}</div>
                                    @else
                                        <span class="muted">Belum ada data warning</span>
                                    @endif
                                </td>
                                 <td data-label="Aksi">
                                      <div class="actions">
                                        @php
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

                                        <a href="{{ route('admin.payments.review', $payment) }}" class="button button-subtle">Review</a>
                                        <a href="{{ route('admin.payments.edit', $payment) }}" class="button button-secondary">Edit</a>

                                        @if ($payment->status === 'pending_verification')
                                            <form method="POST" action="{{ route('admin.payments.review.update', $payment) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="review_action" value="approve">
                                                <button type="submit" class="button button-primary">Setujui</button>
                                            </form>
                                        @endif

                                        @if ($reminderUrl && in_array($payment->status, ['unpaid', 'rejected'], true))
                                            <a href="{{ $reminderUrl }}" target="_blank" rel="noopener noreferrer" class="button button-subtle">Follow up WA</a>
                                        @endif

                                        <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" onsubmit="return confirm('Hapus pembayaran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button button-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-shell">
                {{ $payments->links() }}
            </div>
        </section>
    @endif
@endsection
