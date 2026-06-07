@extends('tenant.layout')

@section('title', 'Ajukan Perbaikan')

@push('styles')
<style>
    .page-header { display:none; }
    .form-wrap { max-width:640px; margin:0 auto; padding-top:24px; }
    .form-wrap h1 { margin:0 0 8px; font-size:22px; font-weight:700; color:var(--ui-ink); }
    .form-wrap p { margin:0 0 24px; color:var(--ui-body); font-size:14px; }
    .form-card { background:#fff; border:1px solid var(--ui-border); border-radius:var(--radius-lg); padding:24px; }
    .field { display:grid; gap:6px; margin-bottom:16px; }
    .field label { font-size:13px; font-weight:600; color:var(--gray-600); }
    .input,.select,.textarea { width:100%; border:1px solid var(--ui-border); background:#fff; color:var(--ui-ink); padding:10px 14px; border-radius:var(--radius-md); font-size:14px; transition:border-color .15s; }
    .input:focus,.select:focus,.textarea:focus { outline:none; border-color:var(--ui-accent); box-shadow:0 0 0 3px rgba(74,124,89,.12); }
    .textarea { min-height:140px; resize:vertical; }
</style>
@endpush

@section('content')
    <div class="form-wrap">
        <h1>Ajukan Perbaikan</h1>
        <p>Laporkan kerusakan atau masalah pada kamar Anda. Admin akan meninjau dan menindaklanjuti laporan Anda.</p>

        <div class="form-card">
            <form method="POST" action="{{ route('tenant.maintenance-requests.store') }}">
                @csrf

                <div class="field">
                    <label for="title">Judul Laporan <span class="muted">*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" class="input" placeholder="Contoh: Lampu kamar mati" required>
                    @error('title') <div class="field-error" style="color:#be123c;font-size:12px;font-weight:600;">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="priority">Prioritas <span class="muted">*</span></label>
                    <select id="priority" name="priority" class="select" required>
                        @foreach ($priorityLabels as $v => $l)
                            <option value="{{ $v }}" @selected(old('priority') === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                    @error('priority') <div class="field-error" style="color:#be123c;font-size:12px;font-weight:600;">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="description">Deskripsi <span class="muted">*</span></label>
                    <textarea id="description" name="description" class="textarea" placeholder="Jelaskan masalah yang Anda alami..." required>{{ old('description') }}</textarea>
                    @error('description') <div class="field-error" style="color:#be123c;font-size:12px;font-weight:600;">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <a href="{{ route('tenant.maintenance-requests.index') }}" class="button button-secondary">Batal</a>
                    <button type="submit" class="button button-primary">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
