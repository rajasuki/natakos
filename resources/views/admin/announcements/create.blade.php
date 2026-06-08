@extends('admin.layout')

@section('title', 'Tambah Pengumuman')
@section('eyebrow', 'Admin Pengumuman')
@section('page_title', 'Tambah pengumuman baru')
@section('page_description', 'Pengumuman akan muncul di halaman penghuni yang sudah memiliki kamar.')

@section('content')
    <div class="form-card">
        <form method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf

            <div class="form-group">
                <label for="title" class="form-label">Judul</label>
                <input id="title" name="title" type="text" class="form-input" value="{{ old('title') }}" required maxlength="200">
                @error('title') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Konten</label>
                <textarea id="content" name="content" class="form-input" rows="4" required>{{ old('content') }}</textarea>
                @error('content') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
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
