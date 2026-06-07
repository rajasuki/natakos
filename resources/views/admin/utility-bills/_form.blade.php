@php $bill = $bill ?? null; @endphp

<div class="card form-card" style="background:transparent;border:none;box-shadow:none;padding:0;">
    <form method="POST" action="{{ $action }}" id="bill-form">
        @csrf
        @isset($method) @method($method) @endisset

        <div class="form-layout">
            <section class="card">
                <div class="card-head has-divider">
                    <h2 class="card-title">Detail Tagihan</h2>
                </div>
                <div class="card-body">
                    <div class="grid grid-two">
                        <div class="field">
                            <label for="tenant_id">Penghuni <span class="muted">*</span></label>
                            <select id="tenant_id" name="tenant_id" class="select" required>
                                <option value="">Pilih penghuni</option>
                                @foreach ($tenants as $t)
                                    <option value="{{ $t->id }}" @selected(old('tenant_id', $bill?->tenant_id) == $t->id)>
                                        {{ $t->user?->name }} ({{ $t->room?->name ?: 'Tanpa kamar' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_id') <div class="field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label for="type">Jenis <span class="muted">*</span></label>
                            <select id="type" name="type" class="select" required>
                                <option value="">Pilih jenis</option>
                                @foreach ($typeLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('type', $bill?->type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type') <div class="field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label for="amount">Jumlah <span class="muted">*</span></label>
                            <input id="amount" name="amount" type="number" min="0" step="1" value="{{ old('amount', $bill?->amount) }}" class="input" required>
                            @error('amount') <div class="field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label for="period">Periode <span class="muted">*</span></label>
                            <input id="period" name="period" type="text" value="{{ old('period', $bill?->period) }}" class="input" placeholder="YYYY-MM" required>
                            @error('period') <div class="field-error">{{ $message }}</div> @enderror
                            <div class="helper">Format: YYYY-MM (contoh: 2026-07)</div>
                        </div>

                        <div class="field">
                            <label for="due_date">Tanggal Jatuh Tempo <span class="muted">*</span></label>
                            <input id="due_date" name="due_date" type="date" value="{{ old('due_date', $bill?->due_date?->format('Y-m-d')) }}" class="input" required>
                            @error('due_date') <div class="field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label for="status">Status <span class="muted">*</span></label>
                            <select id="status" name="status" class="select" required>
                                @foreach ($statusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $bill?->status ?? 'unpaid') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <div class="field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="field" id="paid_at_field" style="{{ old('status', $bill?->status ?? 'unpaid') === 'paid' ? '' : 'display:none;' }}">
                            <label for="paid_at">Tanggal Bayar</label>
                            <input id="paid_at" name="paid_at" type="date" value="{{ old('paid_at', $bill?->paid_at?->format('Y-m-d')) }}" class="input">
                            @error('paid_at') <div class="field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="field field-full">
                            <label for="notes">Catatan</label>
                            <textarea id="notes" name="notes" class="textarea" rows="3">{{ old('notes', $bill?->notes) }}</textarea>
                            @error('notes') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('status').addEventListener('change', function() {
        document.getElementById('paid_at_field').style.display = this.value === 'paid' ? '' : 'none';
    });
</script>
@endpush
