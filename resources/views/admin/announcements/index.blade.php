@extends('admin.layout')

@section('title', 'Pengumuman')
@section('eyebrow', 'Admin Pengumuman')
@section('page_title', 'Kelola pengumuman')
@section('page_description', 'Buat pengumuman yang muncul di halaman penghuni yang sudah memiliki kamar.')

@section('page_actions')
    <a href="{{ route('admin.announcements.create') }}" class="button button-primary">
        <span class="material-symbols-outlined" style="font-size:16px;">add</span>
        Tambah pengumuman
    </a>
@endsection

@section('content')
    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Konten</th>
                    <th>Suara</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($announcements as $a)
                    <tr>
                        <td>
                            <strong>{{ $a->title }}</strong>
                        </td>
                        <td style="max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ Str::limit($a->content, 80) }}
                        </td>
                        <td>
                            @if ($a->has_sound && $a->sound)
                                <span class="badge badge-active">
                                    <span class="material-symbols-outlined" style="font-size:12px;vertical-align:middle;">volume_up</span>
                                    {{ $a->sound->name }}
                                </span>
                            @else
                                <span style="color:var(--gray-400);font-size:12px;">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($a->is_active)
                                <span class="status-badge status-active">Aktif</span>
                            @else
                                <span class="status-badge status-inactive">Nonaktif</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap;color:var(--ui-body);font-size:13px;">
                            {{ $a->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div class="table-actions">
                                <form method="POST" action="{{ route('admin.announcements.toggle', $a) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="button button-subtle button-sm">
                                        {{ $a->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.announcements.edit', $a) }}" class="button button-subtle button-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}" onsubmit="return confirm('Hapus pengumuman ini?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="button button-danger button-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--ui-body);">Belum ada pengumuman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
