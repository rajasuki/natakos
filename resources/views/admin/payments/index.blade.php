@extends('admin.layout')

@section('title', 'Pembayaran')
@section('eyebrow', 'Admin Pembayaran')
@section('page_title', 'Kelola pembayaran manual')
@section('page_description', 'Catat tagihan, atur tenggat bayar, dan verifikasi pembayaran manual penghuni NATAKOS dari dashboard admin.')

@section('page_actions')
    <a href="{{ route('admin.payments.create') }}" class="button button-primary">Tambah pembayaran</a>
@endsection

@section('content')
    @if ($payments->isEmpty())
        <section class="empty-state">
            <h2>Belum ada pembayaran</h2>
            <p>Tambahkan data tagihan pertama untuk mulai mencatat nominal, periode pembayaran, tenggat bayar, dan status verifikasi manual penghuni.</p>
            <a href="{{ route('admin.payments.create') }}" class="button button-primary">Tambah pembayaran sekarang</a>
        </section>
    @else
        <section class="card">
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
                                </td>
                                <td data-label="Warning tenggat">
                                    @if ($deadline)
                                        <div class="tag-list">
                                            <span class="badge badge-{{ str_replace('_', '-', $deadline['status']) }}">{{ $deadline['label'] }}</span>
                                        </div>
                                        <div class="muted" style="margin-top: 8px; line-height: 1.5;">{{ $deadline['message'] }}</div>
                                    @else
                                        <span class="muted">Belum ada data warning</span>
                                    @endif
                                </td>
                                <td data-label="Aksi">
                                    <div class="actions">
                                        <a href="{{ route('admin.payments.edit', $payment) }}" class="button button-secondary">Edit</a>

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
        </section>
    @endif
@endsection
