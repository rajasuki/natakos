@extends('admin.layout')

@section('title', 'Check-out Penghuni')
@section('eyebrow', 'Admin Penghuni')
@section('page_title', 'Proses check-out penghuni')
@section('page_description', 'Selesaikan masa tinggal penghuni aktif, simpan tanggal keluar, lalu kembalikan kamar ke status yang sesuai.')

@section('page_actions')
    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kembali ke penghuni aktif</a>
    <a href="{{ route('admin.tenants.history') }}" class="button button-subtle">Lihat riwayat penghuni</a>
@endsection

@section('content')
    @php
        $errorBag = isset($errors) ? $errors : null;
    @endphp

    <div class="card form-card">
        <form method="POST" action="{{ route('admin.tenants.checkout.update', $tenant) }}" class="form-layout">
            @csrf
            @method('PUT')

            <section class="form-section">
                <div>
                    <h2 class="form-section-title">Ringkasan penghuni aktif</h2>
                    <p class="form-section-copy">Pastikan data penghuni dan kamar sudah sesuai sebelum check-out diproses.</p>
                </div>

                <div class="grid grid-two">
                    <div class="field">
                        <label>Nama penghuni</label>
                        <div class="input">{{ $tenant->user?->name ?: 'User tidak tersedia' }}</div>
                    </div>

                    <div class="field">
                        <label>Kamar</label>
                        <div class="input">{{ $tenant->room?->name ?: 'Kamar tidak tersedia' }}</div>
                    </div>

                    <div class="field">
                        <label>Tanggal masuk</label>
                        <div class="input">{{ \App\Support\UiFormatter::date($tenant->start_date) }}</div>
                    </div>

                    <div class="field">
                        <label>Status saat ini</label>
                        <div class="input">Aktif</div>
                    </div>
                </div>
            </section>

            <section class="form-section">
                <div>
                    <h2 class="form-section-title">Data check-out</h2>
                    <p class="form-section-copy">Setelah disimpan, status penghuni berubah menjadi <code>moved_out</code> dan kamar akan disinkronkan otomatis.</p>
                </div>

                <div class="grid grid-two">
                    <div class="field">
                        <label for="end_date">Tanggal check-out</label>
                        <input id="end_date" name="end_date" type="date" value="{{ old('end_date', now()->format('Y-m-d')) }}" class="input" required>
                        @if ($errorBag?->has('end_date'))
                            <div class="field-error">{{ $errorBag->first('end_date') }}</div>
                        @endif
                    </div>

                    <div class="field field-full">
                        <label for="notes">Catatan check-out</label>
                        <textarea id="notes" name="notes" class="textarea" placeholder="Tulis catatan check-out jika diperlukan...">{{ old('notes', $tenant->notes) }}</textarea>
                        @if ($errorBag?->has('notes'))
                            <div class="field-error">{{ $errorBag->first('notes') }}</div>
                        @endif
                    </div>
                </div>
            </section>

            <div class="form-actions">
                <button type="submit" class="button button-primary">Proses check-out</button>
                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="button button-secondary">Kembali edit penghuni</a>
            </div>
        </form>
    </div>
@endsection
