@php
    $payment = $payment ?? null;
    $errorBag = isset($errors) ? $errors : null;
@endphp

<div class="card form-card">
    @if ($tenants->isEmpty() && $payment === null)
        <section class="empty-state">
            <h2>Belum ada penghuni aktif untuk pembayaran</h2>
            <p>Tambahkan penghuni terlebih dahulu sebelum membuat pembayaran manual agar pilihan tenant tersedia pada form ini.</p>

            <div class="empty-state-actions">
                <a href="{{ route('admin.tenants.create') }}" class="button button-primary">Tambah penghuni</a>
                <a href="{{ route('admin.payments.index') }}" class="button button-secondary">Kembali ke daftar pembayaran</a>
            </div>
        </section>
    @else
        <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="form-layout">
            @csrf

            @isset($method)
                @method($method)
            @endisset

            <section class="form-section">
                <div>
                    <h2 class="form-section-title">Penghuni dan nominal</h2>
                    <p class="form-section-copy">Pilih penghuni tujuan tagihan dan masukkan nominal pembayaran manual yang ingin dicatat.</p>
                </div>

                <div class="grid grid-two">
                    <div class="field field-full">
                        <label for="tenant_id">Penghuni</label>
                        <select id="tenant_id" name="tenant_id" class="select" required>
                            <option value="">Pilih penghuni</option>
                            @foreach ($tenants as $tenant)
                                <option value="{{ $tenant->id }}" @selected((string) old('tenant_id', $payment?->tenant_id) === (string) $tenant->id)>
                                    {{ $tenant->user?->name ?: 'Penghuni tidak tersedia' }} - {{ $tenant->room?->name ?: 'Kamar tidak tersedia' }} - {{ $tenantStatusLabels[$tenant->status] ?? $tenant->status }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errorBag?->has('tenant_id'))
                            <div class="field-error">{{ $errorBag->first('tenant_id') }}</div>
                        @endif
                        <div class="helper">Pilih penghuni yang akan dicatat tagihan atau pembayarannya.</div>
                    </div>

                    <div class="field">
                        <label for="amount">Nominal</label>
                        <input id="amount" name="amount" type="number" min="0" step="1" value="{{ old('amount', $payment?->amount) }}" class="input" required>
                        @if ($errorBag?->has('amount'))
                            <div class="field-error">{{ $errorBag->first('amount') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="status">Status pembayaran</label>
                        <select id="status" name="status" class="select" required>
                            @foreach ($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $payment?->status ?? 'unpaid') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if ($errorBag?->has('status'))
                            <div class="field-error">{{ $errorBag->first('status') }}</div>
                        @endif
                        <div class="helper">Jika status diatur ke <strong>Lunas</strong>, sistem akan mengisi verifikasi admin otomatis.</div>
                    </div>
                </div>
            </section>

            <section class="form-section">
                <div>
                    <h2 class="form-section-title">Periode dan tenggat</h2>
                    <p class="form-section-copy">Atur periode tagihan, tenggat pembayaran, dan waktu bayar jika sudah tersedia.</p>
                </div>

                <div class="grid grid-two">
                    <div class="field">
                        <label for="period_start">Periode mulai</label>
                        <input id="period_start" name="period_start" type="date" value="{{ old('period_start', $payment?->period_start?->format('Y-m-d')) }}" class="input" required>
                        @if ($errorBag?->has('period_start'))
                            <div class="field-error">{{ $errorBag->first('period_start') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="period_end">Periode akhir</label>
                        <input id="period_end" name="period_end" type="date" value="{{ old('period_end', $payment?->period_end?->format('Y-m-d')) }}" class="input" required>
                        @if ($errorBag?->has('period_end'))
                            <div class="field-error">{{ $errorBag->first('period_end') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="due_date">Tenggat bayar</label>
                        <input id="due_date" name="due_date" type="date" value="{{ old('due_date', $payment?->due_date?->format('Y-m-d')) }}" class="input" required>
                        @if ($errorBag?->has('due_date'))
                            <div class="field-error">{{ $errorBag->first('due_date') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="paid_at">Waktu dibayar</label>
                        <input id="paid_at" name="paid_at" type="datetime-local" value="{{ old('paid_at', $payment?->paid_at?->format('Y-m-d\TH:i')) }}" class="input">
                        @if ($errorBag?->has('paid_at'))
                            <div class="field-error">{{ $errorBag->first('paid_at') }}</div>
                        @endif
                        <div class="helper">Opsional. Jika status diubah ke lunas dan kolom ini kosong, sistem akan mengisinya otomatis.</div>
                    </div>
                </div>
            </section>

            <section class="form-section">
                <div>
                    <h2 class="form-section-title">Bukti dan catatan</h2>
                    <p class="form-section-copy">Lampirkan bukti pembayaran jika ada dan simpan catatan verifikasi tambahan.</p>
                </div>

                <div class="grid">
                    <div class="field field-full">
                        <label for="proof_image">Bukti pembayaran</label>
                        <input id="proof_image" name="proof_image" type="file" accept="image/*" class="file-input">
                        @if ($errorBag?->has('proof_image'))
                            <div class="field-error">{{ $errorBag->first('proof_image') }}</div>
                        @endif
                        <div class="helper">Opsional. Jika diupload, file disimpan ke storage Laravel pada folder <code>payments</code>.</div>

                        @if ($payment?->proof_image)
                            <div class="preview-frame preview-frame-spaced">
                                <div class="preview">
                                    <img src="{{ route('admin.payments.proof', $payment) }}" alt="Bukti pembayaran {{ $payment->tenant?->user?->name ?: 'penghuni' }}">
                                    <div class="preview-meta">Path saat ini: <strong>{{ $payment->proof_image }}</strong></div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="field field-full">
                        <label for="notes">Catatan</label>
                        <textarea id="notes" name="notes" class="textarea" placeholder="Tulis catatan pembayaran jika diperlukan...">{{ old('notes', $payment?->notes) }}</textarea>
                        @if ($errorBag?->has('notes'))
                            <div class="field-error">{{ $errorBag->first('notes') }}</div>
                        @endif
                    </div>

                    <div class="field field-full">
                        <label for="rejection_reason">Alasan penolakan</label>
                        <textarea id="rejection_reason" name="rejection_reason" class="textarea" placeholder="Isi jika status pembayaran ditolak...">{{ old('rejection_reason', $payment?->rejection_reason) }}</textarea>
                        @if ($errorBag?->has('rejection_reason'))
                            <div class="field-error">{{ $errorBag->first('rejection_reason') }}</div>
                        @endif
                        <div class="helper">Wajib diisi jika status pembayaran diset ke <strong>Ditolak</strong>.</div>
                    </div>
                </div>
            </section>

            <div class="form-actions">
                <button type="submit" class="button button-primary">{{ $submitLabel }}</button>
                <a href="{{ route('admin.payments.index') }}" class="button button-secondary">Kembali ke daftar pembayaran</a>
            </div>
        </form>
    @endif
</div>
