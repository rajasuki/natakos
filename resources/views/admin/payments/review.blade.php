@extends('admin.layout')

@section('title', 'Review Pembayaran')
@section('eyebrow', 'Admin Pembayaran')
@section('page_title', 'Review pembayaran')
@section('page_description', 'Tinjau bukti pembayaran, status tagihan, dan putuskan apakah pembayaran disetujui atau ditolak.')

@section('page_actions')
    <a href="{{ route('admin.payments.index') }}" class="button button-secondary">Kembali ke daftar pembayaran</a>
    <a href="{{ route('admin.payments.edit', $payment) }}" class="button button-subtle">Edit lengkap</a>
@endsection

@section('content')
    @php
        $errorBag = isset($errors) ? $errors : null;
    @endphp

    <div class="content-stack">
        <section class="card form-card">
            <div class="form-layout">
                <section class="form-section">
                    <div>
                        <h2 class="form-section-title">Ringkasan tagihan</h2>
                        <p class="form-section-copy">Pastikan penghuni, nominal, periode, dan warning tenggat sesuai sebelum mengambil keputusan review.</p>
                    </div>

                    <div class="grid grid-two">
                        <div class="field">
                            <label>Penghuni</label>
                            <div class="input">{{ $payment->tenant?->user?->name ?: 'Penghuni tidak tersedia' }}</div>
                        </div>

                        <div class="field">
                            <label>Kamar</label>
                            <div class="input">{{ $payment->tenant?->room?->name ?: 'Kamar tidak tersedia' }}</div>
                        </div>

                        <div class="field">
                            <label>Nominal</label>
                            <div class="input">{{ \App\Support\UiFormatter::currency($payment->amount) }}</div>
                        </div>

                        <div class="field">
                            <label>Status saat ini</label>
                            <div class="input">{{ $statusLabels[$payment->status] ?? $payment->status }}</div>
                        </div>

                        <div class="field">
                            <label>Periode</label>
                            <div class="input">{{ \App\Support\UiFormatter::date($payment->period_start) }} s/d {{ \App\Support\UiFormatter::date($payment->period_end) }}</div>
                        </div>

                        <div class="field">
                            <label>Warning tenggat</label>
                            <div class="input">{{ $deadline['label'] ?? 'Belum ada data warning' }}</div>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div>
                        <h2 class="form-section-title">Bukti pembayaran</h2>
                        <p class="form-section-copy">Admin bisa meninjau bukti yang diunggah penghuni sebelum menyetujui atau menolak pembayaran.</p>
                    </div>

                    @if ($payment->proof_image)
                        <div class="preview-frame">
                            <div class="preview">
                                <img src="{{ route('admin.payments.proof', $payment) }}" alt="Bukti pembayaran {{ $payment->tenant?->user?->name ?: 'penghuni' }}">
                                <div class="preview-meta">Path saat ini: <strong>{{ $payment->proof_image }}</strong></div>
                            </div>
                        </div>
                    @else
                        <section class="empty-state">
                            <h2>Belum ada bukti pembayaran</h2>
                            <p>Penghuni belum mengunggah bukti bayar untuk tagihan ini. Anda tetap bisa meninjau catatan dan status saat ini.</p>
                        </section>
                    @endif
                </section>

                <section class="form-section">
                    <form method="POST" action="{{ route('admin.payments.review.update', $payment) }}" class="form-layout">
                        @csrf
                        @method('PUT')

                        <div>
                            <h2 class="form-section-title">Keputusan review</h2>
                            <p class="form-section-copy">Gunakan form ini untuk menyimpan catatan admin, mengembalikan status ke pending, menyetujui, atau menolak pembayaran.</p>
                        </div>

                        <div class="grid">
                            <div class="field field-full">
                                <label for="notes">Catatan admin</label>
                                <textarea id="notes" name="notes" class="textarea" placeholder="Tulis catatan review admin jika diperlukan...">{{ old('notes', $payment->notes) }}</textarea>
                                @if ($errorBag?->has('notes'))
                                    <div class="field-error">{{ $errorBag->first('notes') }}</div>
                                @endif
                            </div>

                            <div class="field field-full">
                                <label for="rejection_reason">Alasan penolakan</label>
                                <textarea id="rejection_reason" name="rejection_reason" class="textarea" placeholder="Wajib diisi jika pembayaran ditolak...">{{ old('rejection_reason', $payment->rejection_reason) }}</textarea>
                                @if ($errorBag?->has('rejection_reason'))
                                    <div class="field-error">{{ $errorBag->first('rejection_reason') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" name="review_action" value="approve" class="button button-primary">Setujui dan tandai lunas</button>
                            <button type="submit" name="review_action" value="reject" class="button button-danger">Tolak pembayaran</button>
                            <button type="submit" name="review_action" value="pending" class="button button-secondary">Kembalikan ke pending</button>
                        </div>
                    </form>
                </section>
            </div>
        </section>
    </div>
@endsection
