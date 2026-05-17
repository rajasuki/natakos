@extends('admin.layout')

@section('title', 'Edit Fasilitas')
@section('eyebrow', 'Admin Fasilitas')
@section('page_title', 'Edit fasilitas')
@section('page_description', 'Perbarui data fasilitas tanpa mengubah struktur database manual yang sudah ada.')

@section('content')
    @include('admin.facilities._form', [
        'action' => route('admin.facilities.update', $facility),
        'method' => 'PUT',
        'facility' => $facility,
        'submitLabel' => 'Update fasilitas',
        'typeLabels' => $typeLabels,
    ])
@endsection
