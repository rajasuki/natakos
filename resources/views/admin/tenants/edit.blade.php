@extends('admin.layout')

@section('title', 'Edit Penghuni')
@section('eyebrow', 'Admin Penghuni')
@section('page_title', 'Edit penghuni')
@section('page_description', 'Perbarui akun tenant, kamar yang ditempati, masa tinggal, dan status penghuni sesuai kondisi terbaru.')

@section('content')
    @include('admin.tenants._form', [
        'action' => route('admin.tenants.update', $tenant),
        'method' => 'PUT',
        'tenant' => $tenant,
        'submitLabel' => 'Update penghuni',
        'rooms' => $rooms,
        'roomStatusLabels' => $roomStatusLabels,
        'statusLabels' => $statusLabels,
    ])
@endsection
