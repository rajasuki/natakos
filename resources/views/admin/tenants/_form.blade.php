@php
    $tenant = $tenant ?? null;
    $tenantUser = $tenant?->user;
    $errorBag = isset($errors) ? $errors : null;
@endphp

<div class="card form-card">
    @if ($rooms->isEmpty() && $tenant === null)
        <section class="empty-state">
            <h2>Belum ada kamar untuk penghuni baru</h2>
            <p>Tambahkan kamar terlebih dahulu sebelum membuat data penghuni baru agar pilihan kamar tersedia pada form ini.</p>

            <div class="empty-state-actions">
                <a href="{{ route('admin.rooms.create') }}" class="button button-primary">Tambah kamar</a>
                <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kembali ke daftar penghuni</a>
            </div>
        </section>
    @else
        <form method="POST" action="{{ $action }}" class="form-layout">
            @csrf

            @isset($method)
                @method($method)
            @endisset

            <section class="form-section">
                <div>
                    <h2 class="form-section-title">Akun penghuni</h2>
                    <p class="form-section-copy">Isi identitas dasar akun tenant yang dipakai penghuni untuk login ke dashboard penghuni.</p>
                </div>

                <div class="grid grid-two">
                    <div class="field">
                        <label for="name">Nama penghuni</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $tenantUser?->name) }}" class="input" required>
                        @if ($errorBag?->has('name'))
                            <div class="field-error">{{ $errorBag->first('name') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $tenantUser?->email) }}" class="input" required>
                        @if ($errorBag?->has('email'))
                            <div class="field-error">{{ $errorBag->first('email') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="phone">Nomor HP</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $tenantUser?->phone) }}" class="input" placeholder="Contoh: 0852xxxxxxxx">
                        @if ($errorBag?->has('phone'))
                            <div class="field-error">{{ $errorBag->first('phone') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" class="input" {{ $tenant ? '' : 'required' }}>
                        @if ($errorBag?->has('password'))
                            <div class="field-error">{{ $errorBag->first('password') }}</div>
                        @endif
                        <div class="helper">{{ $tenant ? 'Kosongkan jika tidak ingin mengubah password lama.' : 'Password wajib diisi saat menambah penghuni.' }}</div>
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
                                <option value="{{ $room->id }}" @selected((string) old('room_id', $tenant?->room_id) === (string) $room->id)>
                                    {{ $room->name }} - {{ $roomStatusLabels[$room->status] ?? $room->status }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errorBag?->has('room_id'))
                            <div class="field-error">{{ $errorBag->first('room_id') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="status">Status penghuni</label>
                        <select id="status" name="status" class="select" required>
                            @foreach ($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $tenant?->status ?? 'active') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if ($errorBag?->has('status'))
                            <div class="field-error">{{ $errorBag->first('status') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="start_date">Tanggal masuk</label>
                        <input id="start_date" name="start_date" type="date" value="{{ old('start_date', $tenant?->start_date?->format('Y-m-d')) }}" class="input" required>
                        @if ($errorBag?->has('start_date'))
                            <div class="field-error">{{ $errorBag->first('start_date') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="end_date">Tanggal keluar</label>
                        <input id="end_date" name="end_date" type="date" value="{{ old('end_date', $tenant?->end_date?->format('Y-m-d')) }}" class="input">
                        @if ($errorBag?->has('end_date'))
                            <div class="field-error">{{ $errorBag->first('end_date') }}</div>
                        @endif
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
                    <textarea id="notes" name="notes" class="textarea" placeholder="Tulis catatan penghuni jika diperlukan...">{{ old('notes', $tenant?->notes) }}</textarea>
                    @if ($errorBag?->has('notes'))
                        <div class="field-error">{{ $errorBag->first('notes') }}</div>
                    @endif
                </div>
            </section>

            <div class="form-actions">
                <button type="submit" class="button button-primary">{{ $submitLabel }}</button>
                <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kembali ke daftar penghuni</a>
            </div>
        </form>
    @endif
</div>
