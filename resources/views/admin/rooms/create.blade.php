@extends('admin.layout')

@section('title', 'Tambah Kamar')
@section('eyebrow', 'Admin Kamar')
@section('page_title', 'Tambah kamar baru')
@section('page_description', 'Isi data kamar dengan lengkap. Slug akan dibuat otomatis berdasarkan nama kamar yang Anda masukkan.')

@section('page_actions')
    <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Kembali ke daftar kamar</a>
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
