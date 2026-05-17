@extends('admin.layout')

@section('title', 'Edit Kamar')
@section('eyebrow', 'Admin Kamar')
@section('page_title', 'Edit kamar')
@section('page_description', 'Perbarui data kamar, harga, status, atau foto utama tanpa mengubah struktur database manual.')

@section('content')
    @include('admin.rooms._form', [
        'action' => route('admin.rooms.update', $room),
        'method' => 'PUT',
        'room' => $room,
        'submitLabel' => 'Update kamar',
        'statusLabels' => $statusLabels,
    ])
@endsection
