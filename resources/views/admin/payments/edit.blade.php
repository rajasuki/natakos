@extends('admin.layout')

@section('title', 'Edit Pembayaran')
@section('eyebrow', 'Admin Pembayaran')
@section('page_title', 'Edit pembayaran')
@section('page_description', 'Perbarui tagihan, tenggat bayar, status pembayaran, atau bukti pembayaran manual sesuai kondisi terbaru.')

@section('page_actions')
    <a href="{{ route('admin.payments.index') }}" class="button button-secondary">Kembali ke daftar pembayaran</a>
@endsection

@section('content')
    @include('admin.payments._form', [
        'action' => route('admin.payments.update', $payment),
        'method' => 'PUT',
        'payment' => $payment,
        'submitLabel' => 'Update pembayaran',
        'tenants' => $tenants,
        'statusLabels' => $statusLabels,
        'tenantStatusLabels' => $tenantStatusLabels,
    ])
@endsection
