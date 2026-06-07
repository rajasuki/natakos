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
                            <div style="display:flex;gap:8px;align-items:baseline;">
                                <strong style="font-size:13px;">{{ $msg->user->name }}</strong>
                                <span style="font-size:11px;color:var(--ui-body);">{{ \App\Support\UiFormatter::date($msg->created_at, 'd M Y H:i') }}</span>
                            </div>
                            <div style="font-size:14px;line-height:1.6;margin-top:2px;white-space:pre-wrap;">{{ $msg->content }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.chat.destroy', $msg) }}" onsubmit="return confirm('Hapus pesan ini?');" style="flex-shrink:0;">
                            @csrf @method('DELETE')
                            <button type="submit" class="button button-subtle" style="font-size:12px;color:#be123c;">Hapus</button>
                        </form>
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
