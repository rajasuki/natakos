@extends('admin.layout')

@section('title', 'Tambah Penempatan')
@section('eyebrow', 'Admin Penghuni')
@section('page_title', 'Tambah penempatan')
@section('page_description', 'Assign kamar dan periode tinggal ke akun penghuni yang sudah terdaftar, tanpa perlu membuat akun baru.')

@section('page_actions')
    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kembali ke daftar penghuni</a>
@endsection

@section('content')
    <div class="card form-card">
        @if ($rooms->isEmpty())
            <section class="empty-state">
                <h2>Belum ada kamar tersedia</h2>
                <p>Tambahkan kamar terlebih dahulu sebelum menambahkan penempatan baru.</p>

                <div class="empty-state-actions">
                    <a href="{{ route('admin.rooms.create') }}" class="button button-primary">Tambah kamar</a>
                    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kembali ke daftar penghuni</a>
                </div>
            </section>
        @elseif ($existingUsers->isEmpty())
            <section class="empty-state">
                <h2>Belum ada akun penghuni terdaftar</h2>
                <p>Buat akun penghuni baru terlebih dahulu, lalu gunakan halaman ini untuk menambahkan penempatan berikutnya.</p>

                <div class="empty-state-actions">
                    <a href="{{ route('admin.tenants.create') }}" class="button button-primary">Buat penghuni baru</a>
                    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kembali ke daftar penghuni</a>
                </div>
            </section>
        @else
            <form method="POST" action="{{ route('admin.tenants.store-existing') }}" class="form-layout">
                @csrf

                <section class="form-section">
                    <div>
                        <h2 class="form-section-title">Akun penghuni</h2>
                        <p class="form-section-copy">Pilih akun penghuni yang sudah terdaftar untuk ditambahkan penempatan baru.</p>
                    </div>

                    <div class="grid grid-two">
                        <div class="field field-full">
                            <label for="user_id">Penghuni</label>
                            <select id="user_id" name="user_id" class="select" required>
                                <option value="">Pilih penghuni...</option>
                                @foreach ($existingUsers as $user)
                                    @php
                                        $userTenantStatus = $user->tenant?->status;
                                        $statusSuffix = $userTenantStatus === 'moved_out' ? ' (Riwayat)' : ($userTenantStatus === 'inactive' ? ' (Tidak Aktif)' : '');
                                    @endphp
                                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                        {{ $user->name }}{{ $statusSuffix }}
                                        @if ($user->email) — {{ $user->email }} @endif
                                        @if ($user->phone) ({{ $user->phone }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                            <div class="helper">
                                Tidak menemukan penghuni yang dicari?
                                <a href="{{ route('admin.tenants.create') }}">Buat akun penghuni baru</a>.
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div>
                        <h2 class="form-section-title">Penempatan dan masa tinggal</h2>
                        <p class="form-section-copy">Pilih kamar, status penghuni, dan periode tinggal yang sesuai dengan kondisi terbaru.</p>
                    </div>

                    <div class="grid grid-two">
                        <div class="field">
                            <label for="room_id">Kamar</label>
                            <select id="room_id" name="room_id" class="select" required>
                                <option value="">Pilih kamar</option>
                                @foreach ($rooms as $room)
                                    @php
                                        $isFull = $room->available_slots <= 0;
                                    @endphp
                                    <option value="{{ $room->id }}" @selected(old('room_id') == $room->id) @disabled($isFull)>
                                        {{ $room->name }} — {{ $roomStatusLabels[$room->status] ?? $room->status }}
                                        @if ($room->active_tenant_count > 0)
                                            ({{ $room->active_tenant_count }}/{{ $room->capacity ?? 1 }} terisi)
                                        @else
                                            ({{ $room->capacity ?? 1 }} orang)
                                        @endif
                                        @if ($isFull)
                                            — PENUH
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="status">Status penghuni</label>
                            <select id="status" name="status" class="select" required>
                                @foreach ($statusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', 'active') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="start_date">Tanggal masuk</label>
                            <input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" class="input" required>
                            @error('start_date')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="end_date">Tanggal keluar</label>
                            <input id="end_date" name="end_date" type="date" value="{{ old('end_date') }}" class="input">
                            @error('end_date')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div>
                        <h2 class="form-section-title">Catatan tambahan</h2>
                        <p class="form-section-copy">Gunakan bagian ini untuk menyimpan catatan penting penghuni yang perlu diketahui admin.</p>
                    </div>

                    <div class="field field-full">
                        <label for="notes">Catatan</label>
                        <textarea id="notes" name="notes" class="textarea" placeholder="Tulis catatan penghuni jika diperlukan...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </section>

                <div class="form-actions">
                    <button type="submit" class="button button-primary">Tambah penempatan</button>
                    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kembali ke daftar penghuni</a>
                </div>
            </form>
        @endif
    </div>
@endsection