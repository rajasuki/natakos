@extends('admin.layout')

@section('title', 'Edit Fasilitas')
@section('eyebrow', 'Admin Fasilitas')
@section('page_title', 'Edit fasilitas')
@section('page_description', 'Perbarui data fasilitas tanpa mengubah struktur database manual yang sudah ada.')

@section('page_actions')
    <a href="{{ route('admin.facilities.index') }}" class="button button-secondary">Kembali ke daftar fasilitas</a>
@endsection

@section('content')
    @include('admin.facilities._form', [
        'action' => route('admin.facilities.update', $facility),
        'method' => 'PUT',
        'facility' => $facility,
        'submitLabel' => 'Update fasilitas',
        'typeLabels' => $typeLabels,
    ])
@endsection
