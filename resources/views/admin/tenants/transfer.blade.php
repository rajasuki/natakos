@extends('admin.layout')

@section('title', 'Pindah Kamar')
@section('eyebrow', 'Admin Penghuni')
@section('page_title')
    Pindah Kamar: {{ $tenant->user?->name }}
@endsection

@section('page_description', 'Pindahkan penghuni ke kamar lain. Data pembayaran dan tagihan tetap terhubung ke penghuni.')

@section('content')
    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Informasi Penghuni</h2>
        </div>
        <div class="card-body">
            <div class="meta-line" style="margin-bottom:8px;">
                <strong>Kamar saat ini:</strong>
                <span class="badge badge-occupied">{{ $tenant->room?->name }}</span>
            </div>
            <div class="meta-line" style="margin-bottom:8px;">
                <strong>Status:</strong>
                <span class="badge badge-{{ $tenant->status }}">{{ $statusLabels[$tenant->status] ?? $tenant->status }}</span>
            </div>
            @if ($tenant->start_date)
                <div class="meta-line" style="margin-bottom:8px;">
                    <strong>Mulai sewa:</strong>
                    {{ \App\Support\UiFormatter::date($tenant->start_date) }}
                </div>
            @endif
            @if ($tenant->end_date)
                <div class="meta-line">
                    <strong>Selesai sewa:</strong>
                    {{ \App\Support\UiFormatter::date($tenant->end_date) }}
                </div>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('admin.tenants.transfer.update', $tenant) }}" class="content-stack">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-head">
                <h2 class="card-title">Kamar Baru</h2>
                <p class="card-copy">Pilih kamar tujuan. Kamar yang sedang dalam perbaikan atau sudah penuh tidak akan ditampilkan.</p>
            </div>
            <div class="card-body">
                <div class="form-layout grid-two">
                    <div class="field">
                        <label for="room_id">Kamar tujuan</label>
                        <select id="room_id" name="room_id" class="select">
                            <option value="">Pilih kamar</option>
                            @foreach ($rooms as $room)
                                @php
                                    $available = $room->id !== $tenant->room_id && $room->status !== 'maintenance' && !$room->is_at_capacity;
                                @endphp
                                @if ($available)
                                    <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                                        {{ $room->name }} ({{ $room->active_tenant_count }}/{{ $room->capacity ?? 1 }} - {{ $roomStatusLabels[$room->status] ?? $room->status }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('room_id') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="transfer_date">Tanggal pindah</label>
                        <input id="transfer_date" name="transfer_date" type="date" class="input" value="{{ old('transfer_date', now()->format('Y-m-d')) }}">
                        @error('transfer_date') <span class="field-error">{{ $message }}</span> @enderror
                        <div class="helper">Tanggal efektif penghuni pindah kamar.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-primary">Pindahkan Kamar</button>
            <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Batal</a>
        </div>
    </form>
@endsection
