<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->with('tenant.room')
            ->withCount('tenant')
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'badges' => Badge::where('is_active', true)->get(),
            'roleLabels' => $this->roleLabels(),
            'effectLabels' => $this->effectLabels(),
            'counts' => [
                'total' => User::query()->count(),
                'admin' => User::query()->where('role', 'admin')->count(),
                'tenant' => User::query()->where('role', 'tenant')->count(),
            ],
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Tidak bisa mengubah role akun Anda sendiri.');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(array_keys($this->roleLabels()))],
        ]);

        $user->update(['role' => $validated['role']]);

        $roleLabel = $this->roleLabels()[$validated['role']] ?? $validated['role'];

        ActivityLogger::updated('user', $user->id, "Role {$user->name} menjadi {$roleLabel}");

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Role {$user->name} berhasil diubah menjadi {$roleLabel}.");
    }

    public function updateTitle(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'selected_badge_id' => ['nullable', 'exists:badges,id'],
            'title' => ['nullable', 'string', 'max:100'],
            'title_effect' => ['required', Rule::in(array_keys($this->effectLabels()))],
        ]);

        if (array_key_exists('selected_badge_id', $validated)) {
            Badge::syncUnlockedFor($user);

            if ($validated['selected_badge_id']) {
                $badge = Badge::find($validated['selected_badge_id']);
                if ($badge && $badge->is_active && $badge->isUnlockedFor($user)) {
                    $user->badges()->syncWithoutDetaching([$badge->id => ['is_selected' => true]]);
                    $user->badges()->where('badge_id', '!=', $badge->id)->wherePivot('is_selected', true)->each(function ($b) use ($user) {
                        $user->badges()->updateExistingPivot($b->id, ['is_selected' => false]);
                    });
                    $user->update([
                        'title' => $badge->name,
                        'title_effect' => $badge->effect,
                    ]);
                }
            } else {
                $user->badges()->wherePivot('is_selected', true)->each(function ($b) use ($user) {
                    $user->badges()->updateExistingPivot($b->id, ['is_selected' => false]);
                });
                $user->update(['title' => $validated['title'] ?: null, 'title_effect' => $validated['title_effect']]);
            }
        } else {
            $user->update([
                'title' => $validated['title'] ?: null,
                'title_effect' => $validated['title_effect'],
            ]);
        }

        ActivityLogger::updated('user', $user->id, "Title {$user->name}");

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Title untuk '.$user->name.' berhasil diperbarui.');
    }

    private function roleLabels(): array
    {
        return [
            'admin' => 'Admin',
            'tenant' => 'Penghuni',
        ];
    }

    private function effectLabels(): array
    {
        return Badge::effectOptions();
    }
}
