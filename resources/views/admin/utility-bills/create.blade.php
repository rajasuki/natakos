@extends('admin.layout')

@section('title', 'Tambah Tagihan Utilitas')
@section('eyebrow', 'Admin Utilitas')
@section('page_title', 'Tambah Tagihan Utilitas')
@section('page_description', 'Catat tagihan air, listrik, atau internet untuk penghuni.')

@section('page_actions')
    <a href="{{ route('admin.utility-bills.index') }}" class="button button-secondary">Batal</a>
    <button type="submit" form="bill-form" class="button button-primary">Simpan Tagihan</button>
@endsection

@section('content')
    @include('admin.utility-bills._form', [
        'action' => route('admin.utility-bills.store'),
        'tenants' => $tenants,
        'typeLabels' => $typeLabels,
        'statusLabels' => $statusLabels,
    ])
@endsection
