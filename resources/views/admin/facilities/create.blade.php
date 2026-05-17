@extends('admin.layout')

@section('title', 'Tambah Fasilitas')
@section('eyebrow', 'Admin Fasilitas')
@section('page_title', 'Tambah fasilitas baru')
@section('page_description', 'Isi nama, type, dan icon fasilitas. Kombinasi nama dan type harus unik.')

@section('page_actions')
    <a href="{{ route('admin.facilities.index') }}" class="button button-secondary">Kembali ke daftar fasilitas</a>
@endsection

@section('content')
    @include('admin.facilities._form', [
        'action' => route('admin.facilities.store'),
        'submitLabel' => 'Simpan fasilitas',
        'typeLabels' => $typeLabels,
    ])
@endsection
