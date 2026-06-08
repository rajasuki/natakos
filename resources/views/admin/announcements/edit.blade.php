@extends('admin.layout')

@section('title', 'Edit Pengumuman')
@section('eyebrow', 'Admin Pengumuman')
@section('page_title', 'Edit pengumuman')
@section('page_description', 'Ubah pengumuman yang sudah ada.')

@section('content')
    <div class="form-card">
        <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label for="title" class="form-label">Judul</label>
                <input id="title" name="title" type="text" class="form-input" value="{{ old('title', $announcement->title) }}" required maxlength="200">
                @error('title') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Konten</label>
                <textarea id="content" name="content" class="form-input" rows="4" required>{{ old('content', $announcement->content) }}</textarea>
                @error('content') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $announcement->is_active))>
                    Aktif
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.announcements.index') }}" class="button button-subtle">Batal</a>
                <button type="submit" class="button button-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection
