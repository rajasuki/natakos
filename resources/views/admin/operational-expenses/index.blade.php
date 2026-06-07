@extends('admin.layout')

@section('title', 'Biaya Operasional')
@section('eyebrow', 'Admin Laporan')
@section('page_title', 'Biaya Operasional')
@section('page_description', 'Catat pengeluaran harian dan bulanan kos.')

@section('page_actions')
    <a href="{{ route('admin.operational-expenses.create') }}" class="button button-primary">
        <span class="material-symbols-outlined" style="font-size:16px;">add</span>
        Catat biaya
    </a>
    <a href="{{ route('admin.operational-expenses.export', request()->query()) }}" class="button button-secondary">
        <span class="material-symbols-outlined" style="font-size:16px;">picture_as_pdf</span>
    </a>
    <a href="{{ route('admin.operational-expenses.export-csv', request()->query()) }}" class="button button-secondary">
        <span class="material-symbols-outlined" style="font-size:16px;">download</span>
    </a>
@endsection

@section('content')
    @if ($expenses->isEmpty())
        <section class="empty-state">
            <h2>{{ $hasActiveFilters ? 'Tidak ada biaya yang cocok' : 'Belum ada biaya operasional' }}</h2>
            <p>{{ $hasActiveFilters ? 'Ubah atau reset filter.' : 'Catat pengeluaran harian kos seperti kebersihan, keamanan, perbaikan, dan lainnya.' }}</p>
            <div class="empty-state-actions">
                @if ($hasActiveFilters)
                    <a href="{{ route('admin.operational-expenses.index') }}" class="button button-secondary">Reset filter</a>
                @else
                    <a href="{{ route('admin.operational-expenses.create') }}" class="button button-primary">Catat biaya sekarang</a>
                @endif
            </div>
        </section>
    @else
        <div class="card">
            <div class="card-body" style="padding-bottom:0;">
                <div class="metric-grid">
                    <div class="metric-card is-info">
                        <div class="metric-accent-bar"></div>
                        <p class="metric-label">Total bulan ini</p>
                        <p class="metric-value">{{ \App\Support\UiFormatter::currency($counts['thisMonth']) }}</p>
                    </div>
                    <div class="metric-card">
                        <div class="metric-accent-bar"></div>
                        <p class="metric-label">Total keseluruhan</p>
                        <p class="metric-value">{{ \App\Support\UiFormatter::currency($counts['allTime']) }}</p>
                    </div>
                    <div class="metric-card">
                        <div class="metric-accent-bar"></div>
                        <p class="metric-label">Jumlah transaksi</p>
                        <p class="metric-value">{{ $counts['total'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <form method="GET" class="toolbar-form">
                <div class="toolbar-grid" style="grid-template-columns: 1fr auto;">
                    <div class="toolbar-actions">
                        <input name="q" type="text" class="input" style="min-width:200px;" placeholder="Cari biaya..." value="{{ $filters['q'] }}">
                        <select name="category" class="select" style="min-width:140px;">
                            <option value="">Kategori</option>
                            @foreach ($categoryLabels as $val => $label)
                                <option value="{{ $val }}" @selected($filters['category'] === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="toolbar-actions">
                        <a href="{{ route('admin.operational-expenses.index') }}" class="button button-secondary button-sm">Reset</a>
                        <button type="submit" class="button button-primary button-sm">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Deskripsi</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td><strong>{{ $expense->description }}</strong></td>
                                <td>
                                    <span class="badge badge-{{ $expense->category }}">{{ $categoryLabels[$expense->category] ?? $expense->category }}</span>
                                </td>
                                <td>{{ \App\Support\UiFormatter::currency($expense->amount) }}</td>
                                <td>{{ \App\Support\UiFormatter::date($expense->date) }}</td>
                                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--ui-body);">
                                    {{ $expense->notes ?: '-' }}
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.operational-expenses.edit', $expense) }}" class="button button-sm button-secondary">Edit</a>
                                        <form method="POST" action="{{ route('admin.operational-expenses.destroy', $expense) }}" onsubmit="return confirm('Hapus biaya ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button button-sm button-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-shell">
                {{ $expenses->links() }}
            </div>
        </div>
    @endif
@endsection
