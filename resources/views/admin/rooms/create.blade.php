@extends('admin.layout')

@section('title', 'Tambah Kamar')
@section('eyebrow', 'Admin Kamar')
@section('page_title', 'Tambah Kamar Baru')
@section('page_description', 'Isi data kamar dengan lengkap. Slug akan dibuat otomatis berdasarkan nama kamar yang Anda masukkan.')

@section('page_actions')
    <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Batal</a>
    <button type="submit" form="room-form" class="button button-primary" style="display:inline-flex;align-items:center;gap:8px;">
        <span class="material-symbols-outlined" style="font-size:18px;">save</span>
        Simpan Kamar
    </button>
@endsection

@section('content')
    @include('admin.rooms._form', [
        'action' => route('admin.rooms.store'),
        'submitLabel' => 'Simpan kamar',
        'statusLabels' => $statusLabels,
        'facilityGroups' => $facilityGroups,
        'facilityTypeLabels' => $facilityTypeLabels,
    ])
@endsection
