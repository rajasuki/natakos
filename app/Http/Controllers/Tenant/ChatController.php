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
        $page = (int) $request->query('page', 1);
        $total = ChatMessage::count();
        $lastPage = max(1, (int) ceil($total / $perPage));

        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $messages = ChatMessage::query()
            ->with('user')
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
        ]);

        if (empty($validated['content']) && ! $request->hasFile('image')) {
            return redirect()
                ->route('tenant.chat.index')
                ->with('error', 'Pesan atau gambar wajib diisi.');
        }

        $data = ['user_id' => $user->id];

        if (! empty($validated['content'])) {
            $data['content'] = $validated['content'];
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('chat-images', 'public');
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

        $message->delete();

        return redirect()
            ->route('tenant.chat.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }

    public function poll(Request $request): JsonResponse
    {
        $afterId = (int) $request->query('after', 0);

        $messages = ChatMessage::query()
            ->with('user')
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get();

        $html = '';

        foreach ($messages as $msg) {
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

            $html .= '<div class="chat-message '.($isSelf ? 'chat-message-self' : '').'" data-message-id="'.$msg->id.'">
                <div class="chat-avatar-wrap">
                    <div class="chat-avatar" data-user-id="'.$msg->user_id.'" onclick="openProfilePopup('.$msg->user_id.')">'.$avatarHtml.'</div>
                </div>
                <div class="chat-bubble">
                    <div class="chat-bubble-head">
                        '.($isSelf ? '' : '<span class="chat-bubble-name" data-user-id="'.$msg->user_id.'">'.e($msg->user->name).'</span>').'
                        <span class="chat-bubble-time">'.UiFormatter::date($msg->created_at, 'H:i').$editedBadge.'</span>
                    </div>
                    '.$imageHtml.'
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
        $user = User::findOrFail((int) $request->query('user'));

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'bio' => $user->bio,
            'avatar_url' => $user->avatar ? asset('storage/'.$user->avatar) : null,
            'bg_url' => $user->profile_bg ? asset('storage/'.$user->profile_bg) : null,
            'initial' => strtoupper(substr($user->name, 0, 1)),
        ]);
    }
}
