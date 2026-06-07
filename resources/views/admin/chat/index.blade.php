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
                        <h2 class="card-title">Pengaturan blokir & mute</h2>
                        <p class="card-copy">Blokir atau mute penghuni agar tidak bisa mengirim pesan di obrolan.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.chat.ban') }}" class="form-layout" style="margin-bottom:20px;">
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
                        <div class="field" style="flex:0 0 130px;">
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
                        <p class="card-copy">Pesan terbaru dari penghuni. Hapus pesan yang melanggar aturan.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @forelse ($messages as $msg)
                    @php
                        $initial = strtoupper(substr($msg->user->name, 0, 1));
                        $isEdited = $msg->created_at->timestamp !== $msg->updated_at->timestamp;
                    @endphp
                    <div style="display:flex; gap:12px; padding:12px 0; {{ !$loop->last ? 'border-bottom:1px solid var(--ui-border);' : '' }} align-items:flex-start;">
                        <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;background:var(--ui-accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;overflow:hidden;">
                            @if ($msg->user->avatar)
                                <img src="{{ asset('storage/'.$msg->user->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                {{ $initial }}
                            @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;gap:8px;align-items:baseline;flex-wrap:wrap;">
                                <strong style="font-size:13px;">{{ $msg->user->name }}</strong>
                                @if ($msg->user->title)
                                    <span class="user-title user-title-{{ $msg->user->title_effect ?: 'none' }}">{{ $msg->user->title }}</span>
                                @endif
                                <span style="font-size:11px;color:var(--ui-body);">{{ \App\Support\UiFormatter::date($msg->created_at, 'd M Y H:i') }}</span>
                                @if ($isEdited) <span style="font-size:10px;color:var(--ui-body);font-style:italic;">· diedit</span> @endif
                            </div>
                            <div id="admin-msg-text-{{ $msg->id }}" style="font-size:14px;line-height:1.6;margin-top:2px;white-space:pre-wrap;">{{ $msg->content }}</div>
                            <form method="POST" action="{{ route('admin.chat.update', $msg) }}" id="admin-msg-edit-{{ $msg->id }}" style="display:none;margin-top:6px;">
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
                        <p>Penghuni belum mengirim pesan apapun.</p>
                    </section>
                @endforelse

                <div style="margin-top:16px;">
                    {{ $messages->links() }}
                </div>
            </div>
        </section>

    </div>
@endsection

@push('styles')
<style>
    .user-title { display:inline-block; font-size:10px; font-weight:600; padding:1px 7px; border-radius:4px; margin-left:4px; vertical-align:middle; }
    .user-title-none { background:var(--ui-soft); color:var(--ui-body); }
    .user-title-gold { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 0 8px rgba(245,158,11,.3); }
    .user-title-rainbow { background:linear-gradient(90deg,#f43f5e,#f59e0b,#22c55e,#3b82f6,#a855f7); color:#fff; background-size:200% 100%; animation:rainbowShift 3s linear infinite; }
    @keyframes rainbowShift { 0%{background-position:0% 50%} 100%{background-position:200% 50%} }
    .user-title-glow { background:var(--ui-accent); color:#fff; animation:titleGlow 2s ease-in-out infinite; }
    @keyframes titleGlow { 0%,100%{box-shadow:0 0 4px rgba(74,124,89,.3)} 50%{box-shadow:0 0 14px rgba(74,124,89,.6)} }
    .user-title-fire { background:linear-gradient(135deg,#ef4444,#f97316); color:#fff; box-shadow:0 0 10px rgba(239,68,68,.3); }
</style>
@endpush

@push('scripts')
<script>
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
