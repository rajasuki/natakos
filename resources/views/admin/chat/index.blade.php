@extends('admin.layout')

@section('title', 'Obrolan Penghuni')
@section('eyebrow', 'Admin Obrolan')
@section('page_title', 'Kelola obrolan penghuni')
@section('page_description', 'Pantau dan kelola obrolan antar penghuni kos. Hapus pesan, mute, atau blokir akun yang melanggar.')

@section('content')
    <div style="display:grid; gap:24px;">

        {{-- BANNED USERS --}}
        <section class="card">
            <div class="card-head has-divider">
                <div class="split-actions">
                    <div>
                        <h2 class="card-title">Pengaturan blokir &amp; mute</h2>
                        <p class="card-copy">Blokir atau mute penghuni agar tidak bisa mengirim pesan di obrolan.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.chat.ban') }}" style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--ui-border);">
                    @csrf
                    <div style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
                        <div class="field" style="flex:1;min-width:180px;">
                            <label for="user_id">Penghuni</label>
                            <select id="user_id" name="user_id" class="select" required>
                                <option value="">Pilih penghuni...</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field" style="flex:0 0 120px;">
                            <label for="type">Tipe</label>
                            <select id="type" name="type" class="select" required>
                                <option value="mute">Mute</option>
                                <option value="ban">Blokir</option>
                            </select>
                        </div>
                        <div class="field" style="flex:0 0 180px;">
                            <label for="expires_at">Kadaluarsa</label>
                            <input id="expires_at" name="expires_at" type="datetime-local" class="input">
                            <div class="helper">Kosongkan untuk permanen</div>
                        </div>
                        <div class="field" style="flex:1;min-width:160px;">
                            <label for="reason">Alasan</label>
                            <input id="reason" name="reason" type="text" class="input" maxlength="500" placeholder="Opsional...">
                        </div>
                        <button type="submit" class="button button-danger" style="margin-bottom:16px;">Terapkan</button>
                    </div>
                </form>

                @if ($bans->isNotEmpty())
                    <div style="overflow-x:auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Penghuni</th>
                                    <th>Tipe</th>
                                    <th>Oleh</th>
                                    <th>Alasan</th>
                                    <th>Kadaluarsa</th>
                                    <th>Dibuat</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bans as $ban)
                                    <tr>
                                        <td><strong>{{ $ban->user?->name ?? '-' }}</strong></td>
                                        <td>
                                            <span class="badge {{ $ban->type === 'ban' ? 'badge-rejected' : 'badge-unpaid' }}">
                                                {{ $ban->type === 'ban' ? 'Blokir' : 'Mute' }}
                                            </span>
                                        </td>
                                        <td>{{ $ban->bannedBy?->name ?? '-' }}</td>
                                        <td>{{ $ban->reason ?: '-' }}</td>
                                        <td>{{ $ban->expires_at ? \App\Support\UiFormatter::date($ban->expires_at, 'd M Y H:i') : 'Permanen' }}</td>
                                        <td>{{ \App\Support\UiFormatter::date($ban->created_at, 'd M Y H:i') }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.chat.unban', $ban) }}" onsubmit="return confirm('Hapus hukuman ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="button button-subtle" style="font-size:12px;">Cabut</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <section class="empty-state">
                        <h2>Belum ada hukuman</h2>
                        <p>Tidak ada penghuni yang di-mute atau diblokir saat ini.</p>
                    </section>
                @endif
            </div>
        </section>

        {{-- MESSAGES --}}
        <section class="card">
            <div class="card-head has-divider">
                <div class="split-actions">
                    <div>
                        <h2 class="card-title">Semua pesan</h2>
                        <p class="card-copy">Pantau dan kelola obrolan penghuni. Hapus pesan, mute, atau blokir akun yang melanggar.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.chat.index') }}" class="toolbar-form" style="margin-bottom:0;">
                    <div style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
                        <div class="field" style="flex:1;min-width:160px;">
                            <label for="filter_user">Penghuni</label>
                            <select id="filter_user" name="user_id" class="select">
                                <option value="">Semua penghuni</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" @selected((int)($filters['user_id'] ?? '') === $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field" style="flex:0 0 170px;">
                            <label for="date_from">Dari tanggal</label>
                            <input id="date_from" name="date_from" type="date" class="input" value="{{ $filters['date_from'] ?? '' }}">
                        </div>
                        <div class="field" style="flex:0 0 170px;">
                            <label for="date_to">Sampai tanggal</label>
                            <input id="date_to" name="date_to" type="date" class="input" value="{{ $filters['date_to'] ?? '' }}">
                        </div>
                        <button type="submit" class="button button-primary" style="margin-bottom:16px;">Filter</button>
                        @if (count(array_filter($filters ?? [])))
                            <a href="{{ route('admin.chat.index') }}" class="button button-subtle" style="margin-bottom:16px;">Hapus filter</a>
                        @endif
                    </div>
                </form>

                <div style="margin-top:16px;">
                    @php $prevDate = null; @endphp
                    @forelse ($messages as $msg)
                        @php
                            $msgDate = $msg->created_at->startOfDay();
                        @endphp
                        @if ($prevDate === null || !$msgDate->equalTo($prevDate))
                            @php $prevDate = $msgDate; @endphp
                            <div style="display:flex;align-items:center;gap:12px;margin:16px 0 8px;font-size:11px;font-weight:600;color:var(--gray-400);text-transform:uppercase;letter-spacing:.06em;">
                                <span style="flex:1;height:1px;background:var(--ui-border);"></span>
                                <span>{{ \App\Support\UiFormatter::chatDateLabel($msg->created_at) }}</span>
                                <span style="flex:1;height:1px;background:var(--ui-border);"></span>
                            </div>
                        @endif
                        @php
                            $initial = strtoupper(substr($msg->user->name, 0, 1));
                            $isEdited = $msg->created_at->timestamp !== $msg->updated_at->timestamp;
                            $effect = $msg->user->title_effect ?: 'none';
                            $isOwn = $msg->user_id === Auth::id();
                        @endphp
                        <div style="display:flex; gap:12px; padding:14px 0; {{ !$loop->last ? 'border-bottom:1px solid var(--ui-border);' : '' }} align-items:flex-start; {{ $effect !== 'none' ? "background:var(--gray-50);border-radius:var(--radius-md);padding:14px;margin-bottom:8px;border:1px solid var(--ui-border);" : '' }} {{ $isOwn ? 'background:#f0fdf4;border-radius:var(--radius-md);padding:14px;margin-bottom:8px;border:1px solid #86efac;' : '' }}" class="{{ $effect !== 'none' ? "msg-row-{$effect}" : '' }}">
                            <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;background:{{ $isOwn ? 'var(--gray-600)' : 'var(--ui-accent)' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;overflow:hidden;cursor:pointer;transition:opacity .15s;" data-user-id="{{ $msg->user_id }}" onclick="openProfilePopup(this.dataset.userId)">
                                @if ($msg->user->avatar)
                                    <img src="{{ asset('storage/'.$msg->user->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    {{ $initial }}
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;gap:8px;align-items:baseline;flex-wrap:wrap;">
                                    <strong style="font-size:13px;cursor:pointer;color:{{ $isOwn ? 'var(--gray-600)' : 'var(--ui-accent)' }};" data-user-id="{{ $msg->user_id }}" onclick="openProfilePopup(this.dataset.userId)">{{ $msg->user->name }}</strong>
                                    @if ($isOwn) <span style="font-size:10px;color:var(--gray-400);font-style:italic;">· admin</span> @endif
                                    @if ($msg->user->title)
                                        <span class="user-title user-title-{{ $msg->user->title_effect ?: 'none' }}">{{ $msg->user->title }}</span>
                                    @endif
                                    <span style="font-size:11px;color:var(--gray-400);">{{ \App\Support\UiFormatter::date($msg->created_at, 'd M Y H:i') }}</span>
                                    @if ($isEdited) <span style="font-size:10px;color:var(--gray-400);font-style:italic;">· diedit</span> @endif
                                </div>
                                <div id="admin-msg-text-{{ $msg->id }}" style="font-size:14px;line-height:1.6;margin-top:4px;white-space:pre-wrap;color:var(--ui-ink);">{{ $msg->content }}</div>
                                @if ($msg->image)
                                    <a href="{{ asset('storage/'.$msg->image) }}" target="_blank" style="display:inline-block;margin-top:6px;">
                                        <img src="{{ asset('storage/'.$msg->image) }}" alt="Gambar" style="max-width:100%;max-height:300px;border-radius:var(--radius-md);display:block;border:1px solid var(--ui-border);" loading="lazy">
                                    </a>
                                @endif
                                @if ($msg->audio)
                                    <audio controls class="chat-audio" preload="metadata" style="width:100%;max-width:280px;height:40px;margin-top:6px;">
                                        <source src="{{ asset('storage/'.$msg->audio) }}">
                                    </audio>
                                @endif
                                <form method="POST" action="{{ route('admin.chat.update', $msg) }}" id="admin-msg-edit-{{ $msg->id }}" style="display:none;margin-top:8px;">
                                    @csrf @method('PUT')
                                    <textarea name="content" class="admin-edit-textarea" maxlength="1000" style="width:100%;border:1px solid var(--ui-accent);border-radius:6px;padding:8px 10px;font-size:14px;resize:none;min-height:60px;font-family:inherit;box-sizing:border-box;">{{ $msg->content }}</textarea>
                                    <div style="display:flex;gap:6px;margin-top:6px;">
                                        <button type="submit" class="button button-primary" style="padding:4px 12px;font-size:12px;">Simpan</button>
                                        <button type="button" class="button button-subtle" style="padding:4px 12px;font-size:12px;" onclick="cancelAdminEdit({{ $msg->id }})">Batal</button>
                                    </div>
                                </form>
                            </div>
                            <div style="display:flex;gap:4px;flex-shrink:0;align-items:flex-start;">
                                <button type="button" class="button button-subtle" style="font-size:12px;" onclick="startAdminEdit({{ $msg->id }})">Edit</button>
                                <form method="POST" action="{{ route('admin.chat.destroy', $msg) }}" onsubmit="return confirm('Hapus pesan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="button button-subtle" style="font-size:12px;color:#be123c;">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <section class="empty-state">
                            <h2>Belum ada pesan</h2>
                            <p>Belum ada pesan. Kirim pesan pertama melalui form di bawah.</p>
                        </section>
                    @endforelse
                </div>

                <div style="margin-top:16px;">
                    {{ $messages->appends($filters)->links() }}
                </div>

                {{-- SEND MESSAGE --}}
                <form method="POST" action="{{ route('admin.chat.store') }}" style="margin-top:20px;padding-top:20px;border-top:1px solid var(--ui-border);display:flex;gap:12px;align-items:flex-end;">
                    @csrf
                    <div class="field" style="flex:1;">
                        <label for="chat-input">Kirim pesan sebagai admin</label>
                        <textarea id="chat-input" name="content" class="textarea" rows="2" maxlength="1000" placeholder="Tulis pesan..." required style="min-height:60px;"></textarea>
                    </div>
                    <button type="submit" class="button button-primary" style="margin-bottom:16px;white-space:nowrap;">
                        <span class="material-symbols-outlined" style="font-size:16px;">send</span> Kirim
                    </button>
                </form>
            </div>
        </section>

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

@push('styles')
<style>
    .profile-popup-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.3); z-index:999; align-items:center; justify-content:center; }
    .profile-popup-overlay.is-open { display:flex; }
    .profile-popup { background:#fff; border-radius:var(--radius-xl); max-width:380px; width:90%; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.15); animation:popIn .2s ease; }
    @keyframes popIn { from{transform:scale(.95);opacity:0} to{transform:scale(1);opacity:1} }
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
    .user-title { display:inline-block; font-size:10px; font-weight:600; padding:1px 7px; border-radius:4px; margin-left:4px; vertical-align:middle; }
    .user-title-none { background:var(--ui-soft); color:var(--ui-body); }
    .user-title-gold { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 0 8px rgba(245,158,11,.3); }
    .user-title-rainbow { background:linear-gradient(90deg,#f43f5e,#f59e0b,#22c55e,#3b82f6,#a855f7); color:#fff; background-size:200% 100%; animation:rainbowShift 3s linear infinite; }
    @keyframes rainbowShift { 0%{background-position:0% 50%} 100%{background-position:200% 50%} }
    .user-title-glow { background:var(--ui-accent); color:#fff; animation:titleGlow 2s ease-in-out infinite; }
    @keyframes titleGlow { 0%,100%{box-shadow:0 0 4px rgba(74,124,89,.3)} 50%{box-shadow:0 0 14px rgba(74,124,89,.6)} }
    .user-title-fire { background:linear-gradient(135deg,#ef4444,#f97316); color:#fff; box-shadow:0 0 10px rgba(239,68,68,.3); }
    .user-title-neon { background:#06b6d4; color:#fff; box-shadow:0 0 12px rgba(6,182,212,.6),0 0 4px rgba(236,72,153,.4); text-shadow:0 0 4px rgba(255,255,255,.4); }
    .user-title-ocean { background:linear-gradient(135deg,#0ea5e9,#06b6d4,#14b8a6); color:#fff; }
    .user-title-sunset { background:linear-gradient(135deg,#f43f5e,#fb923c,#fbbf24); color:#fff; }
    .user-title-galaxy { background:linear-gradient(135deg,#4c1d95,#7e22ce,#a855f7); color:#fff; box-shadow:0 0 16px rgba(124,58,237,.4); }
    .user-title-shadow { background:#1f2937; color:#f9fafb; box-shadow:0 4px 12px rgba(0,0,0,.4); }
    .user-title-thunder { background:linear-gradient(135deg,#fbbf24,#3b82f6); color:#1e293b; box-shadow:0 0 12px rgba(251,191,36,.5); }
    .user-title-rose { background:linear-gradient(135deg,#fb7185,#f43f5e,#e11d48); color:#fff; }
    .user-title-ice { background:linear-gradient(135deg,#e0f2fe,#bae6fd,#7dd3fc); color:#0c4a6e; box-shadow:0 0 8px rgba(125,211,252,.4); }
    .user-title-royal { background:linear-gradient(135deg,#7c3aed,#a855f7,#fbbf24); color:#fff; box-shadow:0 0 10px rgba(124,58,237,.3); }
    .user-title-cyber { background:#000; color:#22d3ee; border:1px solid #22d3ee; box-shadow:0 0 10px rgba(34,211,238,.5),inset 0 0 10px rgba(34,211,238,.1); text-shadow:0 0 4px rgba(34,211,238,.6); }

    .msg-row-gold { background:linear-gradient(135deg,#fef3c7,#fde68a); border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-rainbow { background:linear-gradient(135deg,#fce7f3,#fef3c7,#d1fae5,#dbeafe,#f3e8ff); border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-glow { background:#f0fdf4; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-fire { background:#fef2f2; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-neon { background:#ecfeff; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-ocean { background:#f0fdff; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-sunset { background:#fff7ed; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-galaxy { background:#f5f3ff; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-shadow { background:#f3f4f6; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-thunder { background:#fffbeb; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-rose { background:#fff1f2; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-ice { background:#f0f9ff; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-royal { background:#f5f3ff; border-radius:8px; padding:8px 12px; margin:0 -12px; }
    .msg-row-cyber { background:#f0fdfa; border-radius:8px; padding:8px 12px; margin:0 -12px; }
</style>
@endpush

@push('scripts')
<script>
    window.openProfilePopup = function(userId) {
        fetch('{{ route('admin.chat.profile') }}?user=' + userId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var nameEl = document.getElementById('popup-name');
                nameEl.textContent = data.name;

                var titleEl = document.getElementById('popup-title');
                if (data.title) {
                    titleEl.textContent = data.title;
                    titleEl.className = 'user-title user-title-' + (data.title_effect || 'none');
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
                        gold: 'linear-gradient(135deg,#f59e0b,#d97706)',
                        rainbow: 'linear-gradient(135deg,#f43f5e,#f59e0b,#22c55e,#3b82f6,#a855f7)',
                        glow: 'linear-gradient(135deg,#22c55e,#16a34a)',
                        fire: 'linear-gradient(135deg,#ef4444,#f97316)',
                        neon: 'linear-gradient(135deg,#06b6d4,#ec4899)',
                        ocean: 'linear-gradient(135deg,#0ea5e9,#06b6d4,#14b8a6)',
                        sunset: 'linear-gradient(135deg,#f43f5e,#fb923c,#fbbf24)',
                        galaxy: 'linear-gradient(135deg,#4c1d95,#7e22ce,#a855f7)',
                        shadow: 'linear-gradient(135deg,#374151,#111827)',
                        thunder: 'linear-gradient(135deg,#fbbf24,#3b82f6)',
                        rose: 'linear-gradient(135deg,#fb7185,#e11d48)',
                        ice: 'linear-gradient(135deg,#e0f2fe,#7dd3fc,#0ea5e9)',
                        royal: 'linear-gradient(135deg,#7c3aed,#a855f7,#fbbf24)',
                        cyber: 'linear-gradient(135deg,#000,#22d3ee)',
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

    window.startAdminEdit = function(msgId) {
        document.getElementById('admin-msg-text-' + msgId).style.display = 'none';
        document.getElementById('admin-msg-edit-' + msgId).style.display = 'block';
    };
    window.cancelAdminEdit = function(msgId) {
        document.getElementById('admin-msg-text-' + msgId).style.display = 'block';
        document.getElementById('admin-msg-edit-' + msgId).style.display = 'none';
    };
</script>
@endpush
