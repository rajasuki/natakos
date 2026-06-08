@extends('admin.layout')

@section('title', 'Badge')
@section('eyebrow', 'Admin Badge')
@section('page_title', 'Kelola badge penghuni')
@section('page_description', 'Buat dan atur badge yang bisa dipilih penghuni di profil mereka.')

@section('page_actions')
    <a href="{{ route('admin.badges.create') }}" class="button button-primary">
        <span class="material-symbols-outlined" style="font-size:16px;">add</span>
        Tambah badge
    </a>
@endsection

@push('styles')
<style>
    .badge-stats {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (min-width: 640px) {
        .badge-stats { grid-template-columns: repeat(3, 1fr); }
    }
    .badge-stat-card {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .badge-stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ui-soft);
        color: var(--ui-accent);
        flex-shrink: 0;
    }
    .badge-stat-icon .material-symbols-outlined { font-size: 24px; font-variation-settings: 'FILL' 1; }
    .badge-stat-value { display: block; font-size: 22px; font-weight: 700; color: var(--ui-ink); }
    .badge-stat-label { font-size: 13px; color: var(--ui-body); }
</style>
@endpush

@section('content')
    <div class="badge-stats">
        <div class="badge-stat-card">
            <div class="badge-stat-icon"><span class="material-symbols-outlined">verified</span></div>
            <div>
                <span class="badge-stat-value">{{ $badges->count() }}</span>
                <span class="badge-stat-label">Total badge</span>
            </div>
        </div>
        <div class="badge-stat-card">
            <div class="badge-stat-icon"><span class="material-symbols-outlined">check_circle</span></div>
            <div>
                <span class="badge-stat-value">{{ $badges->where('is_active', true)->count() }}</span>
                <span class="badge-stat-label">Aktif</span>
            </div>
        </div>
        <div class="badge-stat-card">
            <div class="badge-stat-icon"><span class="material-symbols-outlined">lock</span></div>
            <div>
                <span class="badge-stat-value">{{ $badges->whereNotNull('requirement_type')->count() }}</span>
                <span class="badge-stat-label">Butuh syarat</span>
            </div>
        </div>
    </div>

    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Efek</th>
                    <th>Syarat</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($badges as $badge)
                    <tr>
                        <td>
                            <strong>{{ $badge->name }}</strong>
                            @if ($badge->description)
                                <br><small style="color:var(--ui-body)">{{ $badge->description }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="user-title user-title-{{ $badge->effect }}">
                                {{ $effectLabels[$badge->effect] ?? $badge->effect }}
                            </span>
                        </td>
                        <td>
                            @if ($badge->requirement_type)
                                {{ $badge->requirement_value }} {{ $badge->requirement_type === 'chat_messages' ? 'pesan' : ($badge->requirement_type === 'payments_count' ? 'kali bayar' : 'hari') }}
                            @else
                                <span style="color:var(--ui-body)">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($badge->is_active)
                                <span class="status-badge status-active">Aktif</span>
                            @else
                                <span class="status-badge status-inactive">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.badges.edit', $badge) }}" class="button button-subtle button-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.badges.destroy', $badge) }}" onsubmit="return confirm('Hapus badge ini?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="button button-danger button-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--ui-body);">Belum ada badge.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
