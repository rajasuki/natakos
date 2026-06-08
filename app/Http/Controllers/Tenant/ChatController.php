<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use App\Support\UiFormatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = 50;
        $total = ChatMessage::count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page = (int) $request->query('page', $lastPage);

        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $messages = ChatMessage::query()
            ->with('user.tenant.room')
            ->orderBy('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        $latestId = ChatMessage::max('id') ?? 0;

        return view('tenant.chat.index', [
            'messages' => $messages,
            'latestId' => $latestId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isChatBanned()) {
            return redirect()
                ->route('tenant.chat.index')
                ->with('error', 'Akun Anda telah diblokir dari fitur obrolan penghuni.');
        }

        if ($user->isChatMuted()) {
            return redirect()
                ->route('tenant.chat.index')
                ->with('error', 'Anda sedang dalam masa mute dan belum bisa mengirim pesan.');
        }

        $validated = $request->validate([
            'content' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'audio' => ['nullable', 'file', 'mimes:webm,m4a,mp4,ogg,opus,wav,mp3,aac,oga', 'max:3072'],
        ]);

        if (empty($validated['content']) && ! $request->hasFile('image') && ! $request->hasFile('audio')) {
            return redirect()
                ->route('tenant.chat.index')
                ->with('error', 'Pesan, gambar, atau audio wajib diisi.');
        }

        $data = ['user_id' => $user->id];

        if (! empty($validated['content'])) {
            $data['content'] = $validated['content'];
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('chat-images', 'public');
        }

        if ($request->hasFile('audio')) {
            $data['audio'] = $request->file('audio')->store('chat-audio', 'public');
        }

        ChatMessage::create($data);

        return redirect()->route('tenant.chat.index');
    }

    public function update(Request $request, ChatMessage $message): JsonResponse
    {
        if ($message->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Aksi tidak diizinkan.'], 403);
        }

        if ($message->created_at->diffInMinutes(now()) >= 2) {
            return response()->json(['success' => false, 'message' => 'Batas waktu edit 2 menit telah lewat.'], 422);
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $message->update(['content' => $validated['content']]);

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, ChatMessage $message): RedirectResponse
    {
        if ($message->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($message->image) {
            Storage::disk('public')->delete($message->image);
        }

        if ($message->audio) {
            Storage::disk('public')->delete($message->audio);
        }

        $message->delete();

        return redirect()
            ->route('tenant.chat.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }

    public function poll(Request $request): JsonResponse
    {
        $afterId = (int) $request->query('after', 0);

        $messages = ChatMessage::query()
            ->with('user.tenant.room')
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get();

        $html = '';

        $lastMsg = $afterId > 0 ? ChatMessage::find($afterId) : null;
        $prevDate = $lastMsg?->created_at?->startOfDay();

        foreach ($messages as $msg) {
            $msgDate = $msg->created_at->startOfDay();
            if ($prevDate === null || !$msgDate->equalTo($prevDate)) {
                $html .= '<div class="chat-date-separator"><span>'.UiFormatter::chatDateLabel($msg->created_at).'</span></div>';
                $prevDate = $msgDate;
            }

            $initial = strtoupper(substr($msg->user->name, 0, 1));
            $isSelf = $msg->user_id === Auth::id();
            $isEditable = $isSelf && $msg->created_at->diffInMinutes(now()) < 2;
            $isEdited = $msg->created_at->timestamp !== $msg->updated_at->timestamp;

            $avatarHtml = $msg->user->avatar
                ? '<img src="'.asset('storage/'.$msg->user->avatar).'" alt="'.$msg->user->name.'">'
                : e($initial);

            $imageHtml = '';
            if ($msg->image) {
                $imageHtml = '<a href="'.asset('storage/'.$msg->image).'" target="_blank" class="chat-image-link">
                    <img src="'.asset('storage/'.$msg->image).'" alt="Gambar" class="chat-image" loading="lazy">
                </a>';
            }

            $audioHtml = '';
            if ($msg->audio) {
                $audioHtml = '<audio controls class="chat-audio" preload="metadata" style="width:100%;max-width:280px;height:40px;margin:4px 0;">
                    <source src="'.asset('storage/'.$msg->audio).'">
                </audio>';
            }

            $textHtml = $msg->content ? '<div class="chat-bubble-text">'.e($msg->content).'</div>' : '';

            $actionsHtml = '';
            if ($isSelf) {
                $actionsHtml = '<div class="chat-bubble-actions">';
                if ($isEditable && $msg->content) {
                    $actionsHtml .= '<button type="button" class="chat-bubble-edit" onclick="startEdit('.$msg->id.')">Edit</button>';
                }
                $actionsHtml .= '<form method="POST" action="'.route('tenant.chat.destroy', $msg).'" onsubmit="return confirm(\'Hapus pesan ini?\');" style="display:inline;">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="chat-bubble-delete">Hapus</button>
                    </form>
                </div>';
            }

            $editFormHtml = '';
            if ($isEditable && $msg->content) {
                $editFormHtml = '<div class="chat-edit-form" id="msg-edit-'.$msg->id.'" style="display:none;">
                    <textarea class="chat-edit-textarea" maxlength="1000">'.e($msg->content).'</textarea>
                    <div class="chat-edit-actions">
                        <button type="button" class="chat-edit-save button button-primary" style="padding:4px 12px;font-size:12px;" onclick="saveEdit('.$msg->id.')">Simpan</button>
                        <button type="button" class="chat-edit-cancel button button-subtle" style="padding:4px 12px;font-size:12px;" onclick="cancelEdit('.$msg->id.')">Batal</button>
                        <span class="chat-edit-error" id="msg-edit-error-'.$msg->id.'" style="color:#be123c;font-size:11px;display:none;"></span>
                    </div>
                </div>';
            }

            $editedBadge = $isEdited ? '<span class="chat-edited-badge"> · diedit</span>' : '';
            $effect = $msg->user->title_effect ?: 'none';
            $titleHtml = $msg->user->title
                ? '<span class="user-title user-title-'.e($effect).'">'.e($msg->user->title).'</span>'
                : '';
            $roomHtml = (! $isSelf && $msg->user->show_room && $msg->user->tenant && $msg->user->tenant->room)
                ? '<span class="chat-bubble-room">'.e($msg->user->tenant->room->name).'</span>'
                : '';

            $html .= '<div class="chat-message '.($isSelf ? 'chat-message-self' : '').'" data-message-id="'.$msg->id.'">
                <div class="chat-avatar-wrap">
                    <div class="chat-avatar" data-user-id="'.$msg->user_id.'" onclick="openProfilePopup('.$msg->user_id.')">'.$avatarHtml.'</div>
                </div>
                <div class="chat-bubble chat-bubble-'.e($effect).'">
                    <div class="chat-bubble-head">
                        '.($isSelf ? '' : '<span class="chat-bubble-name" data-user-id="'.$msg->user_id.'">'.e($msg->user->name).'</span>'.$roomHtml).'
                        '.$titleHtml.'
                        <span class="chat-bubble-time">'.UiFormatter::date($msg->created_at, 'H:i').$editedBadge.'</span>
                    </div>
                    '.$imageHtml.'
                    '.$audioHtml.'
                    '.$textHtml.'
                    '.$editFormHtml.'
                    '.$actionsHtml.'
                </div>
            </div>';
        }

        return response()->json([
            'messages' => $messages->map(fn ($m) => ['id' => $m->id]),
            'html' => $html,
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        $user = User::with('tenant.room')->findOrFail((int) $request->query('user'));
        $isAdmin = $request->user()?->role === 'admin';

        $roomName = null;
        if ($user->tenant && $user->tenant->room) {
            if ($isAdmin || $user->show_room) {
                $roomName = $user->tenant->room->name;
            }
        }

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'bio' => $user->bio,
            'avatar_url' => $user->avatar ? asset('storage/'.$user->avatar) : null,
            'bg_url' => $user->profile_bg ? asset('storage/'.$user->profile_bg) : null,
            'title' => $user->title,
            'title_effect' => $user->title_effect ?? 'none',
            'room' => $roomName,
            'initial' => strtoupper(substr($user->name, 0, 1)),
        ]);
    }
}
