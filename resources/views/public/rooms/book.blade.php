@extends('public.layout')

@section('title', 'Ajukan Sewa - '.$room->name.' | '.$profile['name'])

@push('styles')
<style>
    .booking-shell {
        max-width: 640px;
        margin: 48px auto;
        padding: 0 16px;
    }

    .booking-card {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--ui-shadow);
    }

    .booking-head {
        padding: 24px;
        border-bottom: 1px solid var(--ui-border);
        background: var(--ui-canvas);
    }

    .booking-head h1 {
        margin: 0 0 4px;
        font-size: 22px;
        font-weight: 700;
        color: var(--ui-ink);
    }

    .booking-head p {
        margin: 0;
        font-size: 14px;
        color: var(--ui-body);
        line-height: 1.6;
    }

    .booking-preview {
        display: flex;
        gap: 16px;
        padding: 20px 24px;
        background: var(--ui-soft);
        border-bottom: 1px solid var(--ui-border);
        align-items: center;
    }

    .booking-preview-img {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        background: var(--ui-softer);
        flex-shrink: 0;
    }

    .booking-preview-info h2 {
        margin: 0 0 4px;
        font-size: 18px;
        font-weight: 600;
        color: var(--ui-ink);
    }

    .booking-preview-info .price {
        font-size: 16px;
        font-weight: 700;
        color: var(--ui-accent);
    }

    .booking-preview-info .price span {
        font-weight: 400;
        font-size: 13px;
        color: var(--ui-body);
    }

    .booking-form {
        padding: 24px;
        display: grid;
        gap: 20px;
    }

    .booking-field {
        display: grid;
        gap: 6px;
    }

    .booking-field label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ui-ink);
    }

    .booking-field input,
    .booking-field select,
    .booking-field textarea {
        padding: 10px 14px;
        border: 1px solid var(--ui-border);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        color: var(--ui-ink);
        background: #fff;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }

    .booking-field input:focus,
    .booking-field select:focus,
    .booking-field textarea:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 3px rgba(74,124,89,.12);
    }

    .booking-field textarea {
        min-height: 80px;
        resize: vertical;
    }

    .booking-durasi-hint {
        font-size: 12px;
        color: var(--ui-body);
        margin-top: 4px;
    }

    .booking-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding-top: 8px;
    }

    .booking-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 24px;
        border-radius: 999px;
        font-size: 15px;
        font-weight: 600;
        border: 0;
        cursor: pointer;
        transition: background .2s, box-shadow .2s;
        font-family: inherit;
    }

    .booking-btn-primary {
        background: var(--ui-accent);
        color: #fff;
    }

    .booking-btn-primary:hover {
        background: var(--ui-accent-hover);
    }

    .booking-btn-secondary {
        background: transparent;
        color: var(--ui-body);
        border: 1.5px solid var(--ui-border);
    }

    .booking-btn-secondary:hover {
        background: var(--ui-soft);
        color: var(--ui-ink);
    }

    .booking-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
    <div class="booking-shell">
        @if ($errors->any())
            <div class="booking-error" style="margin-bottom:16px;">
                <ul style="margin:0;padding-left:16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="booking-card">
            <div class="booking-head">
                <h1>Ajukan Sewa Kamar</h1>
                <p>Isi tanggal masuk dan durasi sewa yang diinginkan. Admin akan memproses pengajuan Anda.</p>
            </div>

            @php
                $cover = $room->main_image ?: $room->images->first()?->image_path;
            @endphp
            <div class="booking-preview">
                @if ($cover)
                    <img src="{{ asset('storage/'.$cover) }}" alt="{{ $room->name }}" class="booking-preview-img">
                @else
                    <div class="booking-preview-img" style="display:flex;align-items:center;justify-content:center;color:var(--ui-body);font-size:11px;text-align:center;">Foto<br>tidak ada</div>
                @endif
                <div class="booking-preview-info">
                    <h2>{{ $room->name }}</h2>
                    <div class="price">{{ \App\Support\UiFormatter::currency($room->price) }} <span>/bln</span></div>
                </div>
            </div>

            <form method="POST" action="{{ route('rooms.book.store', $room) }}" class="booking-form">
                @csrf

                <div class="booking-field">
                    <label for="start_date">Tanggal Masuk</label>
                    <input type="date" id="start_date" name="start_date"
                           value="{{ old('start_date', now()->addDay()->format('Y-m-d')) }}"
                           min="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="booking-field">
                    <label for="duration">Durasi Sewa</label>
                    <select id="duration" name="duration" required>
                        <option value="1" @selected(old('duration', '1') === '1')>1 Bulan</option>
                        <option value="3" @selected(old('duration') === '3')>3 Bulan</option>
                        <option value="6" @selected(old('duration') === '6')>6 Bulan</option>
                        <option value="12" @selected(old('duration') === '12')>12 Bulan</option>
                    </select>
                    <div class="booking-durasi-hint">Sewa minimal 1 bulan, bisa diperpanjang nanti.</div>
                </div>

                <div class="booking-field">
                    <label for="notes">Catatan <span style="color:var(--ui-body);font-weight:400;">(opsional)</span></label>
                    <textarea id="notes" name="notes" placeholder="Misal: preferensi lantai, request khusus, dll.">{{ old('notes') }}</textarea>
                </div>

                <div class="booking-actions">
                    <button type="submit" class="booking-btn booking-btn-primary">
                        <span class="material-symbols-outlined" style="font-size:18px;">send</span>
                        Kirim Pengajuan
                    </button>
                    <a href="{{ route('rooms.show', $room) }}" class="booking-btn booking-btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
