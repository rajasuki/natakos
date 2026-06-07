@extends('tenant.layout')

@section('title', 'Obrolan Penghuni')

@push('styles')
<style>
    .chat-shell { max-width:720px; margin:0 auto; display:flex; flex-direction:column; gap:16px; }
    .chat-box { background:#fff; border:1px solid var(--ui-border); border-radius:var(--radius-lg); overflow:hidden; display:flex; flex-direction:column; height:calc(100vh - 260px); min-height:400px; }
    .chat-messages { flex:1; overflow-y:auto; padding:16px 20px; display:flex; flex-direction:column; gap:12px; }

    /* ── Message row ── */
    .chat-message { display:flex; gap:10px; align-items:flex-start; max-width:85%; }
    .chat-message-self { align-self:flex-end; flex-direction:row-reverse; }

    .chat-avatar-wrap { flex-shrink:0; }
    .chat-avatar { width:36px; height:36px; border-radius:50%; background:var(--ui-accent); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; overflow:hidden; cursor:pointer; }
    .chat-avatar img { width:100%; height:100%; object-fit:cover; }

    .chat-bubble { flex:1; min-width:0; background:var(--gray-50); border:1px solid var(--ui-border); border-radius:12px 12px 12px 4px; padding:8px 12px; }
    .chat-message-self .chat-bubble { background:#e8f5e9; border-color:#c8e6c9; border-radius:12px 12px 4px 12px; }

    .chat-bubble-head { display:flex; align-items:baseline; gap:6px; margin-bottom:2px; }
    .chat-message-self .chat-bubble-head { justify-content:flex-end; }
    .chat-bubble-name { font-size:12px; font-weight:700; color:var(--ui-accent); cursor:pointer; }
    .chat-bubble-name:hover { text-decoration:underline; }
    .chat-bubble-room { font-size:10px; color:var(--ui-body); }
    .chat-bubble-time { font-size:11px; color:var(--ui-body); white-space:nowrap; }
    .chat-edited-badge { font-size:10px; color:var(--ui-body); font-style:italic; }

    /* ── Title badges ── */
    .user-title { display:inline-block; font-size:10px; font-weight:600; padding:1px 7px; border-radius:4px; margin-left:4px; vertical-align:middle; }
    .user-title-none { background:var(--ui-soft); color:var(--ui-body); }
    .user-title-gold { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 0 8px rgba(245,158,11,.3); }
    .user-title-rainbow { background:linear-gradient(90deg,#f43f5e,#f59e0b,#22c55e,#3b82f6,#a855f7); color:#fff; background-size:200% 100%; animation:rainbowShift 3s linear infinite; }
    @keyframes rainbowShift { 0%{background-position:0% 50%} 100%{background-position:200% 50%} }
    .user-title-glow { background:var(--ui-accent); color:#fff; animation:titleGlow 2s ease-in-out infinite; }
    @keyframes titleGlow { 0%,100%{box-shadow:0 0 4px rgba(74,124,89,.3)} 50%{box-shadow:0 0 14px rgba(74,124,89,.6)} }
    .user-title-fire { background:linear-gradient(135deg,#ef4444,#f97316); color:#fff; box-shadow:0 0 10px rgba(239,68,68,.3); }

    .chat-image { max-width:100%; max-height:300px; border-radius:var(--radius-md); display:block; margin:4px 0; cursor:pointer; }
    .chat-image-link { display:block; }

    .chat-bubble-text { font-size:14px; line-height:1.6; color:var(--ui-ink); word-wrap:break-word; white-space:pre-wrap; }

    .chat-bubble-actions { margin-top:4px; display:flex; gap:8px; justify-content:flex-end; }
    .chat-bubble-actions form { display:inline; }
    .chat-bubble-delete,
    .chat-bubble-edit { font-size:11px; color:var(--ui-body); border:0; background:none; cursor:pointer; padding:0; text-decoration:underline; }
    .chat-bubble-delete:hover { color:#be123c; }
    .chat-bubble-edit:hover { color:var(--ui-accent); }

    /* ── Edit form ── */
    .chat-edit-form { margin-top:6px; }
    .chat-edit-textarea { width:100%; border:1px solid var(--ui-accent); border-radius:var(--radius-md); padding:8px 10px; font-size:14px; resize:none; min-height:60px; font-family:inherit; box-sizing:border-box; }
    .chat-edit-textarea:focus { outline:none; box-shadow:0 0 0 3px rgba(74,124,89,.12); }
    .chat-edit-actions { display:flex; gap:6px; margin-top:6px; align-items:center; }
    .chat-edit-error { flex:1; }

    /* ── Chat form ── */
    .chat-form { border-top:1px solid var(--ui-border); padding:10px 16px; display:flex; gap:10px; align-items:flex-end; }
    .chat-form-left { flex:1; display:flex; flex-direction:column; gap:6px; }
    .chat-form textarea { width:100%; border:1px solid var(--ui-border); border-radius:var(--radius-md); padding:10px 14px; font-size:14px; resize:none; min-height:44px; max-height:120px; font-family:inherit; box-sizing:border-box; }
    .chat-form textarea:focus { outline:none; border-color:var(--ui-accent); box-shadow:0 0 0 3px rgba(74,124,89,.12); }
    .chat-form-right { display:flex; gap:6px; align-items:flex-end; flex-shrink:0; }
    .chat-image-preview { display:flex; align-items:center; gap:8px; padding:6px 10px; background:var(--gray-50); border-radius:var(--radius-md); font-size:12px; color:var(--ui-body); }
    .chat-image-preview img { height:40px; border-radius:4px; }
    .chat-image-preview button { border:0; background:none; cursor:pointer; color:#be123c; font-size:16px; padding:0; line-height:1; }
    .chat-img-btn { border:1px solid var(--ui-border); background:#fff; border-radius:var(--radius-md); width:38px; height:38px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--ui-body); font-size:20px; }
    .chat-img-btn:hover { border-color:var(--ui-accent); color:var(--ui-accent); }

    /* ── Empty state ── */
    .chat-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; gap:12px; color:var(--ui-body); text-align:center; padding:40px 20px; }
    .chat-empty .material-symbols-outlined { font-size:48px; opacity:.4; }
    .chat-empty h3 { margin:0; font-size:16px; color:var(--gray-600); }
    .chat-empty p { margin:0; font-size:13px; }

    /* ── Profile Popup ── */
    .profile-popup-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.3); z-index:999; align-items:center; justify-content:center; }
    .profile-popup-overlay.is-open { display:flex; }
    .profile-popup { background:#fff; border-radius:var(--radius-xl); max-width:380px; width:90%; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.15); animation:popIn .2s ease; }
    @keyframes popIn { from { opacity:0; transform:scale(.95) translateY(10px); } to { opacity:1; transform:scale(1) translateY(0); } }
    .profile-popup-bg { width:100%; height:140px; object-fit:cover; display:block; background:var(--ui-soft); }
    .profile-popup-bg-empty { width:100%; height:140px; background:linear-gradient(135deg, var(--ui-accent), #2d5a3e); display:flex; align-items:center; justify-content:center; color:#fff; font-size:13px; opacity:.6; }
    .profile-popup-body { padding:0 20px 20px; margin-top:-40px; position:relative; }
    .profile-popup-avatar { width:80px; height:80px; border-radius:50%; border:4px solid #fff; background:var(--ui-accent); color:#fff; display:flex; align-items:center; justify-content:center; font-size:32px; font-weight:700; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.1); }
    .profile-popup-avatar img { width:100%; height:100%; object-fit:cover; }
    .profile-popup-name { margin:12px 0 2px; font-size:18px; font-weight:700; color:var(--ui-ink); }
    .profile-popup-room { margin:4px 0 0; font-size:13px; color:var(--ui-accent); font-weight:600; }
    .profile-popup-email { margin:0 0 12px; font-size:13px; color:var(--ui-body); }
    .profile-popup-bio { font-size:14px; line-height:1.6; color:var(--gray-600); white-space:pre-wrap; padding-top:12px; border-top:1px solid var(--ui-border); }
    .profile-popup-bio-empty { font-size:13px; color:var(--ui-body); font-style:italic; padding-top:12px; border-top:1px solid var(--ui-border); }
    .profile-popup-close { position:absolute; top:12px; right:12px; background:rgba(0,0,0,.4); color:#fff; border:0; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:18px; }
</style>
@endpush

@section('content')
    <div class="chat-shell">
        <div class="chat-box">
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
                    <textarea name="content" placeholder="Tulis pesan..." maxlength="1000"></textarea>
                </div>
                <div class="chat-form-right">
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

    {{-- Profile Popup --}}
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

    /* ── Real-time polling ── */
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

    /* ── Profile Popup ── */
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
                    bgEl.className = 'profile-popup-bg-empty';
                    bgEl.textContent = 'Latar profil';
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

    /* Attach click handlers to avatars and names */
    document.querySelectorAll('.chat-avatar, .chat-bubble-name').forEach(function(el) {
        el.addEventListener('click', function(e) {
            var userId = this.getAttribute('data-user-id');
            if (userId) openProfilePopup(userId);
        });
    });

    /* ── Inline Edit ── */
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
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
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
        .catch(function() {
            errorEl.textContent = 'Terjadi kesalahan. Coba lagi.';
            errorEl.style.display = 'inline';
        });
    };

    /* ── Image preview ── */
    window.previewChatImage = function(input) {
        var preview = document.getElementById('chat-image-preview');
        var img = document.getElementById('chat-img-preview-src');
        var name = document.getElementById('chat-img-preview-name');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
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
})();
</script>
@endpush
