<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatBan;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $query = ChatMessage::query()->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }

        $messages = $query->orderByDesc('created_at')->paginate(50);

        $bans = ChatBan::query()
            ->with(['user', 'bannedBy'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.chat.index', [
            'messages' => $messages,
            'bans' => $bans,
            'users' => User::query()->where('role', 'tenant')->orderBy('name')->get(),
            'filters' => $request->only(['user_id', 'date_from', 'date_to']),
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        ChatMessage::create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('admin.chat.index')
            ->with('success', 'Pesan berhasil dikirim.');
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

    public function profile(Request $request): JsonResponse
    {
        $user = User::with('tenant.room')->findOrFail((int) $request->query('user'));

        $roomName = null;
        if ($user->tenant && $user->tenant->room) {
            $roomName = $user->tenant->room->name;
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
