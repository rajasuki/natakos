@extends('admin.layout')

@section('title', 'Tambah Pembayaran')
@section('eyebrow', 'Admin Pembayaran')
@section('page_title', 'Tambah pembayaran baru')
@section('page_description', 'Catat tagihan baru untuk penghuni dengan nominal, periode pembayaran, tenggat bayar, dan status awal.')

@section('content')
    @include('admin.payments._form', [
        'action' => route('admin.payments.store'),
        'submitLabel' => 'Simpan pembayaran',
        'tenants' => $tenants,
        'statusLabels' => $statusLabels,
        'tenantStatusLabels' => $tenantStatusLabels,
    ])
@endsection
