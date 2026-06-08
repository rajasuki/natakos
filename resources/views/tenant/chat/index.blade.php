@extends('tenant.layout')

@section('title', 'Obrolan Penghuni')

@push('styles')
<style>
    .chat-shell { max-width:780px; margin:0 auto; display:flex; flex-direction:column; gap:16px; }
    .chat-box { background:#fff; border:1px solid var(--ui-border); border-radius:var(--radius-xl); overflow:hidden; display:flex; flex-direction:column; height:calc(100vh - 220px); height:calc(100dvh - 220px); min-height:420px;box-shadow:0 2px 12px rgba(0,0,0,.04); }

    .chat-header {
        display:flex; align-items:center; gap:12px; padding:14px 20px;
        border-bottom:1px solid var(--ui-border); background:var(--gray-50);
    }
    .chat-header-icon {
        width:36px;height:36px;border-radius:10px;
        background:var(--ui-accent);color:#fff;
        display:flex;align-items:center;justify-content:center;flex-shrink:0;
    }
    .chat-header-icon .material-symbols-outlined { font-size:18px;font-variation-settings:'FILL'1; }
    .chat-header-info { flex:1;min-width:0; }
    .chat-header-title { font-size:15px;font-weight:700;color:var(--ui-ink);margin:0; }
    .chat-header-meta { font-size:12px;color:var(--ui-body);margin:0; }

    .chat-messages {
        flex:1;overflow-y:auto;padding:16px 20px;
        display:flex;flex-direction:column;gap:12px;
        scroll-behavior:smooth;
    }
    .chat-messages::-webkit-scrollbar { width:5px; }
    .chat-messages::-webkit-scrollbar-track { background:transparent; }
    .chat-messages::-webkit-scrollbar-thumb { background:var(--ui-border);border-radius:999px; }

    .chat-message { display:flex;gap:10px;align-items:flex-start;max-width:88%; }
    .chat-message-self { align-self:flex-end;flex-direction:row-reverse; }

    .chat-avatar-wrap { flex-shrink:0; }
    .chat-avatar { width:34px;height:34px;border-radius:50%;background:var(--ui-accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;overflow:hidden;cursor:pointer;transition:opacity .15s; }
    .chat-avatar:hover { opacity:.8; }
    .chat-avatar img { width:100%;height:100%;object-fit:cover; }

    .chat-bubble { flex:1;min-width:0;background:#f3f6f4;border-radius:14px 14px 14px 4px;padding:10px 14px;position:relative; }
    .chat-message-self .chat-bubble {
        background:var(--ui-accent-soft);
        border-radius:14px 14px 4px 14px;
    }

    .chat-bubble-head { display:flex;align-items:baseline;gap:6px;margin-bottom:3px;flex-wrap:wrap; }
    .chat-message-self .chat-bubble-head { justify-content:flex-end; }
    .chat-bubble-name { font-size:12px;font-weight:700;color:var(--ui-accent);cursor:pointer; }
    .chat-bubble-name:hover { text-decoration:underline; }
    .chat-bubble-room { font-size:10px;color:var(--ui-body);background:var(--gray-50);padding:1px 6px;border-radius:4px; }
    .chat-bubble-time { font-size:10px;color:var(--gray-400);white-space:nowrap; }
    .chat-edited-badge { font-size:10px;color:var(--gray-400);font-style:italic; }

    .user-title { display:inline-block;font-size:9px;font-weight:600;padding:1px 6px;border-radius:4px;margin-left:2px;vertical-align:middle; }
    .user-title-none { background:var(--ui-soft);color:var(--ui-body); }
    .user-title-gold { background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 0 8px rgba(245,158,11,.3); }
    .user-title-rainbow { background:linear-gradient(90deg,#f43f5e,#f59e0b,#22c55e,#3b82f6,#a855f7);color:#fff;background-size:200% 100%;animation:rainbowShift 3s linear infinite; }
    @keyframes rainbowShift { 0%{background-position:0% 50%} 100%{background-position:200% 50%} }
    .user-title-glow { background:var(--ui-accent);color:#fff;animation:titleGlow 2s ease-in-out infinite; }
    @keyframes titleGlow { 0%,100%{box-shadow:0 0 4px rgba(74,124,89,.3)} 50%{box-shadow:0 0 14px rgba(74,124,89,.6)} }
    .user-title-fire { background:linear-gradient(135deg,#ef4444,#f97316);color:#fff;box-shadow:0 0 10px rgba(239,68,68,.3); }
    .user-title-neon { background:#06b6d4;color:#fff;box-shadow:0 0 12px rgba(6,182,212,.6),0 0 4px rgba(236,72,153,.4);text-shadow:0 0 4px rgba(255,255,255,.4); }
    .user-title-ocean { background:linear-gradient(135deg,#0ea5e9,#06b6d4,#14b8a6);color:#fff; }
    .user-title-sunset { background:linear-gradient(135deg,#f43f5e,#fb923c,#fbbf24);color:#fff; }
    .user-title-galaxy { background:linear-gradient(135deg,#4c1d95,#7e22ce,#a855f7);color:#fff;box-shadow:0 0 16px rgba(124,58,237,.4); }
    .user-title-shadow { background:#1f2937;color:#f9fafb;box-shadow:0 4px 12px rgba(0,0,0,.4); }
    .user-title-thunder { background:linear-gradient(135deg,#fbbf24,#3b82f6);color:#1e293b;box-shadow:0 0 12px rgba(251,191,36,.5); }
    .user-title-rose { background:linear-gradient(135deg,#fb7185,#f43f5e,#e11d48);color:#fff; }
    .user-title-ice { background:linear-gradient(135deg,#e0f2fe,#bae6fd,#7dd3fc);color:#0c4a6e;box-shadow:0 0 8px rgba(125,211,252,.4); }
    .user-title-royal { background:linear-gradient(135deg,#7c3aed,#a855f7,#fbbf24);color:#fff;box-shadow:0 0 10px rgba(124,58,237,.3); }
    .user-title-cyber { background:#000;color:#22d3ee;border:1px solid #22d3ee;box-shadow:0 0 10px rgba(34,211,238,.5),inset 0 0 10px rgba(34,211,238,.1);text-shadow:0 0 4px rgba(34,211,238,.6); }

    .chat-audio { width:100%;max-width:260px;height:36px;margin:4px 0;border-radius:6px; }
    .chat-image { max-width:100%;max-height:260px;border-radius:var(--radius-md);display:block;margin:4px 0;cursor:pointer; }
    .chat-image-link { display:block; }

    .chat-bubble-actions { margin-top:6px;display:flex;gap:8px;justify-content:flex-end; }
    .chat-bubble-actions form { display:inline; }
    .chat-bubble-delete,
    .chat-bubble-edit { font-size:11px;color:var(--gray-400);border:0;background:none;cursor:pointer;padding:0;text-decoration:underline;transition:color .15s; }
    .chat-bubble-delete:hover { color:#be123c; }
    .chat-bubble-edit:hover { color:var(--ui-accent); }

    .chat-edit-form { margin-top:6px; }
    .chat-edit-textarea { width:100%;border:1px solid var(--ui-accent);border-radius:var(--radius-md);padding:8px 10px;font-size:14px;resize:none;min-height:60px;font-family:inherit;box-sizing:border-box; }
    .chat-edit-textarea:focus { outline:none;box-shadow:0 0 0 3px rgba(74,124,89,.12); }
    .chat-edit-actions { display:flex;gap:6px;margin-top:6px;align-items:center; }
    .chat-edit-error { flex:1; }

    .chat-form { border-top:1px solid var(--ui-border);padding:12px 16px;display:flex;gap:10px;align-items:flex-end;background:#fff; }
    .chat-form-left { flex:1;display:flex;flex-direction:column;gap:6px; }
    .chat-form textarea {
        width:100%;border:1px solid var(--ui-border);border-radius:var(--radius-md);padding:10px 14px;
        font-size:14px;resize:none;min-height:44px;max-height:120px;font-family:inherit;box-sizing:border-box;
        transition:border-color .15s,box-shadow .15s;
    }
    .chat-form textarea:focus { outline:none;border-color:var(--ui-accent);box-shadow:0 0 0 3px rgba(74,124,89,.12); }
    .chat-form-right { display:flex;gap:6px;align-items:flex-end;flex-shrink:0; }
    .chat-image-preview { display:flex;align-items:center;gap:8px;padding:6px 10px;background:var(--gray-50);border-radius:var(--radius-md);font-size:12px;color:var(--ui-body); }
    .chat-image-preview img { height:40px;border-radius:4px; }
    .chat-image-preview button { border:0;background:none;cursor:pointer;color:#be123c;font-size:16px;padding:0;line-height:1; }
    .chat-img-btn { border:1px solid var(--ui-border);background:#fff;border-radius:var(--radius-md);width:38px;height:38px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--ui-body);font-size:20px;transition:border-color .15s,color .15s; }
    .chat-img-btn:hover { border-color:var(--ui-accent);color:var(--ui-accent); }

    /* ── Voice Recorder ── */
    .chat-mic-btn { border:1px solid var(--ui-border);background:#fff;border-radius:var(--radius-md);width:38px;height:38px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--ui-body);font-size:20px;transition:border-color .15s,color .15s;position:relative; }
    .chat-mic-btn:hover { border-color:var(--ui-accent);color:var(--ui-accent); }
    .chat-mic-btn.is-recording { border-color:#be123c;color:#be123c;background:#fff1f2;animation:micPulse 1.2s ease-in-out infinite; }
    @keyframes micPulse { 0%,100%{box-shadow:0 0 0 0 rgba(190,18,60,.2)} 50%{box-shadow:0 0 0 6px rgba(190,18,60,.12)} }
    .chat-mic-btn:disabled { opacity:.4;cursor:not-allowed; }

    .chat-audio-preview { display:flex;align-items:center;gap:8px;padding:6px 10px;background:var(--gray-50);border-radius:var(--radius-md);font-size:12px;color:var(--ui-body); }
    .chat-audio-preview audio { height:36px;flex:1;min-width:0; }
    .chat-audio-preview button { border:0;background:none;cursor:pointer;color:#be123c;font-size:16px;padding:0;line-height:1; }

    .chat-recording-indicator { display:none;align-items:center;gap:8px;padding:6px 10px;background:#fff1f2;border-radius:var(--radius-md);font-size:12px;color:#be123c;font-weight:600; }
    .chat-recording-indicator.is-recording { display:flex; }
    .chat-recording-dot { width:8px;height:8px;border-radius:50%;background:#be123c;animation:recDot 1s ease-in-out infinite; }
    @keyframes recDot { 0%,100%{opacity:1} 50%{opacity:.3} }
    .chat-recording-time { font-weight:400;color:var(--ui-body); }
    .chat-recording-stop { border:0;background:#be123c;color:#fff;border-radius:999px;padding:3px 12px;font-size:11px;font-weight:600;cursor:pointer;transition:background .15s; }
    .chat-recording-stop:hover { background:#9f1239; }

    .chat-empty { display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;gap:12px;color:var(--ui-body);text-align:center;padding:40px 20px; }
    .chat-empty .material-symbols-outlined { font-size:52px;opacity:.25;color:var(--gray-300); }
    .chat-empty h3 { margin:0;font-size:16px;color:var(--gray-600);font-weight:600; }
    .chat-empty p { margin:0;font-size:13px; }

    /* ── Bubble effects ── */
    .chat-bubble-gold { background:linear-gradient(135deg,#fef3c7,#fde68a); }
    .chat-message-self .chat-bubble-gold { background:linear-gradient(135deg,#fef3c7,#fde68a); }
    .chat-bubble-rainbow { background:linear-gradient(135deg,#fce7f3,#fef3c7,#d1fae5,#dbeafe,#f3e8ff); }
    .chat-message-self .chat-bubble-rainbow { background:linear-gradient(135deg,#fce7f3,#fef3c7,#d1fae5,#dbeafe,#f3e8ff); }
    .chat-bubble-glow { background:#e8f5e9; }
    .chat-message-self .chat-bubble-glow { background:#e8f5e9; }
    .chat-bubble-fire { background:#fef2f2; }
    .chat-message-self .chat-bubble-fire { background:#fef2f2; }
    .chat-bubble-neon { background:#e0f7fa; }
    .chat-message-self .chat-bubble-neon { background:#e0f7fa; }
    .chat-bubble-ocean { background:#e0f7ff; }
    .chat-message-self .chat-bubble-ocean { background:#e0f7ff; }
    .chat-bubble-sunset { background:#fff3e0; }
    .chat-message-self .chat-bubble-sunset { background:#fff3e0; }
    .chat-bubble-galaxy { background:#ede9fe; }
    .chat-message-self .chat-bubble-galaxy { background:#ede9fe; }
    .chat-bubble-shadow { background:#f3f4f6; }
    .chat-message-self .chat-bubble-shadow { background:#f3f4f6; }
    .chat-bubble-thunder { background:#fffbeb; }
    .chat-message-self .chat-bubble-thunder { background:#fffbeb; }
    .chat-bubble-rose { background:#ffe4e6; }
    .chat-message-self .chat-bubble-rose { background:#ffe4e6; }
    .chat-bubble-ice { background:#e0f2fe; }
    .chat-message-self .chat-bubble-ice { background:#e0f2fe; }
    .chat-bubble-royal { background:#ede9fe; }
    .chat-message-self .chat-bubble-royal { background:#ede9fe; }
    .chat-bubble-cyber { background:#ecfdf5; }
    .chat-message-self .chat-bubble-cyber { background:#ecfdf5; }

    /* ── Profile Popup ── */
    .profile-popup-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,.3);z-index:999;align-items:center;justify-content:center; }
    .profile-popup-overlay.is-open { display:flex; }
    .profile-popup { background:#fff;border-radius:var(--radius-xl);max-width:380px;width:90%;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.15);animation:popIn .2s ease; }
    @keyframes popIn { from{opacity:0;transform:scale(.95)} to{opacity:1;transform:scale(1)} }
    .profile-popup-bg { width:100%;height:140px;object-fit:cover;display:block;background:var(--ui-soft); }
    .profile-popup-bg-empty { width:100%;height:140px;background:linear-gradient(135deg,var(--ui-accent),#2d5a3e);display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;opacity:.6; }
    .profile-popup-body { padding:0 20px 20px;margin-top:-40px;position:relative; }
    .profile-popup-avatar { width:80px;height:80px;border-radius:50%;border:4px solid #fff;background:var(--ui-accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1); }
    .profile-popup-avatar img { width:100%;height:100%;object-fit:cover; }
    .profile-popup-name { margin:12px 0 2px;font-size:18px;font-weight:700;color:var(--ui-ink); }
    .profile-popup-room { margin:4px 0 0;font-size:13px;color:var(--ui-accent);font-weight:600; }
    .profile-popup-email { margin:0 0 12px;font-size:13px;color:var(--ui-body); }
    .profile-popup-bio { font-size:14px;line-height:1.6;color:var(--gray-600);white-space:pre-wrap;padding-top:12px;border-top:1px solid var(--ui-border); }
    .profile-popup-bio-empty { font-size:13px;color:var(--ui-body);font-style:italic;padding-top:12px;border-top:1px solid var(--ui-border); }
    .profile-popup-close { position:absolute;top:12px;right:12px;background:rgba(0,0,0,.4);color:#fff;border:0;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:18px; }

    @media (max-width: 500px) {
        .chat-box { min-height:calc(100dvh - 180px);height:calc(100dvh - 160px) !important;border-radius:0;border-left:0;border-right:0; }
        .chat-shell { gap:0; }
        .chat-messages { padding:12px 12px;gap:10px; }
        .chat-message { max-width:92%; }
        .chat-form { padding:8px 10px;gap:6px; }
        .chat-form textarea { font-size:16px;padding:8px 10px; }
        .chat-header { padding:10px 14px; }
        .chat-bubble { padding:8px 12px;border-radius:12px 12px 12px 4px; }
        .chat-message-self .chat-bubble { border-radius:12px 12px 4px 12px; }
        .chat-audio-preview { flex-wrap:wrap; }
        .chat-audio-preview audio { max-width:100%; }
    }
</style>
@endpush

@section('content')
    <div class="chat-shell">
        <div class="chat-box">
            <div class="chat-header">
                <div class="chat-header-icon">
                    <span class="material-symbols-outlined">forum</span>
                </div>
                <div class="chat-header-info">
                    <h2 class="chat-header-title">Obrolan Penghuni</h2>
                    <p class="chat-header-meta">Terhubung dengan penghuni kos lainnya</p>
                </div>
            </div>

            <div class="chat-messages" id="chat-messages">
                @include('tenant.chat._messages')
            </div>

            <form method="POST" action="{{ route('tenant.chat.store') }}" class="chat-form" id="chat-form" enctype="multipart/form-data">
                @csrf
                <div class="chat-form-left">
                    <div id="chat-image-preview" class="chat-image-preview" style="display:none;">
                        <img id="chat-img-preview-src" src="" alt="">
                        <span id="chat-img-preview-name"></span>
                        <button type="button" onclick="clearChatImage()">&times;</button>
                    </div>
                    <div id="chat-audio-preview" class="chat-audio-preview" style="display:none;">
                        <audio id="chat-audio-preview-player" controls></audio>
                        <span style="flex-shrink:0;color:var(--ui-body);">Voice note</span>
                        <button type="button" onclick="clearChatAudio()">&times;</button>
                    </div>
                    <div id="chat-recording-indicator" class="chat-recording-indicator">
                        <span class="chat-recording-dot"></span>
                        Merekam
                        <span class="chat-recording-time" id="chat-recording-time">0:00</span>
                        <button type="button" class="chat-recording-stop" onclick="stopRecording()">Berhenti</button>
                    </div>
                    <textarea name="content" placeholder="Tulis pesan..." maxlength="1000"></textarea>
                </div>
                <div class="chat-form-right">
                    <button type="button" class="chat-mic-btn" id="chat-mic-btn" title="Rekam suara" onclick="toggleRecording()">
                        <span class="material-symbols-outlined" style="font-size:20px;">mic</span>
                    </button>
                    <input id="chat-audio-input" name="audio" type="file" accept="audio/*" hidden>
                    <label for="chat-image-input" class="chat-img-btn" title="Kirim gambar/GIF">
                        <span class="material-symbols-outlined" style="font-size:20px;">image</span>
                    </label>
                    <input id="chat-image-input" name="image" type="file" accept="image/*" hidden onchange="previewChatImage(this)">
                    <button type="submit" class="button button-primary" style="height:38px;">Kirim</button>
                </div>
            </form>
        </div>

        @if ($messages->hasPages())
            <div style="text-align:center;">
                {{ $messages->links() }}
            </div>
        @endif
    </div>

    <div class="profile-popup-overlay" id="profile-popup" onclick="if(event.target===this)closeProfilePopup()">
        <div class="profile-popup" id="profile-popup-inner">
            <div id="popup-bg" class="profile-popup-bg-empty">Latar profil</div>
            <button class="profile-popup-close" onclick="closeProfilePopup()">&times;</button>
            <div class="profile-popup-body">
                <div class="profile-popup-avatar" id="popup-avatar">?</div>
                <h3 class="profile-popup-name" id="popup-name">-</h3>
                <span class="user-title" id="popup-title" style="display:none;"></span>
                <p class="profile-popup-room" id="popup-room" style="display:none;"></p>
                <p class="profile-popup-email" id="popup-email">-</p>
                <div class="profile-popup-bio" id="popup-bio" style="display:none;"></div>
                <div class="profile-popup-bio-empty" id="popup-bio-empty">Belum ada bio.</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function() {
    var container = document.getElementById('chat-messages');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    var lastId = {{ $latestId }};
    var chatBox = document.getElementById('chat-messages');
    var pollInterval = 5000;

    function poll() {
        fetch('{{ route('tenant.chat.poll') }}?after=' + lastId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.messages || data.messages.length === 0) return;
                var isNearBottom = chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 60;
                chatBox.insertAdjacentHTML('beforeend', data.html);
                if (data.messages.length > 0) {
                    lastId = data.messages[data.messages.length - 1].id;
                }
                if (isNearBottom) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            })
            .catch(function() {});
    }

    setInterval(poll, pollInterval);

    window.openProfilePopup = function(userId) {
        fetch('{{ route('tenant.chat.profile') }}?user=' + userId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                document.getElementById('popup-name').textContent = data.name;
                document.getElementById('popup-email').textContent = data.email;

                var titleEl = document.getElementById('popup-title');
                if (data.title) {
                    titleEl.textContent = data.title;
                    titleEl.className = 'user-title user-title-' + data.title_effect;
                    titleEl.style.display = 'inline-block';
                } else {
                    titleEl.style.display = 'none';
                }

                var roomEl = document.getElementById('popup-room');
                if (data.room) {
                    roomEl.textContent = data.room;
                    roomEl.style.display = 'block';
                } else {
                    roomEl.style.display = 'none';
                }

                var avatarEl = document.getElementById('popup-avatar');
                if (data.avatar_url) {
                    avatarEl.innerHTML = '<img src="' + data.avatar_url + '" alt="' + data.name + '">';
                } else {
                    avatarEl.textContent = data.initial;
                }

                var bgEl = document.getElementById('popup-bg');
                if (data.bg_url) {
                    bgEl.className = 'profile-popup-bg';
                    bgEl.innerHTML = '<img src="' + data.bg_url + '" alt="" style="width:100%;height:100%;object-fit:cover;">';
                } else {
                    var effect = data.title_effect || 'none';
                    var gradients = {
                        gold: 'linear-gradient(135deg,#f59e0b,#d97706)', rainbow: 'linear-gradient(135deg,#f43f5e,#f59e0b,#22c55e,#3b82f6,#a855f7)', glow: 'linear-gradient(135deg,#22c55e,#16a34a)', fire: 'linear-gradient(135deg,#ef4444,#f97316)',
                        neon: 'linear-gradient(135deg,#06b6d4,#ec4899)', ocean: 'linear-gradient(135deg,#0ea5e9,#06b6d4,#14b8a6)', sunset: 'linear-gradient(135deg,#f43f5e,#fb923c,#fbbf24)', galaxy: 'linear-gradient(135deg,#4c1d95,#7e22ce,#a855f7)',
                        shadow: 'linear-gradient(135deg,#374151,#111827)', thunder: 'linear-gradient(135deg,#fbbf24,#3b82f6)', rose: 'linear-gradient(135deg,#fb7185,#e11d48)', ice: 'linear-gradient(135deg,#e0f2fe,#7dd3fc,#0ea5e9)',
                        royal: 'linear-gradient(135deg,#7c3aed,#a855f7,#fbbf24)', cyber: 'linear-gradient(135deg,#000,#22d3ee)',
                    };
                    bgEl.className = 'profile-popup-bg-empty';
                    bgEl.textContent = 'Latar profil';
                    bgEl.style.background = gradients[effect] || '';
                }

                var bioEl = document.getElementById('popup-bio');
                var bioEmptyEl = document.getElementById('popup-bio-empty');
                if (data.bio) {
                    bioEl.textContent = data.bio;
                    bioEl.style.display = 'block';
                    bioEmptyEl.style.display = 'none';
                } else {
                    bioEl.style.display = 'none';
                    bioEmptyEl.style.display = 'block';
                }

                document.getElementById('profile-popup').classList.add('is-open');
            })
            .catch(function() {});
    };

    window.closeProfilePopup = function() {
        document.getElementById('profile-popup').classList.remove('is-open');
    };

    document.querySelectorAll('.chat-avatar, .chat-bubble-name').forEach(function(el) {
        el.addEventListener('click', function(e) {
            var userId = this.getAttribute('data-user-id');
            if (userId) openProfilePopup(userId);
        });
    });

    window.startEdit = function(msgId) {
        var textEl = document.getElementById('msg-text-' + msgId);
        var editForm = document.getElementById('msg-edit-' + msgId);
        if (textEl) textEl.style.display = 'none';
        if (editForm) editForm.style.display = 'block';
    };

    window.cancelEdit = function(msgId) {
        var textEl = document.getElementById('msg-text-' + msgId);
        var editForm = document.getElementById('msg-edit-' + msgId);
        var errorEl = document.getElementById('msg-edit-error-' + msgId);
        if (textEl) textEl.style.display = 'block';
        if (editForm) editForm.style.display = 'none';
        if (errorEl) { errorEl.textContent = ''; errorEl.style.display = 'none'; }
    };

    window.saveEdit = function(msgId) {
        var textarea = document.querySelector('#msg-edit-' + msgId + ' .chat-edit-textarea');
        var errorEl = document.getElementById('msg-edit-error-' + msgId);
        var content = textarea.value.trim();
        if (!content) {
            errorEl.textContent = 'Pesan tidak boleh kosong.';
            errorEl.style.display = 'inline';
            return;
        }
        fetch('/tenant/chat/' + msgId, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ content: content }),
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                var textEl = document.getElementById('msg-text-' + msgId);
                if (textEl) textEl.textContent = content;
                cancelEdit(msgId);
            } else {
                errorEl.textContent = data.message || 'Gagal menyimpan.';
                errorEl.style.display = 'inline';
            }
        })
        .catch(function() { errorEl.textContent = 'Terjadi kesalahan. Coba lagi.'; errorEl.style.display = 'inline'; });
    };

    window.previewChatImage = function(input) {
        var preview = document.getElementById('chat-image-preview');
        var img = document.getElementById('chat-img-preview-src');
        var name = document.getElementById('chat-img-preview-name');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { img.src = e.target.result; };
            reader.readAsDataURL(input.files[0]);
            name.textContent = input.files[0].name;
            preview.style.display = 'flex';
        }
    };

    window.clearChatImage = function() {
        var input = document.getElementById('chat-image-input');
        input.value = '';
        document.getElementById('chat-image-preview').style.display = 'none';
    };

    /* ── Voice Recorder ── */
    var mediaRecorder = null;
    var audioChunks = [];
    var recordingTimer = null;
    var recordingSeconds = 0;
    var isRecording = false;

    window.toggleRecording = function() {
        if (isRecording) {
            stopRecording();
        } else {
            startRecording();
        }
    };

    function startRecording() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Browser tidak mendukung perekaman suara.');
            return;
        }
        if (typeof MediaRecorder === 'undefined') {
            alert('Browser tidak mendukung perekaman suara.');
            return;
        }
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(function(stream) {
                isRecording = true;
                audioChunks = [];
                recordingSeconds = 0;
                document.getElementById('chat-recording-time').textContent = '0:00';
                document.getElementById('chat-recording-indicator').classList.add('is-recording');
                document.getElementById('chat-mic-btn').classList.add('is-recording');

                var mimeType = 'audio/webm;codecs=opus';
                if (!MediaRecorder.isTypeSupported(mimeType)) {
                    mimeType = 'audio/webm';
                }
                if (!MediaRecorder.isTypeSupported(mimeType)) {
                    mimeType = 'audio/mp4';
                }
                if (!MediaRecorder.isTypeSupported(mimeType)) {
                    mimeType = '';
                }
                var options = mimeType ? { mimeType: mimeType } : {};

                mediaRecorder = new MediaRecorder(stream, options);
                mediaRecorder.ondataavailable = function(e) {
                    if (e.data.size > 0) audioChunks.push(e.data);
                };
                mediaRecorder.onstop = function() {
                    stream.getTracks().forEach(function(t) { t.stop(); });
                    var blobType = mediaRecorder.mimeType || 'audio/webm';
                    var ext = blobType.indexOf('mp4') !== -1 ? 'm4a' : 'webm';
                    var blob = new Blob(audioChunks, { type: blobType });
                    var file = new File([blob], 'voice-' + Date.now() + '.' + ext, { type: blobType });
                    showAudioPreview(file);
                };
                mediaRecorder.onerror = function() {
                    stopRecording();
                    alert('Gagal merekam suara. Coba lagi.');
                };
                mediaRecorder.start();
                recordingTimer = setInterval(function() {
                    recordingSeconds++;
                    var m = Math.floor(recordingSeconds / 60);
                    var s = recordingSeconds % 60;
                    document.getElementById('chat-recording-time').textContent = m + ':' + (s < 10 ? '0' : '') + s;
                }, 1000);
            })
            .catch(function(err) {
                if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                    alert('Izin mikrofon ditolak. Izinkan akses mikrofon di pengaturan browser.');
                } else {
                    alert('Tidak dapat mengakses mikrofon. Pastikan perangkat memiliki mikrofon.');
                }
            });
    }

    window.stopRecording = function() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
        }
        clearInterval(recordingTimer);
        isRecording = false;
        document.getElementById('chat-recording-indicator').classList.remove('is-recording');
        document.getElementById('chat-mic-btn').classList.remove('is-recording');
    };

    function showAudioPreview(file) {
        var input = document.getElementById('chat-audio-input');
        var container = new DataTransfer();
        container.items.add(file);
        input.files = container.files;

        var url = URL.createObjectURL(file);
        var player = document.getElementById('chat-audio-preview-player');
        player.src = url;
        document.getElementById('chat-audio-preview').style.display = 'flex';
    }

    window.clearChatAudio = function() {
        document.getElementById('chat-audio-input').value = '';
        document.getElementById('chat-audio-preview').style.display = 'none';
        document.getElementById('chat-audio-preview-player').src = '';
    };
})();
</script>
@endpush
