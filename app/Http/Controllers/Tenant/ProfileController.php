<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        Badge::syncUnlockedFor($user);

        return view('tenant.profile.edit', [
            'user' => $user,
            'badges' => Badge::where('is_active', true)->get(),
            'selectedBadge' => $user->badges()->wherePivot('is_selected', true)->first(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'show_room' => ['boolean'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'profile_bg' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'current_password' => ['nullable', 'required_with:new_password', 'string', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:8'],
            'selected_badge_id' => ['nullable', 'exists:badges,id'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'show_room' => (bool) ($validated['show_room'] ?? false),
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            Storage::disk('public')->makeDirectory('avatars');
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('profile_bg')) {
            if ($user->profile_bg) {
                Storage::disk('public')->delete($user->profile_bg);
            }
            Storage::disk('public')->makeDirectory('profile-bg');
            $data['profile_bg'] = $request->file('profile_bg')->store('profile-bg', 'public');
        }

        if (! empty($validated['new_password'])) {
            $data['password'] = $validated['new_password'];
        }

        $user->update($data);

        if (array_key_exists('selected_badge_id', $validated)) {
            Badge::syncUnlockedFor($user);

            if ($validated['selected_badge_id']) {
                $badge = Badge::find($validated['selected_badge_id']);
                if ($badge && $badge->is_active && $badge->isUnlockedFor($user)) {
                    $user->badges()->updateExistingPivot($badge->id, ['is_selected' => true]);
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
                $user->update(['title' => null, 'title_effect' => null]);
            }
        }

        return redirect()
            ->route('tenant.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
