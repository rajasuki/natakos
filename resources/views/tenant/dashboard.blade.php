@extends('tenant.layout')

@section('title', 'Dashboard Penghuni')

@push('styles')
    <style>
        .hero-card {
            display: grid;
            gap: 20px;
            padding: 26px;
            background: #efefef;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .hero-copy {
            margin: 12px 0 0;
            color: #5e5e5e;
            font-size: 15px;
            line-height: 1.7;
        }

        .dashboard-alerts {
            display: grid;
            gap: 12px;
            margin-bottom: 24px;
        }

        .dashboard-grid {
            display: grid;
            gap: 20px;
        }

        .card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        @media (min-width: 900px) {
            .dashboard-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .card-span-2 {
                grid-column: span 2;
            }
        }
    </style>
@endpush

@section('content')
    <section class="hero-card">
        <div>
            <p class="eyebrow">Dashboard Penghuni</p>
            <h1 class="page-title">Halo, {{ $user->name }}.</h1>
            <p class="hero-copy">
                Lihat ringkasan kamar, masa tinggal, pembayaran terbaru, dan hubungi pengelola kos langsung dari dashboard ini.
            </p>
        </div>

        <div class="card-actions">
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noreferrer" class="button button-primary">Hubungi Pemilik via WhatsApp</a>
        </div>
    </section>

    @if ($tenant === null)
        <section class="empty-state">
            <h2>Data penghuni belum tersedia</h2>
            <p>
                Akun Anda belum terhubung ke data tenant aktif. Silakan hubungi pengelola NATAKOS agar data kamar dan masa tinggal Anda dapat ditampilkan di dashboard ini.
            </p>

            <div class="card-actions">
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noreferrer" class="button button-primary">Hubungi Pemilik via WhatsApp</a>
            </div>
        </section>
    @else
        @if ($paymentWarning || $rentWarning)
            <section class="dashboard-alerts">
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

        <section class="dashboard-grid">
            <article class="card">
                <div class="card-body">
                    <h2 class="card-title">Informasi Kamar</h2>
                    <p class="card-copy">Data kamar yang sedang Anda tempati saat ini.</p>

                    <div class="detail-list">
                        <div class="detail-item">
                            <div class="detail-label">Nama penghuni</div>
                            <div class="detail-value">{{ $user->name }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Kamar yang ditempati</div>
                            <div class="detail-value">{{ $tenant->room->name }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Harga kamar</div>
                            <div class="detail-value">Rp{{ number_format($tenant->room->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status kamar</div>
                            <div class="detail-value">
                                <span class="badge badge-{{ $tenant->room->status }}">{{ $roomStatusLabels[$tenant->room->status] ?? $tenant->room->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="card-body">
                    <h2 class="card-title">Masa Tinggal</h2>
                    <p class="card-copy">Pantau periode tinggal dan status masa tinggal Anda.</p>

                    <div class="detail-list">
                        <div class="detail-item">
                            <div class="detail-label">Tanggal masuk</div>
                            <div class="detail-value">{{ $tenant->start_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Tanggal keluar</div>
                            <div class="detail-value">{{ $tenant->end_date?->format('d M Y') ?? '-' }}</div>
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
                </div>
            </article>

            <article class="card card-span-2">
                <div class="card-body">
                    <h2 class="card-title">Pembayaran</h2>
                    <p class="card-copy">Tagihan aktif atau pembayaran terbaru yang tercatat untuk kamar Anda.</p>

                    @if ($featuredPayment === null)
                        <section class="empty-state" style="margin-top: 18px;">
                            <h2 style="font-size: 24px;">Belum ada data pembayaran</h2>
                            <p>Belum ada tagihan atau riwayat pembayaran yang tercatat untuk akun Anda saat ini.</p>
                        </section>
                    @else
                        <div class="detail-list">
                            <div class="detail-item">
                                <div class="detail-label">Nominal pembayaran</div>
                                <div class="detail-value">Rp{{ number_format($featuredPayment->amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Periode pembayaran</div>
                                <div class="detail-value">
                                    {{ $featuredPayment->period_start?->format('d M Y') ?? '-' }}
                                    <span class="muted">s/d</span>
                                    {{ $featuredPayment->period_end?->format('d M Y') ?? '-' }}
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Tenggat pembayaran</div>
                                <div class="detail-value">{{ $featuredPayment->due_date?->format('d M Y') ?? '-' }}</div>
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
                    @endif
                </div>
            </article>
        </section>
    @endif
@endsection
