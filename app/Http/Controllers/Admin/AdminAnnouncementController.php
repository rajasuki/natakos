<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementSound;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminAnnouncementController extends Controller
{
    public function index(): View
    {
        return view('admin.announcements.index', [
            'announcements' => Announcement::query()->with('sound')->orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.announcements.create', [
            'sounds' => AnnouncementSound::query()->orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'is_active' => ['boolean'],
            'scroll_speed' => ['nullable', 'integer', 'min:30', 'max:600'],
            'has_sound' => ['boolean'],
            'announcement_sound_id' => ['nullable', 'exists:announcement_sounds,id'],
            'new_sound_name' => ['nullable', 'string', 'max:100', 'required_with:new_sound_file'],
            'new_sound_file' => ['nullable', 'file', 'mimes:mp3,wav,ogg,webm,m4a,aac', 'max:5120', 'required_with:new_sound_name'],
        ]);

        if ($request->hasFile('new_sound_file')) {
            $path = $request->file('new_sound_file')->store('announcement-sounds', 'public');
            $sound = AnnouncementSound::create([
                'name' => $data['new_sound_name'],
                'file_path' => $path,
                'uploaded_by' => $request->user()->id,
            ]);
            $data['announcement_sound_id'] = $sound->id;
            ActivityLogger::created('announcement_sound', 0, $data['new_sound_name']);
        }

        $announcement = Announcement::create($data);
        ActivityLogger::created('announcement', $announcement->id, $announcement->title);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', [
            'announcement' => $announcement->load('sound'),
            'sounds' => AnnouncementSound::query()->orderByDesc('id')->get(),
        ]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'is_active' => ['boolean'],
            'scroll_speed' => ['nullable', 'integer', 'min:30', 'max:600'],
            'has_sound' => ['boolean'],
            'announcement_sound_id' => ['nullable', 'exists:announcement_sounds,id'],
            'new_sound_name' => ['nullable', 'string', 'max:100', 'required_with:new_sound_file'],
            'new_sound_file' => ['nullable', 'file', 'mimes:mp3,wav,ogg,webm,m4a,aac', 'max:5120', 'required_with:new_sound_name'],
        ]);

        if ($request->hasFile('new_sound_file')) {
            $path = $request->file('new_sound_file')->store('announcement-sounds', 'public');
            $sound = AnnouncementSound::create([
                'name' => $data['new_sound_name'],
                'file_path' => $path,
                'uploaded_by' => $request->user()->id,
            ]);
            $data['announcement_sound_id'] = $sound->id;
            ActivityLogger::created('announcement_sound', 0, $data['new_sound_name']);
        }

        $announcement->update($data);
        ActivityLogger::updated('announcement', $announcement->id, $announcement->title);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $title = $announcement->title;
        $announcement->delete();

        ActivityLogger::deleted('announcement', $announcement->id, $title);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function toggle(Announcement $announcement): RedirectResponse
    {
        $announcement->update(['is_active' => ! $announcement->is_active]);

        $label = $announcement->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLogger::updated('announcement', $announcement->id, "{$announcement->title} ({$label})");

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', "Pengumuman berhasil {$label}.");
    }

    public function destroySound(AnnouncementSound $sound): RedirectResponse
    {
        $name = $sound->name;

        Announcement::query()
            ->where('announcement_sound_id', $sound->id)
            ->update(['announcement_sound_id' => null, 'has_sound' => false]);

        Storage::disk('public')->delete($sound->file_path);
        $sound->delete();

        ActivityLogger::deleted('announcement_sound', 0, $name);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Suara berhasil dihapus.');
    }
}
