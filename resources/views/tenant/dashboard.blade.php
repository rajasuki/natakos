@extends('tenant.layout')

@section('title', 'Dashboard Penghuni')

@push('styles')
    <style>
        /* ── HERO GRID ── */
        .tenant-hero-grid {
            display: grid;
            gap: 20px;
        }

        .tenant-hero-side {
            display: grid;
            gap: 16px;
        }

        /* ── DARK PANEL ── */
        .tenant-hero-panel {
            background: var(--ui-ink);
            color: #ffffff;
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            box-shadow: 0 4px 20px rgba(15,31,20,0.18);
            position: relative;
            overflow: hidden;
        }

        .tenant-hero-panel::after {
            content: '';
            position: absolute;
            bottom: -20px; right: -20px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(34,197,94,0.12);
            pointer-events: none;
        }

        .tenant-hero-panel .eyebrow {
            color: var(--green-400);
        }

        .tenant-hero-panel .tenant-stat-value {
            color: #ffffff;
        }

        .tenant-hero-panel .hero-copy {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            margin-top: 6px;
        }

        .tenant-hero-panel .hero-meta-pill {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.9);
        }

        /* ── STAT GRID ── */
        .tenant-stat-grid,
        .tenant-dashboard-grid {
            display: grid;
            gap: 16px;
        }

        .tenant-stat-card {
            padding: 18px 20px;
            background: var(--ui-canvas);
            border: 1px solid var(--ui-border);
            border-radius: var(--radius-md);
            box-shadow: var(--ui-shadow-soft);
            transition: box-shadow 0.18s ease, transform 0.18s ease;
        }

        .tenant-stat-card:hover {
            box-shadow: 0 4px 16px rgba(22,163,74,0.12);
            transform: translateY(-1px);
        }

        .tenant-stat-value {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.3px;
            color: var(--ui-ink);
        }

        .tenant-stat-label {
            margin: 0 0 8px;
            color: var(--ui-body);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .tenant-stat-accent {
            display: inline-block;
            width: 20px;
            height: 3px;
            background: var(--ui-accent-mid);
            border-radius: 2px;
            margin-top: 10px;
        }

        /* ── NOTE ── */
        .tenant-note {
            margin-top: 18px;
            padding: 14px 16px;
            border-radius: var(--radius-md);
            background: var(--ui-soft);
            border: 1px solid var(--ui-border);
            font-size: 13px;
            color: var(--ui-body);
            line-height: 1.65;
        }

        /* ── PROOF SECTION ── */
        .tenant-proof-section {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--ui-border);
        }

        .tenant-proof-section-title {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.3;
            letter-spacing: -0.2px;
        }

        .tenant-proof-section-copy {
            margin: 6px 0 0;
            color: var(--ui-body);
            font-size: 13px;
            line-height: 1.65;
        }

        /* ── PAYMENT STACK ── */
        .tenant-payment-stack {
            display: grid;
            gap: 16px;
            margin-top: 20px;
        }

        .tenant-payment-entry {
            display: grid;
            gap: 16px;
            padding: 20px;
            border-radius: var(--radius-lg);
            background: var(--ui-softer);
            border: 1px solid var(--ui-border);
            scroll-margin-top: 108px;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .tenant-payment-entry:hover {
            border-color: var(--ui-border-mid);
            box-shadow: var(--ui-shadow-soft);
        }

        .tenant-payment-entry:target {
            border-color: var(--ui-accent);
            box-shadow: 0 0 0 3px rgba(22,163,74,0.12);
        }

        .tenant-payment-head {
            display: grid;
            gap: 12px;
        }

        .tenant-payment-title {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.4;
            color: var(--ui-ink);
        }

        .tenant-payment-meta-grid {
            display: grid;
            gap: 10px;
        }

        .tenant-payment-meta-item {
            padding: 10px 12px;
            background: var(--ui-canvas);
            border: 1px solid var(--ui-border);
            border-radius: var(--radius-sm);
        }

        /* ── PROOF STATE ── */
        .tenant-proof-state,
        .tenant-payment-inline-flash {
            padding: 14px 16px;
            border-radius: var(--radius-md);
            border: 1px solid var(--ui-border);
            background: var(--ui-canvas);
            font-size: 13px;
            line-height: 1.65;
        }

        .tenant-payment-inline-flash {
            box-shadow: var(--ui-shadow-soft);
        }

        .tenant-payment-inline-flash-success {
            background: var(--ui-success);
            color: var(--green-800);
            border-color: var(--ui-success-border);
        }

        .tenant-payment-inline-flash-error {
            background: var(--ui-danger);
            color: #991b1b;
            border-color: var(--ui-danger-border);
        }

        /* ── UPLOAD FORM ── */
        .tenant-upload-form {
            display: grid;
            gap: 14px;
            padding: 18px;
            background: var(--ui-canvas);
            border: 1.5px dashed var(--ui-border-mid);
            border-radius: var(--radius-md);
        }

        .tenant-upload-field {
            display: grid;
            gap: 8px;
        }

        .tenant-upload-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--ui-ink);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .tenant-upload-input {
            width: 100%;
            border: 1.5px solid var(--ui-border-mid);
            background: var(--ui-softer);
            color: var(--ui-ink);
            padding: 12px 14px;
            border-radius: var(--radius-md);
            font: inherit;
            font-size: 14px;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .tenant-upload-input:focus,
        .tenant-upload-input:focus-visible {
            outline: none;
            border-color: var(--ui-accent);
            box-shadow: 0 0 0 3px rgba(22,163,74,0.1);
            background: var(--ui-canvas);
        }

        .tenant-upload-helper,
        .tenant-upload-error {
            font-size: 12px;
            line-height: 1.65;
        }

        .tenant-upload-helper {
            color: var(--ui-body);
        }

        .tenant-upload-error {
            color: #991b1b;
            font-weight: 600;
        }

        /* ── DASHBOARD CARD HOVER ── */
        .card {
            transition: box-shadow 0.18s ease;
        }

        /* ── RESPONSIVE ── */
        @media (min-width: 900px) {
            .tenant-hero-grid {
                grid-template-columns: 1.2fr 0.8fr;
                align-items: stretch;
            }

            .tenant-stat-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .tenant-dashboard-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .tenant-span-2 {
                grid-column: span 2;
            }

            .tenant-payment-head {
                grid-template-columns: minmax(0, 1fr) auto;
                align-items: flex-start;
            }

            .tenant-payment-meta-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
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
    @endphp

    <div class="content-stack">

        {{-- ── HERO ── --}}
        <section class="tenant-hero-grid">
            <article class="hero-card">
                <div>
                    <p class="eyebrow">Dashboard penghuni</p>
                    <h1 class="page-title">Halo, {{ $user->name }} 👋</h1>
                    <p class="hero-copy">
                        Semua informasi kamar, masa tinggal, dan pembayaran Anda ditampilkan di satu tempat agar lebih mudah dipantau tanpa perlu bertanya berulang ke pengelola.
                    </p>
                </div>

                <div class="hero-meta">
                    @if ($tenant)
                        <span class="hero-meta-pill">{{ $tenant->room?->name ?: 'Kamar belum terhubung' }}</span>
                        <span class="hero-meta-pill">{{ $roomStatusLabels[$roomStatus] ?? 'Status kamar belum ada' }}</span>
                        <span class="hero-meta-pill">{{ $rentStatusLabels[$rentStatus] ?? 'Aman' }}</span>
                        @if ($paymentStatus)
                            <span class="hero-meta-pill">{{ $paymentStatusLabels[$paymentStatus] ?? $paymentStatus }}</span>
                        @endif
                    @else
                        <span class="hero-meta-pill">Akun belum terhubung ke tenant aktif</span>
                    @endif
                </div>

                <div class="card-actions">
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Hubungi Pemilik via WhatsApp</a>
                </div>
            </article>

            <aside class="tenant-hero-side">
                <article class="tenant-hero-panel">
                    <p class="eyebrow">Ringkasan cepat</p>
                    <p class="tenant-stat-value">{{ $tenant ? ($tenant->room?->name ?: '-') : '-' }}</p>
                    <p class="hero-copy">{{ $tenant ? 'Kamar utama yang tercatat untuk akun Anda.' : 'Data tenant aktif belum tersedia.' }}</p>
                </article>

                <div class="tenant-stat-grid">
                    <article class="tenant-stat-card">
                        <p class="tenant-stat-label">Status kamar</p>
                        <p class="tenant-stat-value">{{ $tenant ? ($roomStatusLabels[$roomStatus] ?? '-') : '-' }}</p>
                        <span class="tenant-stat-accent"></span>
                    </article>
                    <article class="tenant-stat-card">
                        <p class="tenant-stat-label">Masa tinggal</p>
                        <p class="tenant-stat-value">{{ $tenant ? ($rentStatusLabels[$rentStatus] ?? '-') : '-' }}</p>
                        <span class="tenant-stat-accent"></span>
                    </article>
                    <article class="tenant-stat-card">
                        <p class="tenant-stat-label">Pembayaran</p>
                        <p class="tenant-stat-value">{{ $featuredPayment ? ($paymentStatusLabels[$paymentStatus] ?? '-') : '-' }}</p>
                        <span class="tenant-stat-accent"></span>
                    </article>
                </div>
            </aside>
        </section>

        {{-- ── EMPTY STATE ── --}}
        @if ($tenant === null)
            <section class="empty-state">
                <h2>Data penghuni belum tersedia</h2>
                <p>
                    Akun Anda belum terhubung ke data tenant aktif. Silakan hubungi pengelola {{ $kosName }} agar data kamar dan masa tinggal Anda dapat ditampilkan di dashboard ini.
                </p>
                <div class="empty-state-actions">
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Hubungi Pemilik via WhatsApp</a>
                </div>
            </section>

        @else

            {{-- ── ALERTS ── --}}
            @if ($paymentWarning || $rentWarning)
                <section class="alert-stack">
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
                </section>
            @endif

            {{-- ── MAIN GRID ── --}}
            <section class="tenant-dashboard-grid">

                {{-- Informasi Kamar --}}
                <article class="card">
                    <div class="card-head has-divider">
                        <h2 class="card-title">Informasi kamar</h2>
                        <p class="card-copy">Detail utama kamar yang sedang Anda tempati saat ini.</p>
                    </div>

                    <div class="card-body">
                        <div class="detail-list">
                            <div class="detail-item">
                                <div class="detail-label">Nama penghuni</div>
                                <div class="detail-value">{{ $user->name }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Kamar yang ditempati</div>
                                <div class="detail-value">{{ $tenant->room?->name ?: 'Kamar tidak tersedia' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Harga kamar</div>
                                <div class="detail-value">{{ $tenant->room ? \App\Support\UiFormatter::currency($tenant->room->price) : '-' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Status kamar</div>
                                <div class="detail-value">
                                    <span class="badge badge-{{ $tenant->room?->status ?? 'maintenance' }}">{{ $roomStatusLabels[$tenant->room?->status] ?? 'Tidak tersedia' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="tenant-note">
                            Jika ada perubahan kamar atau harga yang belum sesuai, segera hubungi pengelola agar data Anda diperbarui.
                        </div>
                    </div>
                </article>

                {{-- Masa Tinggal --}}
                <article class="card">
                    <div class="card-head has-divider">
                        <h2 class="card-title">Masa tinggal</h2>
                        <p class="card-copy">Pantau periode tinggal dan status masa tinggal Anda.</p>
                    </div>

                    <div class="card-body">
                        <div class="detail-list">
                            <div class="detail-item">
                                <div class="detail-label">Tanggal masuk</div>
                                <div class="detail-value">{{ \App\Support\UiFormatter::date($tenant->start_date) }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Tanggal keluar</div>
                                <div class="detail-value">{{ \App\Support\UiFormatter::date($tenant->end_date) }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Status masa tinggal</div>
                                <div class="detail-value">
                                    <span class="badge badge-{{ str_replace('_', '-', $rentSummary?->rent_period_status ?? 'safe') }}">{{ $rentStatusLabels[$rentSummary?->rent_period_status ?? 'safe'] ?? 'Aman' }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Catatan status</div>
                                <div class="detail-value muted">
                                    @if (($rentSummary?->rent_period_status ?? 'safe') === 'ending_soon')
                                        Berakhir dalam {{ $rentSummary?->days_until_end }} hari.
                                    @elseif (($rentSummary?->rent_period_status ?? 'safe') === 'ends_today')
                                        Masa tinggal berakhir hari ini.
                                    @elseif (($rentSummary?->rent_period_status ?? 'safe') === 'ended')
                                        Sudah berakhir {{ abs((int) ($rentSummary?->days_until_end ?? 0)) }} hari yang lalu.
                                    @elseif (($rentSummary?->rent_period_status ?? 'safe') === 'no_end_date')
                                        Tanggal keluar belum ditentukan.
                                    @else
                                        Masa tinggal masih dalam kondisi aman.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="tenant-note">
                            Pastikan tanggal keluar selalu sesuai rencana tinggal Anda agar peringatan masa tinggal lebih akurat.
                        </div>
                    </div>
                </article>

                {{-- Pembayaran --}}
                <article class="card tenant-span-2">
                    <div class="card-head has-divider">
                        <h2 class="card-title">Pembayaran</h2>
                        <p class="card-copy">Tagihan aktif atau pembayaran terbaru yang tercatat untuk kamar Anda.</p>
                    </div>

                    <div class="card-body">
                        @if ($featuredPayment === null)
                            <section class="empty-state">
                                <h2>Belum ada data pembayaran</h2>
                                <p>Belum ada tagihan atau riwayat pembayaran yang tercatat untuk akun Anda saat ini.</p>
                                <div class="empty-state-actions">
                                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Tanya soal pembayaran</a>
                                </div>
                            </section>
                        @else
                            <div class="detail-list">
                                <div class="detail-item">
                                    <div class="detail-label">Nominal pembayaran</div>
                                    <div class="detail-value">{{ \App\Support\UiFormatter::currency($featuredPayment->amount) }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Periode pembayaran</div>
                                    <div class="detail-value">
                                        {{ \App\Support\UiFormatter::date($featuredPayment->period_start) }}
                                        <span class="muted">s/d</span>
                                        {{ \App\Support\UiFormatter::date($featuredPayment->period_end) }}
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Tenggat pembayaran</div>
                                    <div class="detail-value">{{ \App\Support\UiFormatter::date($featuredPayment->due_date) }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Status pembayaran</div>
                                    <div class="detail-value">
                                        <span class="badge badge-{{ str_replace('_', '-', $featuredPayment->status) }}">{{ $paymentStatusLabels[$featuredPayment->status] ?? $featuredPayment->status }}</span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Status warning pembayaran</div>
                                    <div class="detail-value">
                                        <span class="badge badge-{{ str_replace('_', '-', $paymentDeadline?->deadline_status ?? 'safe') }}">{{ $deadlineStatusLabels[$paymentDeadline?->deadline_status ?? 'safe'] ?? 'Aman' }}</span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Catatan tenggat</div>
                                    <div class="detail-value muted">
                                        @if (($paymentDeadline?->deadline_status ?? 'safe') === 'due_soon')
                                            Tagihan akan jatuh tempo dalam {{ $paymentDeadline?->days_remaining }} hari.
                                        @elseif (($paymentDeadline?->deadline_status ?? 'safe') === 'due_today')
                                            Tagihan jatuh tempo hari ini.
                                        @elseif (($paymentDeadline?->deadline_status ?? 'safe') === 'overdue')
                                            Tagihan terlambat {{ abs((int) ($paymentDeadline?->days_remaining ?? 0)) }} hari.
                                        @elseif (($paymentDeadline?->deadline_status ?? 'safe') === 'paid')
                                            Pembayaran sudah lunas.
                                        @else
                                            Tenggat pembayaran masih aman.
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Upload Bukti Bayar --}}
                            <section class="tenant-proof-section">
                                <h3 class="tenant-proof-section-title">Upload bukti bayar</h3>
                                <p class="tenant-proof-section-copy">Unggah bukti bayar hanya untuk tagihan milik Anda sendiri. Setelah berhasil dikirim, status pembayaran akan berubah menjadi menunggu verifikasi admin.</p>

                                <div class="tenant-payment-stack">
                                    @foreach ($payments as $payment)
                                        @php
                                            $deadlineItem = $paymentDeadlines->get($payment->id);
                                            $canUploadProof = in_array($payment->status, ['unpaid', 'rejected'], true);
                                            $isPendingVerification = $payment->status === 'pending_verification';
                                            $isPaid = $payment->status === 'paid';
                                            $isActionTarget = $paymentActionId !== '' && $paymentActionId === (string) $payment->id;
                                            $isErrorTarget = $erroredPaymentId !== '' && $erroredPaymentId === (string) $payment->id;
                                        @endphp

                                        <article class="tenant-payment-entry" id="payment-{{ $payment->id }}">
                                            <div class="tenant-payment-head">
                                                <div>
                                                    <p class="eyebrow">Tagihan #{{ $payment->id }}</p>
                                                    <h4 class="tenant-payment-title">{{ \App\Support\UiFormatter::currency($payment->amount) }} &mdash; Periode {{ \App\Support\UiFormatter::date($payment->period_start) }} s/d {{ \App\Support\UiFormatter::date($payment->period_end) }}</h4>
                                                </div>

                                                <div class="hero-meta">
                                                    <span class="badge badge-{{ str_replace('_', '-', $payment->status) }}">{{ $paymentStatusLabels[$payment->status] ?? $payment->status }}</span>
                                                    <span class="badge badge-{{ str_replace('_', '-', $deadlineItem?->deadline_status ?? 'safe') }}">{{ $deadlineStatusLabels[$deadlineItem?->deadline_status ?? 'safe'] ?? 'Aman' }}</span>
                                                </div>
                                            </div>

                                            <div class="tenant-payment-meta-grid">
                                                <div class="tenant-payment-meta-item">
                                                    <div class="detail-label">Tenggat pembayaran</div>
                                                    <div class="detail-value">{{ \App\Support\UiFormatter::date($payment->due_date) }}</div>
                                                </div>
                                                <div class="tenant-payment-meta-item">
                                                    <div class="detail-label">Bukti bayar</div>
                                                    <div class="detail-value">{{ $payment->proof_image ? 'Sudah diunggah' : 'Belum diunggah' }}</div>
                                                </div>
                                                <div class="tenant-payment-meta-item">
                                                    <div class="detail-label">Waktu dibayar</div>
                                                    <div class="detail-value">{{ \App\Support\UiFormatter::date($payment->paid_at, 'd M Y H:i') }}</div>
                                                </div>
                                            </div>

                                            @if ($isActionTarget && session('success'))
                                                <div class="tenant-payment-inline-flash tenant-payment-inline-flash-success">{{ session('success') }}</div>
                                            @endif

                                            @if ($isActionTarget && session('error'))
                                                <div class="tenant-payment-inline-flash tenant-payment-inline-flash-error">{{ session('error') }}</div>
                                            @endif

                                            @if ($payment->proof_image)
                                                <div class="tenant-proof-state">
                                                    Bukti bayar saat ini sudah tersimpan di sistem.
                                                    @if ($payment->status === 'rejected')
                                                        Upload ulang gambar baru agar admin dapat meninjau ulang pembayaran ini.
                                                        @if ($payment->rejection_reason)
                                                            Alasan penolakan terakhir: {{ $payment->rejection_reason }}.
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif

                                            @if ($canUploadProof)
                                                <form method="POST" action="{{ route('tenant.payments.proof.store', $payment) }}" enctype="multipart/form-data" class="tenant-upload-form">
                                                    @csrf
                                                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                                                    <div class="tenant-upload-field">
                                                        <label for="proof_image_{{ $payment->id }}" class="tenant-upload-label">File bukti bayar</label>
                                                        <input id="proof_image_{{ $payment->id }}" name="proof_image" type="file" accept="image/*" class="tenant-upload-input" required>

                                                        @if ($isErrorTarget && $errors->has('proof_image'))
                                                            <div class="tenant-upload-error">{{ $errors->first('proof_image') }}</div>
                                                        @endif

                                                        <div class="tenant-upload-helper">
                                                            @if ($payment->status === 'rejected')
                                                                Bukti sebelumnya ditolak. Unggah file gambar baru (maks. 2MB) untuk diverifikasi ulang.
                                                            @else
                                                                Format JPG, JPEG, PNG, atau WEBP — ukuran maksimal 2MB.
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="card-actions">
                                                        <button type="submit" class="button button-primary">Upload bukti bayar</button>
                                                    </div>
                                                </form>
                                            @elseif ($isPendingVerification)
                                                <div class="tenant-proof-state">Bukti bayar sedang menunggu verifikasi admin. Upload ulang dinonaktifkan sementara.</div>
                                            @elseif ($isPaid)
                                                <div class="tenant-proof-state">Pembayaran ini sudah lunas. Upload ulang dinonaktifkan.</div>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            </section>

                            <div class="card-actions">
                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Hubungi pemilik soal pembayaran</a>
                            </div>
                        @endif
                    </div>
                </article>

            </section>
        @endif
    </div>
@endsection