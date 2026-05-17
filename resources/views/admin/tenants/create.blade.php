@extends('admin.layout')

@section('title', 'Tambah Penghuni')
@section('eyebrow', 'Admin Penghuni')
@section('page_title', 'Tambah penghuni baru')
@section('page_description', 'Buat akun tenant baru sekaligus data masa tinggal penghuni yang terhubung ke kamar.')

@section('content')
    @include('admin.tenants._form', [
        'action' => route('admin.tenants.store'),
        'submitLabel' => 'Simpan penghuni',
        'rooms' => $rooms,
        'roomStatusLabels' => $roomStatusLabels,
        'statusLabels' => $statusLabels,
    ])
@endsection
