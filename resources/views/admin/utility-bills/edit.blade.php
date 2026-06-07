@extends('admin.layout')

@section('title', 'Edit Tagihan Utilitas')
@section('eyebrow', 'Admin Utilitas')
@section('page_title', 'Edit Tagihan Utilitas')

@section('page_actions')
    <a href="{{ route('admin.utility-bills.index') }}" class="button button-secondary">Batal</a>
    <button type="submit" form="bill-form" class="button button-primary">Update Tagihan</button>
@endsection

@section('content')
    @include('admin.utility-bills._form', [
        'action' => route('admin.utility-bills.update', $bill),
        'method' => 'PUT',
        'bill' => $bill,
        'tenants' => $tenants,
        'typeLabels' => $typeLabels,
        'statusLabels' => $statusLabels,
    ])
@endsection
