@extends('admin.layout')

@section('title', 'Edit Kamar')
@section('eyebrow', 'Admin Kamar')
@section('page_title', 'Edit kamar')
@section('page_description', 'Perbarui data kamar, harga, status, atau foto utama tanpa mengubah struktur database manual.')

@section('page_actions')
    <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Kembali ke daftar kamar</a>
    <a href="{{ route('admin.rooms.images.index', $room) }}" class="button button-subtle">Galeri</a>
@endsection

@section('content')
    @include('admin.rooms._form', [
        'action' => route('admin.rooms.update', $room),
        'method' => 'PUT',
        'room' => $room,
        'submitLabel' => 'Update kamar',
        'statusLabels' => $statusLabels,
        'facilityGroups' => $facilityGroups,
        'facilityTypeLabels' => $facilityTypeLabels,
    ])
@endsection
