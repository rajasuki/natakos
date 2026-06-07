@extends('admin.layout')

@section('title', 'Catat Biaya Operasional')
@section('eyebrow', 'Admin Laporan')
@section('page_title', 'Catat Biaya Operasional')

@section('content')
    <form method="POST" action="{{ route('admin.operational-expenses.store') }}" class="content-stack">
        @csrf
        @include('admin.operational-expenses._form', ['expense' => null])
        <div class="form-actions">
            <button type="submit" class="button button-primary">Simpan</button>
            <a href="{{ route('admin.operational-expenses.index') }}" class="button button-secondary">Batal</a>
        </div>
    </form>
@endsection
