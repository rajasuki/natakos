@extends('admin.layout')

@section('title', 'Edit Kamar')
@section('eyebrow', 'Admin Kamar')
@section('page_title', 'Edit Kamar')
@section('page_description', 'Perbarui informasi dan fasilitas untuk kamar ini.')

@section('page_actions')
    <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Batal</a>
    <button type="submit" form="room-form" class="button button-primary" style="display:inline-flex;align-items:center;gap:8px;">
        <span class="material-symbols-outlined" style="font-size:18px;">save</span>
        Update Kamar
    </button>
@endsection

@section('content')
    @include('admin.rooms._form', [
        'action' => route('admin.rooms.update', $room),
        'method' => 'PUT',
        'room' => $room,
        'submitLabel' => 'Update Kamar',
        'statusLabels' => $statusLabels,
        'facilityGroups' => $facilityGroups,
        'facilityTypeLabels' => $facilityTypeLabels,
    ])
@endsection
