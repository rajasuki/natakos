<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function index(): View
    {
        return view('admin.badges.index', [
            'badges' => Badge::query()->orderBy('id')->get(),
            'effectLabels' => Badge::effectOptions(),
        ]);
    }

    public function create(): View
    {
        return view('admin.badges.create', [
            'effectLabels' => Badge::effectOptions(),
            'requirementTypes' => $this->requirementTypes(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $badge = Badge::create($data);
        ActivityLogger::created('badge', $badge->id, $badge->name);

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge berhasil ditambahkan.');
    }

    public function edit(Badge $badge): View
    {
        return view('admin.badges.edit', [
            'badge' => $badge,
            'effectLabels' => Badge::effectOptions(),
            'requirementTypes' => $this->requirementTypes(),
        ]);
    }

    public function update(Request $request, Badge $badge): RedirectResponse
    {
        $badge->update($this->validatedData($request, $badge));
        ActivityLogger::updated('badge', $badge->id, $badge->name);

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge berhasil diperbarui.');
    }

    public function destroy(Badge $badge): RedirectResponse
    {
        $name = $badge->name;
        $badge->users()->detach();
        $badge->delete();

        ActivityLogger::deleted('badge', $badge->id, $name);

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Badge $badge = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'effect' => ['required', Rule::in(array_keys(Badge::effectOptions()))],
            'requirement_type' => ['nullable', Rule::in(array_keys($this->requirementTypes()))],
            'requirement_value' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
        ]);
    }

    private function requirementTypes(): array
    {
        return [
            'chat_messages' => 'Jumlah Pesan Chat',
            'payments_count' => 'Jumlah Pembayaran Lunas',
            'stay_days' => 'Lama Tinggal (hari)',
        ];
    }
}
