@extends('tenant.layout')

@section('title', 'Dashboard Penghuni')

@push('styles')
    <style>
        .tenant-hero-grid {
            display: grid;
            gap: 20px;
        }

        .tenant-hero-side {
            display: grid;
            gap: 16px;
        }

        .tenant-hero-panel {
            background: #000000;
            color: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 4px 16px 0px;
        }

        .tenant-hero-panel .eyebrow,
        .tenant-hero-panel .hero-copy,
        .tenant-hero-panel .muted {
            color: #afafaf;
        }

        .tenant-hero-panel .hero-meta-pill {
            background: #282828;
            border: 1px solid #282828;
            color: #ffffff;
        }

        .tenant-stat-grid,
        .tenant-dashboard-grid {
            display: grid;
            gap: 20px;
        }

        .tenant-stat-card {
            padding: 20px;
        }

        .tenant-stat-value {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
        }

        .tenant-stat-label {
            margin: 0 0 10px;
            color: var(--ui-body);
            font-size: 13px;
            line-height: 1.5;
        }

        .tenant-note {
            margin-top: 20px;
            padding: 16px;
            border-radius: 16px;
            background: var(--ui-soft);
            border: 1px solid var(--ui-border);
        }

        @media (min-width: 900px) {
            .tenant-hero-grid {
                grid-template-columns: 1.15fr 0.85fr;
                align-items: stretch;
            }

            .tenant-stat-grid,
            .tenant-dashboard-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .tenant-span-2 {
                grid-column: span 2;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $roomStatus = $tenant?->room?->status;
        $rentStatus = $rentSummary->rent_period_status ?? 'safe';
        $paymentStatus = $featuredPayment?->status;
    @endphp

    <div class="content-stack">
        <section class="tenant-hero-grid">
            <article class="hero-card">
                <div>
                    <p class="eyebrow">Dashboard penghuni</p>
                    <h1 class="page-title">Halo, {{ $user->name }}.</h1>
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
                    <p class="hero-copy">{{ $tenant ? 'Kamar utama yang tercatat untuk akun Anda saat ini.' : 'Data tenant aktif belum tersedia untuk akun ini.' }}</p>
                </article>

                <div class="tenant-stat-grid">
                    <article class="card tenant-stat-card">
                        <p class="tenant-stat-label">Status kamar</p>
                        <p class="tenant-stat-value">{{ $tenant ? ($roomStatusLabels[$roomStatus] ?? '-') : '-' }}</p>
                    </article>
                    <article class="card tenant-stat-card">
                        <p class="tenant-stat-label">Status masa tinggal</p>
                        <p class="tenant-stat-value">{{ $tenant ? ($rentStatusLabels[$rentStatus] ?? '-') : '-' }}</p>
                    </article>
                    <article class="card tenant-stat-card">
                        <p class="tenant-stat-label">Status pembayaran</p>
                        <p class="tenant-stat-value">{{ $featuredPayment ? ($paymentStatusLabels[$paymentStatus] ?? '-') : '-' }}</p>
                    </article>
                </div>
            </aside>
        </section>

        @if ($tenant === null)
            <section class="empty-state">
                <h2>Data penghuni belum tersedia</h2>
                <p>
                    Akun Anda belum terhubung ke data tenant aktif. Silakan hubungi pengelola NATAKOS agar data kamar dan masa tinggal Anda dapat ditampilkan di dashboard ini.
                </p>

                <div class="empty-state-actions">
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Hubungi Pemilik via WhatsApp</a>
                </div>
            </section>
        @else
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

            <section class="tenant-dashboard-grid">
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

                        <div class="tenant-note muted">
                            Jika ada perubahan kamar atau harga yang belum sesuai, segera hubungi pengelola agar data Anda diperbarui.
                        </div>
                    </div>
                </article>

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
                                    <span class="badge badge-{{ str_replace('_', '-', $rentSummary->rent_period_status ?? 'safe') }}">{{ $rentStatusLabels[$rentSummary->rent_period_status ?? 'safe'] ?? 'Aman' }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Catatan status</div>
                                <div class="detail-value muted">
                                    @if (($rentSummary->rent_period_status ?? 'safe') === 'ending_soon')
                                        Berakhir dalam {{ $rentSummary->days_until_end }} hari.
                                    @elseif (($rentSummary->rent_period_status ?? 'safe') === 'ends_today')
                                        Masa tinggal berakhir hari ini.
                                    @elseif (($rentSummary->rent_period_status ?? 'safe') === 'ended')
                                        Sudah berakhir {{ abs((int) $rentSummary->days_until_end) }} hari yang lalu.
                                    @elseif (($rentSummary->rent_period_status ?? 'safe') === 'no_end_date')
                                        Tanggal keluar belum ditentukan.
                                    @else
                                        Masa tinggal masih dalam kondisi aman.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="tenant-note muted">
                            Pastikan tanggal keluar selalu sesuai rencana tinggal Anda agar peringatan masa tinggal lebih akurat.
                        </div>
                    </div>
                </article>

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
                                        <span class="badge badge-{{ str_replace('_', '-', $paymentDeadline->deadline_status ?? 'safe') }}">{{ $deadlineStatusLabels[$paymentDeadline->deadline_status ?? 'safe'] ?? 'Aman' }}</span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Catatan tenggat</div>
                                    <div class="detail-value muted">
                                        @if (($paymentDeadline->deadline_status ?? 'safe') === 'due_soon')
                                            Tagihan akan jatuh tempo dalam {{ $paymentDeadline->days_remaining }} hari.
                                        @elseif (($paymentDeadline->deadline_status ?? 'safe') === 'due_today')
                                            Tagihan jatuh tempo hari ini.
                                        @elseif (($paymentDeadline->deadline_status ?? 'safe') === 'overdue')
                                            Tagihan terlambat {{ abs((int) $paymentDeadline->days_remaining) }} hari.
                                        @elseif (($paymentDeadline->deadline_status ?? 'safe') === 'paid')
                                            Pembayaran sudah lunas.
                                        @else
                                            Tenggat pembayaran masih aman.
                                        @endif
                                    </div>
                                </div>
                            </div>

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
