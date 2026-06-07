@extends('admin.layout')

@section('title', 'Edit Biaya Operasional')
@section('eyebrow', 'Admin Laporan')
@section('page_title', 'Edit Biaya Operasional')

@section('content')
    <form method="POST" action="{{ route('admin.operational-expenses.update', $expense) }}" class="content-stack">
        @csrf
        @method('PUT')
        @include('admin.operational-expenses._form', ['expense' => $expense])
        <div class="form-actions">
            <button type="submit" class="button button-primary">Simpan perubahan</button>
            <a href="{{ route('admin.operational-expenses.index') }}" class="button button-secondary">Batal</a>
        </div>
    </form>
@endsection
