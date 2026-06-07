@extends('admin.layout')

@section('title', 'Kelola Pengajuan Perbaikan')
@section('eyebrow', 'Admin Perbaikan')
@section('page_title', 'Kelola Pengajuan Perbaikan')

@section('page_actions')
    <a href="{{ route('admin.maintenance-requests.index') }}" class="button button-secondary">Kembali</a>
    <button type="submit" form="mr-form" class="button button-primary">Simpan</button>
@endsection

@section('content')
    <div class="card form-card">
        <form method="POST" action="{{ route('admin.maintenance-requests.update', $request) }}" id="mr-form">
            @csrf @method('PUT')

            <div class="form-layout">
                <section class="form-section">
                    <div>
                        <h3 class="form-section-title">{{ $request->title }}</h3>
                        <p class="form-section-copy">
                            Dilaporkan oleh <strong>{{ $request->tenant?->user?->name ?: '-' }}</strong>
                            ({{ $request->room?->name ?: '-' }})
                            pada {{ \App\Support\UiFormatter::date($request->created_at, 'd M Y H:i') }}
                        </p>
                    </div>

                    <div style="background:#fff;border:1px solid var(--ui-border);border-radius:12px;padding:16px;">
                        <p style="margin:0;font-size:14px;line-height:1.7;color:var(--ui-ink);">{{ $request->description }}</p>
                    </div>

                    <div class="grid grid-two">
                        <div class="field">
                            <label for="status">Status <span class="muted">*</span></label>
                            <select id="status" name="status" class="select" required>
                                @foreach ($statusLabels as $v => $l)
                                    <option value="{{ $v }}" @selected(old('status', $request->status) === $v)>{{ $l }}</option>
                                @endforeach
                            </select>
                            @error('status') <div class="field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label>Prioritas</label>
                            <div style="padding:10px 0;font-weight:600;">
                                <span class="badge badge-{{ $request->priority }}">{{ $priorityLabels[$request->priority] ?? $request->priority }}</span>
                            </div>
                        </div>

                        <div class="field field-full">
                            <label for="admin_notes">Catatan Admin</label>
                            <textarea id="admin_notes" name="admin_notes" class="textarea" rows="4" placeholder="Tulis catatan atau tanggapan...">{{ old('admin_notes', $request->admin_notes) }}</textarea>
                            @error('admin_notes') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </section>
            </div>
        </form>
    </div>
@endsection
