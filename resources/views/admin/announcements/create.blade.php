@extends('admin.layout')

@section('title', 'Tambah Pengumuman')
@section('eyebrow', 'Admin Pengumuman')
@section('page_title', 'Tambah pengumuman baru')
@section('page_description', 'Pengumuman akan muncul di halaman penghuni yang sudah memiliki kamar.')

@push('styles')
<style>
    .sound-section { display:grid;gap:12px;padding:16px;background:var(--gray-50);border-radius:var(--radius-lg);border:1px solid var(--ui-border); }
    .sound-section .field { display:grid;gap:4px; }
    .sound-section label { font-size:13px;font-weight:600;color:var(--gray-600); }

    .preview-banner {
        background:#fff;
        border:2px solid var(--ui-border);
        border-radius:var(--radius-lg);
        overflow:hidden;
    }
    .preview-banner-head {
        padding:6px 14px;
        background:var(--gray-50);
        border-bottom:1px solid var(--ui-border);
        font-size:11px;
        font-weight:600;
        color:var(--ui-body);
        display:flex;align-items:center;gap:6px;
    }
    .preview-banner-track {
        display:flex;align-items:center;gap:10px;
        padding:8px 14px;min-height:40px;
        overflow:hidden;
    }
    .preview-banner-track .pbi {
        flex-shrink:0;width:24px;height:24px;border-radius:50%;
        background:var(--ui-accent);color:#fff;
        display:flex;align-items:center;justify-content:center;
    }
    .preview-banner-track .pbi .material-symbols-outlined { font-size:14px;font-variation-settings:'FILL'1; }
    .preview-bar {
        flex:1;overflow:hidden;height:24px;display:flex;align-items:center;
        mask-image:linear-gradient(to right,transparent 8px,#000 24px,#000 calc(100% - 24px),transparent calc(100% - 8px));
        -webkit-mask-image:linear-gradient(to right,transparent 8px,#000 24px,#000 calc(100% - 24px),transparent calc(100% - 8px));
    }
    .preview-scroll {
        display:flex;gap:40px;white-space:nowrap;
        animation:previewScroll linear infinite;
        animation-duration:180s;
        will-change:transform;
    }
    .preview-scroll:hover { animation-play-state:paused; }
    @keyframes previewScroll {
        0% { transform:translateX(0); }
        100% { transform:translateX(-50%); }
    }
    .preview-item {
        display:inline-flex;align-items:center;gap:8px;font-size:13px;
    }
    .preview-item .pi-badge {
        display:inline-block;background:var(--gray-600);color:#fff;
        font-size:9px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;
        padding:2px 8px;border-radius:999px;flex-shrink:0;
    }
    .preview-item .pi-dot {
        display:inline-block;width:3px;height:3px;border-radius:50%;
        background:var(--ui-border);flex-shrink:0;
    }
    .preview-item .pi-text { color:var(--ui-ink);font-weight:500;font-size:13px; }
    .preview-item .pi-sep { display:inline-block; }
</style>
@endpush

@section('content')
    <div class="form-card">
        <form method="POST" action="{{ route('admin.announcements.store') }}" id="announcement-form" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title" class="form-label">Judul</label>
                <input id="title" name="title" type="text" class="form-input" value="{{ old('title') }}" required maxlength="200" oninput="refreshPreview()">
                @error('title') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Konten</label>
                <textarea id="content" name="content" class="form-input" rows="4" required oninput="refreshPreview()">{{ old('content') }}</textarea>
                @error('content') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="scroll_speed" class="form-label">Kecepatan scroll (detik)</label>
                <div style="display:flex;gap:12px;align-items:center;">
                    <input id="scroll_speed" name="scroll_speed" type="range" class="form-input" style="flex:1;padding:0;border:none;" value="{{ old('scroll_speed', 180) }}" min="30" max="600" step="10" oninput="updateSpeedLabel()">
                    <span id="speed-label" style="font-size:14px;font-weight:600;color:var(--ui-ink);min-width:48px;text-align:right;">180s</span>
                </div>
                <div class="helper" style="margin-top:4px;">Semakin besar angkanya, semakin lambat gerakannya. (30–600 detik)</div>
                <div style="display:flex;justify-content:space-between;font-size:10px;color:var(--gray-400);margin-top:2px;">
                    <span>Cepat</span>
                    <span>Normal <span style="font-weight:600;color:var(--gray-600);">(180s)</span></span>
                    <span>Sangat lambat</span>
                </div>
                @error('scroll_speed') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Sound --}}
            <div class="form-group">
                <label class="form-label">Suara notifikasi (opsional)</label>
                <div class="sound-section">
                    <div class="field">
                        <label>
                            <input type="checkbox" name="has_sound" value="1" @checked(old('has_sound')) onchange="refreshPreview()">
                            Aktifkan suara notifikasi
                        </label>
                        <div class="helper">Suara akan diputar otomatis di halaman penghuni saat pengumuman aktif.</div>
                    </div>

                    <div class="field">
                        <label for="announcement_sound_id">Pilih suara yang sudah ada</label>
                        <select id="announcement_sound_id" name="announcement_sound_id" class="form-input" onchange="refreshPreview()">
                            <option value="">—</option>
                            @foreach ($sounds as $s)
                                <option value="{{ $s->id }}" data-url="{{ asset('storage/'.$s->file_path) }}" @selected(old('announcement_sound_id') == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="border-top:1px solid var(--ui-border);padding-top:12px;">
                        <div class="helper" style="margin-bottom:8px;">Atau upload suara baru:</div>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;">
                            <div class="field" style="flex:1;min-width:140px;">
                                <label for="new_sound_name">Nama suara</label>
                                <input id="new_sound_name" name="new_sound_name" type="text" class="form-input" maxlength="100" placeholder="Misal: Alarm Banjir">
                            </div>
                            <div class="field" style="flex:1;min-width:140px;">
                                <label for="new_sound_file">File audio (max 5 MB)</label>
                                <input id="new_sound_file" name="new_sound_file" type="file" class="form-input" accept="audio/*" onchange="refreshPreview()">
                            </div>
                        </div>
                        @error('new_sound_name') <span class="form-error">{{ $message }}</span> @enderror
                        @error('new_sound_file') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                    Aktif
                </label>
            </div>

            {{-- Live Preview --}}
            <div class="form-group">
                <label class="form-label">Preview</label>
                <div class="preview-banner">
                    <div class="preview-banner-head">
                        <span class="material-symbols-outlined" style="font-size:14px;">visibility</span>
                        Tampilan di halaman penghuni
                        <span id="preview-sound-badge" style="display:none;margin-left:auto;font-size:11px;color:var(--ui-accent);font-weight:600;">
                            <span class="material-symbols-outlined" style="font-size:13px;vertical-align:middle;">volume_up</span>
                            <span id="preview-play-btn" style="cursor:pointer;text-decoration:underline;margin-left:4px;">Dengar</span>
                        </span>
                    </div>
                    <div class="preview-banner-track">
                        <div class="pbi"><span class="material-symbols-outlined">campaign</span></div>
                        <div class="preview-bar">
                            <div class="preview-scroll" id="preview-scroll">
                                <span class="preview-item" id="preview-item-a">
                                    <span class="pi-badge" id="preview-title">Judul</span>
                                    <span class="pi-dot"></span>
                                    <span class="pi-text" id="preview-content">Isi pengumuman...</span>
                                </span>
                                <span class="preview-item" aria-hidden="true">
                                    <span class="pi-badge">Judul</span>
                                    <span class="pi-dot"></span>
                                    <span class="pi-text">Isi pengumuman...</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.announcements.index') }}" class="button button-subtle">Batal</a>
                <button type="submit" class="button button-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    var previewAudio = null;

    function updateSpeedLabel() {
        var s = document.getElementById('scroll_speed').value;
        document.getElementById('speed-label').textContent = s + 's';
        var el = document.getElementById('preview-scroll');
        el.style.animationDuration = s + 's';
        el.style.animationPlayState = 'running';
    }

    function getSelectedSoundUrl() {
        var sel = document.getElementById('announcement_sound_id');
        if (sel.value) {
            var opt = sel.options[sel.selectedIndex];
            return opt.getAttribute('data-url');
        }
        return null;
    }

    function refreshPreview() {
        var title = document.getElementById('title').value.trim() || 'Judul';
        var content = document.getElementById('content').value.trim() || 'Isi pengumuman...';
        var els = document.querySelectorAll('#preview-scroll .pi-badge');
        var cts = document.querySelectorAll('#preview-scroll .pi-text');
        for (var i = 0; i < els.length; i++) els[i].textContent = title;
        for (var i = 0; i < cts.length; i++) cts[i].textContent = content;

        var hasSound = document.getElementById('has_sound').checked &&
            (document.getElementById('announcement_sound_id').value !== '' || document.getElementById('new_sound_file').files.length > 0);
        var badge = document.getElementById('preview-sound-badge');
        if (hasSound) {
            badge.style.display = 'inline';
        } else {
            badge.style.display = 'none';
            if (previewAudio) { previewAudio.pause(); previewAudio = null; }
        }
    }

    document.getElementById('preview-play-btn').addEventListener('click', function() {
        if (previewAudio) {
            previewAudio.pause();
            previewAudio = null;
            return;
        }
        var url = null;
        var sel = document.getElementById('announcement_sound_id');
        if (sel.value) {
            url = getSelectedSoundUrl();
        } else {
            var fileInput = document.getElementById('new_sound_file');
            if (fileInput.files && fileInput.files[0]) {
                url = URL.createObjectURL(fileInput.files[0]);
            }
        }
        if (url) {
            previewAudio = new Audio(url);
            previewAudio.volume = 0.5;
            previewAudio.play().catch(function(){});
            previewAudio.addEventListener('ended', function() { previewAudio = null; });
        }
    });
</script>
@endpush
