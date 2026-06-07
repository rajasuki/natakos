@forelse ($messages as $msg)
    @php
        $initial = strtoupper(substr($msg->user->name, 0, 1));
        $isSelf = $msg->user_id === Auth::id();
        $isEditable = $isSelf && $msg->created_at->diffInMinutes(now()) < 2;
        $isEdited = $msg->created_at->timestamp !== $msg->updated_at->timestamp;
    @endphp
    <div class="chat-message {{ $isSelf ? 'chat-message-self' : '' }}" data-message-id="{{ $msg->id }}">
        <div class="chat-avatar-wrap">
            <div class="chat-avatar" data-user-id="{{ $msg->user_id }}" onclick="openProfilePopup('{{ $msg->user_id }}')">
                @if ($msg->user->avatar)
                    <img src="{{ asset('storage/'.$msg->user->avatar) }}" alt="{{ $msg->user->name }}">
                @else
                    {{ $initial }}
                @endif
            </div>
        </div>
        <div class="chat-bubble">
            <div class="chat-bubble-head">
                @if (!$isSelf)
                    <span class="chat-bubble-name" data-user-id="{{ $msg->user_id }}">{{ $msg->user->name }}</span>
                @endif
                <span class="chat-bubble-time">
                    {{ \App\Support\UiFormatter::date($msg->created_at, 'H:i') }}
                    @if ($isEdited)
                        <span class="chat-edited-badge">· diedit</span>
                    @endif
                </span>
            </div>
            @if ($msg->image)
                <a href="{{ asset('storage/'.$msg->image) }}" target="_blank" class="chat-image-link">
                    <img src="{{ asset('storage/'.$msg->image) }}" alt="Gambar" class="chat-image" loading="lazy">
                </a>
            @endif
            @if ($msg->content)
                <div class="chat-bubble-text" id="msg-text-{{ $msg->id }}">{{ $msg->content }}</div>
            @endif
            @if ($isEditable && $msg->content)
                <div class="chat-edit-form" id="msg-edit-{{ $msg->id }}" style="display:none;">
                    <textarea class="chat-edit-textarea" maxlength="1000">{{ $msg->content }}</textarea>
                    <div class="chat-edit-actions">
                        <button type="button" class="chat-edit-save button button-primary" style="padding:4px 12px;font-size:12px;" onclick="saveEdit({{ $msg->id }})">Simpan</button>
                        <button type="button" class="chat-edit-cancel button button-subtle" style="padding:4px 12px;font-size:12px;" onclick="cancelEdit({{ $msg->id }})">Batal</button>
                        <span class="chat-edit-error" id="msg-edit-error-{{ $msg->id }}" style="color:#be123c;font-size:11px;display:none;"></span>
                    </div>
                </div>
            @endif
            @if ($isSelf)
                <div class="chat-bubble-actions">
                    @if ($isEditable && $msg->content)
                        <button type="button" class="chat-bubble-edit" onclick="startEdit({{ $msg->id }})">Edit</button>
                    @endif
                    <form method="POST" action="{{ route('tenant.chat.destroy', $msg) }}" onsubmit="return confirm('Hapus pesan ini?');" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="chat-bubble-delete">Hapus</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="chat-empty">
        <span class="material-symbols-outlined">forum</span>
        <h3>Belum ada obrolan</h3>
        <p>Mulai percakapan dengan penghuni lain di sini.</p>
    </div>
@endforelse
