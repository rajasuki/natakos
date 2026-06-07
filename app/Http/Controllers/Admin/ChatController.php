<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatBan;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $messages = ChatMessage::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(50);

        $bans = ChatBan::query()
            ->with(['user', 'bannedBy'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.chat.index', [
            'messages' => $messages,
            'bans' => $bans,
            'users' => User::query()->where('role', 'tenant')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, ChatMessage $message): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $message->update(['content' => $validated['content']]);

        return redirect()
            ->route('admin.chat.index')
            ->with('success', 'Pesan berhasil diedit.');
    }

    public function destroy(ChatMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()
            ->route('admin.chat.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }

    public function ban(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in(['mute', 'ban'])],
            'reason' => ['nullable', 'string', 'max:500'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        ChatBan::create([
            'user_id' => $validated['user_id'],
            'banned_by' => $request->user()->id,
            'type' => $validated['type'],
            'reason' => $validated['reason'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        $action = $validated['type'] === 'ban' ? 'diblokir' : 'dimute';
        $user = User::find($validated['user_id']);

        return redirect()
            ->route('admin.chat.index')
            ->with('success', "{$user->name} berhasil {$action} dari obrolan.");
    }

    public function unban(ChatBan $ban): RedirectResponse
    {
        $user = $ban->user;
        $ban->delete();

        return redirect()
            ->route('admin.chat.index')
            ->with('success', "{$user->name} berhasil di-unban dari obrolan.");
    }
}
