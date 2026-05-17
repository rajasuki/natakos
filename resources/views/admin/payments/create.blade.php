@extends('admin.layout')

@section('title', 'Tambah Pembayaran')
@section('eyebrow', 'Admin Pembayaran')
@section('page_title', 'Tambah pembayaran baru')
@section('page_description', 'Catat tagihan baru untuk penghuni dengan nominal, periode pembayaran, tenggat bayar, dan status awal.')

@section('page_actions')
    <a href="{{ route('admin.payments.index') }}" class="button button-secondary">Kembali ke daftar pembayaran</a>
@endsection

@section('content')
    @include('admin.payments._form', [
        'action' => route('admin.payments.store'),
        'submitLabel' => 'Simpan pembayaran',
        'tenants' => $tenants,
        'statusLabels' => $statusLabels,
        'tenantStatusLabels' => $tenantStatusLabels,
    ])
@endsection
